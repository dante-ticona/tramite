-- DROP FUNCTION public.sp_act_cas_rmin_rech_jub_validar(text, text);

CREATE OR REPLACE FUNCTION public.sp_act_cas_rmin_rech_jub_validar(v_cas_id_jub text, v_cas_id_pcc text)
 RETURNS TABLE(r_htc_id integer, r_codigo text, r_val_codigo text, r_monto_rechado text, r_act_orden text, r_estado_derivacion text, r_descripcion_derivacion text, r_dias integer, r_descripcion text, r_fecha_nacimiento text, r_fecha_fallecimiento text, r_cua text, r_ci text)
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
	o_cua text;
	o_ci text;
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
                        ) AS fecha_fallecimiento,
        htc_cas_data->>'AS_CUA',
        htc_cas_data->>'AS_CI'
    INTO 
        o_htc_id, 
        o_tipo_rechazo, 
        o_monto_rechazo, 
        o_act_orden, 
        o_estado_derivacion, 
        o_descripcion_derivacion, 
        fecha_termino,
		o_fecha_nacimiento,
		o_fecha_fallecimiento,
		o_cua,
		o_ci
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
                        ) AS fecha_fallecimiento,
        htc_cas_data->>'AS_CUA',
        htc_cas_data->>'AS_CI'
        INTO 
            o_htc_id, 
            o_act_orden, 
            o_estado_derivacion, 
            o_descripcion_derivacion,
			o_fecha_nacimiento,
			o_fecha_fallecimiento,
			o_cua,
			o_ci
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
		o_fecha_fallecimiento,	
		o_cua,
		o_ci;
END;
$function$
;








-- DROP FUNCTION public.sp_act_cas_rmin_rech_jub(text, text, text, text, text);

CREATE OR REPLACE FUNCTION public.sp_act_cas_rmin_rech_jub(v_cas_id_jub text, v_cas_id_pcc text, numerocuotas text, valorcuota text, saldobolivianos text)
 RETURNS TABLE(r_dias integer, r_codigo text)
 LANGUAGE plpgsql
AS $function$
    DECLARE 
			hitorico integer;
			id_caso_real integer;
			id_caso_real2 integer;
			id_nodo integer;
			data_jub jsonb;
			fecha_inicio timestamp;
			fecha_termino timestamp;
			dias  integer; 
			fecha_tramite text;
			fecha_notificacion_rechazo text;
			data_DT_FECHA_NAC jsonb;
			v_fila_grilla RECORD;
			v_grilla_campo RECORD;
			v_sin_p text;
			v_dato text;
			v_htc_id integer;
	 		json_query TEXT;
	 	 	resultado JSONB;
	 	 	v_codigo text;
			v_dias integer;
			v_contador integer; 
			v2_htc_id integer; 
			o_tipo_rechazo text;
			o_monto_rechazo text;
    begin
	    
	   
	     	id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
	  
            from rmx_vys_casos where cas_cod_id = v_cas_id_jub);

 				id_caso_real2 := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
	  
            from rmx_vys_casos where cas_cod_id = v_cas_id_pcc);

	       	RAISE NOTICE 'Valor de es mayor a trenta id_caso_real : %', id_caso_real;
	      
				SELECT htc_id, htc_cas_data_valores, htc_cas_registrado, (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'MOTIVO_RECHAZO_JUB'
                        ) AS tipo_rechazo,
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'MONTO_CC'
                        ) AS monto_rechazo into v_htc_id, data_jub, fecha_termino, o_tipo_rechazo,o_monto_rechazo ---act_data, act_data->>'act_descripcion', act_data->>'act_orden' htc_cas_registrado ,htc_cas_data->>'ESTADO_DERIVACION',*
                    from rmx_vys_historico_casos
                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id = id_caso_real
                   	and  act_data->>'act_orden' = '200' and htc_cas_data->>'ESTADO_DERIVACION' = 'CON NOTA DE RECHAZO'
                    order by htc_id asc;
                   
                    dias := (select DATE_PART('day', now() - fecha_termino) );
                   fecha_notificacion_rechazo := to_char(fecha_termino::date , 'YYYY-MM-DD')::text;
					   
					IF  dias> 30  THEN
							RAISE NOTICE 'Valor de es mayor a trenta dias : %', dias;
						fecha_tramite := to_char(now(), 'YYYY-MM-DD')::text; 
					ELSE
					      	RAISE NOTICE 'Valor es menor de los 30 dias : %', dias;
					      select cas_registrado into fecha_inicio from public.rmx_vys_casos rvc where rvc.cas_id = id_caso_real;
					      fecha_tramite :=  to_char(fecha_inicio::date , 'YYYY-MM-DD')::text;
					END IF;                                                      
                  	RAISE NOTICE 'Valor de dias: %', dias;   
                    RAISE NOTICE 'data_jub de dias:====>> %', data_jub;   
                    
                    ----------------------------------------------------------------------------------------------------------------------
                    IF v_htc_id > 0  then
							RAISE NOTICE 'tienes id : %', v_htc_id;
							v_codigo := 'R1';
						                
					        		UPDATE rmx_vys_casos SET
									cas_data_valores = data_jub		
									where    cas_cod_id = v_cas_id_pcc;
								
									--===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "ES_DH_FALLECIDO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
												-------------------------------------------------------------------------------------------------------------------------------	
					                             END LOOP;
					                            --===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "DH_FECHA_FALLECIDO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					                            END LOOP;
					                            --===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "DH_MATRIMONIO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					-------------------------------------------------------------------------------------------------------------------------------	
																
					                             END LOOP;
					                            	--===========================================================================================                        
					                     
					
					                  -----------------------------------------------------------------------------------------
					                                           WITH updated_json AS (
								  SELECT cas_id as id ,
								         jsonb_agg(
								           CASE
								             WHEN elem->>'frm_campo' = 'AS_TIPO_EAP' THEN
								               jsonb_set(elem, '{frm_value}', '"CVEAP-B8"')
								             ELSE
								               elem
								           END
								         ) AS updated_data
								  FROM public.rmx_vys_casos c,
								       jsonb_array_elements(cas_data_valores) AS elem
								          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
								                       cas_cod_id = v_cas_id_pcc
								  GROUP BY cas_id
								)
								-- Aplicamos la actualización a la tabla original
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = updated_json.updated_data
								FROM updated_json
								WHERE cas_id = updated_json.id;
								
								
								                     
								                         WITH updated_json AS (
								  SELECT cas_id as id ,
								         jsonb_agg(
								           CASE
								             WHEN elem->>'frm_campo' = 'AS_TIPO_EAP' THEN
								               jsonb_set(elem, '{frm_value_label}', '"1. SOLICITUD DE  RM/RF (POSTERIOR AL RECHAZO DE  JUBILACIÓN"')
								             ELSE
								               elem
								           END
								         ) AS updated_data
								  FROM public.rmx_vys_casos c,
								       jsonb_array_elements(cas_data_valores) AS elem
								          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
								                       cas_cod_id=v_cas_id_pcc
								  GROUP BY cas_id
								)
								-- Aplicamos la actualización a la tabla original
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = updated_json.updated_data
								FROM updated_json
								WHERE cas_id = updated_json.id;
								
								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'CASO_RECHAZADO',
								            'frm_value', v_cas_id_jub,
								            'frm_deshabilitado', 'false',
								            'frm_deshabilitadoo', false
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'DATE',
								            'frm_campo', 'FECHA_INICIO_TRAMITE',
								            'frm_value', fecha_tramite,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;

								

								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'DATE',
								            'frm_campo', 'FECHA_NOTIFICACION_RECHAZO',
								            'frm_value', fecha_notificacion_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
									UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'MOTIVO_RECHAZO',
								            'frm_value', o_tipo_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'MONTO_RECHAZO',
								            'frm_value', o_monto_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;


								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'AS_NUM_CUOTAS',
								            'frm_value', numeroCuotas,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;

								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'AS_VALOR_CUOTA',
								            'frm_value', valorCuota,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;

								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'AS_SALDO_ACUMULADO',
								            'frm_value', saldoBolivianos,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
			-------------------------------------------------------------------------------------------
							select count(*) into v_contador  from   public._gp_documentos where doc_codigo = v_cas_id_pcc  and doc_categoria != '';
							
							   IF v_contador  > 0  then 
							   
							   UPDATE public._gp_documentos
                                                            SET 
                                                            doc_referencia = '',
                                                            doc_categoria = ''
                                                            WHERE   doc_codigo= v_cas_id_pcc;
							   
							   	else
							   	
							  v2_htc_id := (	select htc_id
                                                            from rmx_vys_historico_casos
                                                            where  htc_cas_cod_id = v_cas_id_pcc order by htc_id asc limit 1);
														    
														     INSERT INTO public._gp_documentos ( doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion)
									                    select  id_caso_real2, doc_usr_id,v2_htc_id, v_cas_id_pcc , doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion
									                    FROM public._gp_documentos
									                    WHERE doc_codigo = v_cas_id_jub and doc_descripcion != 'FORMULARIO DE RECEPCIÓN DE DOCUMENTOS' ; 
								END IF; 
			-------------------------------------------------------------------------------------------
					
					ELSE
					     RAISE NOTICE 'no tienes : %', v_htc_id;
					      v_codigo := 'R2';
					     
					     UPDATE rmx_vys_casos SET
									cas_data_valores = '[]'::jsonb		
									where    cas_cod_id = v_cas_id_pcc;
					   
					END IF;                                                      
				
				  ----------------------------------------------------------------------------------------------------------------------
  
          RETURN QUERY SELECT dias::integer, v_codigo::text;           
                            
                           
    END;
$function$
;



CREATE OR REPLACE FUNCTION public.sp_act_cas_rmin_rech_pm(v_cas_id_jub text, v_cas_id_pcc text, descripcion_rechazo text)
 RETURNS TABLE(r_dias integer, r_codigo text)
 LANGUAGE plpgsql
AS $function$
    DECLARE 
			hitorico integer;
			id_caso_real integer;
			id_caso_real2 integer;
			id_nodo integer;
			data_jub jsonb;
			fecha_inicio timestamp;
			fecha_termino timestamp;
			dias  integer; 
			fecha_tramite text;
			fecha_notificacion_rechazo text;
			data_DT_FECHA_NAC jsonb;
			v_fila_grilla RECORD;
			v_grilla_campo RECORD;
			v_sin_p text;
			v_dato text;
			v_htc_id integer;
	 		json_query TEXT;
	 	 	resultado JSONB;
	 	 	v_codigo text;
			v_dias integer;
			v_contador integer; 
			v2_htc_id integer; 
			o_tipo_rechazo text;
			o_monto_rechazo text;
    begin
	    
	   
	     	id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
	  
            from rmx_vys_casos where cas_cod_id = v_cas_id_jub  limit 1 );

 				id_caso_real2 := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end as caso_id
	  
            from rmx_vys_casos where cas_cod_id = v_cas_id_pcc  limit 1);

	       	RAISE NOTICE 'Valor de es mayor a trenta id_caso_real : %', id_caso_real;
	      
				SELECT htc_id, htc_cas_data_valores, htc_cas_registrado, (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'MOTIVO_RECHAZO_JUB'
                        ) AS tipo_rechazo,
                         (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(htc_cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'MONTO_CC'
                        ) AS monto_rechazo into v_htc_id, data_jub, fecha_termino, o_tipo_rechazo,o_monto_rechazo ---act_data, act_data->>'act_descripcion', act_data->>'act_orden' htc_cas_registrado ,htc_cas_data->>'ESTADO_DERIVACION',*
                    from rmx_vys_historico_casos
                    inner join rmx_vys_actividades on act_id = htc_cas_act_id
                    inner join rmx_vys_nodos on nodo_id = htc_cas_nodo_id
                    inner join users on id = htc_cas_usr_id
                    where htc_cas_id = id_caso_real
                    order by htc_id desc limit 1;
                   
                    dias := (select DATE_PART('day', now() - fecha_termino) );
                   fecha_notificacion_rechazo := to_char(fecha_termino::date , 'YYYY-MM-DD')::text;
					   
					fecha_tramite := to_char(now(), 'YYYY-MM-DD')::text; 
                    
                                                                         
                  	RAISE NOTICE 'Valor de dias: %', dias;   
                    RAISE NOTICE 'data_jub de dias:====>> %', data_jub;   
                    
                    ----------------------------------------------------------------------------------------------------------------------
                    IF v_htc_id > 0  then
							RAISE NOTICE 'tienes id : %', v_htc_id;
							v_codigo := 'R1';
						                
					        		UPDATE rmx_vys_casos SET
									cas_data_valores = data_jub		
									where    cas_cod_id = v_cas_id_pcc;
								
									--===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "ES_DH_FALLECIDO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													
													
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					-------------------------------------------------------------------------------------------------------------------------------	
																
					                             END LOOP;
					                            
					                            
					                            	--===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "DH_FECHA_FALLECIDO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					-------------------------------------------------------------------------------------------------------------------------------	
																
					                             END LOOP;
					                            	--===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "DH_MATRIMONIO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					-------------------------------------------------------------------------------------------------------------------------------	
																
					                             END LOOP;
					                            	--===========================================================================================                        
					                             FOR v_fila_grilla IN  
									                    select  jsonb_path_query(a.jsonb::JSONB, '$[*] ? (@[*].col_campo == "DH_FECHA_EMISION_MATRIMONIO" )') 
														from 	(select   (
									                            SELECT datos.valor->>'frm_value'
									                            FROM (
									                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
									                            ) datos
									                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
									                        			)JSONB  
									                            from public.rmx_vys_casos c
									                            WHERE cas_cod_id = v_cas_id_pcc
									                            order by 1 desc limit 1 ) a 	
												loop
					                                raise notice 'jsonb_path_query -------------=> %',v_fila_grilla.jsonb_path_query;	
					                            ----------------------------------------------------------------------------------				
													WITH json_array AS (
													          select a.cas_id,jsonb_array_elements(a.jsonb::JSONB) AS persons  
													           from     (      select  cas_id, (
													                            SELECT datos.valor->>'frm_value'
													                            FROM (
													                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
													                            ) datos
													                            WHERE datos.valor->>'frm_campo' = 'GRILLA_DERECHOHABIENTES'
													                        )JSONB  
													                            from public.rmx_vys_casos c
													                            WHERE cas_cod_id = v_cas_id_pcc
													                            order by 1 desc limit 1) a 
													),
													filtered_elements AS (
													    SELECT  cas_id, persons,
													           jsonb_agg(elems) FILTER (WHERE NOT (elems @> v_fila_grilla.jsonb_path_query)) AS filtered_person
													    FROM json_array,
													         jsonb_array_elements(persons) AS elems
													    GROUP BY cas_id,persons
													)
													SELECT   jsonb_agg(filtered_person) AS new_json_data into data_DT_FECHA_NAC
													FROM filtered_elements;		
													RAISE NOTICE 'Valor de data_DT_FECHA_NAC: %', data_DT_FECHA_NAC;    
												-------------------------------------------------------------------------------------------------------------------------------
										                             WITH updated_json AS (
																	  SELECT cas_id as id ,
																	         jsonb_agg(
																	           CASE
																	             WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
																	               jsonb_set(elem, '{frm_value}', data_DT_FECHA_NAC)
																	             ELSE
																	               elem
																	           END
																	         ) AS updated_data
																	  FROM public.rmx_vys_casos c,
																	       jsonb_array_elements(cas_data_valores) AS elem
																	          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
																	                       cas_cod_id = v_cas_id_pcc
																	  GROUP BY cas_id
																	)
																	UPDATE public.rmx_vys_casos
																	SET cas_data_valores = updated_json.updated_data
																	FROM updated_json
																	WHERE cas_id = updated_json.id;
					-------------------------------------------------------------------------------------------------------------------------------	
					                             END LOOP;
					                  -----------------------------------------------------------------------------------------
					                                           WITH updated_json AS (
								  SELECT cas_id as id ,
								         jsonb_agg(
								           CASE
								             WHEN elem->>'frm_campo' = 'AS_TIPO_EAP' THEN
								               jsonb_set(elem, '{frm_value}', '"CVEAP-A10"')
								             ELSE
								               elem
								           END
								         ) AS updated_data
								  FROM public.rmx_vys_casos c,
								       jsonb_array_elements(cas_data_valores) AS elem
								          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
								                       cas_cod_id = v_cas_id_pcc
								  GROUP BY cas_id
								)
								-- Aplicamos la actualización a la tabla original
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = updated_json.updated_data
								FROM updated_json
								WHERE cas_id = updated_json.id;
								
								
								                     
								                         WITH updated_json AS (
								  SELECT cas_id as id ,
								         jsonb_agg(
								           CASE
								             WHEN elem->>'frm_campo' = 'AS_TIPO_EAP' THEN
								               jsonb_set(elem, '{frm_value_label}', '"9. SOLICITUD DE  RM/RF (POSTERIOR AL RECHAZO DE PENSIÓN POR MUERTE"')
								             ELSE
								               elem
								           END
								         ) AS updated_data
								  FROM public.rmx_vys_casos c,
								       jsonb_array_elements(cas_data_valores) AS elem
								          WHERE --(c.cas_estado = 'T' or c.cas_estado = 'E')       and
								                       cas_cod_id=v_cas_id_pcc
								  GROUP BY cas_id
								)
								-- Aplicamos la actualización a la tabla original
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = updated_json.updated_data
								FROM updated_json
								WHERE cas_id = updated_json.id;
								
								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'CASO_RECHAZADO',
								            'frm_value', v_cas_id_jub,
								            'frm_deshabilitado', 'false',
								            'frm_deshabilitadoo', false
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}',  -- la posición en el array
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'DATE',
								            'frm_campo', 'FECHA_INICIO_TRAMITE',
								            'frm_value', fecha_tramite,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
								
								UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'DATE',
								            'frm_campo', 'FECHA_NOTIFICACION_RECHAZO',
								            'frm_value', fecha_notificacion_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
									UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'MOTIVO_RECHAZO',
								            'frm_value', descripcion_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
									UPDATE public.rmx_vys_casos
								SET cas_data_valores = jsonb_set(
								    cas_data_valores,
								    '{999}', 
								    (
								        SELECT jsonb_build_object(
								            'frm_tipo', 'TEXT',
								            'frm_campo', 'MONTO_RECHAZO',
								            'frm_value', o_monto_rechazo,
								            'frm_deshabilitado', 'true',
								            'frm_deshabilitadoo', true
								        )
								    ))
								WHERE   cas_cod_id=v_cas_id_pcc;
							
			-------------------------------------------------------------------------------------------
							select count(*) into v_contador  from   public._gp_documentos where doc_codigo = v_cas_id_pcc  and doc_categoria != '';
							
							   IF v_contador  > 0  then 
							   
							   UPDATE public._gp_documentos
                                                            SET 
                                                            doc_referencia = '',
                                                            doc_categoria = ''
                                                            WHERE   doc_codigo= v_cas_id_pcc;
							   
							   	else
							   	
							  v2_htc_id := (	select htc_id
                                                            from rmx_vys_historico_casos
                                                            where  htc_cas_cod_id = v_cas_id_pcc order by htc_id asc limit 1);
					    
					     INSERT INTO public._gp_documentos ( doc_cas_id, doc_usr_id, doc_his_id, doc_codigo, doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion)
                    select  id_caso_real2, doc_usr_id,v2_htc_id, v_cas_id_pcc , doc_referencia, doc_categoria, doc_url, doc_doc_id, doc_registrado, doc_modificado, doc_usuario, doc_estado, doc_descripcion, doc_estado_preparacion, doc_documento_original_obligatorio, doc_presentacion_obligatoria, doc_copia_original, doc_id_persona_sip, doc_id_observacion, doc_detalle_documento, alerta, doc_prestacion
                    FROM public._gp_documentos
                    WHERE doc_codigo = v_cas_id_jub and doc_descripcion != 'FORMULARIO DE RECEPCIÓN DE DOCUMENTOS' ; 
								END IF; 
							
							
							
							
							
							
			-------------------------------------------------------------------------------------------

					                          
					
					ELSE
					     RAISE NOTICE 'no tienes : %', v_htc_id;
					      v_codigo := 'R2';
					     
					     UPDATE rmx_vys_casos SET
									cas_data_valores = '[]'::jsonb		
									where    cas_cod_id = v_cas_id_pcc;
					   
					END IF;                                                      
				
				  ----------------------------------------------------------------------------------------------------------------------
  
          RETURN QUERY SELECT dias::integer, v_codigo::text;           
                            
                           
    END;
$function$
;
