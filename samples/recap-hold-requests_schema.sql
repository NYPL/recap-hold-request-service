--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.4
-- Dumped by pg_dump version 9.5.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: recap_cancel_hold_request_id_seq; Type: SEQUENCE; Schema: public; Owner:
--

CREATE SEQUENCE recap_cancel_hold_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recap_cancel_hold_request_id_seq OWNER TO [username];

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: recap_cancel_hold_request; Type: TABLE; Schema: public; Owner:
--

CREATE TABLE recap_cancel_hold_request (
    tracking_id text,
    owning_institution_id text,
    job_id text,
    created_date text,
    updated_date text,
    patron_barcode text,
    item_barcode text,
    processed boolean,
    success boolean,
    id integer DEFAULT nextval('recap_cancel_hold_request_id_seq'::regclass) NOT NULL
);


ALTER TABLE recap_cancel_hold_request OWNER TO [username];

--
-- Name: recap_hold_request_id_seq; Type: SEQUENCE; Schema: public; Owner:
--

CREATE SEQUENCE recap_hold_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recap_hold_request_id_seq OWNER TO [username];

--
-- Name: recap_hold_request; Type: TABLE; Schema: public; Owner:
--

CREATE TABLE recap_hold_request (
    id integer DEFAULT nextval('recap_hold_request_id_seq'::regclass) NOT NULL,
    tracking_id text,
    patron_barcode text,
    item_barcode text,
    created_date text,
    updated_date text,
    owning_institution_id text,
    description text
);


ALTER TABLE recap_hold_request OWNER TO [username];

--
-- Name: recap_hold_request_id_pkey; Type: SEQUENCE; Schema: public; Owner:
--

CREATE SEQUENCE recap_hold_request_id_pkey
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE recap_hold_request_id_pkey OWNER TO [username];

--
-- Name: recap_cancel_hold_request_id_key; Type: CONSTRAINT; Schema: public; Owner:
--

ALTER TABLE ONLY recap_cancel_hold_request
    ADD CONSTRAINT recap_cancel_hold_request_id_key UNIQUE (id);


--
-- Name: recap_cancel_hold_request_pkey; Type: CONSTRAINT; Schema: public; Owner:
--

ALTER TABLE ONLY recap_cancel_hold_request
    ADD CONSTRAINT recap_cancel_hold_request_pkey PRIMARY KEY (id);


--
-- Name: recap_hold_request_id_key; Type: CONSTRAINT; Schema: public; Owner:
--

ALTER TABLE ONLY recap_hold_request
    ADD CONSTRAINT recap_hold_request_id_key UNIQUE (id);


--
-- Name: recap_hold_request_pkey; Type: CONSTRAINT; Schema: public; Owner:
--

ALTER TABLE ONLY recap_hold_request
    ADD CONSTRAINT recap_hold_request_pkey PRIMARY KEY (id);


--
-- Name: recap_cancel_hold_request_ids_idx; Type: INDEX; Schema: public; Owner:
--

CREATE INDEX recap_cancel_hold_request_ids_idx ON recap_cancel_hold_request USING btree (id, tracking_id, job_id);


--
-- Name: recap_hold_request_ids_idx; Type: INDEX; Schema: public; Owner:
--

CREATE INDEX recap_hold_request_ids_idx ON recap_hold_request USING btree (id, tracking_id);


--
-- Name: public; Type: ACL; Schema: -; Owner:
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM [username];
GRANT ALL ON SCHEMA public TO [username];
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: recap_cancel_hold_request_id_seq; Type: ACL; Schema: public; Owner:
--

REVOKE ALL ON SEQUENCE recap_cancel_hold_request_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE recap_cancel_hold_request_id_seq FROM [username];
GRANT ALL ON SEQUENCE recap_cancel_hold_request_id_seq TO [username];


--
-- Name: recap_cancel_hold_request; Type: ACL; Schema: public; Owner:
--

REVOKE ALL ON TABLE recap_cancel_hold_request FROM PUBLIC;
REVOKE ALL ON TABLE recap_cancel_hold_request FROM [username];
GRANT ALL ON TABLE recap_cancel_hold_request TO [username];


--
-- Name: recap_hold_request_id_seq; Type: ACL; Schema: public; Owner:
--

REVOKE ALL ON SEQUENCE recap_hold_request_id_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE recap_hold_request_id_seq FROM [username];
GRANT ALL ON SEQUENCE recap_hold_request_id_seq TO [username];


--
-- Name: recap_hold_request; Type: ACL; Schema: public; Owner:
--

REVOKE ALL ON TABLE recap_hold_request FROM PUBLIC;
REVOKE ALL ON TABLE recap_hold_request FROM [username];
GRANT ALL ON TABLE recap_hold_request TO [username];


--
-- PostgreSQL database dump complete
--