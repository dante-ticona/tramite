.dominio (
    id serial PRIMARY KEY,
    dominio text NOT NULL,
    codigo text NOT NULL,
    nombre text NOT NULL,
    orden integer NULL,
    descripcion text NULL,
    subdominio text NULL,
    fecha_registro timestamp without time zone NOT NULL DEFAULT now(),
    fecha_modifica timestamp without time zone NOT NULL DEFAULT now(),
    usuario_registro integer NULL,
    estado bpchar(1) NOT NULL DEFAULT 'A'::bpchar
);
