CREATE OR REPLACE FUNCTION public.sp_grabar_casos( 
    v_cas_act_id integer, 
    v_cas_nodo_id integer,
    v_cas_data text, 
    v_cas_data_valores text, 
    v_cas_usr_id integer, 
    v_codigo text
    )
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
DECLARE 
            ultimo integer;
        BEGIN
			insert into rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id, cas_cod_id, cas_estado) values
                     (v_cas_act_id, v_cas_nodo_id, v_cas_data::json, v_cas_data_valores::json, v_cas_usr_id, v_codigo, 'T');
                ultimo := (SELECT currval('rmx_vys_casos_cas_id_seq'));
			INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id, 
                htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id,htc_cas_correlativo, htc_cas_padre_id)
            SELECT * FROM rmx_vys_casos WHERE cas_id = ultimo;
			return ultimo;
        END;
$function$
;

CREATE OR REPLACE FUNCTION public.sp_derivar_caso(v_cas_id integer, v_cas_act_id integer, v_cas_nodo_id integer, v_cas_data text, v_cas_data_valores text, v_cas_usr_id integer, v_cas_estado text)
 RETURNS integer
 LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
        id_caso_real integer;
        id_nodo integer;
    BEGIN

        id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
        id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);

        update rmx_vys_historico_casos 
        set htc_cas_data = v_cas_data::json,
        htc_cas_data_valores=v_cas_data_valores::json
        where htc_id=hitorico;

        UPDATE rmx_vys_casos SET
					cas_nodo_id = v_cas_nodo_id,
					cas_act_id = v_cas_act_id,
					cas_data = v_cas_data::json,
					cas_data_valores = v_cas_data_valores::json,
					cas_modificado = now(),
					cas_usr_id = v_cas_usr_id,
					cas_estado = v_cas_estado
				where cas_id = v_cas_id;

        INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id, 
            htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id, htc_cas_correlativo, htc_cas_padre_id)
        SELECT * FROM rmx_vys_casos WHERE cas_id = v_cas_id;
        return hitorico;
    END;
$function$
;

CREATE OR REPLACE FUNCTION public.sp_derivar_caso_paralelo( 
        v_cas_id integer, 
        v_cas_act_id integer, 
        v_cas_nodo_id integer,
        v_cas_data text, 
        v_cas_data_valores text, 
        v_cas_usr_id integer, 
        v_cas_estado text,
        v_cas_cod_id text
    )
    RETURNS integer
    LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
        ultimo integer;
    BEGIN
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=v_cas_id order by htc_id desc limit 1);
        update rmx_vys_historico_casos 
        set htc_cas_data = v_cas_data::json,
        htc_cas_data_valores=v_cas_data_valores::json
        where htc_id=hitorico;
        UPDATE rmx_vys_casos SET
					cas_data = v_cas_data::json,
					cas_data_valores = v_cas_data_valores::json,
					cas_estado = 'W'
				where cas_id = v_cas_id;
        insert into rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id, cas_cod_id, cas_estado, cas_padre_id) values
                    (v_cas_act_id, v_cas_nodo_id, v_cas_data::json, v_cas_data_valores::json, v_cas_usr_id, v_cas_cod_id, v_cas_estado, v_cas_id);
                ultimo := (SELECT currval('rmx_vys_casos_cas_id_seq'));
        INSERT INTO rmx_vys_historico_casos (htc_cas_id, htc_cas_act_id, 
            htc_cas_nodo_id, htc_cas_data, htc_cas_data_valores, htc_cas_registrado, htc_cas_modificado, htc_cas_usr_id, htc_cas_estado,htc_cas_cod_id, htc_cas_correlativo,htc_cas_padre_id)
        SELECT v_cas_id, cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_registrado, cas_modificado, cas_usr_id, cas_estado, cas_cod_id, cas_correlativo, cas_padre_id FROM public.rmx_vys_casos
        --SELECT * FROM rmx_vys_casos 
        WHERE cas_id = ultimo;
        return ultimo;
    END;
$function$
;




CREATE OR REPLACE FUNCTION public.sp_actualizar_caso( 
        v_cas_id integer, 
        v_cas_act_id integer, 
        v_cas_nodo_id integer,
        v_cas_data text, 
        v_cas_data_valores text, 
        v_cas_usr_id integer, 
        v_cas_estado text
    )
    RETURNS integer
    LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
        id_caso_real integer;
        id_nodo integer;
    BEGIN
        UPDATE rmx_vys_casos SET
					cas_nodo_id = v_cas_nodo_id,
					cas_act_id = v_cas_act_id,
					cas_data = v_cas_data::json,
					cas_data_valores = v_cas_data_valores::json,
					cas_modificado = now(),
					cas_usr_id = v_cas_usr_id,
					cas_estado = v_cas_estado
				where cas_id = v_cas_id;
        
        id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
        id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);

        update rmx_vys_historico_casos 
        set htc_cas_data = v_cas_data::json,
        htc_cas_data_valores=v_cas_data_valores::json,
        htc_cas_modificado = now(),
        htc_cas_usr_id = v_cas_usr_id,
        htc_cas_estado = v_cas_estado
        where htc_id=hitorico;
        return hitorico;
    END;
$function$
;

CREATE OR REPLACE FUNCTION public.sp_actualizar_estado_caso( 
        v_cas_id integer, 
        v_cas_usr_id integer, 
        v_cas_estado text
    )
    RETURNS integer
    LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
        id_user integer;
        id_caso_real integer;
        id_nodo integer;
    BEGIN
        UPDATE rmx_vys_casos SET
					cas_usr_id = v_cas_usr_id,
					cas_estado = v_cas_estado
				where cas_id = v_cas_id;
        id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
        id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);
        update rmx_vys_historico_casos 
        set htc_cas_modificado = now(),
        htc_cas_usr_id = v_cas_usr_id,
        htc_cas_estado = v_cas_estado
        where htc_id=hitorico;
        return hitorico;
    END;
$function$
;


CREATE OR REPLACE FUNCTION public.sp_asignar_caso( 
        v_cas_id integer, 
        v_cas_nom text, 
        v_cas_estado text
    )
    RETURNS integer
    LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
        id_user integer;
        id_caso_real integer;
        id_nodo integer;
    BEGIN
        id_user := (select id from users where email_verified_at=v_cas_nom and status = 'A' limit 1);
        if id_user is NULL
           then id_user = 0;
        end if;
            UPDATE rmx_vys_casos SET
                        cas_usr_id = id_user,
                        cas_estado = v_cas_estado
                    where cas_id = v_cas_id;
            id_caso_real := (select case when cas_padre_id = 0 then cas_id else cas_padre_id end from rmx_vys_casos where cas_id = v_cas_id);
            id_nodo := (select cas_nodo_id from rmx_vys_casos where cas_id = v_cas_id);
            hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=id_caso_real and htc_cas_nodo_id= id_nodo order by htc_id desc limit 1);
            update rmx_vys_historico_casos 
            set htc_cas_modificado = now(),
            htc_cas_usr_id = id_user,
            htc_cas_estado = v_cas_estado
            where htc_id=hitorico;
        return hitorico;
    END;
$function$
;      


CREATE OR REPLACE FUNCTION public.sp_archivar_caso( 
        v_cas_id integer, 
        v_cas_usr_id integer, 
        v_cas_data text
    )
    RETURNS integer
    LANGUAGE plpgsql
AS $function$
    DECLARE 
        hitorico integer;
    BEGIN
        UPDATE rmx_vys_casos SET
					cas_data = v_cas_data::json,
					cas_modificado = now(),
					cas_usr_id = v_cas_usr_id,
					cas_estado = 'H'
				where cas_id = v_cas_id;
        hitorico := (select htc_id from rmx_vys_historico_casos where htc_cas_id=v_cas_id order by htc_id desc limit 1);
        update rmx_vys_historico_casos 
        set htc_cas_data = v_cas_data::json,
            htc_cas_modificado = now(),
            htc_cas_usr_id = v_cas_usr_id,
            htc_cas_estado = 'H'
        where htc_id=hitorico;
        return hitorico;
    END;
$function$
;

CREATE TABLE _gp_documentos(
    doc_id serial PRIMARY KEY,
    doc_cas_id integer not null,
    doc_usr_id integer NOT NULL,
    doc_his_id integer not null,
    doc_codigo text NOT NULL,
    doc_referencia text NOT NULL,
    doc_categoria text NOT NULL, --- GENERADO,ADJUNTO
    doc_url text NOT NULL, --- la url generica creada
    doc_doc_id integer not null, --- id de documento del filestorage
    doc_registrado timestamp NOT NULL DEFAULT now(),
    doc_modificado timestamp NOT NULL DEFAULT now(),
    doc_usuario integer NOT NULL,
    doc_estado char(1) NOT NULL DEFAULT 'A'
);

CREATE TABLE _gp_parametros_grales(
    prm_id serial PRIMARY KEY,
    prm_codigo text NOT NULL,
    prm_descripcion text NOT NULL,
    prm_categoria text NOT NULL,
    prm_padre_id text NOT NULL,
    prm_registrado timestamp NOT NULL DEFAULT now(),
    prm_modificado timestamp NOT NULL DEFAULT now(),
    prm_usr_id integer NOT NULL,
    prm_estado char(1) NOT NULL DEFAULT 'A'
);
INSERT INTO _gp_parametros_grales (prm_id, prm_codigo, prm_descripcion, prm_categoria, prm_padre_id, prm_usr_id, prm_estado) VALUES 
(96,'ORLP','LA PAZ','DEPARTAMENTO',95,1,'A'),
(97,'OROR','ORURO','DEPARTAMENTO',95,1,'A'),
(98,'ORPT','POTOSI','DEPARTAMENTO',95,1,'A'),
(100,'ORCBBA','COCHABAMBA','DEPARTAMENTO',99,1,'A'),
(101,'ORCH','CHUQUISACA','DEPARTAMENTO',99,1,'A'),
(102,'ORT','TARIJA','DEPARTAMENTO',99,1,'A'),
(104,'ORSCZ','SANTA CRUZ','DEPARTAMENTO',103,1,'A'),
(105,'ORBN','BENI','DEPARTAMENTO',103,1,'A'),
(106,'ORPN','PANDO','DEPARTAMENTO',103,1,'A'),
(137,'LA PAZ','LA PAZ','REGIONAL',96,1,'A'),
(138,'EL ALTO','EL ALTO','REGIONAL',96,1,'A'),
(139,'CARANAVI','CARANAVI','REGIONAL',96,1,'A'),
(140,'COPACABANA','COPACABANA','REGIONAL',96,1,'A'),
(141,'ACHACACHI','ACHACACHI','REGIONAL',96,1,'A'),
(153,'ORURO','ORURO','REGIONAL',97,1,'A'),
(157,'POTOSI','POTOSI','REGIONAL',98,1,'A'),
(158,'TUPIZA','TUPIZA','REGIONAL',98,1,'A'),
(160,'UYUNI','UYUNI','REGIONAL',98,1,'A'),
(161,'LLALLAGUA','LLALLAGUA','REGIONAL',98,1,'A'),
(129,'COCHABAMBA','COCHABAMBA','REGIONAL',100,1,'A'),
(130,'IVIRGARZAMA','IVIRGARZAMA','REGIONAL',100,1,'A'),
(131,'AIQUILE','AIQUILE','REGIONAL',100,1,'A'),
(122,'MONTEAGUDO','MONTEAGUDO','REGIONAL',101,1,'A'),
(123,'SUCRE','SUCRE','REGIONAL',101,1,'A'),
(124,'CAMARGO','CAMARGO','REGIONAL',101,1,'A'),
(183,'TARIJA','TARIJA','REGIONAL',102,1,'A'),
(184,'VILLAMONTES','VILLAMONTES','REGIONAL',102,1,'A'),
(185,'BERMEJO','BERMEJO','REGIONAL',102,1,'A'),
(186,'TARIJA','TARIJA','REGIONAL',102,1,'A'),
(187,'YACUIBA','YACUIBA','REGIONAL',102,1,'A'),
(166,'SANTA CRUZ DE LA SIERRA','SANTA CRUZ DE LA SIERRA','REGIONAL',104,1,'A'),
(167,'PUERTO SUAREZ','PUERTO SUAREZ','REGIONAL',104,1,'A'),
(168,'VALLEGRANDE','VALLEGRANDE','REGIONAL',104,1,'A'),
(169,'MONTERO','MONTERO','REGIONAL',104,1,'A'),
(170,'SAN IGNACIO DE VELASCO','SAN IGNACIO DE VELASCO','REGIONAL',104,1,'A'),
(171,'CAMIRI','CAMIRI','REGIONAL',104,1,'A'),
(111,'RIBERALTA','RIBERALTA','REGIONAL',105,1,'A'),
(112,'MAGDALENA','MAGDALENA','REGIONAL',105,1,'A'),
(113,'SANTA ANA DE YACUMA','SANTA ANA DE YACUMA','REGIONAL',105,1,'A'),
(114,'RURRENABAQUE','RURRENABAQUE','REGIONAL',105,1,'A'),
(115,'TRINIDAD','TRINIDAD','REGIONAL',105,1,'A'),
(155,'COBIJA','COBIJA','REGIONAL',106,1,'A'),
(117,'RIBERALTA','RIBERALTA','AGENCIA',111,1,'A'),
(118,'MAGDALENA','MAGDALENA','AGENCIA',112,1,'A'),
(119,'SANTA ANA DE YACUMA','SANTA ANA DE YACUMA','AGENCIA',113,1,'A'),
(120,'RURRENABAQUE 4','RURRENABAQUE 4','AGENCIA',114,1,'A'),
(121,'TRINIDAD','TRINIDAD','AGENCIA',115,1,'A'),
(125,'MONTEAGUDO','MONTEAGUDO','AGENCIA',122,1,'A'),
(126,'ESPAÑA','ESPAÑA','AGENCIA',123,1,'A'),
(128,'AYACUCHO','AYACUCHO','AGENCIA',123,1,'A'),
(127,'CAMARGO','CAMARGO','AGENCIA',124,1,'A'),
(132,'SAN PEDRO','SAN PEDRO','AGENCIA',129,1,'A'),
(134,'ANTEZANA','ANTEZANA','AGENCIA',129,1,'A'),
(136,'25 DE MAYO','25 DE MAYO','AGENCIA',129,1,'A'),
(133,'IVIRGARZAMA','IVIRGARZAMA','AGENCIA',130,1,'A'),
(135,'AIQUILE','AIQUILE','AGENCIA',131,1,'A'),
(142,'ORURO OFICINA REGIONAL','ORURO OFICINA REGIONAL','AGENCIA',137,1,'A'),
(143,'MIRAFLORES AGENCIA URBANA','MIRAFLORES AGENCIA URBANA','AGENCIA',137,1,'A'),
(144,'SUBSUELO AGENCIA URBANA GUNDLACH','SUBSUELO AGENCIA URBANA GUNDLACH','AGENCIA',137,1,'A'),
(145,'GUNDLACH AGENCIA URBANA','GUNDLACH AGENCIA URBANA','AGENCIA',137,1,'A'),
(148,'TRES CRUCES AGENCIA URBANA EDIFICIO','TRES CRUCES AGENCIA URBANA EDIFICIO','AGENCIA',137,1,'A'),
(149,'CALACOTO AGENCIA URBANA','CALACOTO AGENCIA URBANA','AGENCIA',137,1,'A'),
(146,'TELEFERICO - AGENCIA URBANA EL ALTO','TELEFERICO - AGENCIA URBANA EL ALTO','AGENCIA',138,1,'A'),
(147,'CIUDAD SATELITE - AGENCIA URBANA EL ALTO','CIUDAD SATELITE - AGENCIA URBANA EL ALTO','AGENCIA',138,1,'A'),
(151,'CARANAVI AGENCIA RURAL','CARANAVI AGENCIA RURAL','AGENCIA',139,1,'A'),
(150,'COPACABANA AGENCIA RURAL','COPACABANA AGENCIA RURAL','AGENCIA',140,1,'A'),
(152,'ACHACACHI AGENCIA RURAL','ACHACACHI AGENCIA RURAL','AGENCIA',141,1,'A'),
(154,'ORURO OFICINA REGIONAL','ORURO OFICINA REGIONAL','AGENCIA',153,1,'A'),
(156,'COBIJA','COBIJA','AGENCIA',155,1,'A'),
(162,'POTOSÍ OFICINA REGIONAL','POTOSÍ OFICINA REGIONAL','AGENCIA',157,1,'A'),
(164,'TUPIZA AGENCIA RURAL','TUPIZA AGENCIA RURAL','AGENCIA',157,1,'A'),
(163,'UYUNI AGENCIA RURAL','UYUNI AGENCIA RURAL','AGENCIA',160,1,'A'),
(165,'LLALLAGUA AGENCIA RURAL','LLALLAGUA AGENCIA RURAL','AGENCIA',161,1,'A'),
(174,'RENTADIGNIDAD','RENTADIGNIDAD','AGENCIA',166,1,'A'),
(175,'RENTA DIG','RENTA DIG','AGENCIA',166,1,'A'),
(176,'REGIONAL SC','REGIONAL SC','AGENCIA',166,1,'A'),
(177,'PUERTO SUAREZ','PUERTO SUAREZ','AGENCIA',166,1,'A'),
(178,'PLAN 3000','PLAN 3000','AGENCIA',166,1,'A'),
(180,'MONTERO','MONTERO','AGENCIA',166,1,'A'),
(181,'INDANA','INDANA','AGENCIA',166,1,'A'),
(172,'VALLEGRANDE','VALLEGRANDE','AGENCIA',168,1,'A'),
(179,'MONTERO','MONTERO','AGENCIA',169,1,'A'),
(173,'SAN IGNACIO DE VELASCO','SAN IGNACIO DE VELASCO','AGENCIA',170,1,'A'),
(182,'CAMIRI','CAMIRI','AGENCIA',171,1,'A'),
(190,'TARIJA','TARIJA','AGENCIA',183,1,'A'),
(189,'VILLAMONTES','VILLAMONTES','AGENCIA',184,1,'A'),
(191,'BERMEJO','BERMEJO','AGENCIA',185,1,'A'),
(188,'YACUIBA','YACUIBA','AGENCIA',187,1,'A');



CREATE TABLE _bp_usuarios (
usr_id serial PRIMARY KEY,
usr_prs_id integer NOT NULL DEFAULT '1',
usr_usuario text NOT NULL,
usr_clave text NOT NULL,
usr_controlar_ip char(1) NOT NULL DEFAULT 'S',
usr_regional_id integer DEFAULT '1'; --// <----- 2024-01-30
usr_agencia_id integer DEFAULT '1'; --//  <----- 2024-01-30
usr_registrado timestamp NOT NULL DEFAULT now(),
usr_modificado timestamp NOT NULL DEFAULT now(),
usr_usr_id integer NOT NULL,
usr_estado char(1) NOT NULL DEFAULT 'A',
remember_token text,
FOREIGN KEY(usr_prs_id) REFERENCES _bp_personas(prs_id)
);
INSERT INTO _bp_usuarios (usr_prs_id, usr_usuario, usr_clave, usr_controlar_ip, usr_regional_id, usr_agencia_id, usr_usr_id, usr_estado) VALUES 
( 1, 'administrador', '$2y$10$BUvDPJ225PIF8qKtEdrDU.yVr0y5ajp3t/lPz5yYtX6YbJ6BN25V.', 'N', 1, 1, 1, 'A');


INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(1, '0', 'Raíz', NULL, '2023-11-19 11:25:38.539', '2023-11-19 11:25:38.539', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(2, 'GP', 'GP-Nodo', 1, '2023-11-19 11:50:31.402', '2023-11-19 11:50:31.402', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(3, 'GNPR', 'GERENCIA PRESTACIONES', 2, '2023-11-22 21:43:21.869', '2023-11-22 21:43:21.869', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(4, 'GRV', 'GERENCIA REGIONAL VALLES', 2, '2023-11-22 21:43:41.119', '2023-11-22 21:43:41.119', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(5, 'OP', 'OPERACIONES', 3, '2023-11-22 21:46:06.135', '2023-11-22 21:46:06.135', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(6, 'PR', 'PRESTACIONES', 3, '2023-11-22 21:46:43.072', '2023-11-22 21:46:43.072', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(7, 'GROCC', 'GERENCIA REGIONAL OCCIDENTE', 1, '2023-12-15 15:33:41.192', '2023-12-15 15:33:41.192', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(8, 'ORLP', 'OFICINA REGIONAL LA PAZ', 7, '2023-12-15 15:34:20.834', '2023-12-15 15:34:20.834', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(9, 'AGG', 'AGENCIA GUNDLACH', 8, '2023-12-15 15:34:42.905', '2023-12-15 15:34:42.905', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(10, 'LG', 'LEGAL', 1, '2023-12-15 15:59:57.494', '2023-12-15 15:59:57.494', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(11, 'GNARC', 'GERENCIA NACIONAL DE ASEGURAMIENTO, RECAUDACIONES Y CONTROL', 1, '2024-01-15 08:42:10.938', '2024-01-15 08:42:10.938', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(12, 'SNO', 'SUBGERENCIA NACIONAL DE OPERACIONES', 11, '2024-01-15 08:44:24.064', '2024-01-15 08:44:24.064', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(13, 'UAS', 'UNIDAD DE ASEGURAMIENTO', 12, '2024-01-15 08:44:45.838', '2024-01-15 08:44:45.838', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(30, 'GG', 'GERENCIA GENERAL', NULL, '2024-01-23 11:13:09.365', '2024-01-23 11:13:09.365', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(31, 'UAI', 'UNIDAD DE AUDITORIA INTERNA', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(32, 'UAP', 'UNIDAD DE ASESORÍA EN PLANIFICACIÓN', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(33, 'UCIE', 'UNIDAD DE COMUNICACIÓN E IMAGEN EMPRESARIAL', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(34, 'UT', 'UNIDAD DE TRANSPARENCIA', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(35, 'UALA', 'UNIDAD DE ANÁLISIS LEGAL ADMINISTRATIVO', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(36, 'GNCR', 'GERENCIA NACIONAL DE CONTROL DE RIESGOS', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(37, 'URI', 'UNIDAD DE RIESGO DE INVERSIONES', 36, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(38, 'URO', 'UNIDAD DE RIESGO OPERATIVO', 36, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(39, 'URSI', 'UNIDAD DE RIESGOS EN SEGURIDAD DE LA INFORMACIÓN', 36, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(40, 'UAEP', 'UNIDAD DE ANÁLISIS Y ESTUDIOS EN PENSIONES', 36, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(41, 'GNS', 'GERENCIA NACIONAL DE SISTEMAS', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(42, 'SNSI', 'SUBGERENCIA NACIONAL DE SISTEMAS DE INFORMACIÓN', 41, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(43, 'UDS', 'UNIDAD DE DESARROLLO DE SISTEMAS', 42, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(44, 'UIGI', 'UNIDAD DE INVESTIGACIÓN Y GESTIÓN DE INFORMACIÓN', 42, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(45, 'UCCSS', 'UNIDAD DE CONTROL DE CALIDAD Y SEGURIDAD DE SISTEMAS', 42, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(46, 'SNIT', 'SUBGERENCIA NACIONAL DE INFRAESTRUCTURA TECNOLÓGICA ', 41, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(47, 'USBDA', 'UNIDAD DE SERVIDORES DE BASE DE DATOS Y APLICACIONES', 46, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(48, 'URC', 'UNIDAD DE REDES Y COMUNICACIONES', 46, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(49, 'UST', 'UNIDAD DE SOPORTE TÉCNICO (NACIONAL Y REGIONAL)', 46, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(50, 'GNI', 'GERENCIA NACIONAL DE INVERSIONES', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(51, 'SNAI', 'SUBGERENCIA NACIONAL DE ANÁLISIS DE INVERSIÓN', 50, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(52, 'UAIEIFRV', 'UNIDAD DE ANÁLISIS DE INVERSIONES EIF Y RENTA VARIABLE', 51, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(53, 'UAIRFE', 'UNIDAD DE ANÁLISIS DE INVERSIONES RENTA FIJA Y EN EL EXTRANJERO', 51, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(54, 'SNOI', 'SUBGERENCIA NACIONAL OPERATIVA DE INVERSIÓN', 50, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(55, 'UOIFCI', 'UNIDAD OPERATIVA DE INVERSIONES FCI', 54, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(56, 'UOIFRD', 'UNIDAD OPERATIVA DE INVERSIONES FRD', 54, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(57, 'GNL', 'GERENCIA NACIONAL LEGAL', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(58, 'SNCA', 'SUBGERENCIA NACIONAL DE COBRANZA ADMINISTRATIVA', 57, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(59, 'UPA', 'UNIDAD DE PROCESOS ADMINISTRATIVOS', 58, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(60, 'UGM', 'UNIDAD DE GESTIÓN DE MORA', 58, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(61, 'SNCJ', 'SUBGERENCIA NACIONAL DE COBRANZA JUDICIAL', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(62, 'UPJRCSC', 'UNIDAD DE PROCESOS JUDICIALES RC Y SC', 61, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(63, 'UPJRD', 'UNIDAD DE PROCESOS JUDICIALES DE RENTA DIGNIDAD', 61, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(64, 'UAMRPT', 'UNIDAD DE ANÁLISIS DE MORA Y REVISIÓN DE PROCESOS TRANSFERIDOS', 61, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(65, 'GNGFC', 'GERENCIA NACIONAL DE GESTIÓN FINANCIERA Y COMERCIAL', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(66, 'UTH', 'UNIDAD DE TALENTO HUMANO', 65, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(67, 'UAC', 'UNIDAD ADMINISTRATIVA Y COMERCIAL', 65, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(68, 'UF', 'UNIDAD DE FINANZAS', 65, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(70, 'UATA', 'UNIDAD DE ATENCIÓN DE TRÁMITES Y ARCHIVO', 65, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(71, 'GNP', 'GERENCIA NACIONAL DE PRESTACIONES', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(72, 'SNPCSC', 'SUBGERENCIA NACIONAL DE PRESTACIONES CONTRIBUTIVAS Y SEMICONTRIBUTIVAS', 71, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(73, 'UPV', 'UNIDAD DE PRESTACIONES DE VEJEZ', 72, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(74, 'URP', 'UNIDAD DE RIESGOS PREVISIONALES', 72, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(75, 'UPC', 'UNIDAD DE PAGOS Y CONTROL', 72, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(76, 'SNPNC', 'SUBGERENCIA NACIONAL DE PRESTACIONES NO CONTRIBUTIVAS', 71, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(77, 'UATCRD', 'UNIDAD DE ATENCIÓN AL CLIENTE RD', 76, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(78, 'UOCPNC', 'UNIDAD DE OPERACIONES Y CONTROL PNC', 76, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(79, 'GNARC', 'GERENCIA NACIONAL DE ASEGURAMIENTO, RECAUDACIÓN Y CONTROL', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(80, 'SNO', 'SUBGERENCIA NACIONAL DE OPERACIONES', 79, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(81, 'UAS', 'UNIDAD DE ASEGURAMIENTO', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(82, 'UCPP', 'UNIDAD DE CUENTA PERSONAL PREVISIONAL', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(83, 'UR', 'UNIDAD DE RECTIFICACIONES', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(84, 'URD', 'UNIDAD DE RECAUDACIÓN Y DEPÓSITOS', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(85, 'UA', 'UNIDAD DE ACREDITACIÓN', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(86, 'UMP', 'UNIDAD DE MOVIMIENTOS PATRIMONIALES', 80, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(87, 'SNCF', 'SUBGERENCIA NACIONAL DE CONTABILIDAD DE FONDOS', 79, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(88, 'UCFFCI', 'UNIDAD DE CONTABILIDAD DE FONDOS FCI', 87, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(89, 'UCFFRUVP', 'UNIDAD DE CONTABILIDAD DE FONDOS FRUV Y PROVIVIENDA', 87, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(90, 'UTF', 'UNIDAD DE TESORERÍA DE FONDOS', 87, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(91, 'SNSCC', 'SUBGERENCIA NACIONAL DE SERVICIO AL CLIENTE Y COMERCIAL', 79, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(92, 'UCAC', 'UNIDAD DE CANALES DE ATENCIÓN AL CLIENTE', 91, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(93, 'UM', 'UNIDAD DE MARKETING', 91, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(94, 'UFEP', 'UNIDAD DE FORMACIÓN Y EDUCACIÓN PREVISIONAL', 91, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(95, 'GROCC', 'GERENCIA REGIONAL OCCIDENTE', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(96, 'ORLP', 'OFICINA REGIONAL LA PAZ', 95, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(97, 'OROR', 'OFICINA REGIONAL ORURO', 95, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(98, 'ORPT', 'OFICINA REGIONAL POTOSÍ', 95, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(99, 'GRVAL', 'GERENCIA REGIONAL VALLES', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(100, 'ORCBBA', 'OFICINA REGIONAL COCHABAMBA ', 99, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(101, 'ORCH', 'OFICINA REGIONAL CHUQUISACA', 99, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(102, 'ORT', 'OFICINA REGIONAL TARIJA', 99, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(103, 'GRORI', 'GERENCIA REGIONAL ORIENTE', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(104, 'ORSCZ', 'OFICINA REGIONAL SANTA CRUZ', 103, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(105, 'ORBN', 'OFICINA REGIONAL BENI', 103, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(106, 'ORPN', 'OFICINA REGIONAL PANDO', 103, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(107, 'SUM', 'SUMARIANTE', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(108, 'EXT', 'ENTIDAD EXTERNA', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(109, 'CRS', 'COMITE DE RECEPCIÓN SIP', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(110, 'CSCI', 'COMITÉ DE SEGUIMIENTO DE CONTROL INTERNO (CSCI)', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(111, 'RIBERALTA', 'RIBERALTA', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(112, 'MAGDALENA', 'MAGDALENA', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(113, 'SANTA ANA DE YACUMA', 'SANTA ANA DE YACUMA', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(114, 'RURRENABAQUE', 'RURRENABAQUE', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(115, 'TRINIDAD', 'TRINIDAD', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(117, 'RIBERALTA', 'RIBERALTA', 111, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(118, 'MAGDALENA', 'MAGDALENA', 112, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(119, 'SANTA ANA DE YACUMA', 'SANTA ANA DE YACUMA', 113, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(120, 'RURRENABAQUE 4', 'RURRENABAQUE 4', 114, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(121, 'TRINIDAD', 'TRINIDAD', 115, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(122, 'MONTEAGUDO', 'MONTEAGUDO', 101, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(123, 'SUCRE', 'SUCRE', 101, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(124, 'CAMARGO', 'CAMARGO', 101, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(125, 'MONTEAGUDO', 'MONTEAGUDO', 122, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(126, 'ESPAÑA', 'ESPAÑA', 123, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(127, 'CAMARGO', 'CAMARGO', 124, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(128, 'AYACUCHO', 'AYACUCHO', 123, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(129, 'COCHABAMBA', 'COCHABAMBA', 100, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(130, 'IVIRGARZAMA', 'IVIRGARZAMA', 100, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(131, 'AIQUILE', 'AIQUILE', 100, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(132, 'SAN PEDRO', 'SAN PEDRO', 129, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(133, 'IVIRGARZAMA', 'IVIRGARZAMA', 130, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(134, 'ANTEZANA', 'ANTEZANA', 129, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(135, 'AIQUILE', 'AIQUILE', 131, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(136, '25 DE MAYO', '25 DE MAYO', 129, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(137, 'LA PAZ', 'LA PAZ', 96, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(138, 'EL ALTO', 'EL ALTO', 96, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(139, 'CARANAVI', 'CARANAVI', 96, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(140, 'COPACABANA', 'COPACABANA', 96, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(141, 'ACHACACHI', 'ACHACACHI', 96, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(142, 'ORURO OFICINA REGIONAL', 'ORURO OFICINA REGIONAL', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(143, 'MIRAFLORES AGENCIA URBANA', 'MIRAFLORES AGENCIA URBANA', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(144, 'SUBSUELO AGENCIA URBANA GUNDLACH', 'SUBSUELO AGENCIA URBANA GUNDLACH', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(145, 'GUNDLACH AGENCIA URBANA', 'GUNDLACH AGENCIA URBANA', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(146, 'TELEFERICO - AGENCIA URBANA EL ALTO', 'TELEFERICO - AGENCIA URBANA EL ALTO', 138, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(147, 'CIUDAD SATELITE - AGENCIA URBANA EL ALTO', 'CIUDAD SATELITE - AGENCIA URBANA EL ALTO', 138, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(148, 'TRES CRUCES AGENCIA URBANA EDIFICIO', 'TRES CRUCES AGENCIA URBANA EDIFICIO', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(149, 'CALACOTO AGENCIA URBANA', 'CALACOTO AGENCIA URBANA', 137, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(150, 'COPACABANA AGENCIA RURAL', 'COPACABANA AGENCIA RURAL', 140, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(151, 'CARANAVI AGENCIA RURAL', 'CARANAVI AGENCIA RURAL', 139, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(152, 'ACHACACHI AGENCIA RURAL', 'ACHACACHI AGENCIA RURAL', 141, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(153, 'ORURO', 'ORURO', 97, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(154, 'ORURO OFICINA REGIONAL', 'ORURO OFICINA REGIONAL', 153, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(155, 'COBIJA', 'COBIJA', 105, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(156, 'COBIJA', 'COBIJA', 155, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(157, 'POTOSI', 'POTOSI', 98, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(158, 'TUPIZA', 'TUPIZA', 98, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(159, 'POTOSI', 'POTOSI', 98, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(160, 'UYUNI', 'UYUNI', 98, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(161, 'LLALLAGUA', 'LLALLAGUA', 98, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(162, 'POTOSÍ OFICINA REGIONAL', 'POTOSÍ OFICINA REGIONAL', 157, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(163, 'UYUNI AGENCIA RURAL', 'UYUNI AGENCIA RURAL', 160, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(164, 'TUPIZA AGENCIA RURAL', 'TUPIZA AGENCIA RURAL', 157, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(165, 'LLALLAGUA AGENCIA RURAL', 'LLALLAGUA AGENCIA RURAL', 161, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(166, 'SANTA CRUZ DE LA SIERRA', 'SANTA CRUZ DE LA SIERRA', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(167, 'PUERTO SUAREZ', 'PUERTO SUAREZ', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(168, 'VALLEGRANDE', 'VALLEGRANDE', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(169, 'MONTERO', 'MONTERO', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(170, 'SAN IGNACIO DE VELASCO', 'SAN IGNACIO DE VELASCO', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(171, 'CAMIRI', 'CAMIRI', 104, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(172, 'VALLEGRANDE', 'VALLEGRANDE', 168, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(173, 'SAN IGNACIO DE VELASCO', 'SAN IGNACIO DE VELASCO', 170, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(174, 'RENTADIGNIDAD', 'RENTADIGNIDAD', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(175, 'RENTA DIG', 'RENTA DIG', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(176, 'REGIONAL SC', 'REGIONAL SC', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(177, 'PUERTO SUAREZ', 'PUERTO SUAREZ', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(178, 'PLAN 3000', 'PLAN 3000', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(179, 'MONTERO', 'MONTERO', 169, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(180, 'MONTERO', 'MONTERO', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(181, 'INDANA', 'INDANA', 166, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(182, 'CAMIRI', 'CAMIRI', 171, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(183, 'TARIJA', 'TARIJA', 102, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(184, 'VILLAMONTES', 'VILLAMONTES', 102, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(185, 'BERMEJO', 'BERMEJO', 102, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(186, 'TARIJA', 'TARIJA', 102, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(187, 'YACUIBA', 'YACUIBA', 102, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(188, 'YACUIBA', 'YACUIBA', 187, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(189, 'VILLAMONTES', 'VILLAMONTES', 184, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(190, 'TARIJA', 'TARIJA', 183, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) VALUES(191, 'BERMEJO', 'BERMEJO', 185, '2024-01-23 18:51:48.615', '2024-01-23 18:51:48.615', 1, 'A');



INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) 
VALUES(95, 'GR', 'GERENCIAS REGIONALES', 30, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) 
VALUES(96, 'BACK', 'SUPERVISORES', 95, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');
INSERT INTO public.rmx_vys_nodos (nodo_id, nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_registrado, nodo_modificado, nodo_usr_id, nodo_estado) 
VALUES(97, 'ATC', 'ATENCION AL CLIENTE', 96, '2024-01-23 11:14:54.511', '2024-01-23 11:14:54.511', 1, 'A');

