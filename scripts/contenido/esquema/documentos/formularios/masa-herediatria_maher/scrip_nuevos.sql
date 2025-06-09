CREATE OR REPLACE FUNCTION public.sp_legal_masa_validar(v_cua text)
 RETURNS TABLE(r_val_codigo text)
 LANGUAGE plpgsql
AS $function$
    DECLARE 
            o_estado_legal text;
            o_val_codigo text;
    begin
    
	     	
				select       (
                            SELECT datos.valor->>'frm_value'
                            FROM (
                                SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                            ) datos
                            WHERE datos.valor->>'frm_campo' = 'ESTADO_LEGAL'
                        ) AS estado_legal   
                        into   o_estado_legal
                                from rmx_vys_casos c
                                join rmx_vys_actividades a ON a.act_id = c.cas_act_id
                                join rmx_vys_nodos n on nodo_id = a.act_nodo_id
                                where cas_data->>'AS_CUA'= v_cua and cas_cod_id like 'LEGAL/%' 
                                and  act_data->>'act_orden' = '200'
                                order by cas_id asc;
                   
                        RAISE NOTICE 'no o_estado_legal : %', o_estado_legal;

                    IF o_estado_legal = 'VAL'  then    
                        o_val_codigo :='VALIDO';
					ELSE
                        o_val_codigo :='NO VALIDO';
					END IF;     
				  ----------------------------------------------------------------------------------------------------------------------
          RETURN QUERY SELECT o_val_codigo;           
                            
                           
    END;
$function$
;



    CREATE OR REPLACE FUNCTION public.sp_legal_masa_datos_herederos(v_cua text)
    RETURNS TABLE(r_datos_herederos jsonb)
    LANGUAGE plpgsql
    AS $function$
        DECLARE 
            
            
                o_estado_legal text;
                o_val_codigo text;
                o_datos_herederos jsonb;


            
        begin
        
                
                    select      (SELECT datos.valor->>'frm_value'
                                FROM (
                                    SELECT jsonb_array_elements(c.cas_data_valores) AS valor
                                ) datos
                                WHERE datos.valor->>'frm_campo' = 'GRILLA_DAHERDERO'
                            )::jsonb AS dato_grilla      
                            into   o_datos_herederos
                                    from rmx_vys_casos c
                                    join rmx_vys_actividades a ON a.act_id = c.cas_act_id
                                    join rmx_vys_nodos n on nodo_id = a.act_nodo_id
                                    where cas_data->>'AS_CUA'= v_cua and cas_cod_id like 'LEGAL/%' 
                                    and  act_data->>'act_orden' = '200'
                                    order by cas_id asc;
                    
                            RAISE NOTICE 'no o_estado_legal : %', o_estado_legal;
        
                    ----------------------------------------------------------------------------------------------------------------------
            RETURN QUERY SELECT o_datos_herederos;           
                                
                            
        END;
    $function$
    ;


  CREATE OR REPLACE FUNCTION public.sp_legal_masa_guardar_herederos(herederos text, numero_tramite text)
    RETURNS TABLE(r_datos text)
    LANGUAGE plpgsql
    AS $function$
        DECLARE 
            
            
                o_estado_legal text;
                o_val_codigo text;
                o_datos_herederos jsonb;


            
        begin
        

           UPDATE public.rmx_vys_casos
                SET cas_data_valores = jsonb_build_array(
                    jsonb_build_object(
                        'frm_tipo', 'GRID',
                        'frm_campo', 'GRILLA_DERECHOHABIENTES',
                        'frm_value', '[]'::jsonb,
                        'frm_deshabilitado', 'true',
                        'frm_deshabilitadoo', true
                    )
                )
                WHERE cas_cod_id = numero_tramite
                AND cas_data_valores = '[]'::jsonb;
                
    


    WITH updated_json AS (
    SELECT 
        cas_id,
        jsonb_agg(
            CASE
                WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
                    jsonb_set(elem, '{frm_value}', '"2024-07-24"'::jsonb)
                WHEN elem->>'frm_campo' = '_HORA' THEN
                    jsonb_set(elem, '{frm_value}', '"14:45"'::jsonb)
                WHEN elem->>'frm_campo' = '_CASO_GESTION' THEN
                    jsonb_set(elem, '{frm_value}', '2024'::jsonb)
                WHEN elem->>'frm_campo' = '_CASO_PERIODO' THEN
                    jsonb_set(elem, '{frm_value}', '"07"'::jsonb)
                ELSE
                    elem
            END
        ) AS updated_json
    FROM 
        public.rmx_vys_casos,
        jsonb_array_elements(cas_data_valores) AS elem
    WHERE 
        cas_id = 149171
    GROUP BY 
        cas_id
)
UPDATE public.rmx_vys_casos
SET 
    cas_data_valores = updated_json.updated_json
FROM 
    updated_json
WHERE 
    public.rmx_vys_casos.cas_id = updated_json.cas_id;

        
                    ----------------------------------------------------------------------------------------------------------------------
            RETURN QUERY SELECT 'si'::text;           
                                
                            
        END;
    $function$
    ;

--v_apellidoCasada text, v_complemento text, v_correoElectronico text, v_direccion text, v_documentoIdentidad text, v_fechaDefuncion text, v_fechaNacimiento text, v_idEstadoCivil text, v_idGenero text, v_idNacionalidad text, v_idPersonaSip text, v_idSipAsegurados text, v_primerApellido text, v_primerNombre text, v_segundoApellido text, v_segundoNombre text, v_telefonoCelular text, v_telefonoFijo text, v_tipoIdentidad text
 
 
CREATE OR REPLACE FUNCTION public.sp_legal_masa_guardar_herederos(herederos text, numero_tramite text,v_apellidoCasada text, v_complemento text, v_correoElectronico text, v_direccion text, v_documentoIdentidad text, v_fechaDefuncion text, v_fechaNacimiento text, v_idEstadoCivil text, v_idGenero text, v_idNacionalidad text, v_idPersonaSip text, v_idSipAsegurados text, v_primerApellido text, v_primerNombre text, v_segundoApellido text, v_segundoNombre text, v_telefonoCelular text, v_telefonoFijo text, v_tipoIdentidad text, v_cua text)
 RETURNS TABLE(r_datos text)
 LANGUAGE plpgsql
AS $function$
        DECLARE 
            
            
                o_estado_legal text;
                o_val_codigo text;
                o_datos_herederos jsonb;
                o_cas_id integer;


            
        begin
        

      /*  UPDATE public.rmx_vys_casos
       SET cas_data_valores=herederos::jsonb
       <WHERE cas_id=149171;*/
            UPDATE public.rmx_vys_casos
            SET cas_data_valores = jsonb_build_array(
                jsonb_build_object(
                    'frm_tipo', 'GRID',
                    'frm_campo', 'GRILLA_DERECHOHABIENTES',
                    'frm_value', '[]'::jsonb,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'DROPDOWNLIST',
                    'frm_campo', 'AS_TIPO_EAP',
                    'frm_value', 'CVEAP-A10',
                    'frm_value_label', '1. SOLICITUD DE MASA HEREDITARIA - SALDO EN CUENTA',
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TITLE',
                    'frm_campo', 'SUBTITLE_1',
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'HIDDEN',
                    'frm_campo', 'FECHA_INICIO_TRAMITE',
                    'frm_value', '2025-02-12',
                    'frm_deshabilitado', 'false',
                    'frm_deshabilitadoo', false
                ),
                jsonb_build_object(
                    'frm_tipo', 'SUBTITLE',
                    'frm_campo', 'SUBTITULO_1',
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'DROPDOWNLIST',
                    'frm_campo', 'AS_TIPO_DOCUMENTO',
                    'frm_value', 'I',
                    'frm_value_label', 'CEDULA IDENTIDAD',
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'NUMBER',
                    'frm_campo', 'AS_CI',
                    'frm_value', v_documentoIdentidad,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_COMPLEMENTO',
                    'frm_value', v_complemento,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'DATE',
                    'frm_campo', 'AS_NACIMIENTO',
                    'frm_value', v_fechaNacimiento,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'NUMBER',
                    'frm_campo', 'AS_CUA',
                    'frm_value', v_cua,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_PRIMER_APELLIDO',
                    'frm_value', v_primerApellido,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_SEGUNDO_APELLIDO',
                    'frm_value', v_segundoApellido,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_APELLIDO_CASADA',
                    'frm_value', v_apellidoCasada,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_PRIMER_NOMBRE',
                    'frm_value', v_primerNombre,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_SEGUNDO_NOMBRE',
                    'frm_value', v_segundoNombre,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_ESTADO_CIVIL',
                    'frm_value', v_idEstadoCivil,
                    'frm_value_label', v_idEstadoCivil,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'TEXT',
                    'frm_campo', 'AS_GENERO',
                    'frm_value', v_idGenero,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'DATE',
                    'frm_campo', 'AS_FECHA_FALLECIMIENTO',
                    'frm_value', v_fechaDefuncion,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                ),
                jsonb_build_object(
                    'frm_tipo', 'HIDDEN',
                    'frm_campo', 'AS_IDPERSONA',
                    'frm_value', v_idPersonaSip,
                    'frm_deshabilitado', 'true',
                    'frm_deshabilitadoo', true
                )
                
            )
            WHERE cas_cod_id = numero_tramite
            AND cas_data_valores = '[]'::jsonb;
        

        
            select cas_id 
            into o_cas_id
            from rmx_vys_casos rvc
            where cas_cod_id=numero_tramite ;
    


    WITH updated_json AS (
    SELECT 
        cas_id,
        jsonb_agg(
            CASE
                WHEN elem->>'frm_campo' = 'GRILLA_DERECHOHABIENTES' THEN
                    jsonb_set(elem, '{frm_value}', herederos::jsonb)
                WHEN elem->>'frm_campo' = '_HORA' THEN
                    jsonb_set(elem, '{frm_value}', '"14:02"'::jsonb)
                    
                ELSE
                    elem
            END
        ) AS updated_json
    FROM 
        public.rmx_vys_casos,
        jsonb_array_elements(cas_data_valores) AS elem
    WHERE 
        cas_id = o_cas_id
    GROUP BY 
        cas_id
)
UPDATE public.rmx_vys_casos
SET 
    cas_data_valores = updated_json.updated_json
FROM 
    updated_json
WHERE 
    public.rmx_vys_casos.cas_id = updated_json.cas_id;

        
                    ----------------------------------------------------------------------------------------------------------------------
            RETURN QUERY SELECT 'si'::text;           
                                
                            
        END;
    $function$
;

---CREACION DE LA TABLA DE TUTORES 

         

CREATE TABLE public.tutores (
	tut_id serial4 NOT NULL,
	tut_cod_id varchar NULL,
	tut_id_persona int4 NULL,
	tut_ci_persona int4 NULL,
	tut_data jsonb NOT NULL DEFAULT '{}'::jsonb,
	tut_cas_id_ int4 NULL,
	tut_registrado timestamp NULL DEFAULT now(),
	tut_modificado timestamp NULL DEFAULT now(),
	tut_usr_id int4 NOT NULL,
	tut_estado bpchar(1) NULL DEFAULT 'T'::bpchar,
	CONSTRAINT tutores_pkey PRIMARY KEY (tut_id)
);