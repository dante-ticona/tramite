--1
CREATE TABLE _bp_estados_civiles (
	estcivil_id serial PRIMARY KEY,
	estcivil text not null,
	estcivil_registrado timestamp NOT NULL DEFAULT now(),
	estcivil_modificado timestamp NOT NULL DEFAULT now(),
	estcivil_usr_id integer NOT NULL,
	estcivil_estado char(1) NOT NULL DEFAULT 'A'
);
INSERT INTO _bp_estados_civiles (estcivil, estcivil_usr_id, estcivil_estado) VALUES 
('Soltero/a', 1, 'A'),
('Casado/a', 1, 'A'),
('Divorciado/a', 1, 'A'),
('Viudo/a', 1, 'A'),
('Prs. Jurídica', 1, 'A');


--2
CREATE TABLE _bp_personas (
	prs_id serial PRIMARY KEY,
	prs_id_estado_civil integer NOT NULL,
	prs_id_archivo_cv integer,
	prs_ci text NOT NULL,
	prs_nombres text NOT NULL,
	prs_paterno text NOT NULL,
	prs_materno text NOT NULL,
	prs_direccion text NOT NULL,
	prs_direccion2 text DEFAULT '',
	prs_telefono text DEFAULT '',
	prs_telefono2 text DEFAULT '',
	prs_celular text DEFAULT '',
	prs_empresa_telefonica text DEFAULT '',
	prs_correo text DEFAULT '',
	prs_sexo char(1) DEFAULT 'F',
	prs_fec_nacimiento date NOT NULL,
	prs_registrado timestamp NOT NULL DEFAULT now(),
	prs_modificado timestamp NOT NULL DEFAULT now(),
	prs_usr_id integer NOT NULL,
	prs_estado char(1) NOT NULL DEFAULT 'A',
	FOREIGN KEY(prs_id_estado_civil) REFERENCES _bp_estados_civiles(estcivil_id)
);
INSERT INTO _bp_personas (prs_id_estado_civil, prs_id_archivo_cv, prs_ci,prs_nombres, prs_paterno, prs_materno, prs_direccion,
	prs_direccion2, prs_telefono, prs_telefono2, prs_celular, prs_empresa_telefonica, prs_correo, prs_sexo,
	prs_fec_nacimiento, prs_usr_id, prs_estado) VALUES

	(1, 0,  123456,'Administrador', 'Admin', 'Admin', '', '', '', '', '', '', '', 'M', '2017-07-01', 1, 'A'),
	(1, 0,  123456,'Roger', 'Leon', 'Perez', '', '', '', '', '', '', '', 'M', '2017-07-01', 1, 'A');


--3
CREATE TABLE _bp_usuarios (
	usr_id serial PRIMARY KEY,
	usr_prs_id integer NOT NULL DEFAULT '1',
	usr_usuario text NOT NULL,
	usr_clave text NOT NULL,
	usr_controlar_ip char(1) NOT NULL DEFAULT 'S',
	usr_registrado timestamp NOT NULL DEFAULT now(),
	usr_modificado timestamp NOT NULL DEFAULT now(),
	usr_usr_id integer NOT NULL,
	usr_estado char(1) NOT NULL DEFAULT 'A',
	remember_token text,
	FOREIGN KEY(usr_prs_id) REFERENCES _bp_personas(prs_id)
);
INSERT INTO _bp_usuarios (usr_prs_id, usr_usuario, usr_clave, usr_controlar_ip, usr_usr_id, usr_estado) VALUES 
( 1, 'administrador', '$2y$10$BUvDPJ225PIF8qKtEdrDU.yVr0y5ajp3t/lPz5yYtX6YbJ6BN25V.', 'N', 1, 'A');


--4
CREATE TABLE _bp_roles(
	rls_id serial PRIMARY KEY,
	rls_rol text NOT NULL,
	rls_registrado timestamp NOT NULL DEFAULT now(),
	rls_modificado timestamp NOT NULL DEFAULT now(),
	rls_usr_id integer NOT NULL,
	rls_estado char(1) NOT NULL DEFAULT 'A'
);
INSERT INTO _bp_roles (rls_rol, rls_usr_id, rls_estado) VALUES 
('administrador', 1, 'A');


--5
CREATE TABLE _bp_grupos(
	grp_id serial PRIMARY KEY,
	grp_grupo text NOT NULL,
	grp_imagen text DEFAULT '',
	grp_registrado timestamp NOT NULL DEFAULT now(),
	grp_modificado timestamp NOT NULL DEFAULT now(),
	grp_usr_id integer NOT NULL,
	grp_estado char(1) NOT NULL DEFAULT 'A'
);
INSERT INTO _bp_grupos (grp_id, grp_grupo, grp_imagen, grp_usr_id, grp_estado) VALUES 
(1, 'ADMINISTRACIÓN', 'fa fa-users', 1, 'A'),
(2, 'APOYO', 'fa fa-envelope', 1, 'A'),
(3, 'REPORTES', 'fa fa-paint-brush', 1, 'A');

--6
CREATE TABLE _bp_opciones(
	opc_id serial PRIMARY KEY,
	opc_grp_id integer NOT NULL,
	opc_opcion text NOT NULL,
	opc_contenido text DEFAULT '',
	opc_adicional text DEFAULT '',
	opc_orden integer,
	opc_imagen text DEFAULT '',
	opc_registrado timestamp NOT NULL DEFAULT now(),
	opc_modificado timestamp NOT NULL DEFAULT now(),
	opc_usr_id integer NOT NULL,
	opc_estado char(1) NOT NULL DEFAULT 'A',
	FOREIGN KEY(opc_grp_id) REFERENCES _bp_grupos(grp_id)
);
INSERT INTO _bp_opciones (opc_id, opc_grp_id, opc_opcion, opc_contenido, opc_adicional, opc_orden, opc_imagen, opc_usr_id, opc_estado) VALUES 
(1, 1, 'Personas', 'Persona', '', 20, '', 1, 'A'),
(2, 1, 'Usuarios', 'Usuario', '', 30, '', 1, 'A'),
(3, 1, 'Roles', 'Rol', '', 40, '', 1, 'A'),
(4, 1, 'Usuarios Roles', 'RolUsuario', '', 50, '', 1, 'A'),
(5, 1, 'Grupos', 'Grupo', '', 60, '', 1, 'A'),
(6, 1, 'Opciones', 'Opcion', '', 70, '', 1, 'A'),
(7, 1, 'Accesos', 'Asignacion', '', 80, '', 1, 'A');


--7
CREATE TABLE _bp_accesos(
	acc_id serial PRIMARY KEY,
	acc_opc_id integer NOT NULL,
	acc_rls_id integer NOT NULL,
	acc_registrado timestamp NOT NULL DEFAULT now(),
	acc_modificado timestamp NOT NULL DEFAULT now(),
	acc_usr_id integer NOT NULL,
	acc_estado char(1) NOT NULL DEFAULT 'A',
	FOREIGN KEY(acc_opc_id) REFERENCES _bp_opciones(opc_id),
	FOREIGN KEY(acc_rls_id) REFERENCES _bp_roles(rls_id)
);
INSERT INTO _bp_accesos (acc_opc_id, acc_rls_id, acc_usr_id, acc_estado) VALUES 
	(1, 1, 1, 'A'),
	(2, 1, 1, 'A'),
	(3, 1, 1, 'A'),
	(4, 1, 1, 'A'),
	(5, 1, 1, 'A'),
	(6, 1, 1, 'A'),
	(7, 1, 1, 'A');


--8
CREATE TABLE _bp_usuarios_roles (
	usrls_id serial PRIMARY KEY,
	usrls_usr_id integer NOT NULL,
	usrls_rls_id integer NOT NULL,
	usrls_registrado timestamp NOT NULL DEFAULT now(),
	usrls_modificado timestamp NOT NULL DEFAULT now(),
	usrls_usuarios_usr_id integer NOT NULL,
	usrls_estado char(1) NOT NULL DEFAULT 'A',
	FOREIGN KEY(usrls_usr_id) REFERENCES _bp_usuarios(usr_id),
	FOREIGN KEY(usrls_rls_id) REFERENCES _bp_roles(rls_id)
);
INSERT INTO _bp_usuarios_roles (usrls_usr_id, usrls_rls_id, usrls_usuarios_usr_id, usrls_estado) VALUES 
(1, 1, 1, 'A');


--9
CREATE TABLE _bp_log_seguimiento(
	log_id serial PRIMARY KEY,
	log_usr_id integer NOT NULL,
	log_metodo text,
	log_accion text NOT NULL,
	log_detalle text,
	log_modulo text NOT NULL,
	log_consulta text NOT NULL,
	log_registrado timestamp NOT NULL DEFAULT now(),
	log_modificado timestamp NOT NULL DEFAULT now(),
	FOREIGN KEY (log_usr_id) REFERENCES _bp_usuarios (usr_id)
);


--1
CREATE OR REPLACE FUNCTION autenticacion(
    IN usrid text,
      IN usuario text,
      IN terid integer)
    RETURNS TABLE(idusr integer, usr text, nombre text, paterno text, materno text, idrl integer, rol text, vci text, vprs_id integer) AS
  $BODY$
  BEGIN
      RETURN QUERY SELECT usr_id,usr_usuario,prs_nombres,prs_paterno,prs_materno,rls_id,rls_rol,prs_ci,prs_id
       FROM _bp_usuarios
       INNER JOIN _bp_personas ON  prs_id=usr_prs_id  and prs_estado='A'
       INNER JOIN _bp_usuarios_roles ON usrls_usr_id=usr_id  and usrls_estado='A'
       INNER  JOIN _bp_roles ON usrls_rls_id=rls_id and rls_estado='A'

       where usr_usuario=usrId  AND usr_clave=usuario AND usr_estado='A'
       ORDER BY usrls_registrado DESC ;
  END;
  $BODY$
    LANGUAGE plpgsql VOLATILE
    COST 100
    ROWS 1000;
--2
  CREATE OR REPLACE FUNCTION sp_obtiene_usuario(
    IN usrid INTEGER)
  RETURNS TABLE(vusr_id integer, vrls_id integer, vrls_rol text, vusr_usuario text, vprs_nombres text, vprs_paterno text, vprs_materno text, vprs_id integer) AS
  $BODY$
  BEGIN
    RETURN QUERY select usr_id, rls_id,rls_rol,usr_usuario,prs_nombres ,prs_paterno ,prs_materno ,prs_id
    from _bp_usuarios_roles
    inner join _bp_roles as r on r.rls_id= usrls_rls_id and usrls_estado='A' AND r.rls_estado='A'
    inner join _bp_usuarios as u on u.usr_id=usrls_usr_id AND usr_estado<>'B'
    inner join _bp_personas as p on p.prs_id=u.usr_prs_id AND prs_estado='A'
    where usrls_usr_id=usrid;

  END;
  $BODY$
    LANGUAGE plpgsql VOLATILE
    COST 100
    ROWS 1000;

--3
CREATE OR REPLACE FUNCTION public.sp_asignar_accesos(
    opidjson json,
    rolid integer)
  RETURNS boolean AS
$BODY$
  declare fila integer;
  idexiste integer;
   BEGIN
    for fila IN SELECT * FROM json_array_elements(opidjson)
        Loop
    idexiste := (select acc_id from _bp_accesos where acc_opc_id=fila and acc_rls_id=rolid and acc_estado='A');
    if idexiste is null
      then insert into _bp_accesos (acc_opc_id,acc_rls_id,acc_usr_id) 
      values (fila,rolid,1);
    end if;
        END Loop;
  RETURN true;
  END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
-----------------------------------
CREATE OR REPLACE FUNCTION public.sp_asignar_usuroles(
    rolidjson json,
    usuid integer)
  RETURNS boolean AS
$BODY$
  declare fila integer;
  idexiste integer;
   BEGIN
    for fila IN SELECT * FROM json_array_elements(rolidjson)
        Loop
    idexiste := (select usrls_usr_id from _bp_usuarios_roles where usrls_rls_id=fila and usrls_usr_id=usuid and usrls_estado='A');
    if idexiste is null
      then insert into _bp_usuarios_roles (usrls_usr_id,usrls_rls_id,usrls_usuarios_usr_id) 
      values (usuid,fila,1);
    end if;
        END Loop;
  RETURN true;
  END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
-----------------------------------
CREATE OR REPLACE FUNCTION public.sp_desasignar_accesos(opidjson json)
  RETURNS boolean AS
$BODY$
  declare fila integer;
  idexiste integer;
   BEGIN
    for fila IN SELECT * FROM json_array_elements(opidjson)
        Loop
      update _bp_accesos set acc_estado='B' where acc_id=fila;
        END Loop;
  RETURN true;
  END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
-----------------------------------
CREATE OR REPLACE FUNCTION public.sp_desasignar_usuroles(usurolidjson json)
  RETURNS boolean AS
$BODY$
  declare fila integer;
  idexiste integer;
   BEGIN
    for fila IN SELECT * FROM json_array_elements(usurolidjson)
        Loop
      update _bp_usuarios_roles set usrls_estado='B' where usrls_id=fila;
        END Loop;
  RETURN true;
  END;
  $BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;