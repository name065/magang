--
-- PostgreSQL database dump
--

\restrict VBjQadWYpRTAUd7wHU4hehPfka2WhokQWMtpIwmIhRp53HytnRCRPzuk6tHnowp

-- Dumped from database version 17.7
-- Dumped by pg_dump version 17.7

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: tb_tiket_aula_insert(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tb_tiket_aula_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  INSERT INTO public.tb_tiket_detail (id_tiket, tipe, judul, mulai, selesai, detail)
  VALUES (
    NEW.id_tiket,
    'aula',
    NEW.nama_acara,
    NEW.tgl_awal,
    NEW.tgl_akhir,
    jsonb_build_object(
      'id_pelayanan_aula', NEW.id_pelayanan_aula,
      'id_aula', NEW.id_aula,
      'nama_pic', NEW.nama_pic,
      'no_pic', NEW.no_pic,
      'berkas_pengantar', NEW.berkas_pengantar
    )
  );

  RETURN NEW;
END;
$$;


ALTER FUNCTION public.tb_tiket_aula_insert() OWNER TO postgres;

--
-- Name: tb_tiket_zoom_insert(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.tb_tiket_zoom_insert() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
  INSERT INTO public.tb_tiket_detail (id_tiket, tipe, judul, mulai, selesai, detail)
  VALUES (
    NEW.id_tiket,
    'zoom',
    NEW.nama_acara,
    NEW.tgl_awal,
    NEW.tgl_akhir,
    jsonb_build_object(
      'id_pelayanan_zoom', NEW.id_pelayanan_zoom,
      'nama_pic', NEW.nama_pic,
      'no_pic', NEW.no_pic,
      'berkas_pengantar', NEW.berkas_pengantar,
      'jenis_zoom', NEW.jenis_zoom,
      'meeting_id', NEW.meeting_id,
      'passcode', NEW.passcode,
      'tempat', NEW.tempat,
      'operator', NEW."operator"
    )
  );

  RETURN NEW;
END;
$$;


ALTER FUNCTION public.tb_tiket_zoom_insert() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: log_aktifitas_magang; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.log_aktifitas_magang (
    id_log integer NOT NULL,
    id_user integer,
    tgl_aktifitas timestamp without time zone,
    aktifitas character varying(255),
    color character varying(50),
    id_tiket integer,
    icon character varying(50)
);


ALTER TABLE public.log_aktifitas_magang OWNER TO postgres;

--
-- Name: log_aktifitas_magang_id_log_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.log_aktifitas_magang_id_log_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.log_aktifitas_magang_id_log_seq OWNER TO postgres;

--
-- Name: log_aktifitas_magang_id_log_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.log_aktifitas_magang_id_log_seq OWNED BY public.log_aktifitas_magang.id_log;


--
-- Name: log_aktifitas_pelayanan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.log_aktifitas_pelayanan (
    id_log integer NOT NULL,
    id_user integer,
    tgl_aktifitas timestamp without time zone,
    aktifitas character varying(255),
    color character varying(50),
    id_tiket integer,
    icon character varying(50)
);


ALTER TABLE public.log_aktifitas_pelayanan OWNER TO postgres;

--
-- Name: log_aktifitas_pelayanan_id_log _seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."log_aktifitas_pelayanan_id_log _seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public."log_aktifitas_pelayanan_id_log _seq" OWNER TO postgres;

--
-- Name: log_aktifitas_pelayanan_id_log _seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."log_aktifitas_pelayanan_id_log _seq" OWNED BY public.log_aktifitas_pelayanan.id_log;


--
-- Name: ssalat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssalat (
    id_alat integer NOT NULL,
    nama_alat character varying(255),
    nomor_seri character varying(20),
    active integer,
    tgl_input timestamp without time zone,
    merk character varying(255)
);


ALTER TABLE public.ssalat OWNER TO postgres;

--
-- Name: ssalat_id_alat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssalat_id_alat_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssalat_id_alat_seq OWNER TO postgres;

--
-- Name: ssalat_id_alat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssalat_id_alat_seq OWNED BY public.ssalat.id_alat;


--
-- Name: ssaula; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssaula (
    id_aula integer NOT NULL,
    nama_aula character varying(75),
    active integer,
    tgl_input timestamp without time zone
);


ALTER TABLE public.ssaula OWNER TO postgres;

--
-- Name: ssaula_id_aula_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssaula_id_aula_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssaula_id_aula_seq OWNER TO postgres;

--
-- Name: ssaula_id_aula_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssaula_id_aula_seq OWNED BY public.ssaula.id_aula;


--
-- Name: ssfaq; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssfaq (
    id_faq integer NOT NULL,
    id_opd integer,
    pertanyaan character varying(150),
    jawaban character varying(500),
    active integer,
    tgl_input timestamp without time zone
);


ALTER TABLE public.ssfaq OWNER TO postgres;

--
-- Name: ssfaq_id_faq_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssfaq_id_faq_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssfaq_id_faq_seq OWNER TO postgres;

--
-- Name: ssfaq_id_faq_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssfaq_id_faq_seq OWNED BY public.ssfaq.id_faq;


--
-- Name: ssopd; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssopd (
    id_opd integer NOT NULL,
    nama_opd character varying(125),
    akronim_opd character varying(50),
    foto_opd character varying(255)
);


ALTER TABLE public.ssopd OWNER TO postgres;

--
-- Name: ssopd_id_opd_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssopd_id_opd_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssopd_id_opd_seq OWNER TO postgres;

--
-- Name: ssopd_id_opd_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssopd_id_opd_seq OWNED BY public.ssopd.id_opd;


--
-- Name: ssotp; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssotp (
    id_otp integer NOT NULL,
    id_ssuser integer,
    kode_otp character(6),
    exp_date timestamp without time zone,
    active integer
);


ALTER TABLE public.ssotp OWNER TO postgres;

--
-- Name: ssotp_id_otp_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssotp_id_otp_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssotp_id_otp_seq OWNER TO postgres;

--
-- Name: ssotp_id_otp_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssotp_id_otp_seq OWNED BY public.ssotp.id_otp;


--
-- Name: sspelayanan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sspelayanan (
    id_pelayanan integer NOT NULL,
    id_opd integer NOT NULL,
    nama_pelayanan character varying(125),
    route character varying(50),
    url character varying(125),
    file_foto character varying(255),
    active integer,
    tgl_input timestamp without time zone,
    deskripsi character varying(255)
);


ALTER TABLE public.sspelayanan OWNER TO postgres;

--
-- Name: sspelayanan_id_pelayanan_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sspelayanan_id_pelayanan_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sspelayanan_id_pelayanan_seq OWNER TO postgres;

--
-- Name: sspelayanan_id_pelayanan_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sspelayanan_id_pelayanan_seq OWNED BY public.sspelayanan.id_pelayanan;


--
-- Name: ssuser; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssuser (
    id_ssuser integer NOT NULL,
    username character varying(20) NOT NULL,
    nip character varying(20),
    nik character varying(20),
    id_chat bigint,
    active integer,
    role_id integer,
    file_foto character(150),
    id_opd integer,
    tgl_input timestamp without time zone,
    tgl_validasi timestamp without time zone,
    nama character(100)
);


ALTER TABLE public.ssuser OWNER TO postgres;

--
-- Name: ssuser_id_ssuser_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssuser_id_ssuser_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssuser_id_ssuser_seq OWNER TO postgres;

--
-- Name: ssuser_id_ssuser_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssuser_id_ssuser_seq OWNED BY public.ssuser.id_ssuser;


--
-- Name: ssuser_magang; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssuser_magang (
    id_ssuser_magang integer NOT NULL,
    id_ssuser integer,
    gender integer,
    wa character varying(15),
    jenis integer,
    nomor_induk character varying(20),
    jurusan character varying(150),
    civitas character varying(150),
    ktp character varying(50)
);


ALTER TABLE public.ssuser_magang OWNER TO postgres;

--
-- Name: ssuser_magang_id_ssuser_magang_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssuser_magang_id_ssuser_magang_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssuser_magang_id_ssuser_magang_seq OWNER TO postgres;

--
-- Name: ssuser_magang_id_ssuser_magang_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssuser_magang_id_ssuser_magang_seq OWNED BY public.ssuser_magang.id_ssuser_magang;


--
-- Name: ssuser_pembimbing; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ssuser_pembimbing (
    id_ssuser_pembimbing integer NOT NULL,
    id_ssuser integer NOT NULL,
    id_sub integer NOT NULL
);


ALTER TABLE public.ssuser_pembimbing OWNER TO postgres;

--
-- Name: ssuser_pembimbing_id_ssuser_pembimbing_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ssuser_pembimbing_id_ssuser_pembimbing_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ssuser_pembimbing_id_ssuser_pembimbing_seq OWNER TO postgres;

--
-- Name: ssuser_pembimbing_id_ssuser_pembimbing_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ssuser_pembimbing_id_ssuser_pembimbing_seq OWNED BY public.ssuser_pembimbing.id_ssuser_pembimbing;


--
-- Name: sub_bagian; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sub_bagian (
    id_sub integer NOT NULL,
    id_opd integer,
    nama_sub character varying(255),
    tgl_input timestamp without time zone,
    active integer
);


ALTER TABLE public.sub_bagian OWNER TO postgres;

--
-- Name: sub_bagian_id_sub_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.sub_bagian_id_sub_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sub_bagian_id_sub_seq OWNER TO postgres;

--
-- Name: sub_bagian_id_sub_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.sub_bagian_id_sub_seq OWNED BY public.sub_bagian.id_sub;


--
-- Name: tb_tiket; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket (
    id_tiket integer NOT NULL,
    kode_tiket character varying(255),
    tgl_input timestamp without time zone,
    id_pelayanan integer,
    id_user integer,
    status integer,
    catatan character varying(255)
);


ALTER TABLE public.tb_tiket OWNER TO postgres;

--
-- Name: tb_tiket_alat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket_alat (
    id_pelayanan_alat bigint NOT NULL,
    id_tiket bigint,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.tb_tiket_alat OWNER TO postgres;

--
-- Name: tb_tiket_alat_id_pelayanan_alat_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_alat_id_pelayanan_alat_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_alat_id_pelayanan_alat_seq OWNER TO postgres;

--
-- Name: tb_tiket_alat_id_pelayanan_alat_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_alat_id_pelayanan_alat_seq OWNED BY public.tb_tiket_alat.id_pelayanan_alat;


--
-- Name: tb_tiket_alat_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket_alat_list (
    id bigint NOT NULL,
    id_pelayanan_alat bigint NOT NULL,
    id_alat bigint,
    jumlah integer DEFAULT 1,
    keterangan text,
    created_at timestamp without time zone DEFAULT now(),
    updated_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.tb_tiket_alat_list OWNER TO postgres;

--
-- Name: tb_tiket_alat_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_alat_list_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_alat_list_id_seq OWNER TO postgres;

--
-- Name: tb_tiket_alat_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_alat_list_id_seq OWNED BY public.tb_tiket_alat_list.id;


--
-- Name: tb_tiket_detail; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket_detail (
    id_detail bigint NOT NULL,
    id_tiket bigint NOT NULL,
    tipe text NOT NULL,
    judul text,
    keterangan text,
    mulai timestamp with time zone,
    selesai timestamp with time zone,
    detail jsonb DEFAULT '{}'::jsonb NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.tb_tiket_detail OWNER TO postgres;

--
-- Name: tb_tiket_aula; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.tb_tiket_aula AS
 SELECT id_detail AS id_pelayanan_aula,
    id_tiket,
    judul AS nama_acara,
    mulai AS tgl_awal,
    ((detail ->> 'id_aula'::text))::bigint AS id_aula,
    (detail ->> 'nama_pic'::text) AS nama_pic,
    (detail ->> 'no_pic'::text) AS no_pic,
    (detail ->> 'berkas_pengantar'::text) AS berkas_pengantar,
    selesai AS tgl_akhir
   FROM public.tb_tiket_detail d
  WHERE (tipe = 'aula'::text);


ALTER VIEW public.tb_tiket_aula OWNER TO postgres;

--
-- Name: tb_tiket_detail_id_detail_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_detail_id_detail_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_detail_id_detail_seq OWNER TO postgres;

--
-- Name: tb_tiket_detail_id_detail_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_detail_id_detail_seq OWNED BY public.tb_tiket_detail.id_detail;


--
-- Name: tb_tiket_id_tiket_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_id_tiket_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_id_tiket_seq OWNER TO postgres;

--
-- Name: tb_tiket_id_tiket_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_id_tiket_seq OWNED BY public.tb_tiket.id_tiket;


--
-- Name: tb_tiket_magang; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket_magang (
    id_tiket integer NOT NULL,
    kode_tiket character varying(20),
    tgl_input timestamp without time zone,
    status integer,
    catatan character varying(150),
    id_opd integer,
    tgl_awal date,
    tgl_akhir date,
    nama_pembimbing character varying(50),
    no_pembimbing character varying(15),
    surat_pengantar character varying(50),
    id_user integer,
    nama_project character varying(150),
    deskripsi_project character varying(150),
    berkas_project character varying(50),
    tgl_selesai timestamp without time zone,
    id_pembina_lapangan integer
);


ALTER TABLE public.tb_tiket_magang OWNER TO postgres;

--
-- Name: tb_tiket_magang_id_tiket_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_magang_id_tiket_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_magang_id_tiket_seq OWNER TO postgres;

--
-- Name: tb_tiket_magang_id_tiket_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_magang_id_tiket_seq OWNED BY public.tb_tiket_magang.id_tiket;


--
-- Name: tb_tiket_magang_nilai; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.tb_tiket_magang_nilai (
    id_magang_nilai integer NOT NULL,
    id_tiket integer,
    nilai_performance integer,
    nilai_sikap integer,
    nilai_kerjasama integer,
    nilai_disiplin integer,
    nilai_komunikasi integer,
    nilai_tanggung_jawab integer,
    nilai_teknis integer,
    catatan_nilai character varying(255),
    tgl_input timestamp without time zone
);


ALTER TABLE public.tb_tiket_magang_nilai OWNER TO postgres;

--
-- Name: tb_tiket_magang_nilai_id_magang_nilai_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.tb_tiket_magang_nilai_id_magang_nilai_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tb_tiket_magang_nilai_id_magang_nilai_seq OWNER TO postgres;

--
-- Name: tb_tiket_magang_nilai_id_magang_nilai_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.tb_tiket_magang_nilai_id_magang_nilai_seq OWNED BY public.tb_tiket_magang_nilai.id_magang_nilai;


--
-- Name: tb_tiket_zoom; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.tb_tiket_zoom AS
 SELECT id_detail AS id_pelayanan_zoom,
    id_tiket,
    judul AS nama_acara,
    mulai AS tgl_awal,
    selesai AS tgl_akhir,
    (detail ->> 'nama_pic'::text) AS nama_pic,
    (detail ->> 'no_pic'::text) AS no_pic,
    (detail ->> 'berkas_pengantar'::text) AS berkas_pengantar,
    (detail ->> 'jenis_zoom'::text) AS jenis_zoom,
    (detail ->> 'meeting_id'::text) AS meeting_id,
    (detail ->> 'passcode'::text) AS passcode,
    (detail ->> 'tempat'::text) AS tempat,
    (detail ->> 'operator'::text) AS operator
   FROM public.tb_tiket_detail d
  WHERE (tipe = 'zoom'::text);


ALTER VIEW public.tb_tiket_zoom OWNER TO postgres;

--
-- Name: v_kalender_event; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.v_kalender_event AS
 SELECT t.id_tiket,
    t.kode_tiket,
    t.status,
    d.tipe,
    COALESCE(d.judul, (d.detail ->> 'nama_aplikasi'::text), (d.detail ->> 'nama_subdomain'::text), (d.detail ->> 'nama'::text)) AS judul,
    d.mulai AS start_at,
    COALESCE(d.selesai, d.mulai) AS end_at,
    d.detail
   FROM (public.tb_tiket t
     JOIN public.tb_tiket_detail d ON ((d.id_tiket = t.id_tiket)))
  WHERE (d.mulai IS NOT NULL);


ALTER VIEW public.v_kalender_event OWNER TO postgres;

--
-- Name: verifikator_pelayanan; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.verifikator_pelayanan (
    id_vpelayanan integer NOT NULL,
    id_user integer,
    id_pelayanan integer
);


ALTER TABLE public.verifikator_pelayanan OWNER TO postgres;

--
-- Name: verifikator_pelayanan_id_vpelayanan_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.verifikator_pelayanan_id_vpelayanan_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.verifikator_pelayanan_id_vpelayanan_seq OWNER TO postgres;

--
-- Name: verifikator_pelayanan_id_vpelayanan_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.verifikator_pelayanan_id_vpelayanan_seq OWNED BY public.verifikator_pelayanan.id_vpelayanan;


--
-- Name: log_aktifitas_magang id_log; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_magang ALTER COLUMN id_log SET DEFAULT nextval('public.log_aktifitas_magang_id_log_seq'::regclass);


--
-- Name: log_aktifitas_pelayanan id_log; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_pelayanan ALTER COLUMN id_log SET DEFAULT nextval('public."log_aktifitas_pelayanan_id_log _seq"'::regclass);


--
-- Name: ssalat id_alat; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssalat ALTER COLUMN id_alat SET DEFAULT nextval('public.ssalat_id_alat_seq'::regclass);


--
-- Name: ssaula id_aula; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssaula ALTER COLUMN id_aula SET DEFAULT nextval('public.ssaula_id_aula_seq'::regclass);


--
-- Name: ssfaq id_faq; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssfaq ALTER COLUMN id_faq SET DEFAULT nextval('public.ssfaq_id_faq_seq'::regclass);


--
-- Name: ssopd id_opd; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssopd ALTER COLUMN id_opd SET DEFAULT nextval('public.ssopd_id_opd_seq'::regclass);


--
-- Name: ssotp id_otp; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssotp ALTER COLUMN id_otp SET DEFAULT nextval('public.ssotp_id_otp_seq'::regclass);


--
-- Name: sspelayanan id_pelayanan; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sspelayanan ALTER COLUMN id_pelayanan SET DEFAULT nextval('public.sspelayanan_id_pelayanan_seq'::regclass);


--
-- Name: ssuser id_ssuser; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser ALTER COLUMN id_ssuser SET DEFAULT nextval('public.ssuser_id_ssuser_seq'::regclass);


--
-- Name: ssuser_magang id_ssuser_magang; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_magang ALTER COLUMN id_ssuser_magang SET DEFAULT nextval('public.ssuser_magang_id_ssuser_magang_seq'::regclass);


--
-- Name: ssuser_pembimbing id_ssuser_pembimbing; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_pembimbing ALTER COLUMN id_ssuser_pembimbing SET DEFAULT nextval('public.ssuser_pembimbing_id_ssuser_pembimbing_seq'::regclass);


--
-- Name: sub_bagian id_sub; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sub_bagian ALTER COLUMN id_sub SET DEFAULT nextval('public.sub_bagian_id_sub_seq'::regclass);


--
-- Name: tb_tiket id_tiket; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket ALTER COLUMN id_tiket SET DEFAULT nextval('public.tb_tiket_id_tiket_seq'::regclass);


--
-- Name: tb_tiket_alat id_pelayanan_alat; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_alat ALTER COLUMN id_pelayanan_alat SET DEFAULT nextval('public.tb_tiket_alat_id_pelayanan_alat_seq'::regclass);


--
-- Name: tb_tiket_alat_list id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_alat_list ALTER COLUMN id SET DEFAULT nextval('public.tb_tiket_alat_list_id_seq'::regclass);


--
-- Name: tb_tiket_detail id_detail; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_detail ALTER COLUMN id_detail SET DEFAULT nextval('public.tb_tiket_detail_id_detail_seq'::regclass);


--
-- Name: tb_tiket_magang id_tiket; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_magang ALTER COLUMN id_tiket SET DEFAULT nextval('public.tb_tiket_magang_id_tiket_seq'::regclass);


--
-- Name: tb_tiket_magang_nilai id_magang_nilai; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_magang_nilai ALTER COLUMN id_magang_nilai SET DEFAULT nextval('public.tb_tiket_magang_nilai_id_magang_nilai_seq'::regclass);


--
-- Name: verifikator_pelayanan id_vpelayanan; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verifikator_pelayanan ALTER COLUMN id_vpelayanan SET DEFAULT nextval('public.verifikator_pelayanan_id_vpelayanan_seq'::regclass);


--
-- Data for Name: log_aktifitas_magang; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.log_aktifitas_magang (id_log, id_user, tgl_aktifitas, aktifitas, color, id_tiket, icon) FROM stdin;
1	14	2023-09-06 10:33:06	Membuat tiket	warning	3	fas fa-ticket-alt
2	1	2023-09-12 10:45:54	Memperbaharui catatan	primary	3	far fa-edit
3	1	2023-09-12 10:45:54	Tiket telah ditolak	danger	3	fa fa-times
4	1	2023-09-12 10:47:41	Memperbaharui catatan	primary	3	far fa-edit
5	1	2023-09-12 10:47:41	Tiket telah dibatalkan	dark	3	fa fa-times
6	1	2023-09-12 10:52:44	Tiket telah diverifikasi, peserta dapat melaksanakan kegiatan magang di instansi tujuan	info	3	fas fa-user-clock
7	1	2023-09-12 11:18:54	Memperbaharui Form Tugas dan Pembimbing Lapangan	primary	3	far fa-edit
8	1	2023-09-12 13:56:13	Memperbaharui Form Tugas dan Pembimbing Lapangan	primary	3	far fa-edit
9	1	2023-09-12 14:05:59	Tugas dan Pembimbing Lapangan telah ditentukan	info	3	fas fa-user-shield
10	1	2023-09-13 12:35:58	Memperbaharui Form Penilaian	primary	3	far fa-edit
11	1	2023-09-13 12:50:54	Form Penilaian telah diisi oleh Pembimbing Lapangan, peserta dapat mengakses nilai dan sertifikat magang	info	3	fas fa-user-edit
12	1	2023-09-13 12:50:54	Tiket telah diselesaikan	success	3	fa fa-check
13	1	2023-09-22 09:10:20	Membuat tiket	warning	4	fas fa-ticket-alt
14	1	2023-09-22 09:13:52	Tiket telah diverifikasi, peserta dapat melaksanakan kegiatan magang di instansi tujuan	info	4	fas fa-user-clock
15	1	2023-09-22 09:14:12	Memperbaharui Form Tugas dan Pembimbing Lapangan	primary	4	far fa-edit
16	1	2023-09-22 09:14:34	Tugas dan Pembimbing Lapangan telah ditentukan	info	4	fas fa-user-shield
17	1	2023-09-22 09:16:53	Memperbaharui Form Penilaian	primary	4	far fa-edit
18	1	2023-09-22 09:17:20	Form Penilaian telah diisi oleh Pembimbing Lapangan, peserta dapat mengakses nilai dan sertifikat magang	info	4	fas fa-user-edit
19	1	2023-09-22 09:17:20	Tiket telah diselesaikan	success	4	fa fa-check
20	3	2023-10-27 10:35:34	Memperbaharui catatan	primary	3	far fa-edit
21	3	2023-10-27 10:35:34	Tiket telah ditolak	danger	3	fa fa-times
22	3	2023-10-27 10:37:17	Tiket telah diverifikasi, peserta dapat melaksanakan kegiatan magang di instansi tujuan	info	3	fas fa-user-clock
23	3	2023-10-30 11:20:13	Tiket telah diverifikasi, peserta dapat melaksanakan kegiatan magang di instansi tujuan	info	4	fas fa-user-clock
24	3	2023-10-30 11:20:39	Memperbaharui Form Tugas dan Pembimbing Lapangan	primary	4	far fa-edit
25	3	2023-10-30 11:20:44	Tugas dan Pembimbing Lapangan telah ditentukan	info	4	fas fa-user-shield
26	9	2023-10-30 15:30:34	Memperbaharui Form Penilaian	primary	4	far fa-edit
27	9	2023-10-30 15:30:38	Form Penilaian telah diisi oleh Pembimbing Lapangan, peserta dapat mengakses nilai dan sertifikat magang	info	4	fas fa-user-edit
28	9	2023-10-30 15:30:38	Tiket telah diselesaikan	success	4	fa fa-check
29	14	2023-10-30 15:31:18	Membuat tiket	warning	5	fas fa-ticket-alt
30	14	2023-10-31 12:23:12	Membuat tiket	warning	6	fas fa-ticket-alt
31	1	2023-10-31 12:56:31	Tiket telah diverifikasi, peserta dapat melaksanakan kegiatan magang di instansi tujuan	info	4	fas fa-user-clock
32	1	2023-10-31 12:57:48	Memperbaharui Form Tugas dan Pembimbing Lapangan	primary	4	far fa-edit
33	1	2023-10-31 12:57:52	Tugas dan Pembimbing Lapangan telah ditentukan	info	4	fas fa-user-shield
\.


--
-- Data for Name: log_aktifitas_pelayanan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.log_aktifitas_pelayanan (id_log, id_user, tgl_aktifitas, aktifitas, color, id_tiket, icon) FROM stdin;
3	1	2023-08-09 14:23:42	Membuat tiket	warning	11	fas fa-ticket-alt
5	1	2023-08-10 12:35:30	Membuat tiket	warning	14	fas fa-ticket-alt
11	1	2023-08-11 11:21:46	Membuat tiket	warning	34	fas fa-ticket-alt
12	1	2023-08-11 11:22:25	Membuat tiket	warning	35	fas fa-ticket-alt
13	1	2023-08-11 11:23:47	Membuat tiket	warning	37	fas fa-ticket-alt
14	1	2023-08-11 11:26:51	Membuat tiket	warning	38	fas fa-ticket-alt
16	1	2023-08-15 13:41:18	Membuat tiket	warning	40	fas fa-ticket-alt
17	1	2023-08-15 13:41:18	Membuat tiket	warning	41	fas fa-ticket-alt
22	1	2023-08-24 09:58:28	Memperbaharui Meeting Id dan/atau Passcode	primary	40	far fa-edit
23	1	2023-08-24 10:02:19	Memperbaharui catatan	primary	40	far fa-edit
24	1	2023-08-24 10:02:19	Tiket telah dibatalkan	dark	40	fa fa-times
25	1	2023-08-24 10:18:35	Memperbaharui catatan	primary	41	far fa-edit
26	1	2023-08-24 10:18:35	Tiket telah ditolak	danger	41	fa fa-times
29	1	2023-08-24 11:20:07	Memperbaharui catatan	primary	38	far fa-edit
30	1	2023-08-24 11:20:07	Tiket telah ditolak	danger	38	fa fa-times
31	1	2023-08-24 11:20:14	Memperbaharui catatan	primary	37	far fa-edit
32	1	2023-08-24 11:20:14	Tiket telah dibatalkan	dark	37	fa fa-times
33	1	2023-08-24 11:42:43	Memperbaharui catatan	primary	36	far fa-edit
34	1	2023-08-24 11:42:43	Tiket telah ditolak	danger	36	fa fa-times
35	1	2023-08-24 11:42:55	Memperbaharui catatan	primary	35	far fa-edit
36	1	2023-08-24 11:42:55	Tiket telah dibatalkan	dark	35	fa fa-times
37	1	2023-08-24 11:43:50	Memperbaharui catatan	primary	34	far fa-edit
38	1	2023-08-24 11:43:50	Tiket telah dibatalkan	dark	34	fa fa-times
46	1	2023-08-24 12:49:52	Memperbaharui catatan	primary	14	far fa-edit
47	1	2023-08-24 12:49:52	Tiket telah ditolak	danger	14	fa fa-times
53	1	2023-09-01 11:24:10	Memperbaharui catatan	primary	11	far fa-edit
54	1	2023-09-01 11:24:10	Tiket telah ditolak	danger	11	fa fa-times
55	1	2023-09-01 11:27:23	Memperbaharui catatan	primary	11	far fa-edit
56	1	2023-09-01 11:27:23	Tiket telah ditolak	danger	11	fa fa-times
59	5	2023-10-30 10:18:09	Memperbaharui catatan	primary	41	far fa-edit
60	5	2023-10-30 10:18:09	Tiket telah ditolak	danger	41	fa fa-times
63	3	2023-10-31 15:31:22	Membuat tiket	warning	45	fas fa-ticket-alt
66	1	2023-11-01 11:44:03	Memperbaharui catatan	primary	45	far fa-edit
67	1	2023-11-01 11:44:03	Tiket telah ditolak	danger	45	fa fa-times
72	22	2026-02-13 15:26:12	Membuat tiket	warning	62	fas fa-ticket-alt
73	21	2026-02-13 15:36:38	Memperbaharui catatan	primary	62	far fa-edit
74	21	2026-02-13 15:36:38	Tiket telah ditolak	danger	62	fa fa-times
99	22	2026-02-14 14:30:33	Membuat tiket zoom	warning	85	fas fa-video
100	21	2026-02-14 14:32:23	Memperbaharui catatan	primary	85	far fa-edit
101	21	2026-02-14 14:32:23	Tiket telah ditolak	danger	85	fa fa-times
110	22	2026-02-15 00:11:56	Membuat tiket	warning	97	fas fa-ticket-alt
111	22	2026-02-15 00:27:08	Memperbaharui catatan	primary	97	far fa-edit
112	22	2026-02-15 00:27:08	Tiket telah dibatalkan	dark	97	fa fa-times
120	22	2026-02-15 11:02:46	Membuat tiket	warning	101	fas fa-ticket-alt
121	22	2026-02-15 12:28:50	Memperbaharui catatan	primary	101	far fa-edit
122	22	2026-02-15 12:28:50	Tiket telah dibatalkan	dark	101	fa fa-times
129	22	2026-02-17 03:13:51	Membuat tiket	warning	121	fas fa-ticket-alt
130	21	2026-02-17 03:14:34	Tiket telah disetujui	success	121	fa fa-check
131	21	2026-02-24 00:46:29	Membuat tiket	warning	123	fas fa-ticket-alt
132	21	2026-02-24 00:46:41	Tiket telah disetujui	success	123	fa fa-check
133	22	2026-02-24 00:50:28	Membuat tiket	warning	124	fas fa-ticket-alt
134	21	2026-02-24 00:51:26	Tiket telah disetujui	success	124	fa fa-check
\.


--
-- Data for Name: ssalat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssalat (id_alat, nama_alat, nomor_seri, active, tgl_input, merk) FROM stdin;
2	Laptop	253256	1	2023-07-18 10:24:09	Acer
\.


--
-- Data for Name: ssaula; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssaula (id_aula, nama_aula, active, tgl_input) FROM stdin;
5	Aula Diponegoro	1	2024-04-19 09:08:20
3	Command Center	1	2023-07-18 13:13:43
\.


--
-- Data for Name: ssfaq; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssfaq (id_faq, id_opd, pertanyaan, jawaban, active, tgl_input) FROM stdin;
1	1	Kenapa saya tidak mendapat kode otp saat membuat akun PELUIT ?	Pertama, pastikan id chat telegram anda benar. Apabila id chat anda berubah, silahkan hubungi help desk untuk mengganti id chat telegram anda.	1	2023-07-27 12:42:12
2	1	Bagaimana mendapatkan ID Chat ?	Silahkan cari Kode Otp kemudian klik tombol start	1	2023-07-31 09:44:29
\.


--
-- Data for Name: ssopd; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssopd (id_opd, nama_opd, akronim_opd, foto_opd) FROM stdin;
1	Dinas Komunikasi dan Informatika	DISKOMINFO	logokominfo.png
2	Dinas Pendidikan	DISDIK	logokominfo.png
3	Dinas Kesehatan	DINKES	logokominfo.png
4	Dinas Pekerjaan Umum dan Penataan Ruang	PUPR	logokominfo.png
5	Dinas Perumahan Rakyat dan Kawasan Permukiman	PRKP	logokominfo.png
6	Dinas Sosial	DINSOS	logokominfo.png
7	Dinas Keluarga Berencana, Pemberdayaan Perempuan dan Perlindungan Anak	DKBP3A	logokominfo.png
8	Dinas Ketahanan Pangan	Dinas Ketahanan Pang	logokominfo.png
9	Dinas Lingkungan Hidup	DLH	logokominfo.png
10	Dinas Kependudukan dan Pencatatan Sipil	DISPENDUKCAPIL	logokominfo.png
11	Dinas Pemberdayaan Masyarakat dan Desa	DPMD	logokominfo.png
12	Dinas Perhubungan	DISHUB	logokominfo.png
13	Dinas Koperasi dan Usaha Mikro	DINKOP	logokominfo.png
14	Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu	DPMPTSP	logokominfo.png
15	Dinas Pemuda dan Olahraga	DISPORA	logokominfo.png
16	Dinas Kebudayaan dan Pariwisata	DISBUDPAR	logokominfo.png
17	Dinas Perpustakaan dan Kearsipan	DISPUSIP	logokominfo.png
18	Dinas Perikanan	Dinas Perikanan	logokominfo.png
19	Dinas Pertanian Tanaman Pangan, Holtikutura dan Perkebunan	DISPERTAPAHORBUN	logokominfo.png
20	Dinas Peternakan	Dinas Peternakan	logokominfo.png
21	Dinas Perdagangan	DISDAG	logokominfo.png
22	Dinas Perindustrian dan Tenaga Kerja	DISPERINAKER	logokominfo.png
23	Badan Perencanaan Pembangunan Daerah	BAPPEDA	logokominfo.png
24	Badan Kepegawaian dan Pengembangan Sumber Daya Aparatur	BKPSDA	logokominfo.png
25	Badan Penelitian dan Pengembangan Daerah	BALITBANGDA	logokominfo.png
26	Badan Pengelola Keuangan dan Aset Daerah	BPKAD	logokominfo.png
27	Badan Pendapatan Daerah	BAPENDA	logokominfo.png
28	Badan Kesatuan Bangsa dan Politik	BAKESBANGPOL	logokominfo.png
29	Badan Penanggulangan Bencana Daerah	BPBD	logokominfo.png
30	Sekretariat Daerah	SEKDA	logokominfo.png
31	Inspektorat	Inspektorat	logokominfo.png
32	Satuan Polisi Pamong Praja	SATPOL PP	logokominfo.png
33	Rumah Sakit Umum Daerah Syarifah Ambami Rato Ebuh	RSUD	logokominfo.png
34	Sekretariat Dewan Perwakilan Rakyat Daerah	DPRD	logokominfo.png
35	Kecamatan Bangkalan	Kecamatan Bangkalan	logokominfo.png
36	Kelurahan Mlajah	Kelurahan Mlajah	logokominfo.png
37	Kelurahan Kemayoran	Kelurahan Kemayoran	logokominfo.png
38	Kelurahan Pangeranan	Kelurahan Pangeranan	logokominfo.png
39	Kelurahan Demangan	Kelurahan Demangan	logokominfo.png
40	Kelurahan Kraton	Kelurahan Kraton	logokominfo.png
41	Kelurahan Pejagan	Kelurahan Pejagan	logokominfo.png
42	Kelurahan Bancaran	Kelurahan Bancaran	logokominfo.png
43	Kecamatan Socah	Kecamatan Socah	logokominfo.png
44	Kecamatan Burneh	Kecamatan Burneh	logokominfo.png
45	Kecamatan Kamal	Kecamatan Kamal	logokominfo.png
46	Kecamatan  Arosbaya	Kecamatan  Arosbaya	logokominfo.png
47	Kecamatan Geger	Kecamatan Geger	logokominfo.png
48	Kecamatan Klampis	Kecamatan Klampis	logokominfo.png
49	Kecamatan Sepulu	Kecamatan Sepulu	logokominfo.png
50	Kecamatan Tanjung Bumi	Kecamatan Tanjung Bu	logokominfo.png
51	Kecamatan Kokop	Kecamatan Kokop	logokominfo.png
52	Kecamatan Kwanyar	Kecamatan Kwanyar	logokominfo.png
53	Kecamatan Labang	Kecamatan Labang	logokominfo.png
54	Kecamatan Tanahmerah	Kecamatan Tanahmerah	logokominfo.png
55	Kecamatan Tragah	Kecamatan Tragah	logokominfo.png
56	Kecamatan Blega	Kecamatan Blega	logokominfo.png
57	Kecamatan Modung	Kecamatan Modung	logokominfo.png
58	Kecamatan Konang	Kecamatan Konang	logokominfo.png
59	Kecamatan Galis	Kecamatan Galis	logokominfo.png
60	Kelurahan Tunjung	Kelurahan Tunjung	logokominfo.png
\.


--
-- Data for Name: ssotp; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssotp (id_otp, id_ssuser, kode_otp, exp_date, active) FROM stdin;
43	1	TZ67CP	2023-09-05 09:09:15	1
44	1	9OLBR8	2023-09-05 13:57:56	1
45	1	JIVKZ8	2023-09-06 09:16:11	1
46	1	7850EC	2023-09-07 09:19:17	1
47	1	MAAKA6	2023-09-12 09:24:36	1
48	1	ZTRBXL	2023-09-13 10:57:08	1
49	1	BAS6AQ	2023-09-22 09:12:19	1
50	1	TBELMD	2023-09-25 09:16:39	1
51	1	FP2JFK	2023-10-24 09:04:53	1
52	1	R55V2I	2023-10-24 13:26:47	1
53	1	CX8Q0T	2023-10-25 09:05:20	1
54	1	34BCWK	2023-10-25 10:59:10	1
55	3	6TH5YI	2023-10-26 11:31:41	1
56	1	BBKBLF	2023-10-26 11:42:49	1
57	3	0B7IOD	2023-10-26 12:46:58	1
58	3	EX8O1B	2023-10-27 09:38:41	1
59	1	TI6HS0	2023-10-27 09:39:53	1
60	5	NE45JN	2023-10-27 13:29:51	1
61	5	LJNPD7	2023-10-30 09:01:09	1
62	3	LOZ6P8	2023-10-30 09:31:32	1
63	9	NBGQ4P	2023-10-30 10:26:28	1
64	14	M4S20N	2023-10-30 11:40:24	1
68	9	OY13XN	2023-10-30 15:32:20	1
67	1	HO810I	2023-10-31 11:22:57	1
69	14	TXFTD4	2023-10-31 12:24:59	1
65	3	LMF0M8	2023-10-31 15:18:58	1
70	3	K1P408	2023-11-01 11:19:58	1
71	1	FWQXS4	2023-11-01 11:39:47	1
72	1	N7CGDA	2023-11-03 13:03:11	1
73	6	UUXH9W	2024-03-28 09:55:31	0
74	1	9VQRGR	2024-03-28 10:02:15	1
76	17	4LV56Q	2024-03-28 10:39:32	1
78	17	WJET97	2024-04-01 10:40:17	1
79	4	QLN5XI	2024-04-01 10:47:59	1
80	18	R934GN	2024-04-01 10:55:30	1
77	3	9MDMDS	2024-04-01 10:59:44	1
82	3	M6HW9Q	2024-04-04 14:05:17	1
83	1	Q1DO2D	2024-04-04 14:11:50	1
84	2	OAFK0G	2024-04-18 09:06:18	1
85	4	Z49NOA	2024-04-18 09:08:24	1
81	18	GDRHIN	2024-04-18 09:16:21	1
86	2	UJ46Q6	2024-04-18 09:19:57	1
66	5	J0PJVB	2024-04-18 09:21:44	1
87	19	9N4Z30	2024-04-18 09:23:23	1
88	2	W6G5C6	2024-04-19 08:48:50	1
89	4	RI4SJQ	2024-04-19 08:50:40	1
90	19	EYY7D8	2024-04-19 08:52:12	1
91	3	DZJTUC	2024-04-19 08:56:07	1
92	19	SQHHZO	2024-04-19 09:04:23	1
93	20	ZX9B30	2024-04-19 09:08:27	1
94	18	MTLZ8Y	2024-04-19 09:15:37	1
95	2	9SWBWL	2024-04-19 10:41:32	1
96	20	TGNKBW	2024-04-19 13:34:45	1
97	18	FDU7O8	2024-04-19 13:43:39	1
98	19	CWVYDF	2024-04-22 12:11:11	1
99	19	8LMVK3	2024-04-25 09:11:51	1
100	19	KSDKI6	2024-04-29 09:59:46	0
101	21	HN33J5	2026-02-12 09:33:09	1
102	21	9Z3ZHK	2026-02-12 09:36:30	1
103	21	NN5KO1	2026-02-12 09:38:39	1
104	14	VKQ9G2	2026-02-12 09:50:32	1
105	14	NFKUKU	2026-02-12 09:51:50	1
106	21	TH5XGV	2026-02-12 10:44:07	1
107	21	141G6K	2026-02-12 10:48:18	1
108	21	LWQFVR	2026-02-12 14:00:39	1
109	22	UHVIN1	2026-02-12 14:06:06	1
110	22	DMMSHA	2026-02-12 14:56:01	1
111	14	E1N98Y	2026-02-12 14:56:41	1
112	22	QEORRG	2026-02-12 14:57:36	1
113	14	3C4RVA	2026-02-13 09:33:57	1
114	22	89KCRM	2026-02-13 09:34:42	1
115	21	FJ3D9T	2026-02-13 09:37:39	1
117	22	8OQF44	2026-02-13 14:34:56	1
132	21	OP6EQD	2026-02-17 02:31:41	1
116	21	6EJGZ6	2026-02-13 14:56:05	1
118	21	6UNXU6	2026-02-13 16:19:21	1
119	22	ZVRV3T	2026-02-13 16:50:39	1
120	22	KJVP0T	2026-02-14 01:09:03	1
121	21	6JOF8N	2026-02-14 01:12:44	1
122	22	OBCB1P	2026-02-14 12:05:19	1
123	21	KJHOLH	2026-02-14 12:35:36	1
124	22	86F79M	2026-02-14 12:48:30	1
125	21	NKDA4A	2026-02-14 21:28:49	1
133	21	IZWY2M	2026-02-17 09:41:48	1
126	22	9TUGTE	2026-02-14 21:53:45	1
127	21	KPRAL1	2026-02-15 10:55:10	1
128	22	0DTN4J	2026-02-15 10:56:24	1
129	22	UHWRN9	2026-02-15 13:34:23	1
130	22	HJSQ4T	2026-02-16 21:49:30	1
131	22	02MBW8	2026-02-17 02:26:11	1
134	21	X04YQS	2026-02-24 00:47:51	1
135	22	5F10ZJ	2026-02-24 00:51:48	1
136	21	FC64O3	2026-02-24 00:53:51	1
137	21	FTS6LU	2026-02-25 00:34:19	1
138	21	5GVGMO	2026-02-25 09:18:39	1
139	22	UVMGZ9	2026-02-25 09:23:16	1
140	21	PJVRHG	2026-02-25 09:27:39	1
\.


--
-- Data for Name: sspelayanan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sspelayanan (id_pelayanan, id_opd, nama_pelayanan, route, url, file_foto, active, tgl_input, deskripsi) FROM stdin;
6	1	Pinjam Aula	aula	ruang	logokominfo.png	1	2023-06-15 10:47:34.617498	Pelayanan Publik Peminjaman Aula di DISKOMINFO Bangkalan
5	1	Subdomain	sub-domain	hosting	logokominfo.png	1	2023-06-15 09:31:00.173646	Pelayanan Publik Permohonan Subdomain Bangkalankab
4	1	Zoom Meeting	zoom	zoom	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan publik mengenai pelaksaan zoom meeting baik berupa participant atau host.
8	1	Hosting	hosting	hosting	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan Publik Permohonan Hosting Aplikasi di server DISKOMINFO
9	1	Sertifikat TTE	sertifikat-tte	tte	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan Publik Pembuatan Sertifikat TTE untuk ASN di Kabupaten Bangkalan
10	1	Pendampingan Aplikasi	pendampingan-aplikasi	app	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan Publik Permohonan Pendampingan Pembuatan Aplikasi
11	1	Pengaduan Jaringan	pengaduan-jaringan	jaringan	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan Publik Pengajuan Jaringan yang di fasilitasi DISKOMINFO
13	1	Peralatan Zoom	pelaratan-zoom	alat	logokominfo.png	1	2023-08-11 13:48:05	Pelayanan Publik Permohonan Peminjaman Peralatan Zoom di DISKOMINFO Bangkalan
7	1	Upload Dokumen	upload-document	berkas	logokominfo.png	1	2023-06-15 09:30:27.76702	Pelayanan Publik Permohonan Upload SAKIP atau LAKIP di website Bangkalankab
\.


--
-- Data for Name: ssuser; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssuser (id_ssuser, username, nip, nik, id_chat, active, role_id, file_foto, id_opd, tgl_input, tgl_validasi, nama) FROM stdin;
9	123456789874563212	123456789874563212	\N	6155320137	1	3	default.png                                                                                                                                           	1	2023-09-04 13:36:16	\N	Anna Medika                                                                                         
16	12659874123659	\N	3526032204930001	6155320137	3	4	c74d97b01eae257e44aa9d5bade97baf.jpg                                                                                                                  	\N	2023-10-31 09:50:35	\N	Marlena                                                                                             
18	mood.verif		\N	1084718110	1	2	default.png                                                                                                                                           	\N	2024-04-01 10:48:48	2024-04-01 10:48:48	Nurul Makhmud, S.Kom                                                                                
5	romi	\N	\N	5788980260	1	2	default.png                                                                                                                                           	1	\N	\N	Romi Hariyadi                                                                                       
4	198412232005011002	198412232005011002	\N	937041601	1	0	default.png                                                                                                                                           	\N	\N	\N	A. Satria P, S.AP                                                                                   
19	198304212023212032	198304212023212032	\N	5771331912	1	2	default.png                                                                                                                                           	\N	2024-04-18 09:18:47	2024-04-18 09:18:47	Hartini                                                                                             
1	ainur.inas	\N	\N	6155320137	1	0	default.png                                                                                                                                           	1	\N	\N	Ainur Inas Annisa                                                                                   
2	mood		\N	1084718110	1	0	default.png                                                                                                                                           	\N	\N	\N	Nurul Makhmud, S.Kom                                                                                
20	123123123123123123	123123123123123123	\N	1084718110	1	1	default.png                                                                                                                                           	5	2024-04-19 09:05:08	2024-04-19 09:05:08	Admin DPRKP                                                                                         
3	admin.kominfo		\N	1084718110	1	1	eccbc87e4b5ce2fe28308fd9f2a7baf3.png                                                                                                                  	1	\N	\N	Admin Kominfo                                                                                       
21	admin.baru	1234123412341234	\N	8000307657	1	0	default.png                                                                                                                                           	1	2026-02-12 09:27:04.004627	\N	Admin Baru                                                                                          
14	magangbaru	\N	3526015212960002	8000307657	1	3	aab3238922bcc25a6f606eb525ffdc56.jpg                                                                                                                  	\N	2023-09-05 09:02:04	\N	AINUR INAS ANNISA                                                                                   
22	magang.baru	\N	\N	8000307657	1	1	default.png                                                                                                                                           	1	2026-02-12 09:40:03.169954	\N	Magang Baru                                                                                         
\.


--
-- Data for Name: ssuser_magang; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssuser_magang (id_ssuser_magang, id_ssuser, gender, wa, jenis, nomor_induk, jurusan, civitas, ktp) FROM stdin;
1	14	0	082331521923	1	150411100016	TEKNIK INFORMATIKA	UNIVERSITAS TRUNOJOYO MADURA	aab3238922bcc25a6f606eb525ffdc56.jpg
3	16	0	025986326985	0	12659874123659	RPL	SMKN 02 BANGKALN	c74d97b01eae257e44aa9d5bade97baf.jpeg
\.


--
-- Data for Name: ssuser_pembimbing; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ssuser_pembimbing (id_ssuser_pembimbing, id_ssuser, id_sub) FROM stdin;
1	9	1
\.


--
-- Data for Name: sub_bagian; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sub_bagian (id_sub, id_opd, nama_sub, tgl_input, active) FROM stdin;
2	1	Informasi dan Komunikasi Publik	2023-07-26 11:23:11	1
3	1	Sekretariat	2023-07-26 11:23:26	1
1	1	Aplikasi Informatika	2023-07-26 10:57:30	1
5	1	Sumber Daya TIK dan Statistik	2023-10-26 14:22:21	1
\.


--
-- Data for Name: tb_tiket; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket (id_tiket, kode_tiket, tgl_input, id_pelayanan, id_user, status, catatan) FROM stdin;
40	HWJ7UGY3GPAS4VDV	2023-10-15 13:41:18	4	1	3	Aula digunakan internal kominfo, silahkan bersurat kembali untuk mengganti lokasi zoom
11	68aVP91v	2023-10-09 14:23:42	7	1	2	hahaha
41	3T38EW9IOTIMA3ZM	2023-08-15 13:41:18	6	1	2	hahahihi
45	NPD9LW5AP67L0NCL	2023-10-31 15:31:22	11	3	2	apa ya
38	FJA9LUIYKG22S72P	2023-08-11 11:26:51	11	1	2	Duplikasi tiket
37	8WMKGSKQABKVJ6BV	2023-08-11 11:23:47	11	1	3	Duplikasi tiket
36	9Y5P3B16WFDUGN1Q	2023-08-11 11:23:31	11	1	2	Duplikat tiket
35	3517RDQE6J2L97M5	2023-08-11 11:22:25	11	1	3	Duplikat tiket
34	0YXDT6WWW6PE0S6J	2023-08-11 11:21:46	11	1	3	Duplikat tiket
14	8S31JQG9U4W9HBPL	2023-08-10 12:35:30	9	1	2	NIP salah, silahkan membuat tiket baru dengan NIP yang benar
101	QF4HQDS5YWR121KO	2026-02-15 11:02:46	6	22	3	gak jadi prankk
62	16CD7GDQO2P23AJ7	2026-02-13 15:26:12	6	22	2	Jam Nabrak
121	HZN702P6H73LG165	2026-02-17 03:13:51	11	22	1	\N
122	AE2N63LUL1E98INY	2026-02-24 00:42:55	7	21	0	\N
123	PYEMXFJCIJS2060H	2026-02-24 00:46:29	6	21	1	\N
124	P47ARSOAUAA9RUWM	2026-02-24 00:50:28	6	22	1	\N
85	2R6IK07I7N0H2TVE	2026-02-14 14:30:33	4	22	2	jm nabrak
97	9T7H7OZ9P3IPNH0X	2026-02-15 00:11:56	8	22	3	gak jadi udah bisa prankk
\.


--
-- Data for Name: tb_tiket_alat; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket_alat (id_pelayanan_alat, id_tiket, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: tb_tiket_alat_list; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket_alat_list (id, id_pelayanan_alat, id_alat, jumlah, keterangan, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: tb_tiket_detail; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket_detail (id_detail, id_tiket, tipe, judul, keterangan, mulai, selesai, detail, created_at) FROM stdin;
1	41	aula	Testing	\N	2023-08-15 13:45:00+07	2023-08-15 19:41:00+07	{"no_pic": "3123132", "id_aula": 3, "nama_pic": "asdf", "berkas_pengantar": "d645920e395fedad7bbbed0eca3fe2e0.pdf"}	2026-02-12 14:51:00.363321+07
2	40	zoom	Testing	\N	2023-08-15 13:45:00+07	2023-08-15 19:41:00+07	{"no_pic": "3123132", "tempat": "Command Center", "nama_pic": "asdf", "operator": 0, "passcode": "", "jenis_zoom": 0, "meeting_id": "", "berkas_pengantar": "d645920e395fedad7bbbed0eca3fe2e0.pdf"}	2026-02-12 14:51:00.363321+07
6	34	jaringan	\N	\N	2023-08-11 00:00:00+07	\N	{"foto": [], "no_pic": "123123", "keluhan": "asddsf", "nama_pic": "asdfdsf", "tindak_lanjut": null, "berkas_pengantar": "e369853df766fa44e1ed0ff613f563bd.pdf"}	2026-02-12 14:51:00.363321+07
7	35	jaringan	\N	\N	2023-08-11 00:00:00+07	\N	{"foto": [], "no_pic": "123123", "keluhan": "asddsf", "nama_pic": "asdfdsf", "tindak_lanjut": null, "berkas_pengantar": "1c383cd30b7c298ab50293adfecb7b18.pdf"}	2026-02-12 14:51:00.363321+07
8	36	jaringan	\N	\N	2023-08-11 00:00:00+07	\N	{"foto": [], "no_pic": "123123", "keluhan": "asddsf", "nama_pic": "asdfdsf", "tindak_lanjut": null, "berkas_pengantar": "19ca14e7ea6328a42e0eb13d585e4c22.pdf"}	2026-02-12 14:51:00.363321+07
9	37	jaringan	\N	\N	2023-08-11 00:00:00+07	\N	{"foto": [], "no_pic": "123123", "keluhan": "asddsf", "nama_pic": "asdfdsf", "tindak_lanjut": null, "berkas_pengantar": "a5bfc9e07964f8dddeb95fc584cd965d.pdf"}	2026-02-12 14:51:00.363321+07
10	38	jaringan	\N	\N	2023-08-11 00:00:00+07	\N	{"foto": ["37693cfc748049e45d87b8c7d8b9aacd_dokumentasi_0.jpeg", "37693cfc748049e45d87b8c7d8b9aacd_dokumentasi_1.jpg"], "no_pic": "123123", "keluhan": "asddsf", "nama_pic": "asdfdsf", "tindak_lanjut": null, "berkas_pengantar": "a5771bce93e200c36f7cd9dfd0e5deaa.pdf"}	2026-02-12 14:51:00.363321+07
14	45	jaringan	\N	\N	2023-10-28 00:00:00+07	\N	{"foto": ["02e74f10e0327ad868d138f2b4fdd6f0_dokumentasi_0.jpg"], "no_pic": "023654785236", "keluhan": "Lemot", "nama_pic": "Ainur Inas", "tindak_lanjut": null, "berkas_pengantar": "6c8349cc7260ae62e3b1396831a8398f.pdf"}	2026-02-12 14:51:00.363321+07
15	14	tte	test	\N	\N	\N	{"nik": "123123", "nip": "2312313", "no_pic": "234234", "jabatan": "trad", "nama_pic": "asdsd", "berkas_ktp": "aab3238922bcc25a6f606eb525ffdc56.jpg", "jenis_layanan": 0, "berkas_pengantar": "aab3238922bcc25a6f606eb525ffdc56.jpg"}	2026-02-12 14:51:00.363321+07
18	11	upload	\N	\N	\N	\N	{"edisi": "2023", "no_pic": "0223561", "nama_pic": "test", "berkas_upload": "6512bd43d9caa6e02c990b0a82652dca.pdf", "jenis_dokumen": 0, "berkas_pengantar": "6512bd43d9caa6e02c990b0a82652dca.pdf"}	2026-02-12 14:51:00.363321+07
21	62	aula	magang	\N	2026-02-13 15:25:00+07	2026-02-13 16:25:00+07	{"no_pic": "083115724572", "id_aula": 3, "nama_pic": "yudha", "berkas_pengantar": "44f683a84163b3523afe57c2e008bc8c.pdf", "id_pelayanan_aula": null}	2026-02-13 15:26:12.576568+07
45	85	zoom	ggg	\N	2026-02-14 14:29:00+07	2026-02-14 15:30:00+07	{"no_pic": "083115724572", "tempat": "Aula DiponegoroCommand Center", "nama_pic": "t", "operator": "1", "passcode": "12", "jenis_zoom": "0", "meeting_id": "99999999999", "berkas_pengantar": "3ef815416f775098fe977004015c6193.pdf", "id_pelayanan_zoom": 4}	2026-02-14 14:30:33.946655+07
51	97	hosting	yudha	\N	\N	\N	{"port": "", "no_pic": "083115724572", "nama_pic": "yudha", "db_access": "", "deskripsi": "yu", "spesifikasi": "ci 4", "nama_aplikasi": "yudha", "server_access": "", "berkas_pengantar": "e2ef524fbf3d9fe611d5a8e90fefdc9c.pdf"}	2026-02-15 00:11:56.900788+07
55	101	aula	mg	\N	2026-02-15 11:02:00+07	2026-02-15 12:02:00+07	{"no_pic": "083115724572", "id_aula": 5, "nama_pic": "yu", "berkas_pengantar": "38b3eff8baf56627478ec76a704e9b52.pdf", "id_pelayanan_aula": null}	2026-02-15 11:02:46.645576+07
60	121	jaringan	Pengaduan Jaringan	\N	\N	\N	{"no_pic": "083115724572", "keluhan": "wifine elek", "nama_pic": "yudha", "dokumentasi": ["d4e24f25cfe34d1735ecf20d552b2123.jpeg"], "tgl_kejadian": "2026-02-17", "berkas_pengantar": "be18b1cdfa671689fb96cfafd0b8afcd.pdf"}	2026-02-17 03:13:51.961654+07
61	123	aula	YU	\N	2026-02-24 00:46:00+07	2026-02-24 01:46:00+07	{"no_pic": "083115724572", "id_aula": 5, "nama_pic": "yudha", "berkas_pengantar": "202cb962ac59075b964b07152d234b70.pdf", "id_pelayanan_aula": null}	2026-02-24 00:46:29.409767+07
62	124	aula	YU	\N	2026-02-25 00:49:00+07	2026-02-25 01:49:00+07	{"no_pic": "083115724572", "id_aula": 5, "nama_pic": "yudha", "berkas_pengantar": "c8ffe9a587b126f152ed3d89a146b445.pdf", "id_pelayanan_aula": null}	2026-02-24 00:50:28.35694+07
\.


--
-- Data for Name: tb_tiket_magang; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket_magang (id_tiket, kode_tiket, tgl_input, status, catatan, id_opd, tgl_awal, tgl_akhir, nama_pembimbing, no_pembimbing, surat_pengantar, id_user, nama_project, deskripsi_project, berkas_project, tgl_selesai, id_pembina_lapangan) FROM stdin;
3	VK56CRPUUF46IUQE	2023-09-06 10:33:06	1	\N	1	2023-09-06	2023-09-16	AINUR INAS ANNISA	02356985232	eccbc87e4b5ce2fe28308fd9f2a7baf3.pdf	14	test	\N	\N	\N	9
6	X0282O8UHPRRALST	2023-10-31 12:23:12	3	\N	1	2023-11-01	2023-11-30	Zulaikha	023698542369	1679091c5a880faf6fb5e6087eb1b2dc.pdf	14	\N	\N	\N	\N	\N
5	3K3YIOQ7NTI5AWM6	2023-10-30 15:31:18	3	\N	1	2023-10-30	2023-11-30	ROMI HARIYADI	028356985124	e4da3b7fbbce2345d7772b0674a318d5.pdf	14	\N	\N	\N	\N	\N
4	TVB9OMZ9WF7UCFZL	2023-09-22 09:10:20	5	\N	1	2023-09-22	2023-09-30	AINUR INAS ANNISA	023253326859	a87ff679a2f3e71d9181a67b7542122c.pdf	14	GASD	\N	\N	\N	9
\.


--
-- Data for Name: tb_tiket_magang_nilai; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.tb_tiket_magang_nilai (id_magang_nilai, id_tiket, nilai_performance, nilai_sikap, nilai_kerjasama, nilai_disiplin, nilai_komunikasi, nilai_tanggung_jawab, nilai_teknis, catatan_nilai, tgl_input) FROM stdin;
1	3	50	68	78	86	57	78	57		2023-09-13 12:35:58
2	4	98	67	97	67	88	78	67		2023-09-22 09:16:53
\.


--
-- Data for Name: verifikator_pelayanan; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.verifikator_pelayanan (id_vpelayanan, id_user, id_pelayanan) FROM stdin;
15	5	6
16	5	4
18	5	13
19	18	5
20	18	8
21	18	11
22	18	7
23	19	6
24	19	5
25	19	4
26	19	8
27	19	9
28	19	10
29	19	11
30	19	13
31	19	7
\.


--
-- Name: log_aktifitas_magang_id_log_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.log_aktifitas_magang_id_log_seq', 33, true);


--
-- Name: log_aktifitas_pelayanan_id_log _seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."log_aktifitas_pelayanan_id_log _seq"', 134, true);


--
-- Name: ssalat_id_alat_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssalat_id_alat_seq', 2, true);


--
-- Name: ssaula_id_aula_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssaula_id_aula_seq', 5, true);


--
-- Name: ssfaq_id_faq_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssfaq_id_faq_seq', 2, true);


--
-- Name: ssopd_id_opd_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssopd_id_opd_seq', 60, true);


--
-- Name: ssotp_id_otp_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssotp_id_otp_seq', 140, true);


--
-- Name: sspelayanan_id_pelayanan_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sspelayanan_id_pelayanan_seq', 13, true);


--
-- Name: ssuser_id_ssuser_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssuser_id_ssuser_seq', 22, true);


--
-- Name: ssuser_magang_id_ssuser_magang_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssuser_magang_id_ssuser_magang_seq', 3, true);


--
-- Name: ssuser_pembimbing_id_ssuser_pembimbing_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ssuser_pembimbing_id_ssuser_pembimbing_seq', 2, true);


--
-- Name: sub_bagian_id_sub_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.sub_bagian_id_sub_seq', 5, true);


--
-- Name: tb_tiket_alat_id_pelayanan_alat_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_alat_id_pelayanan_alat_seq', 1, false);


--
-- Name: tb_tiket_alat_list_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_alat_list_id_seq', 1, false);


--
-- Name: tb_tiket_detail_id_detail_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_detail_id_detail_seq', 62, true);


--
-- Name: tb_tiket_id_tiket_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_id_tiket_seq', 124, true);


--
-- Name: tb_tiket_magang_id_tiket_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_magang_id_tiket_seq', 6, true);


--
-- Name: tb_tiket_magang_nilai_id_magang_nilai_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.tb_tiket_magang_nilai_id_magang_nilai_seq', 2, true);


--
-- Name: verifikator_pelayanan_id_vpelayanan_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.verifikator_pelayanan_id_vpelayanan_seq', 31, true);


--
-- Name: log_aktifitas_magang log_aktifitas_magang_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_magang
    ADD CONSTRAINT log_aktifitas_magang_pkey PRIMARY KEY (id_log);


--
-- Name: log_aktifitas_pelayanan log_aktifitas_pelayanan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_pelayanan
    ADD CONSTRAINT log_aktifitas_pelayanan_pkey PRIMARY KEY (id_log);


--
-- Name: ssalat ssalat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssalat
    ADD CONSTRAINT ssalat_pkey PRIMARY KEY (id_alat);


--
-- Name: ssaula ssaula_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssaula
    ADD CONSTRAINT ssaula_pkey PRIMARY KEY (id_aula);


--
-- Name: ssfaq ssfaq_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssfaq
    ADD CONSTRAINT ssfaq_pkey PRIMARY KEY (id_faq);


--
-- Name: ssopd ssopd_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssopd
    ADD CONSTRAINT ssopd_pkey PRIMARY KEY (id_opd);


--
-- Name: ssotp ssotp_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssotp
    ADD CONSTRAINT ssotp_pkey PRIMARY KEY (id_otp);


--
-- Name: sspelayanan sspelayanan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sspelayanan
    ADD CONSTRAINT sspelayanan_pkey PRIMARY KEY (id_pelayanan);


--
-- Name: ssuser_magang ssuser_magang_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_magang
    ADD CONSTRAINT ssuser_magang_pkey PRIMARY KEY (id_ssuser_magang);


--
-- Name: ssuser_pembimbing ssuser_pembimbing_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_pembimbing
    ADD CONSTRAINT ssuser_pembimbing_pkey PRIMARY KEY (id_ssuser_pembimbing);


--
-- Name: ssuser ssuser_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser
    ADD CONSTRAINT ssuser_pkey PRIMARY KEY (id_ssuser);


--
-- Name: sub_bagian sub_bagian_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sub_bagian
    ADD CONSTRAINT sub_bagian_pkey PRIMARY KEY (id_sub);


--
-- Name: tb_tiket_alat_list tb_tiket_alat_list_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_alat_list
    ADD CONSTRAINT tb_tiket_alat_list_pkey PRIMARY KEY (id);


--
-- Name: tb_tiket_alat tb_tiket_alat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_alat
    ADD CONSTRAINT tb_tiket_alat_pkey PRIMARY KEY (id_pelayanan_alat);


--
-- Name: tb_tiket_detail tb_tiket_detail_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_detail
    ADD CONSTRAINT tb_tiket_detail_pkey PRIMARY KEY (id_detail);


--
-- Name: tb_tiket_magang_nilai tb_tiket_magang_nilai_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_magang_nilai
    ADD CONSTRAINT tb_tiket_magang_nilai_pkey PRIMARY KEY (id_magang_nilai);


--
-- Name: tb_tiket_magang tb_tiket_magang_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_magang
    ADD CONSTRAINT tb_tiket_magang_pkey PRIMARY KEY (id_tiket);


--
-- Name: tb_tiket tb_tiket_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket
    ADD CONSTRAINT tb_tiket_pkey PRIMARY KEY (id_tiket);


--
-- Name: verifikator_pelayanan verifikator_pelayanan_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verifikator_pelayanan
    ADD CONSTRAINT verifikator_pelayanan_pkey PRIMARY KEY (id_vpelayanan);


--
-- Name: idx_tiket_detail_detail_gin; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_tiket_detail_detail_gin ON public.tb_tiket_detail USING gin (detail);


--
-- Name: idx_tiket_detail_id_tiket; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_tiket_detail_id_tiket ON public.tb_tiket_detail USING btree (id_tiket);


--
-- Name: idx_tiket_detail_tipe; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX idx_tiket_detail_tipe ON public.tb_tiket_detail USING btree (tipe);


--
-- Name: tb_tiket_aula tr_tb_tiket_aula_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_tb_tiket_aula_insert INSTEAD OF INSERT ON public.tb_tiket_aula FOR EACH ROW EXECUTE FUNCTION public.tb_tiket_aula_insert();


--
-- Name: tb_tiket_zoom tr_tb_tiket_zoom_insert; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER tr_tb_tiket_zoom_insert INSTEAD OF INSERT ON public.tb_tiket_zoom FOR EACH ROW EXECUTE FUNCTION public.tb_tiket_zoom_insert();


--
-- Name: tb_tiket_alat_list fk_ta_list_pelayanan; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_alat_list
    ADD CONSTRAINT fk_ta_list_pelayanan FOREIGN KEY (id_pelayanan_alat) REFERENCES public.tb_tiket_alat(id_pelayanan_alat) ON DELETE CASCADE;


--
-- Name: ssuser id_opd; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser
    ADD CONSTRAINT id_opd FOREIGN KEY (id_opd) REFERENCES public.ssopd(id_opd);


--
-- Name: log_aktifitas_pelayanan log_aktifitas_pelayanan_id_tiket_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_pelayanan
    ADD CONSTRAINT log_aktifitas_pelayanan_id_tiket_fkey FOREIGN KEY (id_tiket) REFERENCES public.tb_tiket(id_tiket);


--
-- Name: log_aktifitas_pelayanan log_aktifitas_pelayanan_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.log_aktifitas_pelayanan
    ADD CONSTRAINT log_aktifitas_pelayanan_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.ssuser(id_ssuser);


--
-- Name: sspelayanan sspelayanan_id_opd_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sspelayanan
    ADD CONSTRAINT sspelayanan_id_opd_fkey FOREIGN KEY (id_opd) REFERENCES public.ssopd(id_opd);


--
-- Name: ssuser_magang ssuser_magang_id_ssuser_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_magang
    ADD CONSTRAINT ssuser_magang_id_ssuser_fkey FOREIGN KEY (id_ssuser) REFERENCES public.ssuser(id_ssuser);


--
-- Name: ssuser_pembimbing ssuser_pembimbing_id_ssuser_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_pembimbing
    ADD CONSTRAINT ssuser_pembimbing_id_ssuser_fkey FOREIGN KEY (id_ssuser) REFERENCES public.ssuser(id_ssuser);


--
-- Name: ssuser_pembimbing ssuser_pembimbing_id_sub_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ssuser_pembimbing
    ADD CONSTRAINT ssuser_pembimbing_id_sub_fkey FOREIGN KEY (id_sub) REFERENCES public.sub_bagian(id_sub);


--
-- Name: tb_tiket_detail tb_tiket_detail_id_tiket_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket_detail
    ADD CONSTRAINT tb_tiket_detail_id_tiket_fkey FOREIGN KEY (id_tiket) REFERENCES public.tb_tiket(id_tiket) ON DELETE CASCADE;


--
-- Name: tb_tiket tb_tiket_id_pelayanan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket
    ADD CONSTRAINT tb_tiket_id_pelayanan_fkey FOREIGN KEY (id_pelayanan) REFERENCES public.sspelayanan(id_pelayanan);


--
-- Name: tb_tiket tb_tiket_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.tb_tiket
    ADD CONSTRAINT tb_tiket_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.ssuser(id_ssuser);


--
-- Name: verifikator_pelayanan verifikator_pelayanan_id_pelayanan_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verifikator_pelayanan
    ADD CONSTRAINT verifikator_pelayanan_id_pelayanan_fkey FOREIGN KEY (id_pelayanan) REFERENCES public.sspelayanan(id_pelayanan);


--
-- Name: verifikator_pelayanan verifikator_pelayanan_id_user_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.verifikator_pelayanan
    ADD CONSTRAINT verifikator_pelayanan_id_user_fkey FOREIGN KEY (id_user) REFERENCES public.ssuser(id_ssuser);


--
-- PostgreSQL database dump complete
--

\unrestrict VBjQadWYpRTAUd7wHU4hehPfka2WhokQWMtpIwmIhRp53HytnRCRPzuk6tHnowp

