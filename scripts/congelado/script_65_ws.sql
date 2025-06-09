--
-- SERVICIOS
--


CREATE TABLE rmx_vys_tipos_ws (
  tws_id serial primary key,
  tws_codigo text NOT NULL,
  tws_descripcion text NOT NULL,
  tws_registrado timestamp DEFAULT now(),
  tws_modificado timestamp DEFAULT now(),
  tws_usr_id integer NOT NULL,
  tws_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_vys_tipos_ws (tws_codigo, tws_descripcion, tws_usr_id) VALUES
('BD', 'Conexion a PostgreSQL', 1),
('REST', 'Conexión a Servicio REST', 1),
('OTRO', 'Conexion a WMS', 1);

CREATE TABLE rmx_vys_ws (
  ws_id serial primary key,
  ws_tws_id integer NOT NULL,
  ws_codigo text NOT NULL,
  ws_descripcion text NOT NULL,
  ws_contenido text DEFAULT '',
  ws_registrado timestamp DEFAULT now(),
  ws_modificado timestamp DEFAULT now(),
  ws_usr_id integer NOT NULL,
  ws_estado char(1) DEFAULT 'A'
);
INSERT INTO rmx_vys_ws (ws_tws_id, ws_codigo, ws_descripcion, ws_contenido, ws_usr_id) VALUES
(1, 'WS_LEER_PROCESOS', 'LISTAR PROCESOS DE LA BASE DE DATOS', 'echo "hola mundo!"', 1);

