CREATE TABLE service_logs (
    id SERIAL PRIMARY KEY,
    method TEXT, -- GET, POST, etc.
    endpoint TEXT, -- URL del servicio
    request_body JSONB, -- Cuerpo de la petición (para POST)
    response_body JSONB, -- Respuesta del servicio
    status_code TEXT, -- Código de estado HTTP
    headers JSONB, -- Headers de la petición
    client_ip TEXT,   -- Dirección IP del cliente
    user TEXT ,  
    tramite TEXT,
    registrado timestamp without time zone NOT NULL DEFAULT now(),
    modificado timestamp without time zone NOT NULL DEFAULT now()
);

CREATE OR REPLACE FUNCTION public.sp_create_service_logs(x_method text, x_endpoint text, x_request_body text, x_response_body text, x_status_code text,
 x_headers text, x_client_ip text, x_user text, x_tramite text)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE
id_log integer; 
o_ip text; 
BEGIN  
o_ip := (select   inet_client_addr());
	    INSERT INTO service_logs(
			method,
			endpoint,
			request_body,
			response_body,
			status_code,
			headers,
			client_ip,
			user,
			tramite
			)
	    VALUES (
				x_method,
				x_endpoint,
				x_request_body::jsonb,
				x_response_body::jsonb,
				x_status_code,
				x_headers::jsonb,
				x_client_ip,
				x_user,
				x_tramite
		  
		  ) RETURNING id INTO id_log;
	      return id_log;
END;
$function$
;


select *  from public.sp_create_service_logs('POST','/api/login', '{"username": "juan", "password": "123456"}',  '{"message": "Login exitoso"}','200',
 '{"Content-Type": "application/json"}', '172.68.65.159','juan@gestora.bo', 'JUB/173/2025')
