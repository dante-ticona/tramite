CREATE TABLE query_logs (
   id SERIAL PRIMARY KEY,
   query TEXT ,
   query_response jsonb, 
   user TEXT ,  L
   client_ip TEXT,  
   de_donde TEXT,
   tramite TEXT,
   registrado timestamp without time zone NOT NULL DEFAULT now(),
   modificado timestamp without time zone NOT NULL DEFAULT now()
);


CREATE OR REPLACE FUNCTION public.sp_create_query_logs(x_query text, x_query_response text, x_user text, x_de_donde text, x_tramite text, x_ip text)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
id_log integer; 
o_ip text; 
BEGIN  
o_ip := (select   inet_client_addr());
	    INSERT INTO query_logs(
			query,
			query_response,
			user,
			client_ip,
			de_donde,
			tramite)
	    VALUES (
		  	x_query,
		  	x_query_response::jsonb,
		  	x_user, 
		  	x_ip,
			x_de_donde,
			x_tramite) RETURNING id INTO id_log;
	      return id_log;
END;
$function$
;
