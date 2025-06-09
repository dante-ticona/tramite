
CREATE OR REPLACE FUNCTION public.sp_insertar_nodos(
	codigo text,
	descripcion text,
	padre_id integer,
	usrid integer)
    RETURNS integer
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
DECLARE 
            ultimo integer;
        BEGIN
			insert into rmx_vys_nodos (nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_usr_id) 
                VALUES (codigo, descripcion, padre_id, usrid);
                ultimo := lastval();
			insert into rmx_vys_usrnodos_roundrobin (rr_nodo_id, rr_ultimo_usr_id, rr_usr_id) 
			values (ultimo, 0, usrid);
			return ultimo;
        END;
$BODY$;



insert into rmx_vys_usrnodos_roundrobin (rr_nodo_id, rr_ultimo_usr_id, rr_usr_id) 
select nodo_id, 0, nodo_usr_id from rmx_vys_nodos n where n.nodo_estado = 'A' 

