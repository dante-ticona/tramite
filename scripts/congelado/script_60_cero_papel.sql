--
-- SERVICIOS
--

DROP TABLE IF EXISTS rmx_cp_tipos_archivo;
CREATE TABLE rmx_cp_tipos_archivo (
  tarch_id serial primary key,
  tarch_codigo text NOT NULL,
  tarch_descripcion text NOT NULL,
  tarch_registrado timestamp DEFAULT now(),
  tarch_modificado timestamp DEFAULT now(),
  tarch_usr_id integer NOT NULL,
  tarch_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_cp_tipos_archivo (tarch_codigo, tarch_descripcion, tarch_usr_id) VALUES
('HIS', 'ARCHIVO HISTORICO', 1),
('CEN', 'ARCHIVO CENTRAL', 1),
('INTER', 'ARCHIVO INTERMEDIO', 1),
('UO', 'ARCHIVO UO', 1);


DROP TABLE IF EXISTS rmx_cp_archivos;
CREATE TABLE rmx_cp_archivos (
  arch_id serial primary key,
  arch_tarch_id integer NOT NULL,
  arch_codigo text NOT NULL,
  arch_descripcion text NOT NULL,
  arch_padre_id integer DEFAULT 0,
  arch_registrado timestamp DEFAULT now(),
  arch_modificado timestamp DEFAULT now(),
  arch_usr_id integer NOT NULL,
  arch_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_cp_archivos (arch_tarch_id, arch_codigo, arch_descripcion, arch_padre_id, arch_usr_id) VALUES
(2, '0', 'Archivo Central', 0, 1),
(3, '1', 'Archivo Actividades Económicas', 1, 1),
(4, '1.1', 'Archivo Licencias de Funcionamiento', 2, 1),
(4, '1.1', 'Archivo Publicidad', 2, 1),
(4, '1.2', 'Archivo Áridos', 2, 1);


DROP TABLE IF EXISTS rmx_cp_tipos_documentales;
CREATE TABLE rmx_cp_tipos_documentales (
  tdoc_id serial primary key,
  tdoc_codigo text NOT NULL,
  tdoc_descripcion text NOT NULL,
  tdoc_registrado timestamp DEFAULT now(),
  tdoc_modificado timestamp DEFAULT now(),
  tdoc_usr_id integer NOT NULL,
  tdoc_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_cp_tipos_documentales (tdoc_codigo, tdoc_descripcion, tdoc_usr_id) VALUES
('INF', 'INFORMES', 1),
('ODS', 'ORDENES DE SERVICIOS', 1),
('MEM', 'MEMORANDUMS', 1),
('COM', 'COMUNICADO', 1);


DROP TABLE IF EXISTS rmx_cp_subtipos_documentales;
CREATE TABLE rmx_cp_subtipos_documentales (
  stdoc_id serial primary key,
  stdoc_tdoc_id integer NOT NULL,
  stdoc_codigo text NOT NULL,
  stdoc_descripcion text NOT NULL,
  stdoc_contenido text DEFAULT '',
  stdoc_registrado timestamp DEFAULT now(),
  stdoc_modificado timestamp DEFAULT now(),
  stdoc_usr_id integer NOT NULL,
  stdoc_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_cp_subtipos_documentales (stdoc_tdoc_id, stdoc_codigo, stdoc_descripcion, stdoc_contenido, stdoc_usr_id) VALUES
(1, 'INF_TEC_', 'Informe Técnico', '<CENTER><B>INFORME TÉCNICO</B></CENTER><BR><BR>', 1),
(1, 'INF_LEG_', 'Informe Legal', '<CENTER><B>INFORME LEGAL</B></CENTER><BR><BR>', 1),
(1, 'INF_TEC_LEG_', 'Informe Técnico Legal', '<CENTER><B>INFORME TÉCNICO - LEGAL</B></CENTER><BR><BR>', 1),
(1, 'INF_CON_', 'Informe Conjunto', '<CENTER><B>INFORME CONJUNTO</B></CENTER><BR><BR>', 1);

