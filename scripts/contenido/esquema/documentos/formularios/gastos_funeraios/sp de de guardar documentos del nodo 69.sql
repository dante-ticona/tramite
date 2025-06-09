CREATE OR REPLACE FUNCTION public.sp_derivar_caso_firma(v_cas_id integer, v_cas_act_id integer, v_cas_nodo_id integer, v_cas_usr_id integer, v_estado_derivacion text, v_cas_estado text)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
    DECLARE
        hitorico integer;
        id_caso_real integer;
        id_nodo integer;
        v_estado_generacion_eap integer;
    BEGIN
        id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
        id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);

       update rmx_vys_historico_casos
       set htc_cas_data = jsonb_set(htc_cas_data, '{ESTADO_DERIVACION}', to_jsonb(v_estado_derivacion), false),
       htc_cas_modificado = now()
       where htc_id = hitorico;

        if v_estado_derivacion = 'APROBADO' then
          select usn_user_id
          into v_cas_usr_id
            from rmx_usr_nodos run
            where usn_nodo_id = v_cas_nodo_id
            order by random()
            limit 1;
        end if;

          select count(distinct valor->>'frm_value')
          into v_estado_generacion_eap
            FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_cod_id, cas_act_id, cas_id
                  FROM public.rmx_vys_casos
                  ) datos
            where valor->>'frm_campo' = 'ESTADO_GENERACION_EAP'
            and valor->>'frm_value' = 'A'
            and cas_id = v_cas_id
            and cas_cod_id like 'JUB/%';
            if v_estado_generacion_eap > 0 and v_estado_derivacion = 'FIRMADO' then
                  v_cas_act_id = 110;
            end if;

          select count(distinct valor->>'frm_value')
          into v_estado_generacion_eap
            FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_cod_id, cas_act_id, cas_id
                  FROM public.rmx_vys_casos
                  ) datos
            where valor->>'frm_campo' = 'ESTADO_GENERACION_EAP'
            and valor->>'frm_value' = 'A'
            and cas_id = v_cas_id
            and cas_cod_id like 'PAGCC/%';
            
            if v_estado_generacion_eap > 0 and v_estado_derivacion = 'FIRMADO' then
                  v_cas_act_id = 244;
            end if;
      ---// INICIO  pago de retiro minimo 
      select count(distinct valor->>'frm_value')
      into v_estado_generacion_eap
      FROM (SELECT jsonb_array_elements(cas_data_valores) AS valor, cas_cod_id, cas_act_id, cas_id
            FROM public.rmx_vys_casos
            ) datos
      where valor->>'frm_campo' = 'ESTADO_GENERACION_EAP'
      and valor->>'frm_value' = 'A'
      and cas_id = v_cas_id
      and cas_cod_id like 'RMIN/%';
      
      if v_estado_generacion_eap > 0 and v_estado_derivacion = 'FIRMADO' then
            v_cas_act_id = 258;
      end if;

            ---// FIN  pago de retiro minimo 
            UPDATE rmx_vys_casos SET
                              cas_nodo_id = v_cas_nodo_id,
                              cas_act_id = v_cas_act_id,
                              cas_modificado = now(),
                              cas_usr_id = v_cas_usr_id,
                              cas_estado = 'T'
            where cas_id = v_cas_id;

        INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id,
            htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id, htc_cas_correlativo, htc_cas_padre_id)
        SELECT id_caso_real, cas_act_id, cas_nodo_id, cas_data, cas_data_valores, now(), cas_modificado, cas_usr_id, cas_estado, cas_cod_id, cas_correlativo, cas_padre_id
            FROM rmx_vys_casos
       WHERE cas_id = v_cas_id;
        return hitorico;
    END;

$function$
;
