--
-- DISEÑO
--
CREATE TABLE rmx_vys_catalogos (
  cat_id serial primary key,
  cat_codigo text NOT NULL,
  cat_descripcion text NOT NULL,
  cat_padre_id integer DEFAULT 0,
  cat_registrado timestamp DEFAULT now(),
  cat_modificado timestamp DEFAULT now(),
  cat_usr_id integer NOT NULL,
  cat_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_vys_catalogos (cat_codigo, cat_descripcion, cat_padre_id, cat_usr_id) VALUES
('0', 'Raíz', 0, 1);

-- INSERT INTO rmx_vys_catalogos (cat_codigo, cat_descripcion, cat_padre_id, cat_usr_id) VALUES
-- ('1', 'Actividades Económicas', 1, 1),
-- ('1.1', 'Licencias de Funcionamiento', 2, 1),
-- ('1.1', 'Publicidad en Vía Pública', 2, 1),
-- ('1.2', 'Publicidad Móvil', 2, 1),

-- ('2', 'Deportes', 1 , 1),
-- ('2.1', 'Registro de Deportista', 1 , 6),
-- ('3', 'Mercado', 1 , 1),
-- ('3.1', 'Registro de Comerciante', 1 , 8);


CREATE TABLE rmx_vys_nodos (
  nodo_id serial primary key,
  nodo_codigo text NOT NULL,
  nodo_descripcion text NOT NULL,
  nodo_padre_id integer,
  nodo_registrado timestamp DEFAULT now(),
  nodo_modificado timestamp DEFAULT now(),
  nodo_usr_id integer NOT NULL,
  nodo_estado char(1) DEFAULT 'A',
  FOREIGN KEY (nodo_padre_id) REFERENCES rmx_vys_nodos (nodo_id)
);
INSERT INTO rmx_vys_nodos (nodo_codigo, nodo_descripcion, nodo_usr_id) VALUES 
('0', 'Raíz', 1);

-- INSERT INTO rmx_vys_nodos (nodo_codigo, nodo_descripcion, nodo_padre_id, nodo_usr_id) VALUES 
-- ('1', 'Dirección de Desarrollo Económico', 1, 1),
-- ('1.1', 'Unidad de Actividades Económicas', 2, 1),
-- ('1.1.1', 'Plataforma AE', 3, 1),
-- ('1.1.2', 'Unidad Técnica', 3, 1),
-- ('1.1.3', 'Unidad de Inspección', 3, 1),
-- ('1.1.4', 'Jefatura de Unidad', 3, 1),
-- ('1.2', 'Unidad de Publicidad Urbana', 2, 1),
-- ('1.3', 'Unidad de Mercados', 2, 1),
-- ('1.4', 'Unidad de Comercio en Vía Pública', 2, 1);


CREATE TABLE rmx_vys_procesos (
  prc_id serial primary key,
  prc_cat_id integer NOT NULL,
  prc_data jsonb DEFAULT '{}',
  prc_data_campos_clave jsonb DEFAULT '[]',
  prc_modelo jsonb DEFAULT '{}',
  prc_registrado timestamp DEFAULT now(),
  prc_modificado timestamp DEFAULT now(),
  prc_usr_id integer NOT NULL,
  prc_estado char(1) DEFAULT 'A',
  FOREIGN KEY (prc_cat_id) REFERENCES rmx_vys_catalogos (cat_id)
);
-- INSERT INTO rmx_vys_procesos (prc_cat_id, prc_data, prc_data_campos_clave, prc_usr_id) VALUES 
-- (3, '{"prc_codigo":"P-001", "prc_descripcion":"Registro de Actividad Económica"}', '[{"prc_campo_clave":"CI_1"}, {"prc_campo_clave":"NOMBRES_1"}]', 1),
-- (3, '{"prc_codigo":"P-002", "prc_descripcion":"Renovación Actividad Económica"}', '[{"prc_campo_clave":"CI_1"}]', 1);


CREATE TABLE rmx_vys_tipos_actividad (
  tact_id serial primary key,
  tact_codigo text NOT NULL,
  tact_descripcion text NOT NULL,
  tact_icono text NOT NULL,
  tact_registrado timestamp DEFAULT now(),
  tact_modificado timestamp DEFAULT now(),
  tact_usr_id integer NOT NULL,
  tact_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_vys_tipos_actividad (tact_codigo, tact_descripcion, tact_icono, tact_usr_id) VALUES 
('I', 'Inicio', 'circle green', 1),
('A', 'Actividad', 'square black', 1),
('B', 'Bifurcación', 'diamond black', 1),
('F', 'Fin', 'cicrcle danger', 1);


CREATE TABLE rmx_vys_actividades (
  act_id serial primary key,
  act_prc_id integer NOT NULL,
  act_tact_id integer NOT NULL,
  act_nodo_id integer NOT NULL,
  act_data jsonb,
  act_data_reglas jsonb DEFAULT '[]'::json,
  act_data_form jsonb DEFAULT '[]'::json,
  act_registrado timestamp DEFAULT now(),
  act_modificado timestamp DEFAULT now(),
  act_usr_id integer NOT NULL,
  act_estado char(1) DEFAULT 'A',
  FOREIGN KEY (act_prc_id) REFERENCES rmx_vys_procesos (prc_id),
  FOREIGN KEY (act_tact_id) REFERENCES rmx_vys_tipos_actividad (tact_id),
  FOREIGN KEY (act_nodo_id) REFERENCES rmx_vys_nodos (nodo_id)
);
-- INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_usr_id) VALUES
-- (1, 1, 4, '{"act_orden":"10", "act_descripcion":"Inicio",                          "act_siguiente":"20", "act_duracion_horas":"0"}', 1), -- 1
-- (1, 2, 2, '{"act_orden":"20", "act_descripcion":"Registro de Actividad Económica", "act_siguiente":"30", "act_duracion_horas":"24"}', 1), -- 2
-- (1, 2, 10, '{"act_orden":"30", "act_descripcion":"Validación de Datos",             "act_siguiente":"40", "act_duracion_horas":"12"}', 1); -- 3
-- INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_usr_id) VALUES
-- (1, 3, 5, '{"act_orden":"40", "act_descripcion":"Con Inspección",                  "act_siguiente":"60", "act_duracion_horas":"12"}', '[{"act_regla": "`#INSPECCION_1#` == `1`", "act_siguiente": "50"}]', 1); -- 4
-- INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_usr_id) VALUES
-- (1, 2, 4, '{"act_orden":"50", "act_descripcion":"Inspección",                      "act_siguiente":"60", "act_duracion_horas":"48"}', 1), -- 5
-- (1, 2, 4, '{"act_orden":"60", "act_descripcion":"Autorización",                    "act_siguiente":"70", "act_duracion_horas":"12"}', 1); -- 6
-- INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_data_reglas, act_usr_id) VALUES
-- (1, 3, 4, '{"act_orden":"70", "act_descripcion":"Resultado",                       "act_siguiente":"80", "act_duracion_horas":"12"}', '[{"act_regla": "`#RESULTADO_1#` == `1`", "act_siguiente": "90"}]', 1); -- 7
-- INSERT INTO rmx_vys_actividades (act_prc_id, act_tact_id, act_nodo_id, act_data, act_usr_id) VALUES
-- (1, 2, 4, '{"act_orden":"80", "act_descripcion":"Imprimir Rechazo",                "act_siguiente":"100", "act_duracion_horas":"12"}', 1), -- 8
-- (1, 2, 4, '{"act_orden":"90", "act_descripcion":"Imprimir Licencia",               "act_siguiente":"110", "act_duracion_horas":"12"}', 1), -- 9
-- (1, 4, 4, '{"act_orden":"100", "act_descripcion":"Rechazado (Fin)",                "act_siguiente":"200", "act_duracion_horas":"0"}', 1), -- 10
-- (1, 4, 4, '{"act_orden":"110", "act_descripcion":"Licencia Emitida (Fin)",         "act_siguiente":"200", "act_duracion_horas":"0"}', 1); -- 11


--
-- IMPRESIONES
--
CREATE TABLE rmx_vys_impresiones (
  imp_id serial primary key,
  imp_act_id integer NOT NULL,
  imp_nombre varchar(255),
  imp_data text,
  imp_registrado timestamp DEFAULT now(),
  imp_modificado timestamp DEFAULT now(),
  imp_usr_id integer NOT NULL,
  imp_estado char(1) DEFAULT 'A',
  FOREIGN KEY (imp_act_id) REFERENCES rmx_vys_actividades (act_id)
);
-- INSERT INTO rmx_vys_impresiones (imp_act_id, imp_nombre, imp_data, imp_usr_id) VALUES
-- (6, 'Autorización', '<p class="ql-align-center"><strong>INFORME DE AUTORIZACIÓN</strong></p><p>El señor #NOMBRES_1# #PATERNO_1# #MATERNO_1# solicita el registro de una actividad económica.</p>', 1),
-- (8, 'Rechazo', '<p class="ql-align-center"><strong>INFORME DE RECHAZO</strong></p><p>La solicitud de #NOMBRES_1# #PATERNO_1# #MATERNO_1# fue rechazada.</p>', 1),
-- (9, 'Licencia', '<p class="ql-align-center"><strong>LICENCIA DE FUNCIONAMIENTO</strong></p><p>Licencia otorgada a #NOMBRES_1# #PATERNO_1# #MATERNO_1# de actividad económica.</p>', 1);


--
-- CASOS
--
CREATE TABLE rmx_vys_casos (
  cas_id serial primary key,
  cas_cod_id varchar UNIQUE,
  cas_act_id integer NOT NULL,
  cas_nodo_id integer NOT NULL,
  cas_data jsonb NOT NULL DEFAULT '{}',
  cas_data_valores jsonb NOT NULL DEFAULT '{}',
  cas_registrado timestamp DEFAULT now(),
  cas_modificado timestamp DEFAULT now(),
  cas_usr_id integer NOT NULL,
  cas_estado char(1) DEFAULT 'A',
  FOREIGN KEY (cas_act_id) REFERENCES rmx_vys_actividades (act_id),
  FOREIGN KEY (cas_nodo_id) REFERENCES rmx_vys_nodos (nodo_id)
);
-- INSERT INTO rmx_vys_casos (cas_act_id, cas_nodo_id, cas_data, cas_data_valores, cas_usr_id, cas_estado) VALUES 
-- ( 2, 4, '{"cas_gestion":"2020", "cas_nombre_caso":"3344556 | Eligio Martinez", "cas_nro_caso":"REG-001"}',
-- '[ { "frm_deshabilitado":"false", "frm_tipo": "TEXT", "frm_campo": "CI_1", "frm_etiqueta": "CI", "frm_value": "1" }, 
-- { "frm_deshabilitado":"false", "frm_tipo": "TEXT", "frm_campo": "NOMBRES_1", "frm_etiqueta": "Nombres", "frm_value": "2" }, 
-- { "frm_deshabilitado":"true", "frm_tipo": "TEXT", "frm_campo": "PATERNO_1", "frm_etiqueta": "Paterno", "frm_value": "3" }, 
-- { "frm_deshabilitado":"false", "frm_tipo": "TEXT", "frm_campo": "MATERNO_1", "frm_etiqueta": "Materno", "frm_value": "4" }, 
-- { "frm_deshabilitado":"false", "frm_tipo": "DATE", "frm_campo": "NACIMIENTO_1", "frm_etiqueta": "Fecha Nacimiento", "frm_value": "2020-10-12" }, 
-- { "frm_deshabilitado":"false", "frm_tipo": "TEXTAREA", "frm_campo": "DIRECCION_1", "frm_etiqueta": "Direccion", "frm_value": "6" } ]', 
-- 1, 'T'),
-- (10, 4, '{"cas_gestion":"2020", "cas_nombre_caso":"1233445 | Rosa Lopez", "cas_nro_caso":"REN-201"}', 
-- '[ { "frm_deshabilitado":"true", "frm_tipo": "TEXT", "frm_campo": "CI_1", "frm_etiqueta": "CI", "frm_value": "1" }, 
-- { "frm_deshabilitado":"true", "frm_tipo": "TEXT", "frm_campo": "NOMBRES_1", "frm_etiqueta": "Nombres", "frm_value": "2" }, 
-- { "frm_deshabilitado":"true", "frm_tipo": "TEXT", "frm_campo": "PATERNO_1", "frm_etiqueta": "Paterno", "frm_value": "3" }, 
-- { "frm_deshabilitado":"true", "frm_tipo": "TEXT", "frm_campo": "MATERNO_1", "frm_etiqueta": "Materno", "frm_value": "4" }, 
-- { "frm_deshabilitado":"true", "frm_tipo": "DATE", "frm_campo": "NACIMIENTO_1", "frm_etiqueta": "Fecha Nacimiento", "frm_value": "2020-10-12" }, 
-- { "frm_deshabilitado":"false", "frm_tipo": "TEXTAREA", "frm_campo": "DIRECCION_1", "frm_etiqueta": "Direccion", "frm_value": "6" } ]', 
-- 1, 'A');


--HISTORICO
CREATE TABLE rmx_vys_historico_casos (
  htc_id serial primary key,
  htc_cas_id integer NOT NULL,
  htc_cas_act_id integer NOT NULL,
  htc_cas_nodo_id integer NOT NULL,
  htc_cas_data jsonb NOT NULL DEFAULT '{}',
  htc_cas_data_valores jsonb NOT NULL DEFAULT '{}',
  htc_cas_registrado timestamp DEFAULT now(),
  htc_cas_modificado timestamp DEFAULT now(),
  htc_cas_usr_id integer NOT NULL,
  htc_cas_estado char(1) DEFAULT 'A'
);


--
-- FORMULARIOS
--
CREATE TABLE rmx_vys_tipos_formulario (
  tfrm_id serial primary key,
  tfrm_descripcion text NOT NULL,
  tfrm_registrado timestamp NOT NULL DEFAULT now(),
  tfrm_modificado timestamp NOT NULL DEFAULT now(),
  tfrm_usr_id integer NOT NULL,
  tfrm_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_vys_tipos_formulario (tfrm_id, tfrm_descripcion, tfrm_usr_id, tfrm_estado) VALUES 
( 1, 'Formulario', 1, 'A'),
( 2, 'Adjuntos' , 1, 'A'),
( 3, 'Impresiones', 1, 'A');


CREATE TABLE rmx_vys_formularios (
  frm_id serial primary key,
  frm_act_id integer NOT NULL,
  frm_data jsonb not null,
  frm_data_campos jsonb,
  frm_funciones text DEFAULT '',
  frm_registrado timestamp DEFAULT now(),
  frm_modificado timestamp DEFAULT now(),
  frm_usr_id integer NOT NULL,
  frm_estado char(1) DEFAULT 'A',
  FOREIGN KEY (frm_act_id) REFERENCES rmx_vys_actividades (act_id)
);
-- insert into rmx_vys_formularios(frm_act_id, frm_data, frm_data_campos, frm_usr_id) values
-- (2, '{"frm_orden":"10", "frm_descripcion":"REGISTRO", "frm_tfrm_id":"1", "frm_codigo":"F-101"}',
-- 	'[
-- 	{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"PATERNO_1","frm_etiqueta":"Paterno","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"MATERNO_1","frm_etiqueta":"Materno","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"NOMBRES_1","frm_etiqueta":"Nombres","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"DIRECCION_1","frm_etiqueta":"Dirección","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"ACTIVIDAD_1","frm_etiqueta":"Tipo de Actividad","frm_deshabilitado":"false",
-- 		"frm_items":[
-- 			{"frm_value":"TB","frm_etiqueta":"Tienda de Barrio"},
-- 			{"frm_value":"CO","frm_etiqueta":"Comedor"},
-- 			{"frm_value":"PE","frm_etiqueta":"Peluquería"}
-- 		]
-- 	},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"INSPECCION_1","frm_etiqueta":"Con Inspección","frm_deshabilitado":"false",
-- 		"frm_items":[
-- 			{"frm_value":"1","frm_etiqueta":"SI"},
-- 			{"frm_value":"0","frm_etiqueta":"NO"}
-- 		]
-- 	}
-- 	]', 1),
-- (3, '{"frm_orden":"20", "frm_descripcion":"VALIDACIÓN", "frm_tfrm_id":"1", "frm_codigo":"F-201"}',
-- 	'[
-- 	{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"PATERNO_1","frm_etiqueta":"Paterno","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"MATERNO_1","frm_etiqueta":"Materno","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"NOMBRES_1","frm_etiqueta":"Nombres","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"TEXT","frm_campo":"DIRECCION_1","frm_etiqueta":"Dirección","frm_deshabilitado":"false"},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"ACTIVIDAD_1","frm_etiqueta":"Tipo de Actividad","frm_deshabilitado":"false",
-- 		"frm_items":[
-- 			{"frm_value":"TB","frm_etiqueta":"Tienda de Barrio"},
-- 			{"frm_value":"CO","frm_etiqueta":"Comedor"},
-- 			{"frm_value":"PE","frm_etiqueta":"Peluquería"}
-- 		]
-- 	},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"INSPECCION_1","frm_etiqueta":"Con Inspección","frm_deshabilitado":"false",
-- 		"frm_items":[
-- 			{"frm_value":"1","frm_etiqueta":"SI"},
-- 			{"frm_value":"0","frm_etiqueta":"NO"}
-- 		]
-- 	}
-- 	]', 1),
-- (4, '{"frm_orden":"30", "frm_descripcion":"CON INSPECCIÓN", "frm_tfrm_id":"1", "frm_codigo":"F-301"}',
-- 	'[
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"INSPECCION_1","frm_etiqueta":"Con Inspección","frm_deshabilitado":"true",
-- 		"frm_items":[
-- 			{"frm_value":"1","frm_etiqueta":"SI"},
-- 			{"frm_value":"0","frm_etiqueta":"NO"}
-- 		]
-- 	}
-- 	]', 1),
-- (5, '{"frm_orden":"40", "frm_descripcion":"INSPECCIÓN", "frm_tfrm_id":"1", "frm_codigo":"F-401"}',
-- 	'[
-- 	{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"TEXT","frm_campo":"PATERNO_1","frm_etiqueta":"Paterno","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"TEXT","frm_campo":"MATERNO_1","frm_etiqueta":"Materno","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"TEXT","frm_campo":"NOMBRES_1","frm_etiqueta":"Nombres","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"TEXT","frm_campo":"DIRECCION_1","frm_etiqueta":"Dirección","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"ACTIVIDAD_1","frm_etiqueta":"Tipo de Actividad","frm_deshabilitado":"true",
-- 		"frm_items":[
-- 			{"frm_value":"TB","frm_etiqueta":"Tienda de Barrio"},
-- 			{"frm_value":"CO","frm_etiqueta":"Comedor"},
-- 			{"frm_value":"PE","frm_etiqueta":"Peluquería"}
-- 		]
-- 	}
-- 	]', 1),
-- (6, '{"frm_orden":"50", "frm_descripcion":"RESULTADO", "frm_tfrm_id":"1", "frm_codigo":"F-501"}',
-- 	'[
-- 	{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"RESULTADO_1","frm_etiqueta":"Resultado","frm_deshabilitado":"false",
-- 		"frm_items":[
-- 			{"frm_value":"1","frm_etiqueta":"Aprobado"},
-- 			{"frm_value":"0","frm_etiqueta":"Rechazado"}
-- 		]
-- 	}
-- 	]', 1),
-- (7, '{"frm_orden":"60", "frm_descripcion":"APROBACIÓN", "frm_tfrm_id":"1", "frm_codigo":"F-601"}',
-- 	'[
-- 	{"frm_tipo":"TEXT","frm_campo":"CI_1","frm_etiqueta":"CI","frm_deshabilitado":"true"},
-- 	{"frm_tipo":"DROPDOWNLIST","frm_campo":"RESULTADO_1","frm_etiqueta":"Resultado","frm_deshabilitado":"true",
-- 		"frm_items":[
-- 			{"frm_value":"1","frm_etiqueta":"Aprobado"},
-- 			{"frm_value":"0","frm_etiqueta":"Rechazado"}
-- 		]
-- 	}
-- 	]', 1);


/*
CREATE TABLE rmx_vys_documentos_salida (
  dcs_id serial primary key,
  dcs_act_id integer NOT NULL,
  dcs_data jsonb NOT NULL, --{"orden":"10","titulo":"la cosa"}
  dcs_contenido text NOT NULL,
  dcs_registrado timestamp NOT NULL DEFAULT now(),
  dcs_modificado timestamp NOT NULL DEFAULT now(),
  dcs_usr_id integer NOT NULL,
  dcs_estado char(1) DEFAULT 'A',
  FOREIGN KEY (dcs_act_id) REFERENCES rmx_vys_actividades (act_id)
);
insert into rmx_vys_documentos_salida (dcs_act_id,dcs_data,dcs_contenido,dcs_usr_id)
values(1,'{"orden":"10","titulo":"la cosa"}','<h1>Resumen</h1><p>Ejemplod e documento</p>
<table border="1"><tr><th>CAMPO 1</th><th>CAMPO 2</th><th>CAMPO 3</th></tr>
<tr><td>CAMPO 1</td><td>CAMPO 2</td><tD>CAMPO 3</td></tr></table>',1);
*/


CREATE TABLE rmx_vys_workspace (
  ws_id serial NOT NULL,
  ws_descripcion character varying NOT NULL,
  ws_registrado timestamp NOT NULL DEFAULT now(),
  ws_modificado timestamp NOT NULL DEFAULT now(),
  ws_usr_id integer NOT NULL,
  ws_estado char(1) DEFAULT 'A',
  CONSTRAINT _bp_workspace_pkey PRIMARY KEY (ws_id)
);


CREATE TABLE rmx_vys_usuarios_workspace (
  uw_id serial NOT NULL,
  uw_usuario_id integer NOT NULL,
  uw_ws_id integer NOT NULL,
  uw_registrado timestamp NOT NULL DEFAULT now(),
  uw_modificado timestamp NOT NULL DEFAULT now(),
  uw_usr_id integer NOT NULL,
  uw_estado char(1) DEFAULT 'A',
  FOREIGN KEY (uw_ws_id) REFERENCES rmx_vys_workspace (ws_id)
);


--       {"frm_tipo":"TEXTAREA","frm_campo":"INFORME_INSPECCION_1","frm_etiqueta":"Informe Inspeción","frm_deshabilitado":"false"},
--       {"frm_tipo":"DROPDOWNLIST","frm_campo":"RESULTADO_INSPECCION_1","frm_etiqueta":"Resultado Inspeción","frm_deshabilitado":"false",
--         "frm_items":[
--           {"frm_value":"1","frm_etiqueta":"REPROBADO"},
--           {"frm_value":"2","frm_etiqueta":"APROBADO"}
--         ]
--       }


-- *
-- * CORRESPONDENCIA 2021
-- *


CREATE TABLE rmx_crr_tipos_correspondencia (
    tcrr_id serial PRIMARY KEY,
    tcrr_codigo text NOT NULL,
    tcrr_descripcion text NOT NULL,
    tcrr_registrado timestamp DEFAULT now(),
    tcrr_modificado timestamp,
    tcrr_usr_id integer,
    tcrr_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_crr_tipos_correspondencia (tcrr_codigo, tcrr_descripcion, tcrr_usr_id) VALUES
('I', 'Interna', 1),
('E', 'Externa', 1);


CREATE TABLE rmx_crr_correspondencia (
    crr_id serial PRIMARY KEY,
    crr_nodo_id integer NOT NULL,
    crr_tcrr_id integer NOT NULL DEFAULT 1,
    crr_clonado_id integer,
    crr_data jsonb DEFAULT '{}',
    crr_registrado timestamp DEFAULT now(),
    crr_modificado timestamp,
    crr_usr_id integer,
    crr_estado char(1) DEFAULT 'A',
    FOREIGN KEY (crr_tcrr_id) REFERENCES rmx_crr_tipos_correspondencia (tcrr_id)
);
ALTER TABLE rmx_crr_correspondencia ADD FOREIGN KEY (crr_clonado_id) REFERENCES rmx_crr_correspondencia (crr_id);

-- INSERT INTO rmx_crr_correspondencia (crr_nodo_id, crr_data, crr_usr_id) VALUES
-- (1, '{"crr_origen_id":"1",
--       "crr_destino_id":"1",
--       "crr_ciudadano_ci":"1",
--       "crr_ciudadano_nombres":"1",
--       "crr_ciudadano_celular":"1",
--       "crr_ciudadano_telefono":"1",
--       "crr_fojas":"1 a 20",
--       "crr_cite":"UNIDAD-01/2021",
--       "crr_recibido":"timestamp",
--       "crr_asunto":"Consulta estado de tramite",
--       "crr_aceptado":"T"}', 0); -- no se usara

-- INSERT INTO rmx_crr_correspondencia (crr_nodo_id, crr_data, crr_usr_id) VALUES
-- (2, '{"crr_origen_id":"1",
--       "crr_destino_id":"1",
--       "crr_ciudadano_ci":"1",
--       "crr_ciudadano_nombres":"1",
--       "crr_ciudadano_celular":"1",
--       "crr_ciudadano_telefono":"1",
--       "crr_fojas":"1 a 20",
--       "crr_cite":"UNIDAD-02/2021",
--       "crr_recibido":"timestamp",
--       "crr_asunto":"Solicitud de Reunión",
--       "crr_aceptado":"T"}', 0); -- no se usara
 
-- INSERT INTO rmx_crr_correspondencia (crr_nodo_id, crr_tcrr_id, crr_data, crr_usr_id) VALUES
-- (3, 2, '{"crr_origen_id":"1",
--       "crr_destino_id":"1",
--       "crr_ciudadano_ci":"1112222",
--       "crr_ciudadano_nombres":"Raúl Huarachi",
--       "crr_ciudadano_celular":"729121221",
--       "crr_ciudadano_telefono":"436666",
--       "crr_fojas":"1 a 10",
--       "crr_cite":"UNIDAD-03/2021",
--       "crr_recibido":"timestamp",
--       "crr_asunto":"Solicitud Tarimas",
--       "crr_aceptado":"T"}', 0); -- no se usara
 
-- INSERT INTO rmx_crr_correspondencia (crr_nodo_id, crr_tcrr_id, crr_data, crr_usr_id) VALUES
-- (4, 2, '{"crr_origen_id":"1",
--       "crr_destino_id":"1",
--       "crr_ciudadano_ci":"1112222",
--       "crr_ciudadano_nombres":"Jorge Rémdiz",
--       "crr_ciudadano_celular":"72922222",
--       "crr_ciudadano_telefono":"434444",
--       "crr_fojas":"1 a 10",
--       "crr_cite":"UNIDAD-04/2021",
--       "crr_recibido":"timestamp",
--       "crr_asunto":"Designación",
--       "crr_aceptado":"T"}', 0); -- no se usara


CREATE TABLE rmx_crr_adjuntos (
    adj_id serial PRIMARY KEY,
    adj_crr_id integer NOT NULL,
    adj_data jsonb DEFAULT '{}',
    adj_registrado timestamp DEFAULT now(),
    adj_modificado timestamp,
    adj_usr_id integer,
    adj_estado char(1) DEFAULT 'A',
    FOREIGN KEY (adj_crr_id) REFERENCES rmx_crr_correspondencia (crr_id)
);
-- insert into rmx_crr_adjuntos(adj_crr_id, adj_data, adj_usr_id) values
-- (1, '{"adj_detalle":"fotocopia ci", "adj_nombre_archivo":"cv_3344556.pdf"}', 1),
-- (1, '{"adj_detalle":"fotocopia carta", "adj_nombre_archivo":"carta_3344556.pdf"}', 1),
-- (1, '{"adj_detalle":"fotocopia ci", "adj_nombre_archivo":"cv_1122334.pdf"}', 1);
 
 
CREATE TABLE rmx_crr_actuaciones (
    act_id serial PRIMARY KEY,
    act_crr_id integer NOT NULL,
    act_data jsonb NOT NULL DEFAULT '{}',
    act_registrado timestamp DEFAULT now(),
    act_modificado timestamp,
    act_usr_id integer,
    act_estado char(1) DEFAULT 'A',
    FOREIGN KEY (act_crr_id) REFERENCES rmx_crr_correspondencia (crr_id)
);
-- insert into rmx_crr_actuaciones(act_crr_id, act_data, act_usr_id) values
-- (1, '{"act_descripcion":"Se elaboro informe"}', 1),
-- (1, '{"act_descripcion":"Se envia informe conjunto"}', 1),
-- (1, '{"act_descripcion":"Se realizo reunión con involucrados"}', 1);


CREATE TABLE rmx_crr_copias (
    copia_id serial PRIMARY KEY,
    copia_crr_id integer NOT NULL,
    copia_destino_id integer NOT NULL,
    copia_nro_copia integer NOT NULL,
    copia_registrado timestamp DEFAULT now(),
    copia_modificado timestamp,
    copia_usr_id integer,
    copia_estado char(1) DEFAULT 'A',
    FOREIGN KEY (copia_crr_id) REFERENCES rmx_crr_correspondencia (crr_id)
);
-- insert into rmx_crr_copias(copia_crr_id, copia_destino_id, copia_nro_copia, copia_usr_id) values
-- (1, 3, 1, 1),
-- (1, 4, 2, 1),
-- (1, 5, 3, 1);


CREATE TABLE rmx_crr_historicos (
    hst_id integer,
    hst_nodo_id integer,
    hst_clonado_id integer,
    hst_data jsonb,
    hst_copias jsonb,
    hst_registrado timestamp DEFAULT now(),
    hst_usr_id integer,
    FOREIGN KEY (hst_id) REFERENCES rmx_crr_correspondencia (crr_id),
    FOREIGN KEY (hst_clonado_id) REFERENCES rmx_crr_correspondencia (crr_id)
);


CREATE TABLE rmx_usr_nodos (
	usn_id serial PRIMARY KEY,
	usn_user_id integer,
	usn_nodo_id integer,
	usn_registrado timestamp DEFAULT now(),
	usn_modificado timestamp,
	usn_usr_id integer,
	usn_estado char(1) DEFAULT 'A',
	FOREIGN KEY (usn_user_id) REFERENCES users (id),
	FOREIGN KEY (usn_nodo_id) REFERENCES rmx_vys_nodos (nodo_id)
);
-- INSERT INTO rmx_usr_nodos (usn_user_id, usn_nodo_id, usn_usr_id) VALUES
-- (1, 2, 1),
-- (3, 5, 1),
-- (2, 10, 1);

