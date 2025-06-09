-- DROP FUNCTION public.f_gp_documentos_aud();



CREATE TABLE public.gp_documentos_aud (
	id_aud int8 GENERATED ALWAYS AS IDENTITY( INCREMENT BY 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1 NO CYCLE) NOT NULL,
	doc_id int8 NOT NULL,
	registros jsonb NULL,
	tipo_operacion_aud varchar(50) NULL,
	fecha_operacion_aud timestamp NULL,
	usuario_bd_aud varchar(50) NULL,
	usuario_aud varchar(50) NULL,
	cliente_ip varchar(70) NULL,
	CONSTRAINT boletas_gp_documentos_aud_pk PRIMARY KEY (id_aud)
);

----------------------------------------------------
CREATE INDEX i_idbolmae_gp_documentos ON public.gp_documentos_aud USING btree (doc_id);
CREATE OR REPLACE FUNCTION public.f_gp_documentos_aud()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$

declare

   begin

      if tg_op='INSERT' then  

         insert into public.gp_documentos_aud(doc_id,registros,tipo_operacion_aud,fecha_operacion_aud,usuario_bd_aud,usuario_aud,cliente_ip)

                                                       values(new.doc_id,row_to_json(new),'INSERT',now(),user,null ,inet_client_addr()::varchar );

            return new;

         elseif tg_op='UPDATE'  then 

             insert into public.gp_documentos_aud(doc_id,registros,tipo_operacion_aud,fecha_operacion_aud,usuario_bd_aud,usuario_aud,cliente_ip)

                                                       values(new.doc_id,row_to_json(new),'UPDATE',now(),user,null,inet_client_addr()::varchar  );

            return new;

        elseif tg_op='DELETE'  then

            insert into public.gp_documentos_aud(doc_id,registros,tipo_operacion_aud,fecha_operacion_aud,usuario_bd_aud,usuario_aud,cliente_ip)

                                                       values(old.doc_id,row_to_json(old),'DELETE',now(),user,null ,inet_client_addr()::varchar );

            return old;

      end if;

return null;

end;

$function$
;


---------------------------------------------------------------------------------------------------

create trigger tri_gp_documentos_aud after
insert
    or
delete
    or
update
    on
    public._gp_documentos for each row execute function f_gp_documentos_aud()