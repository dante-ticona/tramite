
CREATE OR REPLACE FUNCTION public.sp_act_cas_rmin_rech_jub_validar(v_cas_id_jub text, v_cas_id_pcc text)
 RETURNS TABLE(r_htc_id integer, r_codigo text, r_val_codigo text, r_monto_rechado text, r_act_orden text, r_estado_derivacion text, r_descripcion_derivacion text, r_dias integer, r_descripcion text, r_fecha_nacimiento text, r_fecha_fallecimiento text)
 LANGUAGE plpgsql
AS $function$
DECLARE 
    id_caso_real integer;
    o_htc_id integer;
    o_tipo_rechazo text;
    o_monto_rechazo text;
    o_act_orden text;
    o_estado_derivacion text;
    o_descripcion_derivacion text;
  	o_descripcion_rechazo text;
	o_fecha_nacimiento text;
	o_fecha_fallecimiento text;
    fecha_termino timestamp;
    v_codigo text := 'RC0';
    v_monto_rechado text := '0';
    val_codigo text := 'NO VALIDO';
    dias integer := 0;


    
BEGIN
    SELECT CASE WHEN cas_padre_id = 0 THEN cas_id ELSE cas_padre_id END
    INTO id_caso_real
    FROM rmx_vys_casos
    WHERE cas_cod_id = v_cas_id_jub;
    
    SELECT 
        htc_id, 
        (SELECT datos.valor->>'frm_value'
         FROM jsonb_array_elements(c.htc_cas_data_valores) AS datos(valor)
         WHERE datos.valor->>'frm_campo' = 'MOTIVO_RECHAZO_JUB') AS tipo_rechazo,
        (SELECT datos.valor->>'frm_value'
         FROM jsonb_array_elements(c.htc_cas_data_valores) AS datos(valor)
         WHERE datos.valor->>'frm_campo' = 'MONTO_CC') AS monto_rechazo,
        act_data->>'act_orden',
        htc_cas_data->>'ESTADO_DERIVACION',
        htc_cas_data->>'DESCRIPCION_DERIVACION',
        htc_cas_registrado,
			(
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(c.htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_NACIMIENTO'
                        ) AS fecha_nacimientos,
 			(
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(c.htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                        ) AS fecha_fallecimiento
    INTO 
        o_htc_id, 
        o_tipo_rechazo, 
        o_monto_rechazo, 
        o_act_orden, 
        o_estado_derivacion, 
        o_descripcion_derivacion, 
        fecha_termino,
		o_fecha_nacimiento,
		o_fecha_fallecimiento
    FROM rmx_vys_historico_casos c
    INNER JOIN rmx_vys_actividades ON act_id = htc_cas_act_id
    INNER JOIN rmx_vys_nodos ON nodo_id = htc_cas_nodo_id
    WHERE htc_cas_id = id_caso_real
      AND act_data->>'act_orden' = '200'
      AND htc_cas_data->>'ESTADO_DERIVACION' = 'CON NOTA DE RECHAZO'
    ORDER BY htc_id ASC
    LIMIT 1; 
    
    IF fecha_termino IS NOT NULL THEN
        dias := DATE_PART('day', NOW() - fecha_termino);
    ELSE
        dias := 0;
    END IF;
    
    IF o_htc_id IS NOT NULL THEN
        v_codigo := o_tipo_rechazo;
        v_monto_rechado := o_monto_rechazo;
        val_codigo := 'VALIDO';
		o_descripcion_rechazo := ( select nombre  from dominio   where dominio = 'manejo_codigo' and codigo = o_tipo_rechazo);


    ELSE
        SELECT 
            htc_id, 
            act_data->>'act_orden',
            htc_cas_data->>'ESTADO_DERIVACION',
            htc_cas_data->>'DESCRIPCION_DERIVACION',
			(
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(c.htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_NACIMIENTO'
                        ) AS fecha_nacimientos,
 			(
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(c.htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'AS_FECHA_FALLECIMIENTO'
                        ) AS fecha_fallecimiento
        INTO 
            o_htc_id, 
            o_act_orden, 
            o_estado_derivacion, 
            o_descripcion_derivacion,
			o_fecha_nacimiento,
			o_fecha_fallecimiento
        FROM rmx_vys_historico_casos c
        INNER JOIN rmx_vys_actividades ON act_id = htc_cas_act_id
        INNER JOIN rmx_vys_nodos ON nodo_id = htc_cas_nodo_id
        WHERE htc_cas_id = id_caso_real
        ORDER BY htc_id DESC
        LIMIT 1;
    END IF;
    
    RETURN QUERY SELECT 
        o_htc_id, 
        v_codigo, 
        val_codigo, 
        v_monto_rechado, 
        o_act_orden, 
        o_estado_derivacion, 
        o_descripcion_derivacion, 
        dias,
		o_descripcion_rechazo,
		o_fecha_nacimiento,
		o_fecha_fallecimiento;
END;
$function$
;
	