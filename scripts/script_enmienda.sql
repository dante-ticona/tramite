CREATE OR REPLACE FUNCTION public.enmienda(v_cas_id integer, v_cas_usr_id integer, v_cas_estado text, v_observacion text) RETURNS TABLE(vrespuesta text) LANGUAGE plpgsql AS $function$
    DECLARE
     v_act_id INT;
    v_act_nodo_id INT;
    v_act_ido INT;
    v_act_nodo_ido INT;
   v_usuario text;
  v_idusuarioo int;
        hitorico integer;
        id_caso_real integer;
        id_nodo integer;
        v_cas_data text;
        v_cas_data_valores text;
       vemail text;
    BEGIN

	    select cas_act_id,cas_nodo_id, cas_data ->>'USUARIO_REGISTRO',cas_data,cas_data_valores  into v_act_ido,v_act_nodo_ido,v_usuario,v_cas_data,v_cas_data_valores
		from public.rmx_vys_casos c
		where c.cas_id=v_cas_id ;

	   select id    into v_idusuarioo
	   FROM public.users
	   where email =v_usuario;

	    SELECT act_id, act_nodo_id INTO v_act_id, v_act_nodo_id
	    FROM public.rmx_vys_actividades
	    WHERE act_prc_id = (
	        SELECT act_prc_id
	        FROM public.rmx_vys_actividades
	        WHERE act_nodo_id = v_act_nodo_ido AND act_id = v_act_ido
	    ) AND act_data->>'act_orden' = '100' AND act_estado = 'A';



        id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
        id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);

        update rmx_vys_historico_casos
        set htc_cas_data = v_cas_data::jsonb,
            htc_cas_data_valores = v_cas_data_valores::json,
            htc_cas_modificado = now()
        where htc_id = hitorico;

        update rmx_vys_historico_casos
        set htc_cas_data = jsonb_set(htc_cas_data, '{ESTADO_DERIVACION}', '"ENMIENDA"', false)

        where htc_id = hitorico;

               update rmx_vys_historico_casos
        set

        htc_cas_data = jsonb_set(htc_cas_data, '{DESCRIPCION_DERIVACION}',  to_jsonb(v_observacion), false)

        where htc_id = hitorico;

       select email    into vemail
	   FROM public.users
	   where id =v_cas_usr_id;

        UPDATE rmx_vys_casos SET
					cas_nodo_id = v_act_nodo_id,
					cas_act_id = v_act_id,
					cas_modificado = now(),
					--cas_data = v_cas_data::json,
					--cas_data_valores = v_cas_data_valores::json,
				 cas_data = jsonb_set(
        jsonb_set(
            cas_data,
            '{usuario_enmienda}',
            to_jsonb(vemail::text)
        ),
        '{act_id_e}',
        to_jsonb(v_act_ido::text),
        true -- Este argumento indica que se debe realizar una inserción si la clave no existe
    ) || jsonb_set(
        '{}'::jsonb, -- Inicializa un objeto JSONB vacío
        '{act_nodo_id_e}',
        to_jsonb(v_act_nodo_ido::text)
    ),


					cas_usr_id = v_idusuarioo, --v_cas_usr_id,
					cas_estado = v_cas_estado
				where cas_id = v_cas_id;

        INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id,
            htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id, htc_cas_correlativo, htc_cas_padre_id)
        	SELECT id_caso_real, cas_act_id, cas_nodo_id, cas_data, cas_data_valores, now(), null, cas_usr_id, cas_estado, cas_cod_id, cas_correlativo, cas_padre_id
        	FROM public.rmx_vys_casos
       WHERE cas_id = v_cas_id;

        RETURN QUERY  select v_usuario;
    END;
$function$ ;

