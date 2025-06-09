CREATE TABLE rmx_vys_externos (
  ext_id serial primary key,
  ext_act_id integer NOT NULL,
  ext_nodo_id integer NOT NULL,
  ext_body jsonb NOT NULL DEFAULT '{}',
  ext_registrado timestamp DEFAULT now(),
  ext_modificado timestamp DEFAULT now(),
  ext_usr_id integer NOT NULL,
  ext_estado char(1) DEFAULT 'A'
);
-- INSERT INTO rmx_vys_externos (ext_act_id, ext_nodo_id, ext_body, ext_usr_id) VALUES
-- (222, 34, '{"campo1":"externo", "campo2":"externo"}', 1);


CREATE TABLE rmx_vys_nodos_procesos (
  nopr_id serial primary key,
  nopr_nodo_id integer NOT NULL,
  nopr_prc_id integer NOT NULL,
  nopr_registrado timestamp DEFAULT now(),
  nopr_modificado timestamp DEFAULT now(),
  nopr_usr_id integer NOT NULL,
  nopr_estado char(1) DEFAULT 'A'
);
-- INSERT INTO rmx_vys_nodos_procesos (nopr_nodo_id, nopr_prc_id, nopr_usr_id) VALUES
-- (34, 31, 1),
-- (34, 36, 1),
-- (34, 37, 1);


CREATE TABLE rmx_vys_usrnodos_roundrobin (
  rr_id serial primary key,
  rr_nodo_id integer NOT NULL,
  rr_ultimo_usr_id integer NOT NULL,
  rr_registrado timestamp DEFAULT now(),
  rr_modificado timestamp DEFAULT now(),
  rr_usr_id integer NOT NULL,
  rr_estado char(1) DEFAULT 'A'
);
