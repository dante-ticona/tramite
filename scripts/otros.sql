

ALTER TABLE public.rmx_vys_casos ADD cas_correlativo serial NOT NULL;

ALTER SEQUENCE public.rmx_vys_casos_cas_correlativo_seq restart with 57000;

