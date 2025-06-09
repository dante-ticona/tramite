
CREATE OR REPLACE FUNCTION public.f_rmx_vys_casos_usuario_estado()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
declare
var_usr_id int8;
idx int := 0;
var_email text;
begin
      --raise exception 'dfjklgjfdkjdfklgk';
      if NEW.cas_nodo_id = 97
      then
      --raise exception 'LLLEE';
            if NEW.cas_nodo_id <> OLD.cas_nodo_id
            then
                  if NEW.cas_act_id = (select act_id from rmx_vys_actividades where act_data->>'act_orden' = '20' and act_id = NEW.cas_act_id) then
                        var_email := OLD.cas_data->>'USUARIO_REGISTRO';
                  else if OLD.cas_data ? 'USUARIO_SUPERVISOR' then
                                    var_email := OLD.cas_data->>'USUARIO_SUPERVISOR';
                              else
                                    var_email := OLD.cas_data->>'USUARIO_REGISTRO';
                              end if;
                  end if;
                      select u.id
                      into var_usr_id
                      from users u
                        where u.email = var_email ;
                        NEW.cas_usr_id := var_usr_id;
              NEW.cas_estado := 'T';
            end if;
      end if;

      if NEW.cas_act_id = 53 and substring(NEW.cas_cod_id FROM '([^/]+)') = 'JUB' and not exists (select 1
            from jsonb_array_elements(NEW.cas_data_valores) as datos
            where datos ->> 'frm_campo' = 'ESTADO_GENERACION_EAP_ORIGINAL')
            then
            for idx in (select i
                              from jsonb_array_elements(NEW.cas_data_valores) with ordinality arr(valores, i)
                              where valores ->> 'frm_campo' = 'ESTADO_GENERACION_EAP'
                              and valores ->> 'frm_value' = 'A')
            loop
                  raise notice '%', (NEW.cas_data_valores -> (idx-1));
                  NEW.cas_data_valores := jsonb_set(
                      NEW.cas_data_valores,
                      array[idx-1]::int[],
                      jsonb_set(NEW.cas_data_valores -> (idx-1), '{frm_value}', '"M"'::jsonb)
                  );
                  NEW.cas_data_valores := NEW.cas_data_valores || '{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP_ORIGINAL", "frm_value": "A", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}'::jsonb;
            end loop;

           update "_gp_documentos" gd
                  set doc_descripcion = '-'||doc_descripcion
                  where gd.doc_codigo = NEW.cas_cod_id
                  and doc_referencia = 'documento_PRES'
                  and doc_descripcion like 'CONTRATO%';
      end if;
      --// retiros minimos \\--
      if NEW.cas_act_id = 73 and substring(NEW.cas_cod_id FROM '([^/]+)') = 'RMIN' and not exists (select 1
            from jsonb_array_elements(NEW.cas_data_valores) as datos
            where datos ->> 'frm_campo' = 'ESTADO_GENERACION_EAP_ORIGINAL')
            then
            for idx in (select i
                              from jsonb_array_elements(NEW.cas_data_valores) with ordinality arr(valores, i)
                              where valores ->> 'frm_campo' = 'ESTADO_GENERACION_EAP'
                              and valores ->> 'frm_value' = 'A')
            loop
                  raise notice '%', (NEW.cas_data_valores -> (idx-1));


                  NEW.cas_data_valores := jsonb_set(
                      NEW.cas_data_valores,
                      array[idx-1]::int[],
                      jsonb_set(NEW.cas_data_valores -> (idx-1), '{frm_value}', '"M"'::jsonb)
                  );

                  NEW.cas_data_valores := NEW.cas_data_valores || '{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP_ORIGINAL", "frm_value": "A", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}'::jsonb;
            end loop;
            
           update "_gp_documentos" gd
                  set doc_descripcion = '-'||doc_descripcion
                  where gd.doc_codigo = NEW.cas_cod_id
                  and doc_referencia = 'documento_PRES'
                  and doc_descripcion like 'CONTRATO%';
      end if;



      return NEW;
end;
$function$
;




CREATE OR REPLACE FUNCTION public.f_rmx_vys_casos_usuario_estado()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
declare
      var_usr_id int8;
      idx int := 0;
      var_email text;
begin
      --raise exception 'dfjklgjfdkjdfklgk';
      if NEW.cas_nodo_id = 97 then
            --raise exception 'LLLEE';
            if NEW.cas_nodo_id <> OLD.cas_nodo_id then
                  if NEW.cas_act_id = (
                        select act_id
                        from rmx_vys_actividades
                        where act_data->>'act_orden' = '20'
                          and act_id = NEW.cas_act_id
                  ) then
                        var_email := OLD.cas_data->>'USUARIO_REGISTRO';
                  else
                        if OLD.cas_data ? 'USUARIO_SUPERVISOR' then
                              var_email := OLD.cas_data->>'USUARIO_SUPERVISOR';
                        else
                              var_email := OLD.cas_data->>'USUARIO_REGISTRO';
                        end if;
                  end if;
                  select u.id
                  into var_usr_id
                  from users u
                  where u.email = var_email;
                  NEW.cas_usr_id := var_usr_id;
                  NEW.cas_estado := 'T';
            end if;
      end if;

      if NEW.cas_act_id = 53
         and substring(NEW.cas_cod_id FROM '([^/]+)') = 'JUB'
         and not exists (
                  select 1
                  from jsonb_array_elements(NEW.cas_data_valores) as datos
                  where datos ->> 'frm_campo' = 'ESTADO_GENERACION_EAP_ORIGINAL'
         )
      then
            for idx in (
                  select i
                  from jsonb_array_elements(NEW.cas_data_valores) with ordinality arr(valores, i)
                  where valores ->> 'frm_campo' = 'ESTADO_GENERACION_EAP'
                    and valores ->> 'frm_value' = 'A'
            )
            loop
                  raise notice '%', (NEW.cas_data_valores -> (idx-1));
                  NEW.cas_data_valores := jsonb_set(
                        NEW.cas_data_valores,
                        array[idx-1]::int[],
                        jsonb_set(NEW.cas_data_valores -> (idx-1), '{frm_value}', '"M"'::jsonb)
                  );
                  NEW.cas_data_valores := NEW.cas_data_valores || '{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP_ORIGINAL", "frm_value": "A", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}'::jsonb;
            end loop;

            update "_gp_documentos" gd
            set doc_descripcion = '-'||doc_descripcion
            where gd.doc_codigo = NEW.cas_cod_id
              and doc_referencia = 'documento_PRES'
              and doc_descripcion like 'CONTRATO%';
      end if;
      --// inicio retiros minimos \\--
      if NEW.cas_act_id = 73 and substring(NEW.cas_cod_id FROM '([^/]+)') = 'RMIN' and not exists (select 1
            from jsonb_array_elements(NEW.cas_data_valores) as datos
            where datos ->> 'frm_campo' = 'ESTADO_GENERACION_EAP_ORIGINAL')
            then
            for idx in (select i
                              from jsonb_array_elements(NEW.cas_data_valores) with ordinality arr(valores, i)
                              where valores ->> 'frm_campo' = 'ESTADO_GENERACION_EAP'
                              and valores ->> 'frm_value' = 'A')
            loop
                  raise notice '%', (NEW.cas_data_valores -> (idx-1));


                  NEW.cas_data_valores := jsonb_set(
                      NEW.cas_data_valores,
                      array[idx-1]::int[],
                      jsonb_set(NEW.cas_data_valores -> (idx-1), '{frm_value}', '"M"'::jsonb)
                  );

                  NEW.cas_data_valores := NEW.cas_data_valores || '{"frm_tipo": "HIDDEN", "frm_campo": "ESTADO_GENERACION_EAP_ORIGINAL", "frm_value": "A", "frm_deshabilitado": "false", "frm_deshabilitadoo": false}'::jsonb;
            end loop;
            
           update "_gp_documentos" gd
                  set doc_descripcion = '-'||doc_descripcion
                  where gd.doc_codigo = NEW.cas_cod_id
                  and doc_referencia = 'documento_PRES'
                  and doc_descripcion like 'CONTRATO%';
      end if;
      --// fin retiros minimos \\--



      return NEW;
end;
$function$
;
