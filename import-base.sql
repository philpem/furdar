--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.17
-- Dumped by pg_dump version 9.5.17

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
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


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: app_configuration_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.app_configuration_information (
    extension_id character varying(255) NOT NULL,
    configuration_key character varying(255) NOT NULL,
    value_text text
);


ALTER TABLE public.app_configuration_information OWNER TO opentechcalendar3;

--
-- Name: area_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.area_history (
    area_id integer NOT NULL,
    title character varying(255),
    description text,
    country_id integer,
    parent_area_id integer,
    is_deleted boolean,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    country_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    parent_area_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    is_duplicate_of_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    edit_comment text,
    from_ip inet,
    max_lat real,
    max_lng real,
    min_lat real,
    min_lng real,
    max_lat_changed smallint DEFAULT (0)::smallint NOT NULL,
    max_lng_changed smallint DEFAULT (0)::smallint NOT NULL,
    min_lat_changed smallint DEFAULT (0)::smallint NOT NULL,
    min_lng_changed smallint DEFAULT (0)::smallint NOT NULL
);


ALTER TABLE public.area_history OWNER TO opentechcalendar3;

--
-- Name: area_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.area_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    slug integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    country_id integer NOT NULL,
    parent_area_id integer,
    is_deleted boolean DEFAULT false NOT NULL,
    cache_area_has_parent_generated boolean DEFAULT false NOT NULL,
    cached_future_events integer DEFAULT 0 NOT NULL,
    cached_max_lat real,
    cached_max_lng real,
    cached_min_lat real,
    cached_min_lng real,
    created_at timestamp without time zone NOT NULL,
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    slug_human character varying(255),
    max_lat real,
    max_lng real,
    min_lat real,
    min_lng real,
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.area_information OWNER TO opentechcalendar3;

--
-- Name: area_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.area_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.area_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: area_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.area_information_id_seq OWNED BY public.area_information.id;


--
-- Name: cached_area_has_parent; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.cached_area_has_parent (
    area_id integer NOT NULL,
    has_parent_area_id integer NOT NULL
);


ALTER TABLE public.cached_area_has_parent OWNER TO opentechcalendar3;

--
-- Name: contact_support; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.contact_support (
    id integer NOT NULL,
    subject character varying(255),
    message text,
    email character varying(255),
    user_account_id integer,
    ip character varying(255),
    browser text,
    created_at timestamp without time zone NOT NULL,
    is_spam_manually_detected boolean DEFAULT false NOT NULL,
    is_spam_honeypot_field_detected boolean DEFAULT false NOT NULL
);


ALTER TABLE public.contact_support OWNER TO opentechcalendar3;

--
-- Name: contact_support_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.contact_support_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contact_support_id_seq OWNER TO opentechcalendar3;

--
-- Name: contact_support_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.contact_support_id_seq OWNED BY public.contact_support.id;


--
-- Name: country; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.country (
    id integer NOT NULL,
    two_char_code character varying(2),
    title character varying(255),
    timezones text,
    max_lat real,
    max_lng real,
    min_lat real,
    min_lng real,
    address_code_label character varying(255)
);


ALTER TABLE public.country OWNER TO opentechcalendar3;

--
-- Name: country_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.country_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.country_id_seq OWNER TO opentechcalendar3;

--
-- Name: country_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.country_id_seq OWNED BY public.country.id;


--
-- Name: country_in_site_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.country_in_site_information (
    site_id integer NOT NULL,
    country_id integer NOT NULL,
    is_in boolean DEFAULT false NOT NULL,
    is_previously_in boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    cached_future_events integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.country_in_site_information OWNER TO opentechcalendar3;

--
-- Name: curated_list_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.curated_list_history (
    curated_list_id integer NOT NULL,
    title character varying(255),
    description text,
    is_deleted boolean,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL
);


ALTER TABLE public.curated_list_history OWNER TO opentechcalendar3;

--
-- Name: curated_list_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.curated_list_information (
    id integer NOT NULL,
    site_id integer,
    slug integer NOT NULL,
    title character varying(255),
    description text,
    is_deleted boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    cached_future_events integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.curated_list_information OWNER TO opentechcalendar3;

--
-- Name: curated_list_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.curated_list_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.curated_list_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: curated_list_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.curated_list_information_id_seq OWNED BY public.curated_list_information.id;


--
-- Name: event_custom_field_definition_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_custom_field_definition_history (
    event_custom_field_definition_id integer NOT NULL,
    key character varying(255),
    key_changed smallint DEFAULT (0)::smallint NOT NULL,
    extension_id character varying(255),
    extension_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    type character varying(255),
    type_changed smallint DEFAULT (0)::smallint NOT NULL,
    label character varying(255),
    label_changed smallint DEFAULT (0)::smallint NOT NULL,
    user_account_id integer,
    is_active boolean,
    is_active_changed smallint DEFAULT (0)::smallint NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.event_custom_field_definition_history OWNER TO opentechcalendar3;

--
-- Name: event_custom_field_definition_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_custom_field_definition_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    key character varying(255) NOT NULL,
    extension_id character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    label character varying(255) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.event_custom_field_definition_information OWNER TO opentechcalendar3;

--
-- Name: event_custom_field_definition_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.event_custom_field_definition_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_custom_field_definition_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: event_custom_field_definition_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.event_custom_field_definition_information_id_seq OWNED BY public.event_custom_field_definition_information.id;


--
-- Name: event_has_tag; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_has_tag (
    tag_id integer NOT NULL,
    event_id integer NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    addition_approved_at timestamp without time zone,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone,
    removal_approved_at timestamp without time zone
);


ALTER TABLE public.event_has_tag OWNER TO opentechcalendar3;

--
-- Name: event_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_history (
    event_id integer NOT NULL,
    summary character varying(255),
    description text,
    start_at timestamp without time zone,
    end_at timestamp without time zone,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    is_deleted boolean,
    reverted_from_created_at timestamp without time zone,
    country_id integer,
    timezone character varying(255),
    venue_id integer,
    url character varying(255),
    is_virtual boolean,
    is_physical boolean,
    legacy_location_id integer,
    area_id integer,
    summary_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    start_at_changed smallint DEFAULT (0)::smallint NOT NULL,
    end_at_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL,
    country_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    timezone_changed smallint DEFAULT (0)::smallint NOT NULL,
    venue_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    url_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_virtual_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_physical_changed smallint DEFAULT (0)::smallint NOT NULL,
    area_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    approved_at timestamp without time zone,
    ticket_url character varying(255),
    ticket_url_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_duplicate_of_id integer,
    is_duplicate_of_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_cancelled boolean,
    is_cancelled_changed smallint DEFAULT (0)::smallint NOT NULL,
    edit_comment text,
    custom_fields text,
    custom_fields_changed text,
    from_ip inet
);


ALTER TABLE public.event_history OWNER TO opentechcalendar3;

--
-- Name: event_in_curated_list; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_in_curated_list (
    curated_list_id integer NOT NULL,
    event_id integer NOT NULL,
    added_by_user_account_id integer NOT NULL,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.event_in_curated_list OWNER TO opentechcalendar3;

--
-- Name: event_in_group; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_in_group (
    group_id integer NOT NULL,
    event_id integer NOT NULL,
    is_main_group boolean NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone,
    addition_approved_at timestamp without time zone,
    removal_approved_at timestamp without time zone
);


ALTER TABLE public.event_in_group OWNER TO opentechcalendar3;

--
-- Name: event_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_information (
    id integer NOT NULL,
    site_id integer,
    slug integer,
    summary character varying(255),
    description text,
    start_at timestamp without time zone NOT NULL,
    end_at timestamp without time zone NOT NULL,
    created_at timestamp without time zone NOT NULL,
    is_deleted boolean DEFAULT false NOT NULL,
    event_recur_set_id integer,
    country_id integer,
    timezone character varying(255) DEFAULT 'Europe/London'::character varying NOT NULL,
    venue_id integer,
    import_url_id integer,
    import_id character varying(255),
    url character varying(255),
    is_virtual boolean DEFAULT false NOT NULL,
    is_physical boolean DEFAULT true NOT NULL,
    legacy_location_id integer,
    area_id integer,
    approved_at timestamp without time zone,
    ticket_url character varying(255),
    is_duplicate_of_id integer,
    is_cancelled boolean DEFAULT false NOT NULL,
    custom_fields text,
    slug_human character varying(255),
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.event_information OWNER TO opentechcalendar3;

--
-- Name: event_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.event_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: event_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.event_information_id_seq OWNED BY public.event_information.id;


--
-- Name: event_recur_set; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.event_recur_set (
    id integer NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.event_recur_set OWNER TO opentechcalendar3;

--
-- Name: event_recur_set_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.event_recur_set_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.event_recur_set_id_seq OWNER TO opentechcalendar3;

--
-- Name: event_recur_set_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.event_recur_set_id_seq OWNED BY public.event_recur_set.id;


--
-- Name: gist_content_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.gist_content_information (
    id integer NOT NULL,
    gist_id integer NOT NULL,
    sort smallint DEFAULT 0 NOT NULL,
    event_id integer,
    group_id integer,
    area_id integer,
    venue_id integer,
    content_title character varying(255),
    content_text text,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.gist_content_information OWNER TO opentechcalendar3;

--
-- Name: gist_content_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.gist_content_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gist_content_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: gist_content_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.gist_content_information_id_seq OWNED BY public.gist_content_information.id;


--
-- Name: gist_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.gist_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    slug character varying(255) NOT NULL,
    title character varying(255),
    is_deleted boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.gist_information OWNER TO opentechcalendar3;

--
-- Name: gist_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.gist_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gist_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: gist_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.gist_information_id_seq OWNED BY public.gist_information.id;


--
-- Name: group_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.group_history (
    group_id integer NOT NULL,
    title character varying(255),
    description text,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    url character varying(255),
    twitter_username character varying(255),
    is_deleted boolean DEFAULT false NOT NULL,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    url_changed smallint DEFAULT (0)::smallint NOT NULL,
    twitter_username_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    is_duplicate_of_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    edit_comment text,
    from_ip inet
);


ALTER TABLE public.group_history OWNER TO opentechcalendar3;

--
-- Name: group_in_curated_list; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.group_in_curated_list (
    curated_list_id integer NOT NULL,
    group_id integer NOT NULL,
    added_by_user_account_id integer NOT NULL,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.group_in_curated_list OWNER TO opentechcalendar3;

--
-- Name: group_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.group_information (
    id integer NOT NULL,
    site_id integer,
    slug integer,
    title character varying(255),
    description text,
    created_at timestamp without time zone NOT NULL,
    url character varying(255),
    twitter_username character varying(255),
    is_deleted boolean DEFAULT false NOT NULL,
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    cached_future_events integer DEFAULT 0 NOT NULL,
    slug_human character varying(255),
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.group_information OWNER TO opentechcalendar3;

--
-- Name: group_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.group_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.group_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: group_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.group_information_id_seq OWNED BY public.group_information.id;


--
-- Name: import_url_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.import_url_history (
    import_url_id integer NOT NULL,
    group_id integer,
    title character varying(255),
    is_enabled boolean,
    expired_at timestamp without time zone,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    legacy_location_id integer,
    country_id integer,
    area_id integer,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_enabled_changed smallint DEFAULT (0)::smallint NOT NULL,
    expired_at_changed smallint DEFAULT (0)::smallint NOT NULL,
    country_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    area_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    approved_at timestamp without time zone,
    group_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_manual_events_creation boolean,
    is_manual_events_creation_changed smallint DEFAULT (0)::smallint NOT NULL,
    edit_comment text,
    from_ip inet
);


ALTER TABLE public.import_url_history OWNER TO opentechcalendar3;

--
-- Name: import_url_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.import_url_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    slug integer NOT NULL,
    group_id integer,
    title character varying(255),
    url character varying(255),
    is_enabled boolean DEFAULT true NOT NULL,
    expired_at timestamp without time zone,
    created_at timestamp without time zone NOT NULL,
    url_canonical character varying(255),
    legacy_location_id integer,
    country_id integer,
    area_id integer,
    approved_at timestamp without time zone,
    is_manual_events_creation boolean DEFAULT false NOT NULL,
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.import_url_information OWNER TO opentechcalendar3;

--
-- Name: import_url_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.import_url_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.import_url_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: import_url_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.import_url_information_id_seq OWNED BY public.import_url_information.id;


--
-- Name: import_url_result; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.import_url_result (
    import_url_id integer NOT NULL,
    new_count smallint NOT NULL,
    existing_count smallint NOT NULL,
    saved_count smallint NOT NULL,
    in_past_count smallint NOT NULL,
    to_far_in_future_count smallint NOT NULL,
    not_valid_count smallint NOT NULL,
    created_at timestamp without time zone NOT NULL,
    is_success boolean NOT NULL,
    message character varying(255)
);


ALTER TABLE public.import_url_result OWNER TO opentechcalendar3;

--
-- Name: imported_event; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.imported_event (
    id integer NOT NULL,
    import_url_id integer,
    import_id character varying(255),
    title character varying(255),
    description text,
    start_at timestamp without time zone,
    end_at timestamp without time zone,
    is_deleted boolean DEFAULT false NOT NULL,
    url character varying(255),
    created_at timestamp without time zone NOT NULL,
    ticket_url character varying(255),
    timezone character varying(255),
    ics_rrule_1 text,
    reoccur text,
    lng real,
    lat real
);


ALTER TABLE public.imported_event OWNER TO opentechcalendar3;

--
-- Name: imported_event_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.imported_event_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.imported_event_id_seq OWNER TO opentechcalendar3;

--
-- Name: imported_event_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.imported_event_id_seq OWNED BY public.imported_event.id;


--
-- Name: imported_event_is_event; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.imported_event_is_event (
    imported_event_id integer NOT NULL,
    event_id integer NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.imported_event_is_event OWNER TO opentechcalendar3;

--
-- Name: incoming_link; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.incoming_link (
    id integer NOT NULL,
    site_id integer,
    extension_id character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    source_url text,
    target_url text,
    reporter_useragent text,
    reporter_ip inet,
    is_verified boolean DEFAULT false NOT NULL,
    data text,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.incoming_link OWNER TO opentechcalendar3;

--
-- Name: incoming_link_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.incoming_link_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.incoming_link_id_seq OWNER TO opentechcalendar3;

--
-- Name: incoming_link_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.incoming_link_id_seq OWNED BY public.incoming_link.id;


--
-- Name: legacy_location_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.legacy_location_information (
    id integer NOT NULL,
    title character varying(255),
    country_id integer,
    legacy_region_id integer,
    post_code_filter character varying(255),
    lat real,
    lng real,
    cached_future_events integer,
    area_id integer
);


ALTER TABLE public.legacy_location_information OWNER TO opentechcalendar3;

--
-- Name: legacy_location_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.legacy_location_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.legacy_location_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: legacy_location_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.legacy_location_information_id_seq OWNED BY public.legacy_location_information.id;


--
-- Name: legacy_region_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.legacy_region_information (
    id integer NOT NULL,
    title character varying(255),
    country_id integer,
    area_id integer
);


ALTER TABLE public.legacy_region_information OWNER TO opentechcalendar3;

--
-- Name: legacy_region_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.legacy_region_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.legacy_region_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: legacy_region_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.legacy_region_information_id_seq OWNED BY public.legacy_region_information.id;


--
-- Name: media_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.media_history (
    media_id integer NOT NULL,
    title character varying(255),
    title_changed smallint DEFAULT 0 NOT NULL,
    source_text character varying(255),
    source_text_changed smallint DEFAULT 0 NOT NULL,
    source_url character varying(255),
    source_url_changed smallint DEFAULT 0 NOT NULL,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    from_ip inet,
    is_new smallint DEFAULT (0)::smallint NOT NULL
);


ALTER TABLE public.media_history OWNER TO opentechcalendar3;

--
-- Name: media_in_event; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.media_in_event (
    event_id integer NOT NULL,
    media_id integer NOT NULL,
    added_by_user_account_id integer NOT NULL,
    added_at timestamp without time zone NOT NULL,
    addition_approved_at timestamp without time zone,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone,
    removal_approved_at timestamp without time zone
);


ALTER TABLE public.media_in_event OWNER TO opentechcalendar3;

--
-- Name: media_in_group; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.media_in_group (
    group_id integer NOT NULL,
    media_id integer NOT NULL,
    added_by_user_account_id integer NOT NULL,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone,
    addition_approved_at timestamp without time zone,
    removal_approved_at timestamp without time zone
);


ALTER TABLE public.media_in_group OWNER TO opentechcalendar3;

--
-- Name: media_in_venue; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.media_in_venue (
    venue_id integer NOT NULL,
    media_id integer NOT NULL,
    added_by_user_account_id integer NOT NULL,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone,
    addition_approved_at timestamp without time zone,
    removal_approved_at timestamp without time zone
);


ALTER TABLE public.media_in_venue OWNER TO opentechcalendar3;

--
-- Name: media_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.media_information (
    id integer NOT NULL,
    site_id integer,
    slug integer,
    is_file_lost boolean DEFAULT false NOT NULL,
    storage_size integer,
    created_by_user_account_id integer NOT NULL,
    created_at timestamp without time zone NOT NULL,
    deleted_by_user_account_id integer,
    deleted_at timestamp without time zone,
    title character varying(255),
    source_text character varying(255),
    source_url character varying(255),
    md5 character varying(32),
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.media_information OWNER TO opentechcalendar3;

--
-- Name: media_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.media_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.media_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: media_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.media_information_id_seq OWNED BY public.media_information.id;


--
-- Name: migration; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.migration (
    id character varying(255) NOT NULL,
    installed_at timestamp without time zone NOT NULL
);


ALTER TABLE public.migration OWNER TO opentechcalendar3;

--
-- Name: new_event_draft_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.new_event_draft_history (
    new_event_draft_id integer NOT NULL,
    details text,
    details_changed smallint DEFAULT (0)::smallint NOT NULL,
    not_duplicate_events text,
    not_duplicate_events_changed smallint DEFAULT (0)::smallint NOT NULL,
    event_id integer,
    event_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    was_existing_event_id integer,
    was_existing_event_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.new_event_draft_history OWNER TO opentechcalendar3;

--
-- Name: new_event_draft_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.new_event_draft_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    slug integer NOT NULL,
    details text NOT NULL,
    not_duplicate_events text,
    event_id integer,
    was_existing_event_id integer,
    user_account_id integer,
    updated_at timestamp without time zone NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.new_event_draft_information OWNER TO opentechcalendar3;

--
-- Name: new_event_draft_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.new_event_draft_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.new_event_draft_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: new_event_draft_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.new_event_draft_information_id_seq OWNED BY public.new_event_draft_information.id;


--
-- Name: permission_in_user_group; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.permission_in_user_group (
    user_group_id integer NOT NULL,
    extension_id character varying(255) NOT NULL,
    permission_key character varying(255) NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.permission_in_user_group OWNER TO opentechcalendar3;

--
-- Name: site_feature_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_feature_history (
    site_id integer NOT NULL,
    extension_id character varying(255) NOT NULL,
    feature_id character varying(255) NOT NULL,
    is_on boolean NOT NULL,
    is_on_changed smallint DEFAULT (0)::smallint NOT NULL,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.site_feature_history OWNER TO opentechcalendar3;

--
-- Name: site_feature_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_feature_information (
    site_id integer NOT NULL,
    extension_id character varying(255) NOT NULL,
    feature_id character varying(255) NOT NULL,
    is_on boolean NOT NULL
);


ALTER TABLE public.site_feature_information OWNER TO opentechcalendar3;

--
-- Name: site_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_history (
    site_id integer NOT NULL,
    title character varying(255),
    slug character varying(255),
    slug_canonical character varying(255),
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    description_text text,
    footer_text text,
    is_web_robots_allowed boolean,
    is_closed_by_sys_admin boolean,
    is_all_users_editors boolean DEFAULT true NOT NULL,
    closed_by_sys_admin_reason text,
    is_listed_in_index boolean,
    is_request_access_allowed boolean DEFAULT false NOT NULL,
    request_access_question text,
    is_feature_map boolean,
    is_feature_importer boolean,
    is_feature_curated_list boolean,
    prompt_emails_days_in_advance smallint,
    is_feature_virtual_events boolean,
    is_feature_physical_events boolean,
    is_feature_group boolean,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    slug_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_text_changed smallint DEFAULT (0)::smallint NOT NULL,
    footer_text_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_web_robots_allowed_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_closed_by_sys_admin_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_all_users_editors_changed smallint DEFAULT (0)::smallint NOT NULL,
    closed_by_sys_admin_reason_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_listed_in_index_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_request_access_allowed_changed smallint DEFAULT (0)::smallint NOT NULL,
    request_access_question_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_map_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_importer_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_curated_list_changed smallint DEFAULT (0)::smallint NOT NULL,
    prompt_emails_days_in_advance_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_virtual_events_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_physical_events_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_group_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    is_feature_tag boolean,
    is_feature_tag_changed smallint DEFAULT (0)::smallint NOT NULL,
    from_ip inet
);


ALTER TABLE public.site_history OWNER TO opentechcalendar3;

--
-- Name: site_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_information (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    slug character varying(255),
    slug_canonical character varying(255),
    created_at timestamp without time zone NOT NULL,
    description_text text,
    footer_text text,
    is_web_robots_allowed boolean DEFAULT true NOT NULL,
    is_closed_by_sys_admin boolean DEFAULT false NOT NULL,
    is_all_users_editors boolean DEFAULT true NOT NULL,
    closed_by_sys_admin_reason text,
    is_listed_in_index boolean DEFAULT true NOT NULL,
    cached_is_multiple_timezones boolean DEFAULT false NOT NULL,
    cached_is_multiple_countries boolean DEFAULT false NOT NULL,
    cached_timezones text,
    is_request_access_allowed boolean DEFAULT false NOT NULL,
    request_access_question text,
    is_feature_map boolean DEFAULT false NOT NULL,
    is_feature_importer boolean DEFAULT false NOT NULL,
    is_feature_curated_list boolean DEFAULT false NOT NULL,
    prompt_emails_days_in_advance smallint DEFAULT (30)::smallint NOT NULL,
    is_feature_virtual_events boolean DEFAULT false NOT NULL,
    is_feature_physical_events boolean DEFAULT true NOT NULL,
    is_feature_group boolean DEFAULT true NOT NULL,
    site_quota_id integer,
    is_feature_tag boolean DEFAULT false NOT NULL,
    cached_event_custom_field_definitions text,
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.site_information OWNER TO opentechcalendar3;

--
-- Name: site_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.site_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.site_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: site_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.site_information_id_seq OWNED BY public.site_information.id;


--
-- Name: site_profile_media_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_profile_media_history (
    site_id integer NOT NULL,
    logo_media_id integer,
    user_account_id integer NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.site_profile_media_history OWNER TO opentechcalendar3;

--
-- Name: site_profile_media_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_profile_media_information (
    site_id integer NOT NULL,
    logo_media_id integer
);


ALTER TABLE public.site_profile_media_information OWNER TO opentechcalendar3;

--
-- Name: site_quota_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.site_quota_information (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    code character varying(255) NOT NULL,
    max_new_events_per_month integer DEFAULT 1 NOT NULL,
    max_new_groups_per_month integer DEFAULT 1 NOT NULL,
    max_new_venues_per_month integer DEFAULT 1 NOT NULL,
    max_countries integer DEFAULT 1 NOT NULL,
    max_media_mb integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.site_quota_information OWNER TO opentechcalendar3;

--
-- Name: site_quota_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.site_quota_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.site_quota_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: site_quota_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.site_quota_information_id_seq OWNED BY public.site_quota_information.id;


--
-- Name: sysadmin_comment_about_area; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_area (
    sysadmin_comment_id integer NOT NULL,
    area_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_area OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_event; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_event (
    sysadmin_comment_id integer NOT NULL,
    event_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_event OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_group; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_group (
    sysadmin_comment_id integer NOT NULL,
    group_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_group OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_media; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_media (
    sysadmin_comment_id integer NOT NULL,
    media_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_media OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_site; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_site (
    sysadmin_comment_id integer NOT NULL,
    site_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_site OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_user; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_user (
    sysadmin_comment_id integer NOT NULL,
    user_account_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_user OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_about_venue; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_about_venue (
    sysadmin_comment_id integer NOT NULL,
    venue_id integer NOT NULL
);


ALTER TABLE public.sysadmin_comment_about_venue OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.sysadmin_comment_information (
    id integer NOT NULL,
    user_account_id integer,
    comment text NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.sysadmin_comment_information OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.sysadmin_comment_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sysadmin_comment_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: sysadmin_comment_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.sysadmin_comment_information_id_seq OWNED BY public.sysadmin_comment_information.id;


--
-- Name: tag_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.tag_history (
    tag_id integer NOT NULL,
    title character varying(255),
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    description text,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted boolean,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL,
    user_account_id integer,
    is_new smallint DEFAULT (0)::smallint,
    created_at timestamp without time zone NOT NULL,
    approved_at timestamp without time zone,
    edit_comment text,
    from_ip inet
);


ALTER TABLE public.tag_history OWNER TO opentechcalendar3;

--
-- Name: tag_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.tag_information (
    id integer NOT NULL,
    site_id integer NOT NULL,
    slug integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    is_deleted boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    approved_at timestamp without time zone,
    slug_human character varying(255),
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.tag_information OWNER TO opentechcalendar3;

--
-- Name: tag_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.tag_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tag_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: tag_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.tag_information_id_seq OWNED BY public.tag_information.id;


--
-- Name: task_log; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.task_log (
    extension_id character varying(255) NOT NULL,
    task_id character varying(255) NOT NULL,
    started_at timestamp without time zone NOT NULL,
    ended_at timestamp without time zone,
    result_data text,
    exception_data text
);


ALTER TABLE public.task_log OWNER TO opentechcalendar3;

--
-- Name: user_account_general_security_key; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_general_security_key (
    user_account_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_account_general_security_key OWNER TO opentechcalendar3;

--
-- Name: user_account_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_information (
    id integer NOT NULL,
    username character varying(255),
    username_canonical character varying(255),
    email character varying(255),
    email_canonical character varying(255),
    password_hash character varying(255) NOT NULL,
    is_email_verified boolean DEFAULT false NOT NULL,
    email_verify_code character varying(255),
    email_verify_last_sent_at timestamp without time zone,
    is_editor boolean DEFAULT true NOT NULL,
    is_system_admin boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    is_email_watch_notify boolean DEFAULT true NOT NULL,
    is_email_watch_prompt boolean DEFAULT true NOT NULL,
    email_upcoming_events character varying(1) DEFAULT 'w'::character varying NOT NULL,
    is_email_watch_import_expired boolean DEFAULT true NOT NULL,
    email_upcoming_events_days_notice smallint DEFAULT (1)::smallint NOT NULL,
    is_clock_12hour boolean DEFAULT true NOT NULL,
    is_closed_by_sys_admin boolean DEFAULT false NOT NULL,
    closed_by_sys_admin_reason text,
    is_email_newsletter boolean DEFAULT true NOT NULL,
    created_from_ip inet,
    displayname character varying(255),
    last_website_login_at timestamp without time zone
);


ALTER TABLE public.user_account_information OWNER TO opentechcalendar3;

--
-- Name: user_account_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.user_account_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_account_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: user_account_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.user_account_information_id_seq OWNED BY public.user_account_information.id;


--
-- Name: user_account_private_feed_key; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_private_feed_key (
    user_account_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    last_used_at timestamp without time zone
);


ALTER TABLE public.user_account_private_feed_key OWNER TO opentechcalendar3;

--
-- Name: user_account_remember_me; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_remember_me (
    user_account_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    last_used_at timestamp without time zone
);


ALTER TABLE public.user_account_remember_me OWNER TO opentechcalendar3;

--
-- Name: user_account_reset; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_reset (
    user_account_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    reset_at timestamp without time zone
);


ALTER TABLE public.user_account_reset OWNER TO opentechcalendar3;

--
-- Name: user_account_verify_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_account_verify_email (
    user_account_id integer NOT NULL,
    email character varying(255) NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL,
    verified_at timestamp without time zone,
    verified_from_ip inet
);


ALTER TABLE public.user_account_verify_email OWNER TO opentechcalendar3;

--
-- Name: user_at_event_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_at_event_information (
    user_account_id integer NOT NULL,
    event_id integer NOT NULL,
    is_plan_attending boolean DEFAULT false NOT NULL,
    is_plan_maybe_attending boolean DEFAULT false NOT NULL,
    is_plan_public boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    is_plan_not_attending boolean DEFAULT false NOT NULL
);


ALTER TABLE public.user_at_event_information OWNER TO opentechcalendar3;

--
-- Name: user_group_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_group_history (
    user_group_id integer NOT NULL,
    title character varying(255),
    title_changed smallint DEFAULT 0 NOT NULL,
    description text,
    description_changed smallint DEFAULT 0 NOT NULL,
    is_deleted boolean,
    is_deleted_changed smallint DEFAULT 0 NOT NULL,
    is_in_index boolean,
    is_in_index_changed smallint DEFAULT 0 NOT NULL,
    is_includes_anonymous boolean,
    is_includes_anonymous_changed smallint DEFAULT 0 NOT NULL,
    is_includes_users boolean,
    is_includes_users_changed smallint DEFAULT 0 NOT NULL,
    is_includes_verified_users boolean,
    is_includes_verified_users_changed smallint DEFAULT 0 NOT NULL,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    from_ip inet
);


ALTER TABLE public.user_group_history OWNER TO opentechcalendar3;

--
-- Name: user_group_in_site; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_group_in_site (
    user_group_id integer NOT NULL,
    site_id integer NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.user_group_in_site OWNER TO opentechcalendar3;

--
-- Name: user_group_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_group_information (
    id integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    is_deleted boolean DEFAULT false NOT NULL,
    is_in_index boolean DEFAULT false NOT NULL,
    is_includes_anonymous boolean DEFAULT false NOT NULL,
    is_includes_users boolean DEFAULT false NOT NULL,
    is_includes_verified_users boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_group_information OWNER TO opentechcalendar3;

--
-- Name: user_group_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.user_group_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_group_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: user_group_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.user_group_information_id_seq OWNED BY public.user_group_information.id;


--
-- Name: user_has_no_editor_permissions_in_site; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_has_no_editor_permissions_in_site (
    site_id integer NOT NULL,
    user_account_id integer NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.user_has_no_editor_permissions_in_site OWNER TO opentechcalendar3;

--
-- Name: user_in_curated_list_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_in_curated_list_information (
    user_account_id integer NOT NULL,
    curated_list_id integer NOT NULL,
    is_owner boolean DEFAULT false NOT NULL,
    is_editor boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_in_curated_list_information OWNER TO opentechcalendar3;

--
-- Name: user_in_gist_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_in_gist_information (
    user_account_id integer NOT NULL,
    gist_id integer NOT NULL,
    is_owner boolean DEFAULT false NOT NULL,
    is_editor boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_in_gist_information OWNER TO opentechcalendar3;

--
-- Name: user_in_site_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_in_site_information (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    is_owner boolean DEFAULT false NOT NULL,
    is_administrator boolean DEFAULT false NOT NULL,
    is_editor boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_in_site_information OWNER TO opentechcalendar3;

--
-- Name: user_in_user_group; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_in_user_group (
    user_group_id integer NOT NULL,
    user_account_id integer NOT NULL,
    added_by_user_account_id integer,
    added_at timestamp without time zone NOT NULL,
    removed_by_user_account_id integer,
    removed_at timestamp without time zone
);


ALTER TABLE public.user_in_user_group OWNER TO opentechcalendar3;

--
-- Name: user_interested_in_site_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_interested_in_site_information (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    is_interested boolean DEFAULT false NOT NULL,
    is_not_interested boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_interested_in_site_information OWNER TO opentechcalendar3;

--
-- Name: user_notification; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_notification (
    id integer NOT NULL,
    user_id integer NOT NULL,
    site_id integer,
    from_extension_id character varying(255) NOT NULL,
    from_user_notification_type character varying(255) NOT NULL,
    is_email boolean DEFAULT false NOT NULL,
    data_json text DEFAULT '{}'::text NOT NULL,
    created_at timestamp without time zone NOT NULL,
    emailed_at timestamp without time zone,
    read_at timestamp without time zone
);


ALTER TABLE public.user_notification OWNER TO opentechcalendar3;

--
-- Name: user_notification_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.user_notification_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_notification_id_seq OWNER TO opentechcalendar3;

--
-- Name: user_notification_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.user_notification_id_seq OWNED BY public.user_notification.id;


--
-- Name: user_notification_preference; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_notification_preference (
    user_id integer NOT NULL,
    extension_id character varying(255) NOT NULL,
    user_notification_preference_type character varying(255) NOT NULL,
    is_email boolean DEFAULT true NOT NULL,
    last_save_at timestamp without time zone
);


ALTER TABLE public.user_notification_preference OWNER TO opentechcalendar3;

--
-- Name: user_watches_area_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_area_information (
    user_account_id integer NOT NULL,
    area_id integer NOT NULL,
    is_watching boolean DEFAULT false NOT NULL,
    is_was_once_watching boolean DEFAULT false NOT NULL,
    last_notify_email_sent timestamp without time zone,
    last_prompt_email_sent timestamp without time zone,
    last_watch_started timestamp without time zone,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_area_information OWNER TO opentechcalendar3;

--
-- Name: user_watches_area_stop; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_area_stop (
    user_account_id integer NOT NULL,
    area_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_area_stop OWNER TO opentechcalendar3;

--
-- Name: user_watches_group_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_group_information (
    user_account_id integer NOT NULL,
    group_id integer NOT NULL,
    is_watching boolean DEFAULT false NOT NULL,
    is_was_once_watching boolean DEFAULT false NOT NULL,
    last_notify_email_sent timestamp without time zone,
    last_prompt_email_sent timestamp without time zone,
    last_watch_started timestamp without time zone,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_group_information OWNER TO opentechcalendar3;

--
-- Name: user_watches_group_notify_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_group_notify_email (
    user_account_id integer NOT NULL,
    group_id integer NOT NULL,
    sent_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_group_notify_email OWNER TO opentechcalendar3;

--
-- Name: user_watches_group_prompt_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_group_prompt_email (
    user_account_id integer NOT NULL,
    group_id integer NOT NULL,
    sent_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_group_prompt_email OWNER TO opentechcalendar3;

--
-- Name: user_watches_group_stop; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_group_stop (
    user_account_id integer NOT NULL,
    group_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_group_stop OWNER TO opentechcalendar3;

--
-- Name: user_watches_site_group_prompt_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_site_group_prompt_email (
    user_account_id integer NOT NULL,
    group_id integer NOT NULL,
    sent_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_site_group_prompt_email OWNER TO opentechcalendar3;

--
-- Name: user_watches_site_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_site_information (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    is_watching boolean DEFAULT false NOT NULL,
    is_was_once_watching boolean DEFAULT false NOT NULL,
    last_notify_email_sent timestamp without time zone,
    last_prompt_email_sent timestamp without time zone,
    last_watch_started timestamp without time zone,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_site_information OWNER TO opentechcalendar3;

--
-- Name: user_watches_site_notify_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_site_notify_email (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    sent_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_site_notify_email OWNER TO opentechcalendar3;

--
-- Name: user_watches_site_prompt_email; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_site_prompt_email (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    sent_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_site_prompt_email OWNER TO opentechcalendar3;

--
-- Name: user_watches_site_stop; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.user_watches_site_stop (
    user_account_id integer NOT NULL,
    site_id integer NOT NULL,
    access_key character varying(255) NOT NULL,
    created_at timestamp without time zone NOT NULL
);


ALTER TABLE public.user_watches_site_stop OWNER TO opentechcalendar3;

--
-- Name: venue_history; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.venue_history (
    venue_id integer NOT NULL,
    title character varying(255),
    description text,
    lat real,
    lng real,
    country_id integer,
    is_deleted boolean,
    user_account_id integer,
    created_at timestamp without time zone NOT NULL,
    area_id integer,
    title_changed smallint DEFAULT (0)::smallint NOT NULL,
    description_changed smallint DEFAULT (0)::smallint NOT NULL,
    lat_changed smallint DEFAULT (0)::smallint NOT NULL,
    lng_changed smallint DEFAULT (0)::smallint NOT NULL,
    country_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_deleted_changed smallint DEFAULT (0)::smallint NOT NULL,
    area_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    address text,
    address_changed smallint DEFAULT (0)::smallint NOT NULL,
    address_code character varying(255),
    address_code_changed smallint DEFAULT (0)::smallint NOT NULL,
    is_new smallint DEFAULT (0)::smallint NOT NULL,
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    is_duplicate_of_id_changed smallint DEFAULT (0)::smallint NOT NULL,
    edit_comment text,
    from_ip inet
);


ALTER TABLE public.venue_history OWNER TO opentechcalendar3;

--
-- Name: venue_information; Type: TABLE; Schema: public; Owner: opentechcalendar3
--

CREATE TABLE public.venue_information (
    id integer NOT NULL,
    site_id integer,
    slug integer,
    title character varying(255),
    description text,
    lat real,
    lng real,
    country_id integer,
    is_deleted boolean DEFAULT false NOT NULL,
    created_at timestamp without time zone NOT NULL,
    area_id integer,
    address text,
    address_code character varying(255),
    approved_at timestamp without time zone,
    is_duplicate_of_id integer,
    cached_future_events integer DEFAULT 0 NOT NULL,
    slug_human character varying(255),
    cached_updated_at timestamp without time zone
);


ALTER TABLE public.venue_information OWNER TO opentechcalendar3;

--
-- Name: venue_information_id_seq; Type: SEQUENCE; Schema: public; Owner: opentechcalendar3
--

CREATE SEQUENCE public.venue_information_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.venue_information_id_seq OWNER TO opentechcalendar3;

--
-- Name: venue_information_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: opentechcalendar3
--

ALTER SEQUENCE public.venue_information_id_seq OWNED BY public.venue_information.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information ALTER COLUMN id SET DEFAULT nextval('public.area_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.contact_support ALTER COLUMN id SET DEFAULT nextval('public.contact_support_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.country ALTER COLUMN id SET DEFAULT nextval('public.country_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_information ALTER COLUMN id SET DEFAULT nextval('public.curated_list_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_information ALTER COLUMN id SET DEFAULT nextval('public.event_custom_field_definition_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information ALTER COLUMN id SET DEFAULT nextval('public.event_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_recur_set ALTER COLUMN id SET DEFAULT nextval('public.event_recur_set_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information ALTER COLUMN id SET DEFAULT nextval('public.gist_content_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_information ALTER COLUMN id SET DEFAULT nextval('public.gist_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_information ALTER COLUMN id SET DEFAULT nextval('public.group_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information ALTER COLUMN id SET DEFAULT nextval('public.import_url_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event ALTER COLUMN id SET DEFAULT nextval('public.imported_event_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.incoming_link ALTER COLUMN id SET DEFAULT nextval('public.incoming_link_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_location_information ALTER COLUMN id SET DEFAULT nextval('public.legacy_location_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_region_information ALTER COLUMN id SET DEFAULT nextval('public.legacy_region_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_information ALTER COLUMN id SET DEFAULT nextval('public.media_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information ALTER COLUMN id SET DEFAULT nextval('public.new_event_draft_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_information ALTER COLUMN id SET DEFAULT nextval('public.site_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_quota_information ALTER COLUMN id SET DEFAULT nextval('public.site_quota_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_information ALTER COLUMN id SET DEFAULT nextval('public.sysadmin_comment_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_information ALTER COLUMN id SET DEFAULT nextval('public.tag_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_information ALTER COLUMN id SET DEFAULT nextval('public.user_account_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_information ALTER COLUMN id SET DEFAULT nextval('public.user_group_information_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification ALTER COLUMN id SET DEFAULT nextval('public.user_notification_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information ALTER COLUMN id SET DEFAULT nextval('public.venue_information_id_seq'::regclass);


--
-- Data for Name: app_configuration_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.app_configuration_information (extension_id, configuration_key, value_text) FROM stdin;
\.


--
-- Data for Name: area_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.area_history (area_id, title, description, country_id, parent_area_id, is_deleted, user_account_id, created_at, title_changed, description_changed, country_id_changed, parent_area_id_changed, is_deleted_changed, is_new, approved_at, is_duplicate_of_id, is_duplicate_of_id_changed, edit_comment, from_ip, max_lat, max_lng, min_lat, min_lng, max_lat_changed, max_lng_changed, min_lat_changed, min_lng_changed) FROM stdin;
147	Linlithgow	\N	77	59	f	\N	2018-01-29 19:38:05	1	-1	1	1	-1	1	2018-01-29 19:38:05	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
146	Düsseldorf	\N	57	\N	f	\N	2017-11-28 08:35:55	1	-1	1	-1	-1	1	2017-11-28 08:35:55	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
48	Westminster	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
56	Lisburn	\N	77	53	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
57	Londonderry	\N	77	53	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
120	Liskeard	\N	77	108	f	\N	2015-09-15 09:04:51	1	-1	1	1	-1	1	2015-09-15 09:04:51	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
62	Edinburgh	\N	\N	\N	f	\N	2016-09-23 08:18:00	-1	-1	-2	-2	-1	0	2016-09-23 08:18:00	\N	-2	\N	\N	55.9983826	-3.04595947	55.8737717	-3.3480835	1	1	1	1
60	Aberdeen	\N	\N	\N	f	\N	2016-09-23 08:19:35	-1	-1	-2	-2	-1	0	2016-09-23 08:19:35	\N	-2	\N	\N	57.209198	-1.98303223	57.0720978	-2.23297119	1	1	1	1
37	Ripon	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
138	France	\N	\N	\N	t	\N	2017-06-14 19:47:17	-1	-2	-2	-2	1	0	2017-06-14 19:47:17	\N	-2	\N	\N	\N	\N	\N	\N	-2	-2	-2	-2
64	Falkirk	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
65	Glasgow	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
66	Inverness	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
72	Cardiff	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
73	Newport	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
74	St Asaph	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
75	St David's	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
45	Truro	\N	\N	\N	f	\N	2015-09-15 08:46:13	-1	-1	-2	-2	-1	0	2015-09-15 08:46:13	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
19	Hereford	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
20	Kingston-upon-Hull	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
29	Newcastle-upon-Tyne	\N	\N	\N	f	\N	2015-08-26 07:37:59	1	1	-2	-2	-1	0	2015-08-26 07:37:59	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
108	Cornwall	\N	77	1	f	\N	2015-09-15 08:44:46	1	-1	1	1	-1	1	2015-09-15 08:44:46	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
109	Falmouth	\N	77	108	f	\N	2015-09-15 08:58:15	1	-1	1	1	-1	1	2015-09-15 08:58:15	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
111	Camborne	\N	77	108	f	\N	2015-09-15 08:58:44	1	-1	1	1	-1	1	2015-09-15 08:58:44	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
67	Perth	\N	\N	\N	f	\N	2016-09-23 08:11:43	-1	-1	-2	-2	-1	0	2016-09-23 08:11:43	\N	-2	\N	\N	56.4340286	-3.35906982	56.3447075	-3.53416443	1	1	1	1
68	St. Andrews	\N	\N	\N	f	\N	2016-09-23 08:12:25	-1	-1	-2	-2	-1	0	2016-09-23 08:12:25	\N	-2	\N	\N	56.3565025	-2.76031494	56.3157768	-2.84751892	1	1	1	1
86	Hebden Bridge	\N	77	1	f	\N	2014-07-27 12:34:42	1	-1	1	1	-1	1	2014-07-27 12:34:42	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
63	Elgin	\N	\N	\N	f	\N	2016-09-23 08:17:32	-1	-1	-2	-2	-1	0	2016-09-23 08:17:32	\N	-2	\N	\N	57.6692772	-3.26087952	57.6167259	-3.37417603	1	1	1	1
53	Northern Ireland	\N	\N	\N	f	\N	2016-09-23 08:20:07	-1	-1	-2	-2	-1	0	2016-09-23 08:20:07	\N	-2	\N	\N	55.3666267	-5.42724609	53.994854	-8.17382812	1	1	1	1
77	Paisley	\N	\N	\N	f	\N	2016-09-23 08:11:12	-1	-1	-2	-2	-1	0	2016-09-23 08:11:12	\N	-2	\N	\N	55.8652954	-4.38766479	55.8244286	-4.48242188	1	1	1	1
62	Edinburgh	\N	\N	\N	f	\N	2016-05-01 22:11:05	1	1	-2	-2	-1	0	2016-05-01 22:11:05	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
70	Wales	\N	\N	\N	f	\N	2016-09-23 08:20:26	-1	-1	-2	-2	-1	0	2016-09-23 08:20:26	\N	-2	\N	\N	53.514183	-2.74658203	51.3031464	-5.42724609	1	1	1	1
1	England	\N	\N	\N	f	\N	2016-09-23 08:20:58	-1	-1	-2	-2	-1	0	2016-09-23 08:20:58	\N	-2	\N	\N	55.5286293	1.84570312	49.8379822	-6.328125	1	1	1	1
91	Isle of Man	\N	\N	\N	f	\N	2016-09-23 08:21:34	-1	-1	-2	-2	-1	0	2016-09-23 08:21:34	\N	-2	\N	\N	54.4253235	-4.31488037	54.0351982	-4.85595703	1	1	1	1
122	Hayle	\N	77	108	f	\N	2015-09-15 09:05:25	1	-1	1	1	-1	1	2015-09-15 09:05:25	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
45	Truro	\N	\N	\N	f	\N	2015-09-15 08:45:24	-1	-1	-2	-2	-1	0	2015-09-15 08:45:24	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
45	Truro	\N	\N	108	\N	\N	2015-09-15 08:56:27	-1	-2	-2	1	-2	0	2015-09-15 08:56:27	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
110	Penzance	\N	77	108	f	\N	2015-09-15 08:58:30	1	-1	1	1	-1	1	2015-09-15 08:58:30	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
112	Newquay	\N	77	108	f	\N	2015-09-15 08:58:59	1	-1	1	1	-1	1	2015-09-15 08:58:59	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
129		\N	\N	\N	f	\N	2016-10-13 08:40:28	1	-1	-2	-2	-1	0	2016-10-13 08:40:28	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
124	Treverbyn	\N	77	108	f	\N	2015-09-15 20:38:52	1	-1	1	1	-1	1	2015-09-15 20:38:52	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
126	Torpoint	\N	77	108	f	\N	2015-09-15 20:39:37	1	-1	1	1	-1	1	2015-09-15 20:39:37	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
69	Stirling	\N	77	59	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
59	Scotland	\N	77	\N	f	\N	2014-01-28 11:33:51	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
1	England	\N	77	\N	f	\N	2014-01-28 11:33:50	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
36	Preston	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
47	Wells	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
29	Newcastle-upon-Tyne	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
62	EIE16,Edinburgh	Now in its ninth year, EIE has grown to be the largest investor technology showcase in the UK. EIE Scotland focuses on technology investment opportunities from companies headquartered in Scotland, which is fast becoming one of Europe’s most vibrant tech scenes following the announcement of two Unicorns to come from just one square mile in Edinburgh in Skyscanner and FanDuel.\r\nTake the opportunity to see the next generation of Scottish technology companies from the Life Sciences, Energy and ICT sectors, and see first hand the true potential of Scotland’s thriving tech scene at this one of a kind investor showcase.	\N	\N	f	\N	2016-05-01 21:50:43	1	1	-2	-2	-1	0	2016-05-01 21:50:43	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
17	Exeter	\N	\N	\N	f	\N	2016-09-23 08:27:06	-1	-1	-2	-2	-1	0	2016-09-23 08:27:06	\N	-2	\N	\N	50.7462311	-3.48438263	50.6812325	-3.5684967	1	1	1	1
45	Truro	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
5	Brighton & Hove	\N	\N	\N	f	\N	2016-09-29 09:50:31	-1	-1	-2	-2	-1	0	2016-09-29 09:50:31	\N	-2	\N	\N	50.8553772	-0.0926971436	50.802681	-0.233459473	1	1	1	1
6	Bristol	\N	\N	\N	f	\N	2016-09-29 09:51:38	-1	-1	-2	-2	-1	0	2016-09-29 09:51:38	\N	-2	\N	\N	51.5668259	-2.46643066	51.3700676	-2.73696899	1	1	1	1
60	Aberdeen	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
61	Dundee	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
62	Edinburgh	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
16	Ely	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
28	Manchester	\N	\N	\N	f	\N	2016-09-23 08:22:19	-1	-1	-2	-2	-1	0	2016-09-23 08:22:19	\N	-2	\N	\N	53.5745697	-2.09838867	53.3620262	-2.43347168	1	1	1	1
35	Portsmouth	\N	\N	\N	f	\N	2016-09-23 08:24:25	-1	-1	-2	-2	-1	0	2016-09-23 08:24:25	\N	-2	\N	\N	50.8536415	-1.016922	50.7607841	-1.1769104	1	1	1	1
31	Nottingham	\N	\N	\N	f	\N	2016-09-23 08:24:53	-1	-1	-2	-2	-1	0	2016-09-23 08:24:53	\N	-2	\N	\N	53.0089989	-1.0546875	52.8774185	-1.25656128	1	1	1	1
136	Cologne	\N	57	\N	f	\N	2016-11-23 12:52:30	1	-1	1	-1	-1	1	2016-11-23 12:52:30	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
62	EIE16,Edinburgh	Now in its ninth year, EIE has grown to be the largest investor technology showcase in the UK. EIE Scotland focuses on technology investment opportunities from companies headquartered in Scotland, which is fast becoming one of Europe’s most vibrant tech scenes following the announcement of two Unicorns to come from just one square mile in Edinburgh in Skyscanner and FanDuel.\r\nTake the opportunity to see the next generation of Scottish technology companies from the Life Sciences, Energy and ICT sectors, and see first hand the true potential of Scotland’s thriving tech scene at this one of a kind investor showcase.	\N	\N	f	\N	2016-05-01 21:53:53	-1	-1	-2	-2	-1	0	2016-05-01 21:53:53	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
129	Huddersfield	\N	77	\N	f	\N	2016-10-13 08:26:45	1	-1	1	-1	-1	1	2016-10-13 08:26:45	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
129		\N	\N	\N	t	\N	2016-10-13 09:51:58	-1	-2	-2	-2	1	0	2016-10-13 09:51:58	\N	-2	\N	\N	\N	\N	\N	\N	-2	-2	-2	-2
137	Frankfurt	\N	57	\N	f	\N	2016-11-23 12:52:37	1	-1	1	-1	-1	1	2016-11-23 12:52:37	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
130	Huddersfield	'Huddersfield is a large market town and is the largest settlement in the metropolitan borough of Kirklees, West Yorkshire, England. It is the 11th largest town in the United Kingdom with a population of 162,949 (2011 census). Halfway between Leeds and Manchester, it lies 190 miles (310 km) north of London, and 10.3 miles (16.6 km) south of Bradford, the nearest city.'\r\n- Wikipedia, Oct 2016	77	1	f	\N	2016-10-13 08:33:51	1	1	1	1	-1	1	2016-10-13 08:33:51	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
133	Berlin	\N	\N	\N	f	\N	2016-11-23 22:09:44	-1	-1	-2	-2	-1	0	2016-11-23 22:09:44	\N	-2	\N	\N	52.676384	13.7054443	52.3554726	13.0819702	1	1	1	1
135	Munich	\N	\N	\N	f	\N	2016-11-23 22:10:12	-1	-1	-2	-2	-1	0	2016-11-23 22:10:12	\N	-2	\N	\N	48.246624	11.8048096	47.9880829	11.292572	1	1	1	1
134	Hamburg	\N	\N	\N	f	\N	2016-11-23 22:10:32	-1	-1	-2	-2	-1	0	2016-11-23 22:10:32	\N	-2	\N	\N	53.7308426	10.3244019	53.373497	9.65698242	1	1	1	1
49	Winchester	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
50	Wolverhampton	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
51	Worcester	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
52	York	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
53	Northern Ireland	\N	77	\N	f	\N	2014-01-28 11:33:51	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
54	Armagh	\N	77	53	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
128	St Stephen-in-Brannel	\N	77	108	f	\N	2015-09-15 20:49:05	1	-1	1	1	-1	1	2015-09-15 20:49:05	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
2	Bath	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
70	Wales	\N	77	\N	f	\N	2014-01-28 11:33:52	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
3	Birmingham	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
4	Bradford	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
23	Leicester	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
79	Oslo	\N	167	\N	f	\N	2014-04-23 22:19:40	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
23	Leicester	\N	\N	\N	f	\N	2016-11-10 08:34:38	-1	-1	-2	-2	-1	0	2016-11-10 08:34:38	\N	-2	\N	\N	52.6747169	-1.05743408	52.5621605	-1.23596191	1	1	1	1
41	Southampton	\N	\N	\N	f	\N	2016-11-19 21:28:32	-1	-1	-2	-2	-1	0	2016-11-19 21:28:32	\N	-2	\N	\N	50.9627533	-1.34101868	50.8748779	-1.52503967	1	1	1	1
131	Kirkcaldy	\N	77	59	f	\N	2016-11-21 07:34:51	1	-1	1	1	-1	1	2016-11-21 07:34:51	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
64	Falkirk	\N	\N	\N	f	\N	2016-11-23 00:09:36	-1	-1	-2	-2	-1	0	2016-11-23 00:09:36	\N	-2	\N	\N	56.0256004	-3.71200991	55.9772987	-3.84108996	-1	-1	-1	-1
139	Paris	\N	75	\N	f	\N	2017-06-28 06:25:56	1	-1	1	-1	-1	1	2017-06-28 06:25:56	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
140	Dijon	\N	75	\N	f	\N	2017-06-28 06:26:12	1	-1	1	-1	-1	1	2017-06-28 06:26:12	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
141	Marseille	\N	75	\N	f	\N	2017-06-28 06:27:18	1	-1	1	-1	-1	1	2017-06-28 06:27:18	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
142	Lyon	\N	75	\N	f	\N	2017-06-28 06:27:33	1	-1	1	-1	-1	1	2017-06-28 06:27:33	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
143	Toulouse	\N	75	\N	f	\N	2017-06-28 06:27:44	1	-1	1	-1	-1	1	2017-06-28 06:27:44	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
144	Nice	\N	75	\N	f	\N	2017-06-28 06:27:58	1	-1	1	-1	-1	1	2017-06-28 06:27:58	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
145	Nantes	\N	75	\N	f	\N	2017-06-28 06:28:09	1	-1	1	-1	-1	1	2017-06-28 06:28:09	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
21	Lancaster	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
22	Leeds	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
24	Lichfield	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
25	Lincoln	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
26	Liverpool	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
91	Isle of Man	\N	\N	\N	t	\N	2017-07-03 20:34:38	-1	-2	-2	-2	1	0	2017-07-03 20:34:38	\N	-2	\N	\N	\N	\N	\N	\N	-2	-2	-2	-2
81	Forgue	\N	\N	\N	f	\N	2016-09-23 08:14:59	-1	-1	-2	-2	-1	0	2016-09-23 08:14:59	\N	-2	\N	\N	57.4883385	-2.61611938	57.478466	-2.6374054	1	1	1	1
115	Bodmin	\N	77	108	f	\N	2015-09-15 09:03:29	1	-1	1	1	-1	1	2015-09-15 09:03:29	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
117	St Ives	\N	77	108	f	\N	2015-09-15 09:04:00	1	-1	1	1	-1	1	2015-09-15 09:04:00	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
28	Manchester	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
80	The Riverdale Centre	\N	77	\N	f	\N	2014-04-24 22:16:52	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
80	The Riverdale Centre	\N	77	\N	t	\N	2014-04-28 11:52:37	-1	-1	-1	-1	1	0	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
81	Forgue	\N	77	59	f	\N	2014-05-22 12:44:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
130	Huddersfield	'Huddersfield is a large market town and is the largest settlement in the metropolitan borough of Kirklees, West Yorkshire, England. It is the 11th largest town in the United Kingdom with a population of 162,949 (2011 census). Halfway between Leeds and Manchester, it lies 190 miles (310 km) north of London, and 10.3 miles (16.6 km) south of Bradford, the nearest city.'\r\n- Wikipedia, Oct 2016	\N	\N	f	\N	2016-10-13 08:36:01	-1	-1	-2	-2	-1	0	2016-10-13 08:36:01	\N	-2	\N	\N	53.6739006	-1.71112001	53.5778008	-1.85394001	-1	-1	-1	-1
121	Launceston	\N	77	108	f	\N	2015-09-15 09:05:07	1	-1	1	1	-1	1	2015-09-15 09:05:07	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
123	Penryn	\N	77	108	f	\N	2015-09-15 20:37:46	1	-1	1	1	-1	1	2015-09-15 20:37:46	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
125	Carn Brea	\N	77	108	f	\N	2015-09-15 20:39:21	1	-1	1	1	-1	1	2015-09-15 20:39:21	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
114	Saltash	\N	77	108	f	\N	2015-09-15 08:59:35	1	-1	1	1	-1	1	2015-09-15 08:59:35	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
116	Redruth	\N	77	108	f	\N	2015-09-15 09:03:46	1	-1	1	1	-1	1	2015-09-15 09:03:46	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
118	Helston	\N	77	108	f	\N	2015-09-15 09:04:18	1	-1	1	1	-1	1	2015-09-15 09:04:18	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
92	Ipswich	\N	\N	\N	f	\N	2016-09-23 08:26:04	-1	-1	-2	-2	-1	0	2016-09-23 08:26:04	\N	-2	\N	\N	52.087101	1.21398926	52.0098228	1.06636047	1	1	1	1
65	Glasgow	\N	\N	\N	f	\N	2016-09-23 08:10:07	-1	-1	-2	-2	-1	0	2016-09-23 08:10:07	\N	-2	\N	\N	55.9615021	-4.0045166	55.668293	-4.57580566	1	1	1	1
30	Norwich	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
31	Nottingham	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
32	Oxford	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
33	Peterborough	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
34	Plymouth	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
35	Portsmouth	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
46	Wakefield	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
63	Elgin	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
67	Perth	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
68	St. Andrews	\N	77	59	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
76	Swansea	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
78	Blackpool	\N	77	1	f	\N	2014-03-11 21:55:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
40	Sheffield	\N	\N	\N	f	\N	2016-09-23 08:23:07	-1	-1	-2	-2	-1	0	2016-09-23 08:23:07	\N	-2	\N	\N	53.4381752	-1.34307861	53.3111839	-1.54632568	1	1	1	1
64	Falkirk	\N	\N	\N	f	\N	2016-09-23 08:15:50	-1	-1	-2	-2	-1	0	2016-09-23 08:15:50	\N	-2	\N	\N	56.0256348	-3.71200562	55.9772568	-3.84109497	1	1	1	1
61	Dundee	\N	\N	\N	f	\N	2016-09-23 08:19:12	-1	-1	-2	-2	-1	0	2016-09-23 08:19:12	\N	-2	\N	\N	56.4882774	-2.89764404	56.4340286	-3.09127808	1	1	1	1
22	Leeds	\N	\N	\N	f	\N	2016-09-23 08:23:57	-1	-1	-2	-2	-1	0	2016-09-23 08:23:57	\N	-2	\N	\N	53.8411827	-1.4515686	53.7519608	-1.61224365	1	1	1	1
27	London	\N	\N	\N	f	\N	2016-09-23 08:23:30	-1	-1	-2	-2	-1	0	2016-09-23 08:23:30	\N	-2	\N	\N	51.7202225	0.302124023	51.2103233	-0.55480957	1	1	1	1
66	Inverness	\N	\N	\N	f	\N	2016-09-23 08:10:39	-1	-1	-2	-2	-1	0	2016-09-23 08:10:39	\N	-2	\N	\N	57.5132408	-4.14527893	57.4312744	-4.33410645	1	1	1	1
88	Reading	Thames Valley	77	1	f	\N	2014-09-04 18:34:41	1	1	1	1	-1	1	2014-09-04 18:34:41	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
71	Bangor	\N	77	70	f	\N	2014-01-28 11:33:52	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
5	Brighton & Hove	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
6	Bristol	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
7	Cambridge	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
8	Canterbury	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
9	Carlisle	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
10	Chelmsford	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
11	Chester	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
12	Chichester	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
13	Coventry	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
14	Derby	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
15	Durham	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
137	Frankfurt	\N	\N	\N	f	\N	2016-11-23 22:11:00	-1	-1	-2	-2	-1	0	2016-11-23 22:11:00	\N	-2	\N	\N	50.2243652	8.84674072	49.945919	8.44848633	1	1	1	1
136	Cologne	\N	\N	\N	f	\N	2016-11-23 22:12:46	-1	-1	-2	-2	-1	0	2016-11-23 22:12:46	\N	-2	\N	\N	51.037075	7.0930481	50.8415031	6.81427002	1	1	1	1
89	Southend-on-Sea	\N	77	1	f	\N	2014-11-02 07:22:41	1	-1	1	1	-1	1	2014-11-02 07:22:41	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
90	Bournemouth	\N	77	1	f	\N	2014-12-02 11:46:12	1	-1	1	1	-1	1	2014-12-02 11:46:12	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
91	Isle of Man	\N	77	\N	f	\N	2015-04-12 21:47:26	1	-1	1	-1	-1	1	2015-04-12 21:47:26	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
92	Ipswich	\N	77	1	f	\N	2015-05-22 17:04:42	1	-1	1	1	-1	1	2015-05-22 17:04:42	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
130	Huddersfield	'Huddersfield is a large market town and is the largest settlement in the metropolitan borough of Kirklees, West Yorkshire, England. It is the 11th largest town in the United Kingdom with a population of 162,949 (2011 census). Halfway between Leeds and Manchester, it lies 190 miles (310 km) north of London, and 10.3 miles (16.6 km) south of Bradford, the nearest city.'\r\n- Wikipedia, Oct 2016	\N	\N	f	\N	2016-10-13 08:35:16	-1	-1	-2	-2	-1	0	2016-10-13 08:35:16	\N	-2	\N	\N	53.6739349	-1.71112061	53.5778313	-1.85394287	1	1	1	1
62	Edinburgh	\N	77	59	f	\N	2014-02-06 19:23:26	-1	-1	-1	-1	-1	0	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
77	Paisley	\N	77	59	f	\N	2014-03-02 14:03:41	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
82	Forres	\N	\N	\N	f	\N	2016-09-23 08:09:46	-1	-1	-2	-2	-1	0	2016-09-23 08:09:46	\N	-2	\N	\N	57.6380501	-3.52935791	57.5512085	-3.74633789	1	1	1	1
62	EIE16, 12 May Edinburgh	Now in its ninth year, EIE Scotland has grown to be Scotland's largest investor technology showcase. EIE Scotland focuses on technology investment opportunities from companies headquartered in Scotland, which is fast becoming one of Europe’s most vibrant tech scenes following the announcement of two Unicorns to come from just one square mile in Edinburgh in Skyscanner and FanDuel.\r\nTake the opportunity to see the next generation of Scottish technology companies from the Life Sciences, Energy and ICT sectors, and see first hand the true potential of Scotland’s thriving tech scene at this one of a kind investor showcase.	\N	\N	f	\N	2016-05-01 22:02:22	1	1	-2	-2	-1	0	2016-05-01 22:02:22	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
59	Scotland	\N	\N	\N	f	\N	2016-09-23 08:08:15	-1	-1	-2	-2	-1	0	2016-09-23 08:08:15	\N	-2	\N	\N	61.0582848	0	54.6865349	-7.84423828	1	1	1	1
69	Stirling	\N	\N	\N	f	\N	2016-09-23 08:09:19	-1	-1	-2	-2	-1	0	2016-09-23 08:09:19	\N	-2	\N	\N	56.1577873	-3.87268066	56.0620689	-4.00314331	1	1	1	1
134	Hamburg	\N	57	\N	f	\N	2016-11-23 12:52:17	1	-1	1	-1	-1	1	2016-11-23 12:52:17	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
135	Munich	\N	57	\N	f	\N	2016-11-23 12:52:24	1	-1	1	-1	-1	1	2016-11-23 12:52:24	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
45	Truro	\N	\N	\N	f	\N	2015-09-15 08:45:42	-1	-1	-2	-2	-1	0	2015-09-15 08:45:42	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
38	Salford	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
39	Salisbury	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
40	Sheffield	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
41	Southampton	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
148	Glossop	\N	77	1	f	\N	2018-09-11 13:01:02	1	-1	1	1	-1	1	2018-09-11 13:01:02	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
149	Blackburn	\N	77	1	f	\N	2018-09-17 13:33:17	1	-1	1	1	-1	1	2018-09-17 13:33:17	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
42	St Albans	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
44	Sunderland	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
85	Edinburgh	\N	77	\N	f	\N	2014-07-17 14:14:19	1	-1	1	-1	-1	1	2014-07-17 14:14:19	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
85	Edinburgh	\N	77	\N	t	\N	2014-07-17 17:38:44	-1	-1	-1	-1	1	0	2014-07-17 17:38:44	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
87	Hebden Bridge	\N	77	1	f	\N	2014-07-29 19:58:56	1	-1	1	1	-1	1	2014-07-29 19:58:56	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
86	Hebden Bridge	\N	77	1	t	\N	2014-07-29 20:57:17	-1	-1	-1	-1	1	0	2014-07-29 20:57:17	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
132	Kassel	\N	\N	\N	f	\N	2016-11-23 22:09:15	-1	-1	-2	-2	-1	0	2016-11-23 22:09:15	\N	-2	\N	\N	51.3477707	9.58007812	51.2490234	9.36859131	1	1	1	1
132	Kassel	\N	57	\N	f	\N	2016-11-23 12:51:29	1	-1	1	-1	-1	1	2016-11-23 12:51:29	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
133	Berlin	\N	57	\N	f	\N	2016-11-23 12:52:11	1	-1	1	-1	-1	1	2016-11-23 12:52:11	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
55	Belfast	\N	77	53	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
17	Exeter	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
18	Gloucester	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
58	Newry	\N	77	53	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
138	France	\N	77	\N	f	\N	2017-06-14 19:01:19	1	-1	1	-1	-1	1	2017-06-14 19:01:19	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
82	Forres	\N	77	59	f	\N	2014-06-03 06:49:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
83	Adlib	\N	77	\N	f	\N	2014-06-17 15:18:17	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
84	Aberdeen	\N	77	\N	f	\N	2014-06-21 08:45:43	1	-1	1	-1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
84	Aberdeen	\N	77	\N	t	\N	2014-06-26 16:26:20	-1	-1	-1	-1	1	0	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
83	Adlib	\N	77	\N	t	\N	2014-06-26 16:26:20	-1	-1	-1	-1	1	0	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
127	St Agnes	\N	77	108	f	\N	2015-09-15 20:48:52	1	-1	1	1	-1	1	2015-09-15 20:48:52	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
29	Newcastle-upon-Tyne	\N	\N	\N	f	\N	2015-08-21 17:19:34	1	1	-2	-2	-1	0	2015-08-21 17:19:34	\N	-2	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
43	Stoke-on-Trent	\N	77	1	f	\N	2014-01-28 11:33:51	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
27	London	\N	77	1	f	\N	2014-01-28 11:33:50	1	-1	1	1	-1	1	\N	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
113	St Austell	\N	77	108	f	\N	2015-09-15 08:59:17	1	-1	1	1	-1	1	2015-09-15 08:59:17	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
119	Bude	\N	77	108	f	\N	2015-09-15 09:04:36	1	-1	1	1	-1	1	2015-09-15 09:04:36	\N	1	\N	\N	\N	\N	\N	\N	-1	-1	-1	-1
\.


--
-- Data for Name: area_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.area_information (id, site_id, slug, title, description, country_id, parent_area_id, is_deleted, cache_area_has_parent_generated, cached_future_events, cached_max_lat, cached_max_lng, cached_min_lat, cached_min_lng, created_at, approved_at, is_duplicate_of_id, slug_human, max_lat, max_lng, min_lat, min_lng, cached_updated_at) FROM stdin;
83	1	83	Adlib	\N	77	\N	t	t	0	\N	\N	\N	\N	2014-06-17 15:18:17	\N	\N	adlib	\N	\N	\N	\N	\N
54	1	54	Armagh	\N	77	53	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	armagh	\N	\N	\N	\N	\N
71	1	71	Bangor	\N	77	70	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:52	\N	\N	bangor	\N	\N	\N	\N	\N
2	1	2	Bath	\N	77	1	f	t	0	51.3834991	-2.35898995	51.3834991	-2.35898995	2014-01-28 11:33:50	\N	\N	bath	\N	\N	\N	\N	\N
55	1	55	Belfast	\N	77	53	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	belfast	\N	\N	\N	\N	\N
133	1	118	Berlin	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:52:11	2016-11-23 12:52:11	\N	berlin	52.676384	13.7054443	52.3554726	13.0819702	2016-11-23 22:09:44
3	1	3	Birmingham	\N	77	1	f	t	0	52.4790993	-1.86266994	52.4746017	-1.90777004	2014-01-28 11:33:50	\N	\N	birmingham	\N	\N	\N	\N	\N
78	1	78	Blackpool	\N	77	1	f	t	0	53.8129997	-3.03827	53.8129997	-3.03827	2014-03-11 21:55:50	\N	\N	blackpool	\N	\N	\N	\N	\N
115	1	100	Bodmin	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:03:29	2015-09-15 09:03:29	\N	bodmin	\N	\N	\N	\N	\N
4	1	4	Bradford	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	bradford	\N	\N	\N	\N	\N
5	1	5	Brighton & Hove	\N	77	1	f	t	0	50.8208008	-0.141412005	50.8208008	-0.141412005	2014-01-28 11:33:50	\N	\N	brighton-hove	50.8553772	-0.0926971436	50.802681	-0.233459473	2016-09-29 09:50:31
6	1	6	Bristol	\N	77	1	f	t	0	51.4560013	-2.59329009	51.4511986	-2.60310006	2014-01-28 11:33:50	\N	\N	bristol	51.5668259	-2.46643066	51.3700676	-2.73696899	2016-09-29 09:51:38
119	1	104	Bude	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:04:36	2015-09-15 09:04:36	\N	bude	\N	\N	\N	\N	\N
111	1	96	Camborne	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 08:58:44	2015-09-15 08:58:44	\N	camborne	\N	\N	\N	\N	\N
7	1	7	Cambridge	\N	77	1	f	t	0	52.2282982	0.155390993	52.1938019	0.101373002	2014-01-28 11:33:50	\N	\N	cambridge	\N	\N	\N	\N	\N
8	1	8	Canterbury	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	canterbury	\N	\N	\N	\N	\N
72	1	72	Cardiff	\N	77	70	f	t	0	51.4757004	-3.16610003	51.4757004	-3.16610003	2014-01-28 11:33:52	\N	\N	cardiff	\N	\N	\N	\N	\N
9	1	9	Carlisle	\N	77	1	f	t	0	54.8950996	-2.93811011	54.8950996	-2.93811011	2014-01-28 11:33:50	\N	\N	carlisle	\N	\N	\N	\N	\N
125	1	110	Carn Brea	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 20:39:21	2015-09-15 20:39:21	\N	carn-brea	\N	\N	\N	\N	\N
10	1	10	Chelmsford	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	chelmsford	\N	\N	\N	\N	\N
11	1	11	Chester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	chester	\N	\N	\N	\N	\N
12	1	12	Chichester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	chichester	\N	\N	\N	\N	\N
108	1	93	Cornwall	\N	77	1	f	t	0	50.4538002	-4.46294022	50.1679993	-5.12489986	2015-09-15 08:44:46	2015-09-15 08:44:46	\N	cornwall	\N	\N	\N	\N	\N
13	1	13	Coventry	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	coventry	\N	\N	\N	\N	\N
61	1	61	Dundee	\N	77	59	f	t	0	56.4632988	-2.97289991	56.4553986	-2.98224998	2014-01-28 11:33:51	\N	\N	dundee	56.4882774	-2.89764404	56.4340286	-3.09127808	2016-09-23 08:19:12
146	1	131	Düsseldorf	\N	57	\N	f	t	0	\N	\N	\N	\N	2017-11-28 08:35:55	2017-11-28 08:35:55	\N	dusseldorf	\N	\N	\N	\N	\N
138	1	123	France	\N	77	\N	t	t	0	\N	\N	\N	\N	2017-06-14 19:01:19	2017-06-14 19:01:19	\N	france	\N	\N	\N	\N	2017-06-14 19:47:17
18	1	18	Gloucester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	gloucester	\N	\N	\N	\N	\N
134	1	119	Hamburg	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:52:17	2016-11-23 12:52:17	\N	hamburg	53.7308426	10.3244019	53.373497	9.65698242	2016-11-23 22:10:32
122	1	107	Hayle	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:05:25	2015-09-15 09:05:25	\N	hayle	\N	\N	\N	\N	\N
91	1	91	Isle of Man	\N	77	\N	t	t	0	\N	\N	\N	\N	2015-04-12 21:47:26	2015-04-12 21:47:26	\N	isle-of-man	54.4253235	-4.31488037	54.0351982	-4.85595703	2017-07-03 20:34:38
132	1	117	Kassel	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:51:29	2016-11-23 12:51:29	\N	kassel	51.3477707	9.58007812	51.2490234	9.36859131	2016-11-23 22:09:15
20	1	20	Kingston-upon-Hull	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	kingston-upon-hull	\N	\N	\N	\N	\N
131	1	116	Kirkcaldy	\N	77	59	f	t	0	\N	\N	\N	\N	2016-11-21 07:34:51	2016-11-21 07:34:51	\N	kirkcaldy	\N	\N	\N	\N	\N
21	1	21	Lancaster	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	lancaster	\N	\N	\N	\N	\N
121	1	106	Launceston	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:05:07	2015-09-15 09:05:07	\N	launceston	\N	\N	\N	\N	\N
23	1	23	Leicester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	leicester	52.6747169	-1.05743408	52.5621605	-1.23596191	2016-11-10 08:34:38
24	1	24	Lichfield	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	lichfield	\N	\N	\N	\N	\N
25	1	25	Lincoln	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	lincoln	\N	\N	\N	\N	\N
147	1	132	Linlithgow	\N	77	59	f	t	0	\N	\N	\N	\N	2018-01-29 19:38:05	2018-01-29 19:38:05	\N	linlithgow	\N	\N	\N	\N	\N
120	1	105	Liskeard	\N	77	108	f	t	0	50.4538002	-4.46294022	50.4538002	-4.46294022	2015-09-15 09:04:51	2015-09-15 09:04:51	\N	liskeard	\N	\N	\N	\N	\N
26	1	26	Liverpool	\N	77	1	f	t	0	53.4173012	-2.89584994	53.4026985	-2.98907995	2014-01-28 11:33:50	\N	\N	liverpool	\N	\N	\N	\N	\N
27	1	27	London	\N	77	1	f	t	0	51.5317001	0.0287440997	51.4938011	-0.208960995	2014-01-28 11:33:50	\N	\N	london	51.7202225	0.302124023	51.2103233	-0.55480957	2016-09-23 08:23:30
57	1	57	Londonderry	\N	77	53	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	londonderry	\N	\N	\N	\N	\N
28	1	28	Manchester	\N	77	1	f	t	0	53.4930992	-2.23161006	53.4690018	-2.51018	2014-01-28 11:33:50	\N	\N	manchester	53.5745697	-2.09838867	53.3620262	-2.43347168	2016-09-23 08:22:19
141	1	126	Marseille	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:27:18	2017-06-28 06:27:18	\N	marseille	\N	\N	\N	\N	\N
73	1	73	Newport	\N	77	70	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:52	\N	\N	newport	\N	\N	\N	\N	\N
112	1	97	Newquay	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 08:58:59	2015-09-15 08:58:59	\N	newquay	\N	\N	\N	\N	\N
58	1	58	Newry	\N	77	53	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	newry	\N	\N	\N	\N	\N
144	1	129	Nice	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:27:58	2017-06-28 06:27:58	\N	nice	\N	\N	\N	\N	\N
53	1	53	Northern Ireland	\N	77	\N	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	northern-ireland	55.3666267	-5.42724609	53.994854	-8.17382812	2016-09-23 08:20:07
30	1	30	Norwich	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	norwich	\N	\N	\N	\N	\N
31	1	31	Nottingham	\N	77	1	f	t	0	52.9552002	-1.14169002	52.9552002	-1.14169002	2014-01-28 11:33:51	\N	\N	nottingham	53.0089989	-1.0546875	52.8774185	-1.25656128	2016-09-23 08:24:53
77	1	77	Paisley	\N	77	59	f	t	0	55.844101	-364.431	55.844101	-364.431	2014-03-02 14:03:41	\N	\N	paisley	55.8652954	-4.38766479	55.8244286	-4.48242188	2016-09-23 08:11:12
139	1	124	Paris	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:25:56	2017-06-28 06:25:56	\N	paris	\N	\N	\N	\N	\N
123	1	108	Penryn	\N	77	108	f	t	0	50.1679993	-5.12489986	50.1679993	-5.12489986	2015-09-15 20:37:46	2015-09-15 20:37:46	\N	penryn	\N	\N	\N	\N	\N
110	1	95	Penzance	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 08:58:30	2015-09-15 08:58:30	\N	penzance	\N	\N	\N	\N	\N
67	1	67	Perth	\N	77	59	f	t	0	56.4056015	-3.46060991	56.3839989	-3.94310999	2014-01-28 11:33:51	\N	\N	perth	56.4340286	-3.35906982	56.3447075	-3.53416443	2016-09-23 08:11:43
33	1	33	Peterborough	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	peterborough	\N	\N	\N	\N	\N
36	1	36	Preston	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	preston	\N	\N	\N	\N	\N
37	1	37	Ripon	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	ripon	\N	\N	\N	\N	\N
89	1	89	Southend-on-Sea	\N	77	1	f	t	0	51.5479012	0.710673988	51.5479012	0.710673988	2014-11-02 07:22:41	2014-11-02 07:22:41	\N	southend-on-sea	\N	\N	\N	\N	\N
127	1	112	St Agnes	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 20:48:52	2015-09-15 20:48:52	\N	st-agnes	\N	\N	\N	\N	\N
42	1	42	St Albans	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	st-albans	\N	\N	\N	\N	\N
44	1	44	Sunderland	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	sunderland	\N	\N	\N	\N	\N
76	1	76	Swansea	\N	77	70	f	t	0	51.6194	-3.93969989	51.6194	-3.93969989	2014-01-28 11:33:52	\N	\N	swansea	\N	\N	\N	\N	\N
80	1	80	The Riverdale Centre	\N	77	\N	t	t	0	\N	\N	\N	\N	2014-04-24 22:16:52	\N	\N	the-riverdale-centre	\N	\N	\N	\N	\N
126	1	111	Torpoint	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 20:39:37	2015-09-15 20:39:37	\N	torpoint	\N	\N	\N	\N	\N
124	1	109	Treverbyn	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 20:38:52	2015-09-15 20:38:52	\N	treverbyn	\N	\N	\N	\N	\N
84	1	84	Aberdeen	\N	77	\N	t	t	0	\N	\N	\N	\N	2014-06-21 08:45:43	\N	\N	aberdeen	\N	\N	\N	\N	\N
90	1	90	Bournemouth	\N	77	1	f	t	0	50.7226982	-1.86410999	50.7219009	-1.88063002	2014-12-02 11:46:12	2014-12-02 11:46:12	\N	bournemouth	\N	\N	\N	\N	\N
136	1	121	Cologne	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:52:30	2016-11-23 12:52:30	\N	cologne	51.037075	7.0930481	50.8415031	6.81427002	2016-11-23 22:12:46
14	1	14	Derby	\N	77	1	f	t	0	52.7868996	-1.65272999	52.7868996	-1.65272999	2014-01-28 11:33:50	\N	\N	derby	\N	\N	\N	\N	\N
140	1	125	Dijon	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:26:12	2017-06-28 06:26:12	\N	dijon	\N	\N	\N	\N	\N
15	1	15	Durham	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	durham	\N	\N	\N	\N	\N
62	1	62	Edinburgh	\N	77	59	f	t	1	55.9963989	-2.55338001	55.9179993	-3.39502001	2014-01-28 11:33:51	\N	\N	edinburgh	55.9983826	-3.04595947	55.8737717	-3.3480835	2016-09-23 08:18:00
63	1	63	Elgin	\N	77	59	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	elgin	57.6692772	-3.26087952	57.6167259	-3.37417603	2016-09-23 08:17:32
85	1	85	Edinburgh	\N	77	\N	t	t	0	\N	\N	\N	\N	2014-07-17 14:14:19	2014-07-17 14:14:19	\N	edinburgh	\N	\N	\N	\N	\N
16	1	16	Ely	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	ely	\N	\N	\N	\N	\N
1	1	1	England	\N	77	\N	f	t	0	54.9729004	1.32114995	50.1679993	-5.12489986	2014-01-28 11:33:50	\N	\N	england	55.5286293	1.84570312	49.8379822	-6.328125	2016-09-23 08:20:58
17	1	17	Exeter	\N	77	1	f	t	0	50.7246017	-3.52930999	50.7159004	-3.53833008	2014-01-28 11:33:50	\N	\N	exeter	50.7462311	-3.48438263	50.6812325	-3.5684967	2016-09-23 08:27:06
64	1	64	Falkirk	\N	77	59	f	t	0	56.0078011	-3.57862997	55.9855995	-3.78426003	2014-01-28 11:33:51	\N	\N	falkirk	56.0256004	-3.71200991	55.9772987	-3.84108996	2016-11-23 00:09:36
109	1	94	Falmouth	\N	77	108	f	t	0	50.1699982	-5.12465	50.1699982	-5.12465	2015-09-15 08:58:15	2015-09-15 08:58:15	\N	falmouth	\N	\N	\N	\N	\N
81	1	81	Forgue	\N	77	59	f	t	0	\N	\N	\N	\N	2014-05-22 12:44:50	\N	\N	forgue	57.4883385	-2.61611938	57.478466	-2.6374054	2016-09-23 08:14:59
82	1	82	Forres	\N	77	59	f	t	0	57.6141014	-3.60686994	57.6141014	-3.60686994	2014-06-03 06:49:51	\N	\N	forres	57.6380501	-3.52935791	57.5512085	-3.74633789	2016-09-23 08:09:46
137	1	122	Frankfurt	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:52:37	2016-11-23 12:52:37	\N	frankfurt	50.2243652	8.84674072	49.945919	8.44848633	2016-11-23 22:11:00
65	1	65	Glasgow	\N	77	59	f	t	0	55.8762016	-4.23325014	55.8515015	-4.31970978	2014-01-28 11:33:51	\N	\N	glasgow	55.9615021	-4.0045166	55.668293	-4.57580566	2016-09-23 08:10:07
87	1	87	Hebden Bridge	\N	77	1	f	t	0	53.7436981	-2.00890994	53.7422981	-2.01165009	2014-07-29 19:58:56	2014-07-29 19:58:56	\N	hebden-bridge	\N	\N	\N	\N	\N
118	1	103	Helston	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:04:18	2015-09-15 09:04:18	\N	helston	\N	\N	\N	\N	\N
86	1	86	Hebden Bridge	\N	77	1	t	t	0	\N	\N	\N	\N	2014-07-27 12:34:42	2014-07-27 12:34:42	\N	hebden-bridge	\N	\N	\N	\N	\N
19	1	19	Hereford	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:50	\N	\N	hereford	\N	\N	\N	\N	\N
66	1	66	Inverness	\N	77	59	f	t	0	57.4807014	-4.18289995	57.4747009	-4.22707987	2014-01-28 11:33:51	\N	\N	inverness	57.5132408	-4.14527893	57.4312744	-4.33410645	2016-09-23 08:10:39
92	1	92	Ipswich	\N	77	1	f	t	0	52.0582008	1.32114995	51.9958992	1.15832996	2015-05-22 17:04:42	2015-05-22 17:04:42	\N	ipswich	52.087101	1.21398926	52.0098228	1.06636047	2016-09-23 08:26:04
22	1	22	Leeds	\N	77	1	f	t	0	53.8002014	-1.53239	53.7905006	-1.55762994	2014-01-28 11:33:50	\N	\N	leeds	53.8411827	-1.4515686	53.7519608	-1.61224365	2016-09-23 08:23:57
56	1	56	Lisburn	\N	77	53	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	lisburn	\N	\N	\N	\N	\N
142	1	127	Lyon	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:27:33	2017-06-28 06:27:33	\N	lyon	\N	\N	\N	\N	\N
135	1	120	Munich	\N	57	\N	f	t	0	\N	\N	\N	\N	2016-11-23 12:52:24	2016-11-23 12:52:24	\N	munich	48.246624	11.8048096	47.9880829	11.292572	2016-11-23 22:10:12
145	1	130	Nantes	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:28:09	2017-06-28 06:28:09	\N	nantes	\N	\N	\N	\N	\N
29	1	29	Newcastle-upon-Tyne	\N	77	1	f	t	0	54.9729004	-1.60669994	54.966301	-1.62083995	2014-01-28 11:33:50	\N	\N	newcastle-upon-tyne	\N	\N	\N	\N	\N
79	1	79	Oslo	\N	167	\N	f	t	0	\N	\N	\N	\N	2014-04-23 22:19:40	\N	\N	oslo	\N	\N	\N	\N	\N
32	1	32	Oxford	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	oxford	\N	\N	\N	\N	\N
34	1	34	Plymouth	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	plymouth	\N	\N	\N	\N	\N
35	1	35	Portsmouth	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	portsmouth	50.8536415	-1.016922	50.7607841	-1.1769104	2016-09-23 08:24:25
88	1	88	Reading	Thames Valley	77	1	f	t	0	\N	\N	\N	\N	2014-09-04 18:34:41	2014-09-04 18:34:41	\N	reading	\N	\N	\N	\N	\N
116	1	101	Redruth	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:03:46	2015-09-15 09:03:46	\N	redruth	\N	\N	\N	\N	\N
38	1	38	Salford	\N	77	1	f	t	0	53.472599	-2.29823995	53.472599	-2.29823995	2014-01-28 11:33:51	\N	\N	salford	\N	\N	\N	\N	\N
39	1	39	Salisbury	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	salisbury	\N	\N	\N	\N	\N
114	1	99	Saltash	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 08:59:35	2015-09-15 08:59:35	\N	saltash	\N	\N	\N	\N	\N
59	1	59	Scotland	\N	77	\N	f	t	1	57.6141014	-2.08150005	55.844101	-364.431	2014-01-28 11:33:51	\N	\N	scotland	61.0582848	0	54.6865349	-7.84423828	2016-09-23 08:08:15
40	1	40	Sheffield	\N	77	1	f	t	0	53.5549011	-1.28772998	53.2377014	-1.50624001	2014-01-28 11:33:51	\N	\N	sheffield	53.4381752	-1.34307861	53.3111839	-1.54632568	2016-09-23 08:23:07
41	1	41	Southampton	\N	77	1	f	t	0	50.9129982	-1.42633998	50.9129982	-1.42633998	2014-01-28 11:33:51	\N	\N	southampton	50.9627533	-1.34101868	50.8748779	-1.52503967	2016-11-19 21:28:32
68	1	68	St. Andrews	\N	77	59	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	st-andrews	56.3565025	-2.76031494	56.3157768	-2.84751892	2016-09-23 08:12:25
74	1	74	St Asaph	\N	77	70	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:52	\N	\N	st-asaph	\N	\N	\N	\N	\N
113	1	98	St Austell	\N	77	108	f	t	0	50.3590012	-4.74174023	50.3590012	-4.74174023	2015-09-15 08:59:17	2015-09-15 08:59:17	\N	st-austell	\N	\N	\N	\N	\N
75	1	75	St David's	\N	77	70	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:52	\N	\N	st-davids	\N	\N	\N	\N	\N
69	1	69	Stirling	\N	77	59	f	t	0	56.1492996	-3.9254601	56.1198006	-3.93400002	2014-01-28 11:33:52	\N	\N	stirling	56.1577873	-3.87268066	56.0620689	-4.00314331	2016-09-23 08:09:19
117	1	102	St Ives	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 09:04:00	2015-09-15 09:04:00	\N	st-ives	\N	\N	\N	\N	\N
43	1	43	Stoke-on-Trent	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	stoke-on-trent	\N	\N	\N	\N	\N
128	1	113	St Stephen-in-Brannel	\N	77	108	f	t	0	\N	\N	\N	\N	2015-09-15 20:49:05	2015-09-15 20:49:05	\N	st-stephen-in-brannel	\N	\N	\N	\N	\N
143	1	128	Toulouse	\N	75	\N	f	t	0	\N	\N	\N	\N	2017-06-28 06:27:44	2017-06-28 06:27:44	\N	toulouse	\N	\N	\N	\N	\N
45	1	45	Truro	\N	77	108	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	truro	\N	\N	\N	\N	\N
46	1	46	Wakefield	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	wakefield	\N	\N	\N	\N	\N
70	1	70	Wales	\N	77	\N	f	t	0	51.6194	-3.16610003	51.4757004	-3.93969989	2014-01-28 11:33:52	\N	\N	wales	53.514183	-2.74658203	51.3031464	-5.42724609	2016-09-23 08:20:26
47	1	47	Wells	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	wells	\N	\N	\N	\N	\N
48	1	48	Westminster	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	westminster	\N	\N	\N	\N	\N
49	1	49	Winchester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	winchester	\N	\N	\N	\N	\N
50	1	50	Wolverhampton	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	wolverhampton	\N	\N	\N	\N	\N
51	1	51	Worcester	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	worcester	\N	\N	\N	\N	\N
52	1	52	York	\N	77	1	f	t	0	\N	\N	\N	\N	2014-01-28 11:33:51	\N	\N	york	\N	\N	\N	\N	\N
129	1	114		\N	77	\N	t	t	0	\N	\N	\N	\N	2016-10-13 08:26:45	2016-10-13 08:26:45	\N	huddersfield	\N	\N	\N	\N	2016-10-13 09:51:58
60	1	60	Aberdeen	\N	77	59	f	t	0	57.1655006	-2.08150005	57.1185989	-2.14240003	2014-01-28 11:33:51	\N	\N	aberdeen	57.209198	-1.98303223	57.0720978	-2.23297119	2016-09-23 08:19:35
149	1	134	Blackburn	\N	77	1	f	t	0	\N	\N	\N	\N	2018-09-17 13:33:17	2018-09-17 13:33:17	\N	blackburn	\N	\N	\N	\N	\N
148	1	133	Glossop	\N	77	1	f	t	0	\N	\N	\N	\N	2018-09-11 13:01:02	2018-09-11 13:01:02	\N	glossop	\N	\N	\N	\N	\N
130	1	115	Huddersfield	'Huddersfield is a large market town and is the largest settlement in the metropolitan borough of Kirklees, West Yorkshire, England. It is the 11th largest town in the United Kingdom with a population of 162,949 (2011 census). Halfway between Leeds and Manchester, it lies 190 miles (310 km) north of London, and 10.3 miles (16.6 km) south of Bradford, the nearest city.'\r\n- Wikipedia, Oct 2016	77	1	f	t	0	\N	\N	\N	\N	2016-10-13 08:33:51	2016-10-13 08:33:51	\N	huddersfield	53.6739006	-1.71112001	53.5778008	-1.85394001	2016-10-13 08:36:01
\.


--
-- Name: area_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.area_information_id_seq', 149, true);


--
-- Data for Name: cached_area_has_parent; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.cached_area_has_parent (area_id, has_parent_area_id) FROM stdin;
109	108
109	1
110	108
110	1
111	108
111	1
112	108
112	1
113	108
113	1
114	108
114	1
115	108
115	1
116	108
116	1
117	108
117	1
118	108
118	1
119	108
119	1
120	108
120	1
121	108
121	1
122	108
122	1
60	59
54	53
71	70
2	1
55	53
3	1
78	1
90	1
4	1
5	1
6	1
7	1
8	1
72	70
9	1
10	1
11	1
12	1
108	1
13	1
14	1
61	59
15	1
62	59
63	59
16	1
17	1
64	59
81	59
82	59
65	59
18	1
87	1
86	1
19	1
66	59
92	1
20	1
21	1
22	1
23	1
24	1
25	1
56	53
26	1
27	1
57	53
28	1
29	1
73	70
58	53
30	1
31	1
32	1
77	59
67	59
33	1
34	1
35	1
36	1
88	1
37	1
38	1
39	1
40	1
41	1
89	1
42	1
68	59
74	70
75	70
69	59
43	1
44	1
76	70
45	108
45	1
46	1
47	1
48	1
49	1
50	1
51	1
52	1
123	108
123	1
124	108
124	1
125	108
125	1
126	108
126	1
127	108
127	1
128	108
128	1
130	1
131	59
147	59
148	1
149	1
\.


--
-- Data for Name: contact_support; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.contact_support (id, subject, message, email, user_account_id, ip, browser, created_at, is_spam_manually_detected, is_spam_honeypot_field_detected) FROM stdin;
\.


--
-- Name: contact_support_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.contact_support_id_seq', 474, true);


--
-- Data for Name: country; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.country (id, two_char_code, title, timezones, max_lat, max_lng, min_lat, min_lng, address_code_label) FROM stdin;
5	AI	Anguilla	America/Anguilla	18.6000004	-62.9166679	18.1499996	-63.4333344	Postcode
7	AM	Armenia	Asia/Yerevan	41.2999992	46.5605545	38.8941689	43.4425011	Postcode
8	AO	Angola	Africa/Luanda	-4.4000001	23.9833336	-32	10	Postcode
11	AS	Samoa (American)	Pacific/Pago_Pago	\N	\N	\N	\N	Postcode
14	AW	Aruba	America/Aruba	12.6166668	-69.8499985	12.416667	-70.0666656	Postcode
15	AX	Aaland Islands	Europe/Mariehamn	\N	\N	\N	\N	Postcode
17	BA	Bosnia & Herzegovina	Europe/Sarajevo	45.2683334	19.6183338	42.5580559	15.7472219	Postcode
18	BB	Barbados	America/Barbados	13.333333	-59.4166679	13.0333328	-59.6500015	Postcode
19	BD	Bangladesh	Asia/Dhaka	26.5	92.6833344	20.6000004	84	Postcode
20	BE	Belgium	Europe/Brussels	51.6833344	6.4000001	49.5166664	2.56666708	Postcode
21	BF	Burkina Faso	Africa/Ouagadougou	14.9833326	2.26550007	9.44999981	-5.46666718	Postcode
22	BG	Bulgaria	Europe/Sofia	44.1936111	28.6000004	41	22.3713894	Postcode
23	BH	Bahrain	Asia/Bahrain	26.4166679	50.8233337	25	45	Postcode
24	BI	Burundi	Africa/Bujumbura	-2.34249997	30.8313885	-4.44333315	29.0238895	Postcode
25	BJ	Benin	Africa/Porto-Novo	12.3613997	3.81666708	6.23333311	-4	Postcode
26	BL	St Barthelemy	America/St_Barthelemy	\N	\N	\N	\N	Postcode
27	BM	Bermuda	Atlantic/Bermuda	32.3905563	-64.6333313	32.2469444	-64.8827744	Postcode
28	BN	Brunei	Asia/Brunei	5.05000019	120	-2	110	Postcode
29	BO	Bolivia	America/La_Paz	-9.66666698	-57.5666656	-22.8833332	-69.5999985	Postcode
32	BS	Bahamas	America/Nassau	29.375	-70	20	-80.4833298	Postcode
33	BT	Bhutan	Asia/Thimphu	30	92.0333328	26.7166672	80	Postcode
34	BV	Bouvet Island		-54.3861122	3.43388891	-54.4527779	3.28527808	Postcode
35	BW	Botswana	Africa/Gaborone	-17.8333321	29.0166664	-26.8333321	20	Postcode
36	BY	Belarus	Europe/Minsk	56.0666656	32.7080574	50.7166672	22.5499992	Postcode
37	BZ	Belize	America/Belize	18.4833336	-87.4833298	15.8999996	-89.2169418	Postcode
41	CF	Central African Rep.	Africa/Bangui	10.6999998	27.2166672	2.43333292	14.5333328	Postcode
42	CG	Congo (Rep.)	Africa/Brazzaville	3.86666703	20	-4.99555588	11.166667	Postcode
43	CH	Switzerland	Europe/Zurich	49.8666687	10.5	45.3666687	6	Postcode
44	CI	Cote d'Ivoire	Africa/Abidjan	10.6522217	-2.56666708	4.3499999	-8.53888893	Postcode
45	CK	Cook Islands	Pacific/Rarotonga	-8.91861057	-157.337494	-21.9530563	-171.78334	Postcode
53	CW	Curacao	America/Curacao	\N	\N	\N	\N	Postcode
54	CX	Christmas Island	Indian/Christmas	-10.3999996	105.75	-10.5666666	105.566666	Postcode
55	CY	Cyprus	Asia/Nicosia	35.7000008	34.5999985	34.5666656	32.2708321	Postcode
56	CZ	Czech Republic	Europe/Prague	59.6500015	25.5	40.6500015	12.1166668	Postcode
58	DJ	Djibouti	Africa/Djibouti	13	43.4519424	10.9825001	41	Postcode
59	DK	Denmark	Europe/Copenhagen	64	18	53.5833321	4.51666689	Postcode
60	DM	Dominica	America/Dominica	15.6333332	-61.25	15.1999998	-61.4833336	Postcode
61	DO	Dominican Republic	America/Santo_Domingo	19.9333324	-68.3166656	17.4730568	-71.9666672	Postcode
62	DZ	Algeria	Africa/Algiers	37.1166687	13	19	-8.66666698	Postcode
63	EC	Ecuador	America/Guayaquil,Pacific/Galapagos	1.64999998	-75.2166672	-4.94999981	-92	Postcode
64	EE	Estonia	Europe/Tallinn	59.9833336	28.8833332	57.521389	21.7958336	Postcode
65	EG	Egypt	Africa/Cairo	31.9166679	36.3333321	20.3833332	24.7000008	Postcode
66	EH	Western Sahara	Africa/El_Aaiun	27.6666679	-8.66666698	20.7999992	-17.1105556	Postcode
67	ER	Eritrea	Africa/Asmara	18.0333328	43.1147232	12.3833332	36.4833336	Postcode
69	ET	Ethiopia	Africa/Addis_Ababa	14.6988888	47.4500008	3.43333292	33.0333328	Postcode
70	FI	Finland	Europe/Helsinki	70.0833359	32	58.8300018	18	Postcode
71	FJ	Fiji	Pacific/Fiji	-12.4666672	-179.983337	-21.0166664	180	Postcode
72	FK	Falkland Islands	Atlantic/Stanley	-50.9666672	-57.6666679	-52.9666672	-61.4333344	Postcode
74	FO	Faroe Islands	Atlantic/Faroe	62.4000015	-6.25	61.3333321	-7.80000019	Postcode
75	FR	France	Europe/Paris	51.0833321	9.53333282	41.3333321	-5.13333321	Postcode
77	GB	United Kingdom	Europe/London	61.5	2.86666703	49.8666687	-13.6499996	Postcode
78	GD	Grenada	America/Grenada	12.666667	-61.25	11.9833326	-61.7999992	Postcode
79	GE	Georgia	Asia/Tbilisi	43.5705566	46.6355553	41.1500015	40.0130577	Postcode
82	GH	Ghana	Africa/Accra	11.1499996	1.19277799	4.73333311	-4	Postcode
85	GM	Gambia	Africa/Banjul	13.8166666	-4	7	-16.8169441	Postcode
86	GN	Guinea	Africa/Conakry	12.6333332	-4	7	-15.3666668	Postcode
87	GP	Guadeloupe	America/Guadeloupe	18.1166668	-61	15	-63.1500015	Postcode
89	GR	Greece	Europe/Athens	44	29.648056	34.7999992	19.3816662	Postcode
91	GT	Guatemala	America/Guatemala	17.8166676	-87.0500031	13.751111	-92.5833359	Postcode
97	HN	Honduras	America/Tegucigalpa	17.4500008	-82.5	13.0166674	-89.3333359	Postcode
98	HR	Croatia	Europe/Zagreb	46.5269432	19.3830566	42.3802795	13.4933329	Postcode
104	IM	Isle of Man	Europe/Isle_of_Man	54.4000015	-4.31666708	54.0333328	-4.83333302	Postcode
105	IN	India	Asia/Kolkata	35.9558334	97.3499985	6.75555611	67.0166702	Postcode
107	IQ	Iraq	Asia/Baghdad	37.3527794	48.8333321	28.8666668	38.8008728	Postcode
109	IS	Iceland	Atlantic/Reykjavik	66.5666656	-13.1999998	63.2999992	-24.5333328	Postcode
110	IT	Italy	Europe/Rome	48.5333328	20.4333324	35.4833336	1.35000002	Postcode
113	JO	Jordan	Asia/Amman	33.0022202	38.8833313	29	34.9874992	Postcode
114	JP	Japan	Asia/Tokyo	45.5208321	154	20.4166679	122.933334	Postcode
122	KR	Korea (South)	Asia/Seoul	38.5866661	131.866669	33.1175003	124.612221	Postcode
123	KW	Kuwait	Asia/Kuwait	30.0694447	49.4105568	25	45	Postcode
126	LA	Laos	Asia/Vientiane	22.5	107.633331	13.9333334	100.095833	Postcode
127	LB	Lebanon	Asia/Beirut	34.6899986	36.5927773	33.0783348	35.103611	Postcode
130	LK	Sri Lanka	Asia/Colombo	9.83333302	81.8666687	5.91666698	79.5166702	Postcode
134	LU	Luxembourg	Europe/Luxembourg	50.1816673	6.50583315	49.4608345	5.74277782	Postcode
135	LV	Latvia	Europe/Riga	58.0666656	28.2000008	55.7000008	20.9666672	Postcode
136	LY	Libya	Africa/Tripoli	33.1500015	25.5	20.7999992	5	Postcode
138	MC	Monaco	Europe/Monaco	43.7458344	7.43944407	43.7166672	7.4000001	Postcode
139	MD	Moldova	Europe/Chisinau	48.4672203	30.0961113	45.4816666	26.6722221	Postcode
140	ME	Montenegro	Europe/Podgorica	43.5477791	20.3425007	41.8641663	18.4380569	Postcode
142	MG	Madagascar	Indian/Antananarivo	-11.9499998	50.4833336	-25.6000004	43.1833344	Postcode
144	MK	Macedonia	Europe/Skopje	42.3730545	23.0333328	40.8666687	20.4591675	Postcode
145	ML	Mali	Africa/Bamako	26	13	10.1499996	-12.5500002	Postcode
148	MO	Macau	Asia/Macau	22.2163887	113.592224	22.1124992	113.531387	Postcode
149	MP	Northern Mariana Islands	Pacific/Saipan	\N	\N	\N	\N	Postcode
151	MR	Mauritania	Africa/Nouakchott	26.8999996	13	14.7382736	-17.0794449	Postcode
48	CN	China	Asia/Shanghai,Asia/Harbin,Asia/Chongqing,Asia/Urumqi,Asia/Kashgar	20.7000008	117.816666	6.18333292	106.699997	Postcode
68	ES	Spain	Europe/Madrid,Africa/Ceuta,Atlantic/Canary	43.9166679	4.33333302	27.6333332	-18.1666679	Postcode
73	FM	Micronesia	Pacific/Chuuk,Pacific/Pohnpei,Pacific/Kosrae	10.0936108	163.034439	1.026389	137.425003	Postcode
80	GF	French Guiana	America/Cayenne	5.75	-51.6500015	2.16666698	-60	Postcode
81	GG	Guernsey	Europe/Guernsey	49.7333336	-2.15805602	49.4011116	-2.70000005	Postcode
100	HU	Hungary	Europe/Budapest	48.9833336	22.8666668	45.75	16.1833324	Postcode
101	ID	Indonesia	Asia/Jakarta,Asia/Pontianak,Asia/Makassar,Asia/Jayapura	10.6166668	141.016663	-11	94.9702759	Postcode
102	IE	Ireland	Europe/Dublin	55.4333344	-6.00250006	51.4255562	-10.6808329	Postcode
106	IO	British Indian Ocean Territory	Indian/Chagos	-5.23333311	72.4833298	-7.3499999	71.2652817	Postcode
108	IR	Iran	Asia/Tehran	39.7753983	62	25.0499992	27.4454994	Postcode
111	JE	Jersey	Europe/Jersey	49.3058319	-1.92777801	49.1127777	-2.25388908	Postcode
112	JM	Jamaica	America/Jamaica	18.5333328	-70	17	-78.3666687	Postcode
115	KE	Kenya	Africa/Nairobi	4.88333321	41.8583832	-4.71666718	27.4333324	Postcode
116	KG	Kyrgyzstan	Asia/Bishkek	43.0166664	80.1158295	39.25	69.3333359	Postcode
117	KH	Cambodia	Asia/Phnom_Penh	17.4833336	107.566666	9.91666698	102.35833	Postcode
118	KI	Kiribati	Pacific/Tarawa,Pacific/Enderbury,Pacific/Kiritimati	4.71666718	-174.53334	-10.3000002	179.71666	Postcode
119	KM	Comoros	Indian/Comoro	-11.3500004	45.3166656	-13	43.2261124	Postcode
120	KN	St Kitts & Nevis	America/St_Kitts	17.4166679	-62.5166664	17.1000004	-62.8499985	Postcode
121	KP	Korea (North)	Asia/Pyongyang	43.0038872	130.672226	37.6775017	124.1875	Postcode
124	KY	Cayman Islands	America/Cayman	19.75	-79.7166672	19.25	-81.4166641	Postcode
125	KZ	Kazakhstan	Asia/Almaty,Asia/Qyzylorda,Asia/Aqtobe,Asia/Aqtau,Asia/Oral	55.330555	90	40.4166679	46.5897217	Postcode
131	LR	Liberia	Africa/Monrovia	9.5	-4	4.3283329	-11.4722223	Postcode
132	LS	Lesotho	Africa/Maseru	-28.6166668	29.3166676	-30.6666679	24	Postcode
152	MS	Montserrat	America/Montserrat	16.8166676	-62.1500015	16.6666679	-62.2333336	Postcode
153	MT	Malta	Europe/Malta	36.0819435	14.5749998	35.7838898	14.1855564	Postcode
154	MU	Mauritius	Indian/Mauritius	-5.25	72.4666672	-20.5166664	56.5999985	Postcode
156	MW	Malawi	Africa/Blantyre	-5	37	-17.1499996	32.7166672	Postcode
159	MZ	Mozambique	Africa/Maputo	15.0333328	40.8452797	-26.8572216	30.2313881	Postcode
160	NA	Namibia	Africa/Windhoek	-16.9833336	25.25	-28.9333324	12.0166674	Postcode
161	NC	New Caledonia	Pacific/Noumea	-18.0166664	172.050003	-22.7833328	158.246674	Postcode
162	NE	Niger	Africa/Niamey	26	16	11.7166672	0.233333007	Postcode
163	NF	Norfolk Island	Pacific/Norfolk	-29.0499992	167.949997	-29.0499992	167.949997	Postcode
164	NG	Nigeria	Africa/Lagos	13.8666668	14.6499996	4.26666689	2.71666694	Postcode
165	NI	Nicaragua	America/Managua	15	-82.5666656	10.7166672	-87.684166	Postcode
166	NL	Netherlands	Europe/Amsterdam	53.5833321	7.19999981	50.75	3.13333297	Postcode
167	NO	Norway	Europe/Oslo	71.1819458	31.1666679	56.1500015	3.03333306	Postcode
168	NP	Nepal	Asia/Kathmandu	30.4500008	88.1833344	26.4500008	80	Postcode
170	NU	Niue	Pacific/Niue	-18.9333324	-169.78334	-19.1000004	-169.916672	Postcode
171	NZ	New Zealand	Pacific/Auckland,Pacific/Chatham	-29.2166672	-178.899994	-52.6166687	179.066666	Postcode
174	PE	Peru	America/Lima	4.62666702	-68.8333359	-18.3333321	-81.3583298	Postcode
176	PG	Papua New Guinea	Pacific/Port_Moresby	-0.733332992	159.483337	-11.6499996	120	Postcode
177	PH	Philippines	Asia/Manila	21.1130562	126.604446	4.58888912	116.650002	Postcode
178	PK	Pakistan	Asia/Karachi	37.0837097	77.8000107	23.9666672	60.8666687	Postcode
179	PL	Poland	Europe/Warsaw	54.8333321	26.5	45.5	14	Postcode
180	PM	St Pierre & Miquelon	America/Miquelon	47.1397209	-56.1205559	46.748333	-56.4052773	Postcode
181	PN	Pitcairn	Pacific/Pitcairn	-23.9166679	-124.783333	-25.0666676	-130.733337	Postcode
182	PR	Puerto Rico	America/Puerto_Rico	\N	\N	\N	\N	Postcode
183	PS	Palestine	Asia/Gaza,Asia/Hebron	\N	\N	\N	\N	Postcode
186	PY	Paraguay	America/Asuncion	-19.3333321	-54.3499985	-27.5333328	-62.6333313	Postcode
187	QA	Qatar	Asia/Qatar	26.4411106	52.75	24.2847214	50.6805573	Postcode
189	RO	Romania	Europe/Bucharest	48.25	29.6499996	43.6666679	19	Postcode
192	RW	Rwanda	Africa/Kigali	5	37	-2.79999995	28.8666668	Postcode
194	SB	Solomon Islands	Pacific/Guadalcanal	-5.16666698	170.199997	-12.8833332	155.516663	Postcode
195	SC	Seychelles	Indian/Mahe	-3.71666694	56.2666664	-10.2166672	46.2166672	Postcode
196	SD	Sudan	Africa/Khartoum	27.1666679	38.8333321	3.51666689	21.8833332	Postcode
197	SE	Sweden	Europe/Stockholm	69.0333328	25	46.7583313	10.958333	Postcode
198	SG	Singapore	Asia/Singapore	4	104.407501	1.15944397	102	Postcode
200	SI	Slovenia	Europe/Ljubljana	46.8666687	17.4666672	45.0833321	13.4266672	Postcode
201	SJ	Svalbard & Jan Mayen	Arctic/Longyearbyen	80.8166656	32.5833321	74.3499985	10.5	Postcode
202	SK	Slovakia	Europe/Bratislava	49.5999985	26.5	45.5	17	Postcode
203	SL	Sierra Leone	Africa/Freetown	10	-4	5	-13.3166666	Postcode
204	SM	San Marino	Europe/San_Marino	43.9666672	12.5	43.9083328	12.416667	Postcode
205	SN	Senegal	Africa/Dakar	16.6666679	-11.3780212	12.3366671	-17.6827774	Postcode
206	SO	Somalia	Africa/Mogadishu	11.9833326	51.4000015	-1.65942967	41	Postcode
207	SR	Suriname	America/Paramaribo	6	-53.9833336	2.0999999	-60	Postcode
208	SS	South Sudan	Africa/Juba	\N	\N	\N	\N	Postcode
210	SV	El Salvador	America/El_Salvador	14.4333334	-87.6572189	13.1586113	-90.1163864	Postcode
212	SY	Syria	Asia/Damascus	37.2802773	42.3377762	32	35.5999985	Postcode
213	SZ	Swaziland	Africa/Mbabane	-25.7833328	32.1333313	-27.3166676	30.7833328	Postcode
215	TD	Chad	Africa/Ndjamena	26	24	7.5	2	Postcode
218	TH	Thailand	Asia/Bangkok	20.4427776	105.76667	5.61666679	97.3666687	Postcode
219	TJ	Tajikistan	Asia/Dushanbe	40.9000015	75	36.7166672	67.4166641	Postcode
220	TK	Tokelau	Pacific/Fakaofo	-8.53333282	-171.183334	-9.4333334	-172.516663	Postcode
221	TL	East Timor	Asia/Dili	-7.59722185	127.33667	-9.46972179	124.085556	Postcode
222	TM	Turkmenistan	Asia/Ashgabat	42.5666656	66.6500015	35.2166672	52.5	Postcode
223	TN	Tunisia	Africa/Tunis	37.5666656	13	26	7	Postcode
224	TO	Tonga	Pacific/Tongatapu	-15.5666666	-150	-22.3333321	-176.199997	Postcode
225	TR	Turkey	Europe/Istanbul	42.0999985	44.7999992	35.8194427	25	Postcode
226	TT	Trinidad & Tobago	America/Port_of_Spain	20	-60.5	10.0333328	-74	Postcode
227	TV	Tuvalu	Pacific/Funafuti	-5.6500001	179.883331	-10.75	176.116669	Postcode
228	TW	Taiwan	Asia/Taipei	26.3894444	122.10778	21.7333336	118.115257	Postcode
1	AD	Andorra	Europe/Andorra	42.6500015	1.78333294	42.4333344	1.41666698	Postcode
2	AE	United Arab Emirates	Asia/Dubai	26.1333332	58	22.1666679	45	Postcode
4	AG	Antigua & Barbuda	America/Antigua	17.7333336	-61.6666679	16.9166679	-62.3333321	Postcode
6	AL	Albania	Europe/Tirane	42.6591682	21.0499992	39.6500015	19.2666664	Postcode
9	AQ	Antarctica	Antarctica/McMurdo,Antarctica/South_Pole,Antarctica/Rothera,Antarctica/Palmer,Antarctica/Mawson,Antarctica/Davis,Antarctica/Casey,Antarctica/Vostok,Antarctica/DumontDUrville,Antarctica/Syowa	\N	\N	\N	\N	Postcode
13	AU	Australia	Australia/Lord_Howe,Antarctica/Macquarie,Australia/Hobart,Australia/Currie,Australia/Melbourne,Australia/Sydney,Australia/Broken_Hill,Australia/Brisbane,Australia/Lindeman,Australia/Adelaide,Australia/Darwin,Australia/Perth,Australia/Eucla	-9.13333321	167.983337	-55.0499992	112.928886	Postcode
16	AZ	Azerbaijan	Asia/Baku	41.9105568	50.8583336	38.4166679	44.8763885	Postcode
30	BQ	Bonaire, St Eustatius & Saba	America/Kralendijk	\N	\N	\N	\N	Postcode
38	CA	Canada	America/St_Johns,America/Halifax,America/Glace_Bay,America/Moncton,America/Goose_Bay,America/Blanc-Sablon,America/Montreal,America/Toronto,America/Nipigon,America/Thunder_Bay,America/Iqaluit,America/Pangnirtung,America/Resolute,America/Atikokan,America/Rankin_Inlet,America/Winnipeg,America/Rainy_River,America/Regina,America/Swift_Current,America/Edmonton,America/Cambridge_Bay,America/Yellowknife,America/Inuvik,America/Creston,America/Dawson_Creek,America/Vancouver,America/Whitehorse,America/Dawson	83.1166687	-52.6666679	40	-141.666672	Postcode
40	CD	Congo (Dem. Rep.)	Africa/Kinshasa,Africa/Lubumbashi	5.13333321	31.2333336	-13.4666672	12.2666674	Postcode
46	CL	Chile	America/Santiago,Pacific/Easter	-17.5300007	-66.4333344	-56.5333328	-109.466667	Postcode
83	GI	Gibraltar	Europe/Gibraltar	36.1500015	-5.33333302	36.0999985	-5.3499999	Postcode
84	GL	Greenland	America/Godthab,America/Danmarkshavn,America/Scoresbysund,America/Thule	83.6666641	-12.1333332	51.7000008	-73.0500031	Postcode
88	GQ	Equatorial Guinea	Africa/Malabo	3.78333306	11.3999996	-1.48333299	5.05000019	Postcode
90	GS	South Georgia & the South Sandwich Islands	Atlantic/South_Georgia	-53.9702797	-26.3333321	-59.4666672	-38.3050003	Postcode
99	HT	Haiti	America/Port-au-Prince	20.0833321	-71.6333313	18.0166664	-74.4833298	Postcode
129	LI	Liechtenstein	Europe/Vaduz	47.2333336	9.75	47.0499992	9.5	Postcode
133	LT	Lithuania	Europe/Vilnius	56.4416656	27	53	21	Postcode
137	MA	Morocco	Africa/Casablanca	36.2099991	2	5.51000023	-13.1000004	Postcode
141	MF	St Martin (French part)	America/Marigot	\N	\N	\N	\N	Postcode
143	MH	Marshall Islands	Pacific/Majuro,Pacific/Kwajalein	19.3166676	172.800003	4.56666708	160.800003	Postcode
146	MM	Myanmar (Burma)	Asia/Rangoon	28.3500004	102	6	92.190834	Postcode
147	MN	Mongolia	Asia/Ulaanbaatar,Asia/Hovd,Asia/Choibalsan	52.0999985	119.916664	41.5499992	87.7833328	Postcode
150	MQ	Martinique	America/Martinique	14.8666668	-60.8166656	14.3833332	-61.9666672	Postcode
155	MV	Maldives	Indian/Maldives	7.0999999	73.6999969	-0.699999988	72.5833359	Postcode
157	MX	Mexico	America/Mexico_City,America/Cancun,America/Merida,America/Monterrey,America/Matamoros,America/Mazatlan,America/Chihuahua,America/Ojinaga,America/Hermosillo,America/Tijuana,America/Santa_Isabel,America/Bahia_Banderas	32.9833336	-86.7166672	14.5500002	-119.921669	Postcode
158	MY	Malaysia	Asia/Kuala_Lumpur,Asia/Kuching	7.38333321	120	0.850000024	99.6412735	Postcode
169	NR	Nauru	Pacific/Nauru	-0.5	166.949997	-0.550000012	166.916672	Postcode
172	OM	Oman	Asia/Muscat	26.5049992	59.8380547	16.6333332	45	Postcode
173	PA	Panama	America/Panama	9.64999962	-77.2833328	7.21333313	-82.9499969	Postcode
175	PF	French Polynesia	Pacific/Tahiti,Pacific/Marquesas,Pacific/Gambier	16.6333332	-179.800003	-27.9166679	180	Postcode
184	PT	Portugal	Europe/Lisbon,Atlantic/Madeira,Atlantic/Azores	42.1500015	-5	30.0333328	-31.2666664	Postcode
185	PW	Palau	Pacific/Palau	8.16666698	134.716385	2.89833307	131.175003	Postcode
188	RE	Reunion	Indian/Reunion	-20	57	-21.3666668	55.2166672	Postcode
190	RS	Serbia	Europe/Belgrade	46.1555557	22.9666672	41.8666687	18.9288883	Postcode
191	RU	Russia	Europe/Kaliningrad,Europe/Moscow,Europe/Volgograd,Europe/Samara,Asia/Yekaterinburg,Asia/Omsk,Asia/Novosibirsk,Asia/Novokuznetsk,Asia/Krasnoyarsk,Asia/Irkutsk,Asia/Yakutsk,Asia/Khandyga,Asia/Vladivostok,Asia/Sakhalin,Asia/Ust-Nera,Asia/Magadan,Asia/Kamchatka,Asia/Anadyr	86.2166672	147.172226	38.7000008	19.6555557	Postcode
193	SA	Saudi Arabia	Asia/Riyadh	32.2000008	55.1666679	5	34.5666656	Postcode
199	SH	St Helena	Atlantic/St_Helena	-7.9000001	-5.63333321	-40.4000015	-14.416667	Postcode
209	ST	Sao Tome & Principe	Africa/Sao_Tome	1.73333299	7.48333311	-0.0166669991	6.46666718	Postcode
211	SX	St Maarten (Dutch part)	America/Lower_Princes	\N	\N	\N	\N	Postcode
214	TC	Turks & Caicos Is	America/Grand_Turk	21.9500008	-71.0833359	21.1166668	-72.4666672	Postcode
216	TF	French Southern & Antarctic Lands	Indian/Kerguelen	-37.7833328	77.5999985	-50.0166664	50.2333336	Postcode
217	TG	Togo	Africa/Lome	11.1038895	1.81666696	6.13194418	-4	Postcode
230	UA	Ukraine	Europe/Kiev,Europe/Uzhgorod,Europe/Zaporozhye,Europe/Simferopol	63.4000015	68.8499985	37.7999992	20.9333324	Postcode
231	UG	Uganda	Africa/Kampala	4.16666698	34.9500008	-1.43333304	29.5833321	Postcode
232	UM	US minor outlying islands	Pacific/Johnston,Pacific/Midway,Pacific/Wake	\N	\N	\N	\N	Postcode
234	UY	Uruguay	America/Montevideo	-30.1833324	-53.2666664	-35.0333328	-58.5	Postcode
235	UZ	Uzbekistan	Asia/Samarkand,Asia/Tashkent	48.5833321	80.3833313	35.2666664	56.0833321	Postcode
236	VA	Vatican City	Europe/Vatican	41.9000015	12.4499998	41.9000015	12.4499998	Postcode
237	VC	St Vincent	America/St_Vincent	13.3666668	-61.1166687	12.5333328	-61.4333344	Postcode
238	VE	Venezuela	America/Caracas	15.6999998	-59.9666672	0.766667008	-73.1600037	Postcode
239	VG	Virgin Islands (UK)	America/Tortola	18.7666664	-64.2666702	18.2999992	-64.8499985	Postcode
240	VI	Virgin Islands (US)	America/St_Thomas	\N	\N	\N	\N	Postcode
241	VN	Vietnam	Asia/Ho_Chi_Minh	23.6666679	109.466667	8.38333321	102.216667	Postcode
242	VU	Vanuatu	Pacific/Efate	-13.0666666	170.21666	-20.25	166.016663	Postcode
243	WF	Wallis & Futuna	Pacific/Wallis	-13.1833334	-176.083328	-14.3500004	-178.183334	Postcode
244	WS	Samoa (western)	Pacific/Apia	-13.4333334	-171	-14.0500002	-172.816666	Postcode
245	YE	Yemen	Asia/Aden	27.6952782	54.5333328	12.1000004	41.8333321	Postcode
246	YT	Mayotte	Indian/Mayotte	-12.6413889	45.2886124	-12.9938889	45.0244446	Postcode
247	ZA	South Africa	Africa/Johannesburg	-22.1333332	32.8833313	-34.8333321	16.4666672	Postcode
248	ZM	Zambia	Africa/Lusaka	5	39.2833328	-18.0499992	22	Postcode
249	ZW	Zimbabwe	Africa/Harare	-15.6000004	33.0499992	-22.3166676	25.3333321	Postcode
3	AF	Afghanistan	Asia/Kabul	38.4836121	74.8868713	29.3833332	60.5666656	Postcode
10	AR	Argentina	America/Argentina/Buenos_Aires,America/Argentina/Cordoba,America/Argentina/Salta,America/Argentina/Jujuy,America/Argentina/Tucuman,America/Argentina/Catamarca,America/Argentina/La_Rioja,America/Argentina/San_Juan,America/Argentina/Mendoza,America/Argentina/San_Luis,America/Argentina/Rio_Gallegos,America/Argentina/Ushuaia	-21.7833328	-53.6500015	-58.1166687	-73.5333328	Postcode
12	AT	Austria	Europe/Vienna	49.0166664	19	46.3772202	1.20000005	Postcode
31	BR	Brazil	America/Noronha,America/Belem,America/Fortaleza,America/Recife,America/Araguaina,America/Maceio,America/Bahia,America/Sao_Paulo,America/Campo_Grande,America/Cuiaba,America/Santarem,America/Porto_Velho,America/Boa_Vista,America/Manaus,America/Eirunepe,America/Rio_Branco	5.26666689	-28.8500004	-33.7333336	-73.75	Postcode
39	CC	Cocos (Keeling) Islands	Indian/Cocos	-11.833333	96.9180527	-12.2041674	96.8166656	Postcode
47	CM	Cameroon	Africa/Douala	16	16	2.01666689	8.48333263	Postcode
49	CO	Colombia	America/Bogota	13.3833332	-66.8547211	-4.21472216	-81.8499985	Postcode
50	CR	Costa Rica	America/Costa_Rica	11.2166672	-82.0500031	5.5	-87.0999985	Postcode
51	CU	Cuba	America/Havana	23.2658329	-74.1350021	19.8280563	-84.9508362	Postcode
52	CV	Cape Verde	Atlantic/Cape_Verde	17.2000008	-22.6666679	14.8000002	-25.3666668	Postcode
57	DE	Germany	Europe/Berlin,Europe/Busingen	55.0499992	15.0333328	47.2666664	5.9000001	Postcode
76	GA	Gabon	Africa/Libreville	2.28333306	14.4833326	-3.9000001	8.69999981	Postcode
92	GU	Guam	Pacific/Guam	\N	\N	\N	\N	Postcode
93	GW	Guinea-Bissau	Africa/Bissau	12.6833334	-4	5	-16.6519432	Postcode
94	GY	Guyana	America/Guyana	8.4333334	-56	1.31666696	-61.2333336	Postcode
95	HK	Hong Kong	Asia/Hong_Kong	22.5666676	114.433334	22.1499996	113.833336	Postcode
96	HM	Heard Island & McDonald Islands		-52.9000015	73.8499985	-53.2000008	72.5666656	Postcode
103	IL	Israel	Asia/Jerusalem	33.2861099	35.6666679	29.5166664	34.2833328	Postcode
128	LC	St Lucia	America/St_Lucia	14.1000004	-60.8666687	13.6999998	-61.0666656	Postcode
229	TZ	Tanzania	Africa/Dar_es_Salaam	0.833333015	40.4333344	-11.6999998	29.5833321	Postcode
233	US	United States	America/New_York,America/Detroit,America/Kentucky/Louisville,America/Kentucky/Monticello,America/Indiana/Indianapolis,America/Indiana/Vincennes,America/Indiana/Winamac,America/Indiana/Marengo,America/Indiana/Petersburg,America/Indiana/Vevay,America/Chicago,America/Indiana/Tell_City,America/Indiana/Knox,America/Menominee,America/North_Dakota/Center,America/North_Dakota/New_Salem,America/North_Dakota/Beulah,America/Denver,America/Boise,America/Shiprock,America/Phoenix,America/Los_Angeles,America/Anchorage,America/Juneau,America/Sitka,America/Yakutat,America/Nome,America/Adak,America/Metlakatla,Pacific/Honolulu	\N	\N	\N	\N	Zipcode
\.


--
-- Name: country_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.country_id_seq', 249, true);


--
-- Data for Name: country_in_site_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.country_in_site_information (site_id, country_id, is_in, is_previously_in, created_at, cached_future_events) FROM stdin;
1	167	f	t	2014-04-02 11:51:02	0
1	184	f	t	2015-06-26 19:27:12	0
1	197	f	t	2016-06-22 21:53:33	0
1	75	t	t	2017-06-14 19:44:39	0
1	57	t	t	2016-11-23 12:49:24	3
1	104	t	t	2017-07-03 15:48:51	0
1	179	t	t	2017-07-07 06:56:57	0
1	77	t	t	2013-12-06 13:13:21	255
\.


--
-- Data for Name: curated_list_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.curated_list_history (curated_list_id, title, description, is_deleted, user_account_id, created_at, is_new, title_changed, description_changed, is_deleted_changed) FROM stdin;
\.


--
-- Data for Name: curated_list_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.curated_list_information (id, site_id, slug, title, description, is_deleted, created_at, cached_future_events) FROM stdin;
\.


--
-- Name: curated_list_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.curated_list_information_id_seq', 52, true);


--
-- Data for Name: event_custom_field_definition_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_custom_field_definition_history (event_custom_field_definition_id, key, key_changed, extension_id, extension_id_changed, type, type_changed, label, label_changed, user_account_id, is_active, is_active_changed, created_at) FROM stdin;
\.


--
-- Data for Name: event_custom_field_definition_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_custom_field_definition_information (id, site_id, key, extension_id, type, label, is_active, created_at) FROM stdin;
1	1	code_of_conduct	org.openacalendar	TextMultiLine	Code of Conduct	t	2015-05-19 20:55:09
\.


--
-- Name: event_custom_field_definition_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.event_custom_field_definition_information_id_seq', 1, true);


--
-- Data for Name: event_has_tag; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_has_tag (tag_id, event_id, added_by_user_account_id, added_at, addition_approved_at, removed_by_user_account_id, removed_at, removal_approved_at) FROM stdin;
\.


--
-- Data for Name: event_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_history (event_id, summary, description, start_at, end_at, user_account_id, created_at, is_deleted, reverted_from_created_at, country_id, timezone, venue_id, url, is_virtual, is_physical, legacy_location_id, area_id, summary_changed, description_changed, start_at_changed, end_at_changed, is_deleted_changed, country_id_changed, timezone_changed, venue_id_changed, url_changed, is_virtual_changed, is_physical_changed, area_id_changed, is_new, approved_at, ticket_url, ticket_url_changed, is_duplicate_of_id, is_duplicate_of_id_changed, is_cancelled, is_cancelled_changed, edit_comment, custom_fields, custom_fields_changed, from_ip) FROM stdin;
8656	Thanks for contributing to this community project! Open data and open source.	You rock!	2023-12-02 19:00:00	2023-12-02 21:00:00	2048	2019-06-01 10:20:44	f	\N	77	Europe/London	\N	https://gitlab.com/opentechcalendar	f	t	\N	62	0	0	0	0	0	0	0	0	0	0	0	0	1	2019-06-01 10:20:44		0	\N	0	f	0	\N	[]	\N	10.0.2.2
\.


--
-- Data for Name: event_in_curated_list; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_in_curated_list (curated_list_id, event_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
\.


--
-- Data for Name: event_in_group; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_in_group (group_id, event_id, is_main_group, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at, addition_approved_at, removal_approved_at) FROM stdin;
\.


--
-- Data for Name: event_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_information (id, site_id, slug, summary, description, start_at, end_at, created_at, is_deleted, event_recur_set_id, country_id, timezone, venue_id, import_url_id, import_id, url, is_virtual, is_physical, legacy_location_id, area_id, approved_at, ticket_url, is_duplicate_of_id, is_cancelled, custom_fields, slug_human, cached_updated_at) FROM stdin;
8656	1	1	Thanks for contributing to this community project! Open data and open source.	You rock!	2023-12-02 19:00:00	2023-12-02 21:00:00	2019-06-01 10:20:44	f	\N	77	Europe/London	\N	\N	\N	https://gitlab.com/opentechcalendar	f	t	\N	62	2019-06-01 10:20:44		\N	f	[]	thanks-for-contributing-to-this-community-project-	\N
\.


--
-- Name: event_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.event_information_id_seq', 8656, true);


--
-- Data for Name: event_recur_set; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.event_recur_set (id, created_at) FROM stdin;
\.


--
-- Name: event_recur_set_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.event_recur_set_id_seq', 332, true);


--
-- Data for Name: gist_content_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.gist_content_information (id, gist_id, sort, event_id, group_id, area_id, venue_id, content_title, content_text, created_at) FROM stdin;
\.


--
-- Name: gist_content_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.gist_content_information_id_seq', 115, true);


--
-- Data for Name: gist_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.gist_information (id, site_id, slug, title, is_deleted, created_at) FROM stdin;
\.


--
-- Name: gist_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.gist_information_id_seq', 36, true);


--
-- Data for Name: group_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.group_history (group_id, title, description, user_account_id, created_at, url, twitter_username, is_deleted, title_changed, description_changed, url_changed, twitter_username_changed, is_deleted_changed, is_new, approved_at, is_duplicate_of_id, is_duplicate_of_id_changed, edit_comment, from_ip) FROM stdin;
\.


--
-- Data for Name: group_in_curated_list; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.group_in_curated_list (curated_list_id, group_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
\.


--
-- Data for Name: group_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.group_information (id, site_id, slug, title, description, created_at, url, twitter_username, is_deleted, approved_at, is_duplicate_of_id, cached_future_events, slug_human, cached_updated_at) FROM stdin;
\.


--
-- Name: group_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.group_information_id_seq', 607, true);


--
-- Data for Name: import_url_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.import_url_history (import_url_id, group_id, title, is_enabled, expired_at, user_account_id, created_at, legacy_location_id, country_id, area_id, is_new, title_changed, is_enabled_changed, expired_at_changed, country_id_changed, area_id_changed, approved_at, group_id_changed, is_manual_events_creation, is_manual_events_creation_changed, edit_comment, from_ip) FROM stdin;
\.


--
-- Data for Name: import_url_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.import_url_information (id, site_id, slug, group_id, title, url, is_enabled, expired_at, created_at, url_canonical, legacy_location_id, country_id, area_id, approved_at, is_manual_events_creation, cached_updated_at) FROM stdin;
\.


--
-- Name: import_url_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.import_url_information_id_seq', 238, true);


--
-- Data for Name: import_url_result; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.import_url_result (import_url_id, new_count, existing_count, saved_count, in_past_count, to_far_in_future_count, not_valid_count, created_at, is_success, message) FROM stdin;
\.


--
-- Data for Name: imported_event; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.imported_event (id, import_url_id, import_id, title, description, start_at, end_at, is_deleted, url, created_at, ticket_url, timezone, ics_rrule_1, reoccur, lng, lat) FROM stdin;
\.


--
-- Name: imported_event_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.imported_event_id_seq', 2661, true);


--
-- Data for Name: imported_event_is_event; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.imported_event_is_event (imported_event_id, event_id, created_at) FROM stdin;
\.


--
-- Data for Name: incoming_link; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.incoming_link (id, site_id, extension_id, type, source_url, target_url, reporter_useragent, reporter_ip, is_verified, data, created_at) FROM stdin;
\.


--
-- Name: incoming_link_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.incoming_link_id_seq', 1644, true);


--
-- Data for Name: legacy_location_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.legacy_location_information (id, title, country_id, legacy_region_id, post_code_filter, lat, lng, cached_future_events, area_id) FROM stdin;
2	Glasgow	77	1	G1-G80	55.8630753	-4.26164341	25	65
46	Gloucester	77	2	GL1-GL4	51.8422737	-2.27626801	0	18
44	Hereford	77	2	HR1, HR2, HR3, HR4	52.0569534	-2.72983432	0	19
5	Inverness	77	1	IV1-IV3, IV5, IV13, IV63	57.5841713	-4.21025705	1	66
42	Kingston-upon-Hull	77	2	HU	53.7593918	-0.358570337	0	20
40	Lancaster	77	2		\N	\N	0	21
69	Leeds	77	2	LS, WF	53.7791786	-1.54419398	0	22
67	Leicester	77	2	LE	52.6369743	-1.13967776	0	23
19	Newry	77	4	BT34, BT35	\N	\N	0	58
35	Norwich	77	2	NR1-NR16	52.6361351	1.30518782	0	30
32	Nottingham	77	2	NG	52.9860954	-1.15021908	0	31
30	Oxford	77	2	OX	51.7591629	-1.27525282	0	32
7	Perth	77	1	PH1-PH3, PH14	56.4861946	-3.46007371	0	67
28	Peterborough	77	2	PE	52.6523018	-0.0749405101	0	33
26	Plymouth	77	2	PL1 - 9	\N	\N	0	34
24	Portsmouth	77	2		\N	\N	2	35
22	Preston	77	2	PR1-PR4	53.76754	-2.71485734	0	36
20	Ripon	77	2	HG4	54.1401482	-1.52970052	0	37
57	Salford	77	2	M3, M5, M6, M7, M27, M28, M30, M38, M44, M50	53.4829559	-2.31159186	0	38
55	Salisbury	77	2	SP1, SP2	51.1958237	-1.5734148	0	39
53	Sheffield	77	2	S1-S10	53.389473	-1.42832148	44	40
51	Southampton	77	2	SO14-SO19	50.9186821	-1.39691961	0	41
49	St Albans	77	2		\N	\N	0	42
71	St. Andrews	77	1	KY16	56.3370361	-2.80224347	0	68
8	Stirling	77	1	FK7-FK9	56.1156502	-3.93621874	0	69
47	Stoke-on-Trent	77	2	ST	53.0044479	-2.15811419	0	43
45	Sunderland	77	2		\N	\N	0	44
14	Swansea	77	3	SA1-SA7	51.7094345	-4.09701347	0	76
43	Truro	77	2	TR1-4	\N	\N	0	45
41	Wakefield	77	2		\N	\N	0	46
70	Wells	77	2	BA5	51.2096291	-2.65235496	0	47
68	Westminster	77	2	NW, SW, WC, W	51.5616913	-0.261055976	0	48
66	Winchester	77	2		\N	\N	0	49
64	Wolverhampton	77	2	WV	52.5860443	-2.12704492	0	50
62	Worcester	77	2	WR1-5	\N	\N	0	51
60	York	77	2	YO	54.0190353	-0.973573685	0	52
3	Aberdeen	77	1	AB10-AB13, AB15, AB16, AB21-AB25	57.1510773	-2.12391973	9	60
15	Armagh	77	4	BT60, BT61	\N	\N	0	54
9	Bangor	77	3	LL57	53.2144051	-4.12124014	0	71
39	Bath	77	2	BA1, BA2	51.2948952	-2.35124969	0	2
16	Belfast	77	4	BT1-BT17, BT29, BT36, BT58	\N	\N	0	55
36	Birmingham	77	2	B1-B21	52.4725304	-1.89127684	0	3
34	Bradford	77	2	BD	53.8151932	-1.79034722	0	4
33	Brighton & Hove	77	2		\N	\N	0	5
31	Bristol	77	2	BS	51.4554291	-2.59028244	0	6
27	Cambridge	77	2	CB1, CB2, CB3, CB4, CB5	52.203968	0.140720263	1	7
25	Canterbury	77	2		\N	\N	0	8
10	Cardiff	77	3	CF	51.5247574	-3.27837086	0	72
29	Carlisle	77	2	CA	54.6856422	-3.03637815	0	9
23	Chelmsford	77	2		\N	\N	0	10
21	Chester	77	2	CH1, CH2, CH3, CH4	53.3749924	-3.02944398	0	11
58	Chichester	77	2	PO19	50.8357735	-0.778357565	0	12
56	Coventry	77	2	CV	52.3915253	-1.50929272	0	13
54	Derby	77	2	DE1, DE3, DE21, DE22, DE23, DE24, DE73	52.8913689	-1.49864328	0	14
4	Dundee	77	1	DD1-DD5	56.4798241	-2.94595933	4	61
52	Durham	77	2	DH1	54.7808113	-1.56917834	0	15
1	Edinburgh	77	1	EH	55.9359436	-3.207587	35	62
6	Elgin	77	1	IV30	57.6485252	-3.31279469	0	63
50	Ely	77	2	CB6, CB7	52.3961983	0.269246578	0	16
48	Exeter	77	2	EX1, EX2, EX3, EX4, EX5, EX6	50.8037186	-3.54840422	0	17
72	Falkirk	77	1	\N	\N	\N	5	64
65	Lichfield	77	2	WS13, WS14	52.6797104	-1.82745099	0	24
63	Lincoln	77	2	LN1-LN6	53.2304726	-0.517336726	0	25
17	Lisburn	77	4	BT27,BT28	\N	\N	0	56
61	Liverpool	77	2	L1-L8, L69	53.4266434	-2.92651176	0	26
59	London	77	2	EC, WC,E1	51.5231209	-0.0273813158	7	27
18	Londonderry	77	4	BT47,BT48	\N	\N	0	57
38	Manchester	77	2	M1-M14,M61	53.4729462	-2.23788834	0	28
37	Newcastle-upon-Tyne	77	2	NE	54.9946823	-1.59853625	0	29
11	Newport	77	3	NP	51.6645775	-3.02452707	0	73
12	St Asaph	77	3	LL17	53.2555656	-3.44320893	0	74
13	St David's	77	3		\N	\N	0	75
\.


--
-- Name: legacy_location_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.legacy_location_information_id_seq', 72, true);


--
-- Data for Name: legacy_region_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.legacy_region_information (id, title, country_id, area_id) FROM stdin;
2	England	77	1
4	Northern Ireland	77	53
1	Scotland	77	59
3	Wales	77	70
\.


--
-- Name: legacy_region_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.legacy_region_information_id_seq', 4, true);


--
-- Data for Name: media_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.media_history (media_id, title, title_changed, source_text, source_text_changed, source_url, source_url_changed, user_account_id, created_at, from_ip, is_new) FROM stdin;
\.


--
-- Data for Name: media_in_event; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.media_in_event (event_id, media_id, added_by_user_account_id, added_at, addition_approved_at, removed_by_user_account_id, removed_at, removal_approved_at) FROM stdin;
\.


--
-- Data for Name: media_in_group; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.media_in_group (group_id, media_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at, addition_approved_at, removal_approved_at) FROM stdin;
\.


--
-- Data for Name: media_in_venue; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.media_in_venue (venue_id, media_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at, addition_approved_at, removal_approved_at) FROM stdin;
\.


--
-- Data for Name: media_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.media_information (id, site_id, slug, is_file_lost, storage_size, created_by_user_account_id, created_at, deleted_by_user_account_id, deleted_at, title, source_text, source_url, md5, cached_updated_at) FROM stdin;
\.


--
-- Name: media_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.media_information_id_seq', 109, true);


--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.migration (id, installed_at) FROM stdin;
20130712203300-users	2013-12-06 13:11:31
20130713073000-site	2013-12-06 13:11:31
20130714201700-userinsite	2013-12-06 13:11:31
20130715080500-events	2013-12-06 13:11:32
20130715160000-rememberuser	2013-12-06 13:11:32
20130716202900-groups	2013-12-06 13:11:32
20130801110500-group-url	2013-12-06 13:11:32
20130802171900-delete-event	2013-12-06 13:11:33
20130802175700-rollback-event	2013-12-06 13:11:33
20130802214600-eventrecurset	2013-12-06 13:11:33
20130807074300-user-at-event	2013-12-06 13:11:33
20130807184300-user-private-feed	2013-12-06 13:11:33
20130830100100-site-discover	2013-12-06 13:11:33
20130830155500-country	2013-12-06 13:11:33
20130901084200-site-country	2013-12-06 13:11:33
20130901095200-site-int	2013-12-06 13:11:33
20130901110600-event-country-timezone	2013-12-06 13:11:33
20130910214200-watches	2013-12-06 13:11:34
20130913145500-venue	2013-12-06 13:11:34
20130920115300-watches-stop	2013-12-06 13:11:34
20130920155900-email	2013-12-06 13:11:34
20130924082900-requestaccess	2013-12-06 13:11:35
20130927111200-sitelogo	2013-12-06 13:11:35
20130928095900-media	2013-12-06 13:11:35
20130929201700-drop-user-interested-in-site	2013-12-06 13:11:35
20131001064300-record-emails-sent	2013-12-06 13:11:35
20131002091900-site-features-1	2013-12-06 13:11:36
20131002091900-site-config-1	2013-12-06 13:11:36
20131002110300-user-email-options	2013-12-06 13:11:36
20131003083700-contact-support	2013-12-06 13:11:37
20131003113000-importer	2013-12-06 13:11:37
20131003132300-curated-lists	2013-12-06 13:11:37
20131010085500-importer2	2013-12-06 13:11:37
20131011164200-event-url	2013-12-06 13:11:37
20131012172500-user-email-key	2013-12-06 13:11:37
20131014094900-importer3	2013-12-06 13:11:37
20131014134100-user-email-options2	2013-12-06 13:11:37
20131015090900-user-email-options3	2013-12-06 13:11:37
20131017081100-group-twitter	2013-12-06 13:11:37
20131017153300-site-group-prompt-emails-sent	2013-12-06 13:11:37
20131020092800-userclockprefs	2013-12-06 13:11:37
20131020165100-userverifyemail	2013-12-06 13:11:37
20131021112400-lock-users	2013-12-06 13:11:38
20131102135700-virtual-events	2013-12-06 13:11:39
20131111073200-event-in-group	2013-12-06 13:11:39
20131111104000-site-features-2	2013-12-06 13:11:39
20131118090400-media-in-group	2013-12-06 13:11:39
20131118133500-media-data	2013-12-06 13:11:39
20131129072100-delete-group	2013-12-06 13:11:39
20131129080500-media-md5	2013-12-06 13:11:39
20131129094500-media-in-venue	2013-12-06 13:11:39
20131203115900-legacy-region-location	2013-12-06 13:11:40
20131203171800-group-in-legacy-location	2013-12-06 13:11:40
20131203173900-old-passwords	2013-12-06 13:11:40
20131224102300-newsletter	2013-12-26 10:53:35
20131224151000-newsletter	2013-12-26 10:53:35
20140108112200-quotas	2014-01-09 08:08:38
20140111091600-country-bounds	2014-01-28 11:33:35
20140111165000-places	2014-01-28 11:33:37
20140128085300-legacy-region-location-to-areas	2014-01-28 11:33:37
20140210081700-event-area	2014-02-10 10:46:17
20140220144400-better-history	2014-03-22 08:47:35
20140223000100-venue-address	2014-03-22 08:47:36
20140305111900-better-history2	2014-03-22 08:47:36
20140407140100-importer-country	2014-04-09 07:33:22
20140522114200-importer-area	2014-05-22 12:03:49
20140511150900-api2	2014-07-11 14:45:20
20140603085100-user-notifications	2014-07-11 14:45:21
20140609165500-importer4	2014-07-11 14:45:21
20140609220300-moderation	2014-07-11 14:45:21
20140619082400-event-ticket-url	2014-07-11 14:45:22
20140621101500-tags	2014-07-11 14:45:22
20140627141400-app-config	2014-07-11 14:45:22
20140629215200-importer5	2014-07-11 14:45:22
20140629222600-better-history3	2014-07-11 14:45:23
20140702100900-constraints	2014-07-11 14:45:23
20140708132900-moderation2	2014-07-11 14:45:23
20140708134400-media-in-event	2014-07-11 14:45:23
20140814170600-duplicate	2014-08-18 11:33:11
20140818110000-curated-lists2	2015-01-15 09:21:50
20140908153700-event-cancelled	2015-01-15 09:21:51
20140918212300-history	2015-01-15 09:21:52
20140929144600-import_url	2015-01-15 09:21:52
20141002165800-history	2015-01-15 09:21:52
20141009120000-user-groups	2015-01-15 09:21:52
20141009120100-user-groups	2015-01-15 09:21:52
20141101172300-user-groups-2	2015-01-15 09:21:52
20141117102300-import-ics-reoccur	2015-01-15 09:21:52
20150105153900-media-history	2015-01-15 09:21:52
20150113095000-import-ics-reoccur	2015-01-15 09:21:52
20150203092700-cache-future-events	2015-05-16 06:53:03
20150203143400-task-log	2015-05-16 06:53:03
20150205094500-watch-area	2015-05-16 06:53:03
20150212091200-import-draft	2015-05-16 06:53:03
20150217110200-incoming-links	2015-05-16 06:53:04
20150320112300-site-feature-edit-comments	2015-05-16 06:53:04
20150328180300-custom-fields	2015-05-16 06:53:04
20150407092200-new-events	2015-05-16 06:53:04
20150509213000-site-features	2015-05-16 06:53:04
20150515164100-delete-old-passwords	2015-05-16 06:53:04
20150515113000-user-interested-is-site	2015-05-19 07:53:36
20150826090000-sysadmin-comments	2015-08-26 09:17:58
20150827083500-ip	2015-08-27 10:48:25
20151029084100-sysadmin-comments2	2015-10-29 19:44:26
20160120211600-gists	2016-01-21 13:27:53
20151212220600-user-not-at-event	2016-02-13 14:07:53
20151220082700-cache-future-events	2016-02-13 14:07:53
20151220091900-cache-future-events	2016-02-13 14:07:53
20160128170000-human-slugs	2016-02-13 14:07:53
20160414120000-media-history	2016-04-17 18:28:11
20160721140700-area-lat-lng	2016-09-23 08:03:34
20160727200700-cached-updated-at	2016-09-23 08:03:34
20160805173100-import-lat-lng	2016-09-23 08:03:34
20170325202600-cache	2017-07-17 19:18:08
20180409180800-user-notification-pref-last-edited	2018-04-09 17:42:21
20170922084800-displayname	2018-04-14 10:28:32
20171109091000-dropapi2	2018-04-14 10:28:32
20180528142000-track-inactive-users	2018-06-04 20:29:16
\.


--
-- Data for Name: new_event_draft_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.new_event_draft_history (new_event_draft_id, details, details_changed, not_duplicate_events, not_duplicate_events_changed, event_id, event_id_changed, was_existing_event_id, was_existing_event_id_changed, user_account_id, created_at) FROM stdin;
2413	[]	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:17:51
2413	{"group.none":true,"group.new":false,"group.existing":false}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:17:53
2413	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:18:58
2413	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null,"event.start_end_freetext.start":"2023-12-02T19:00:00+00:00","event.start_end_freetext.end":"2023-12-02T21:00:00+00:00","event.start_end_freetext.text":"2nd dec 2023 7pm to 9pm","event.start_end_freetext.done":"yes"}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:19:08
2413	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null,"event.start_end_freetext.start":"2023-12-02T19:00:00+00:00","event.start_end_freetext.end":"2023-12-02T21:00:00+00:00","event.start_end_freetext.text":"2nd dec 2023 7pm to 9pm","event.start_end_freetext.done":"yes","event.start_at":"2023-12-02T19:00:00+00:00","event.end_at":"2023-12-02T21:00:00+00:00","event.country_id":77,"event.timezone":"Europe\\/London"}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:20:26
2413	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null,"event.start_end_freetext.start":"2023-12-02T19:00:00+00:00","event.start_end_freetext.end":"2023-12-02T21:00:00+00:00","event.start_end_freetext.text":"2nd dec 2023 7pm to 9pm","event.start_end_freetext.done":"yes","event.start_at":"2023-12-02T19:00:00+00:00","event.end_at":"2023-12-02T21:00:00+00:00","event.country_id":77,"event.timezone":"Europe\\/London","where.mode":3}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:20:29
2413	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null,"event.start_end_freetext.start":"2023-12-02T19:00:00+00:00","event.start_end_freetext.end":"2023-12-02T21:00:00+00:00","event.start_end_freetext.text":"2nd dec 2023 7pm to 9pm","event.start_end_freetext.done":"yes","event.start_at":"2023-12-02T19:00:00+00:00","event.end_at":"2023-12-02T21:00:00+00:00","event.country_id":77,"event.timezone":"Europe\\/London","where.mode":3,"area.id":62,"area.title":"Edinburgh"}	0	\N	-2	\N	-2	\N	-2	2048	2019-06-01 10:20:35
2413	\N	-2	\N	-2	8656	0	\N	-2	2048	2019-06-01 10:20:44
\.


--
-- Data for Name: new_event_draft_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.new_event_draft_information (id, site_id, slug, details, not_duplicate_events, event_id, was_existing_event_id, user_account_id, updated_at, created_at) FROM stdin;
2413	1	1	{"group.none":true,"group.new":false,"group.existing":false,"event.summary":"Thanks for contributing to this community project! Open data and open source.","event.description":"You rock!","event.url":"https:\\/\\/gitlab.com\\/opentechcalendar","event.ticket_url":null,"event.is_physical":true,"event.is_virtual":false,"event.custom.code_of_conduct":null,"event.start_end_freetext.start":"2023-12-02T19:00:00+00:00","event.start_end_freetext.end":"2023-12-02T21:00:00+00:00","event.start_end_freetext.text":"2nd dec 2023 7pm to 9pm","event.start_end_freetext.done":"yes","event.start_at":"2023-12-02T19:00:00+00:00","event.end_at":"2023-12-02T21:00:00+00:00","event.country_id":77,"event.timezone":"Europe\\/London","where.mode":3,"area.id":62,"area.title":"Edinburgh"}	\N	8656	\N	2048	2019-06-01 10:20:35	2019-06-01 10:17:51
\.


--
-- Name: new_event_draft_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.new_event_draft_information_id_seq', 2413, true);


--
-- Data for Name: permission_in_user_group; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.permission_in_user_group (user_group_id, extension_id, permission_key, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
1	org.openacalendar	CREATE_SITE	\N	2015-01-15 09:21:52	\N	\N
3	org.openacalendar	CALENDAR_ADMINISTRATE	\N	2015-01-15 09:21:52	\N	\N
2	org.openacalendar	CALENDAR_CHANGE	\N	2015-01-15 09:21:52	\N	2015-08-21 17:40:59
2	org.openacalendar	AREAS_CHANGE	\N	2015-08-21 17:41:12	\N	2015-08-26 07:35:46
2	org.openacalendar	CALENDAR_CHANGE	\N	2015-08-27 11:52:20	\N	\N
2	org.openacalendar	EVENTS_CHANGE	\N	2015-08-21 17:40:52	\N	2015-08-27 11:52:21
2	org.openacalendar	GROUPS_CHANGE	\N	2015-08-21 17:40:53	\N	2015-08-27 11:52:22
2	org.openacalendar	IMPORTURL_CHANGE	\N	2015-08-21 17:40:55	\N	2015-08-27 11:52:24
2	org.openacalendar	MEDIAS_CHANGE	\N	2015-08-21 17:40:57	\N	2015-08-27 11:52:24
2	org.openacalendar	VENUES_CHANGE	\N	2015-08-21 17:40:56	\N	2015-08-27 11:52:26
\.


--
-- Data for Name: site_feature_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_feature_history (site_id, extension_id, feature_id, is_on, is_on_changed, user_account_id, created_at) FROM stdin;
\.


--
-- Data for Name: site_feature_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_feature_information (site_id, extension_id, feature_id, is_on) FROM stdin;
1	org.openacalendar	Group	t
1	org.openacalendar	PhysicalEvents	t
1	org.openacalendar	VirtualEvents	t
1	org.openacalendar.curatedlists	CuratedList	t
1	org.openacalendar	Importer	t
1	org.openacalendar	Map	t
\.


--
-- Data for Name: site_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_history (site_id, title, slug, slug_canonical, user_account_id, created_at, description_text, footer_text, is_web_robots_allowed, is_closed_by_sys_admin, is_all_users_editors, closed_by_sys_admin_reason, is_listed_in_index, is_request_access_allowed, request_access_question, is_feature_map, is_feature_importer, is_feature_curated_list, prompt_emails_days_in_advance, is_feature_virtual_events, is_feature_physical_events, is_feature_group, title_changed, slug_changed, description_text_changed, footer_text_changed, is_web_robots_allowed_changed, is_closed_by_sys_admin_changed, is_all_users_editors_changed, closed_by_sys_admin_reason_changed, is_listed_in_index_changed, is_request_access_allowed_changed, request_access_question_changed, is_feature_map_changed, is_feature_importer_changed, is_feature_curated_list_changed, prompt_emails_days_in_advance_changed, is_feature_virtual_events_changed, is_feature_physical_events_changed, is_feature_group_changed, is_new, is_feature_tag, is_feature_tag_changed, from_ip) FROM stdin;
1	Open Tech Calendar	otc	otc	\N	2013-12-06 13:13:21	\N	\N	t	f	t	\N	t	f	\N	f	f	f	30	f	t	t	1	1	-1	-1	1	1	1	-1	1	1	-1	1	1	1	1	1	1	1	1	f	-1	\N
1	Open Tech Calendar	otc	otc	\N	2013-12-06 13:21:43	\N	\N	t	f	t	\N	t	f	Why to you want to edit this calendar?	t	t	t	30	t	t	t	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	1	1	1	1	-1	1	-1	-1	0	f	-1	\N
1	Open Tech Calendar	otc	otc	\N	2013-12-08 18:20:50	\N	\N	t	f	t	\N	t	f	Why to you want to edit this calendar?	t	t	t	14	t	t	t	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	1	-1	-1	-1	0	f	-1	\N
1	Open Tech Calendar	otc	otc	\N	2013-12-12 12:14:10	\N	\N	t	f	t	\N	t	f	Why to you want to edit this calendar?	t	t	t	14	t	t	t	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	-1	0	f	-1	\N
\.


--
-- Data for Name: site_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_information (id, title, slug, slug_canonical, created_at, description_text, footer_text, is_web_robots_allowed, is_closed_by_sys_admin, is_all_users_editors, closed_by_sys_admin_reason, is_listed_in_index, cached_is_multiple_timezones, cached_is_multiple_countries, cached_timezones, is_request_access_allowed, request_access_question, is_feature_map, is_feature_importer, is_feature_curated_list, prompt_emails_days_in_advance, is_feature_virtual_events, is_feature_physical_events, is_feature_group, site_quota_id, is_feature_tag, cached_event_custom_field_definitions, cached_updated_at) FROM stdin;
1	Open Tech Calendar	otc	otc	2013-12-06 13:13:21	\N	\N	t	f	t	\N	t	t	t	Europe/Berlin,Europe/Busingen,Europe/Isle_of_Man,Europe/London,Europe/Paris,Europe/Warsaw	f	Why to you want to edit this calendar?	t	t	t	14	t	t	t	2	f	[{"id":1,"extension_id":"org.openacalendar","type":"TextMultiLine","key":"code_of_conduct","label":"Code of Conduct","is_active":true}]	\N
\.


--
-- Name: site_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.site_information_id_seq', 1, true);


--
-- Data for Name: site_profile_media_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_profile_media_history (site_id, logo_media_id, user_account_id, created_at) FROM stdin;
\.


--
-- Data for Name: site_profile_media_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_profile_media_information (site_id, logo_media_id) FROM stdin;
\.


--
-- Data for Name: site_quota_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.site_quota_information (id, title, code, max_new_events_per_month, max_new_groups_per_month, max_new_venues_per_month, max_countries, max_media_mb) FROM stdin;
2	Free Trial	TRIAL	500	500	500	500	500
\.


--
-- Name: site_quota_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.site_quota_information_id_seq', 4, true);


--
-- Data for Name: sysadmin_comment_about_area; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_area (sysadmin_comment_id, area_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_event; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_event (sysadmin_comment_id, event_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_group; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_group (sysadmin_comment_id, group_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_media; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_media (sysadmin_comment_id, media_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_site; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_site (sysadmin_comment_id, site_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_user; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_user (sysadmin_comment_id, user_account_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_about_venue; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_about_venue (sysadmin_comment_id, venue_id) FROM stdin;
\.


--
-- Data for Name: sysadmin_comment_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.sysadmin_comment_information (id, user_account_id, comment, created_at) FROM stdin;
\.


--
-- Name: sysadmin_comment_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.sysadmin_comment_information_id_seq', 1482, true);


--
-- Data for Name: tag_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.tag_history (tag_id, title, title_changed, description, description_changed, is_deleted, is_deleted_changed, user_account_id, is_new, created_at, approved_at, edit_comment, from_ip) FROM stdin;
\.


--
-- Data for Name: tag_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.tag_information (id, site_id, slug, title, description, is_deleted, created_at, approved_at, slug_human, cached_updated_at) FROM stdin;
\.


--
-- Name: tag_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.tag_information_id_seq', 1, false);


--
-- Data for Name: task_log; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.task_log (extension_id, task_id, started_at, ended_at, result_data, exception_data) FROM stdin;
\.


--
-- Data for Name: user_account_general_security_key; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_general_security_key (user_account_id, access_key, created_at) FROM stdin;
\.


--
-- Data for Name: user_account_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_information (id, username, username_canonical, email, email_canonical, password_hash, is_email_verified, email_verify_code, email_verify_last_sent_at, is_editor, is_system_admin, created_at, is_email_watch_notify, is_email_watch_prompt, email_upcoming_events, is_email_watch_import_expired, email_upcoming_events_days_notice, is_clock_12hour, is_closed_by_sys_admin, closed_by_sys_admin_reason, is_email_newsletter, created_from_ip, displayname, last_website_login_at) FROM stdin;
2048	5kbvbh1kf4lmrebmzujclhrasl7djd6pz8icmgad	5kbvbh1kf4lmrebmzujclhrasl7djd6pz8icmgad	hello@opentechcalendar.co.uk	hello@opentechcalendar.co.uk	$2y$05$uNw84ZQFvYsIrYuddmI0/uUfkug0KeGsIebM2hyvRp6xX9rWN8fRC	t	\N	\N	t	t	2019-06-01 10:09:25	t	t	w	t	1	t	f	\N	t	10.0.2.2	USER	2019-06-01 10:09:25
\.


--
-- Name: user_account_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.user_account_information_id_seq', 2048, true);


--
-- Data for Name: user_account_private_feed_key; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_private_feed_key (user_account_id, access_key, created_at, last_used_at) FROM stdin;
2048	7nebyoi3urqp1vru0ecgovzowls3etqj0vz9hfjs08n1lxmbj41hhaxzjwhbzr5bfv4d9jzls5jghl	2019-06-01 10:09:29	\N
\.


--
-- Data for Name: user_account_remember_me; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_remember_me (user_account_id, access_key, created_at, last_used_at) FROM stdin;
\.


--
-- Data for Name: user_account_reset; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_reset (user_account_id, access_key, created_at, reset_at) FROM stdin;
\.


--
-- Data for Name: user_account_verify_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_account_verify_email (user_account_id, email, access_key, created_at, verified_at, verified_from_ip) FROM stdin;
2048	hello@opentechcalendar.co.uk	0d84zc0bcw0p2p3fovmph0jm5gstv6tge3j5qx7orl8crorsh	2019-06-01 10:09:25	2019-06-01 10:09:43	10.0.2.2
\.


--
-- Data for Name: user_at_event_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_at_event_information (user_account_id, event_id, is_plan_attending, is_plan_maybe_attending, is_plan_public, created_at, is_plan_not_attending) FROM stdin;
\.


--
-- Data for Name: user_group_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_group_history (user_group_id, title, title_changed, description, description_changed, is_deleted, is_deleted_changed, is_in_index, is_in_index_changed, is_includes_anonymous, is_includes_anonymous_changed, is_includes_users, is_includes_users_changed, is_includes_verified_users, is_includes_verified_users_changed, user_account_id, created_at, from_ip) FROM stdin;
\.


--
-- Data for Name: user_group_in_site; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_group_in_site (user_group_id, site_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
2	1	\N	2015-01-15 09:21:52	\N	\N
3	1	\N	2015-01-15 09:21:52	\N	\N
\.


--
-- Data for Name: user_group_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_group_information (id, title, description, is_deleted, is_in_index, is_includes_anonymous, is_includes_users, is_includes_verified_users, created_at) FROM stdin;
1	Can Create Sites	\N	f	t	f	f	t	2015-01-15 09:21:52
2	Editors	\N	f	f	f	f	t	2015-01-15 09:21:52
3	Admins	\N	f	f	f	f	f	2015-01-15 09:21:52
\.


--
-- Name: user_group_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.user_group_information_id_seq', 3, true);


--
-- Data for Name: user_has_no_editor_permissions_in_site; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_has_no_editor_permissions_in_site (site_id, user_account_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
\.


--
-- Data for Name: user_in_curated_list_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_in_curated_list_information (user_account_id, curated_list_id, is_owner, is_editor, created_at) FROM stdin;
\.


--
-- Data for Name: user_in_gist_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_in_gist_information (user_account_id, gist_id, is_owner, is_editor, created_at) FROM stdin;
\.


--
-- Data for Name: user_in_site_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_in_site_information (user_account_id, site_id, is_owner, is_administrator, is_editor, created_at) FROM stdin;
\.


--
-- Data for Name: user_in_user_group; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_in_user_group (user_group_id, user_account_id, added_by_user_account_id, added_at, removed_by_user_account_id, removed_at) FROM stdin;
\.


--
-- Data for Name: user_interested_in_site_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_interested_in_site_information (user_account_id, site_id, is_interested, is_not_interested, created_at) FROM stdin;
\.


--
-- Data for Name: user_notification; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_notification (id, user_id, site_id, from_extension_id, from_user_notification_type, is_email, data_json, created_at, emailed_at, read_at) FROM stdin;
\.


--
-- Name: user_notification_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.user_notification_id_seq', 90707, true);


--
-- Data for Name: user_notification_preference; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_notification_preference (user_id, extension_id, user_notification_preference_type, is_email, last_save_at) FROM stdin;
2048	org.openacalendar	WatchPrompt	t	2019-06-01 10:09:25
2048	org.openacalendar	WatchNotify	t	2019-06-01 10:09:25
2048	org.openacalendar	UpcomingEvents	t	2019-06-01 10:09:25
2048	org.openacalendar	WatchImportExpired	t	2019-06-01 10:09:25
\.


--
-- Data for Name: user_watches_area_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_area_information (user_account_id, area_id, is_watching, is_was_once_watching, last_notify_email_sent, last_prompt_email_sent, last_watch_started, created_at) FROM stdin;
\.


--
-- Data for Name: user_watches_area_stop; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_area_stop (user_account_id, area_id, access_key, created_at) FROM stdin;
\.


--
-- Data for Name: user_watches_group_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_group_information (user_account_id, group_id, is_watching, is_was_once_watching, last_notify_email_sent, last_prompt_email_sent, last_watch_started, created_at) FROM stdin;
\.


--
-- Data for Name: user_watches_group_notify_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_group_notify_email (user_account_id, group_id, sent_at) FROM stdin;
\.


--
-- Data for Name: user_watches_group_prompt_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_group_prompt_email (user_account_id, group_id, sent_at) FROM stdin;
\.


--
-- Data for Name: user_watches_group_stop; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_group_stop (user_account_id, group_id, access_key, created_at) FROM stdin;
\.


--
-- Data for Name: user_watches_site_group_prompt_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_site_group_prompt_email (user_account_id, group_id, sent_at) FROM stdin;
\.


--
-- Data for Name: user_watches_site_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_site_information (user_account_id, site_id, is_watching, is_was_once_watching, last_notify_email_sent, last_prompt_email_sent, last_watch_started, created_at) FROM stdin;
\.


--
-- Data for Name: user_watches_site_notify_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_site_notify_email (user_account_id, site_id, sent_at) FROM stdin;
\.


--
-- Data for Name: user_watches_site_prompt_email; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_site_prompt_email (user_account_id, site_id, sent_at) FROM stdin;
\.


--
-- Data for Name: user_watches_site_stop; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.user_watches_site_stop (user_account_id, site_id, access_key, created_at) FROM stdin;
\.


--
-- Data for Name: venue_history; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.venue_history (venue_id, title, description, lat, lng, country_id, is_deleted, user_account_id, created_at, area_id, title_changed, description_changed, lat_changed, lng_changed, country_id_changed, is_deleted_changed, area_id_changed, address, address_changed, address_code, address_code_changed, is_new, approved_at, is_duplicate_of_id, is_duplicate_of_id_changed, edit_comment, from_ip) FROM stdin;
\.


--
-- Data for Name: venue_information; Type: TABLE DATA; Schema: public; Owner: opentechcalendar3
--

COPY public.venue_information (id, site_id, slug, title, description, lat, lng, country_id, is_deleted, created_at, area_id, address, address_code, approved_at, is_duplicate_of_id, cached_future_events, slug_human, cached_updated_at) FROM stdin;
\.


--
-- Name: venue_information_id_seq; Type: SEQUENCE SET; Schema: public; Owner: opentechcalendar3
--

SELECT pg_catalog.setval('public.venue_information_id_seq', 911, true);


--
-- Name: app_configuration_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.app_configuration_information
    ADD CONSTRAINT app_configuration_information_pkey PRIMARY KEY (extension_id, configuration_key);


--
-- Name: area_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_history
    ADD CONSTRAINT area_history_pkey PRIMARY KEY (area_id, created_at);


--
-- Name: area_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information
    ADD CONSTRAINT area_information_pkey PRIMARY KEY (id);


--
-- Name: cached_area_has_parent_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.cached_area_has_parent
    ADD CONSTRAINT cached_area_has_parent_pkey PRIMARY KEY (area_id, has_parent_area_id);


--
-- Name: contact_support_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.contact_support
    ADD CONSTRAINT contact_support_pkey PRIMARY KEY (id);


--
-- Name: country_in_site_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.country_in_site_information
    ADD CONSTRAINT country_in_site_information_pkey PRIMARY KEY (site_id, country_id);


--
-- Name: country_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.country
    ADD CONSTRAINT country_pkey PRIMARY KEY (id);


--
-- Name: curated_list_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_history
    ADD CONSTRAINT curated_list_history_pkey PRIMARY KEY (curated_list_id, created_at);


--
-- Name: curated_list_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_information
    ADD CONSTRAINT curated_list_information_pkey PRIMARY KEY (id);


--
-- Name: event_custom_field_definition_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_history
    ADD CONSTRAINT event_custom_field_definition_history_pkey PRIMARY KEY (event_custom_field_definition_id, created_at);


--
-- Name: event_custom_field_definition_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_information
    ADD CONSTRAINT event_custom_field_definition_information_pkey PRIMARY KEY (id);


--
-- Name: event_has_tag_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_has_tag
    ADD CONSTRAINT event_has_tag_pkey PRIMARY KEY (tag_id, event_id, added_at);


--
-- Name: event_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_pkey PRIMARY KEY (event_id, created_at);


--
-- Name: event_in_curated_list_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_curated_list
    ADD CONSTRAINT event_in_curated_list_pkey PRIMARY KEY (curated_list_id, event_id, added_at);


--
-- Name: event_in_group_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_group
    ADD CONSTRAINT event_in_group_pkey PRIMARY KEY (group_id, event_id, added_at);


--
-- Name: event_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_pkey PRIMARY KEY (id);


--
-- Name: event_recur_set_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_recur_set
    ADD CONSTRAINT event_recur_set_pkey PRIMARY KEY (id);


--
-- Name: gist_content_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_pkey PRIMARY KEY (id);


--
-- Name: gist_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_information
    ADD CONSTRAINT gist_information_pkey PRIMARY KEY (id);


--
-- Name: group_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_history
    ADD CONSTRAINT group_history_pkey PRIMARY KEY (group_id, created_at);


--
-- Name: group_in_curated_list_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_in_curated_list
    ADD CONSTRAINT group_in_curated_list_pkey PRIMARY KEY (curated_list_id, group_id, added_at);


--
-- Name: group_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_information
    ADD CONSTRAINT group_information_pkey PRIMARY KEY (id);


--
-- Name: import_url_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_pkey PRIMARY KEY (import_url_id, created_at);


--
-- Name: import_url_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_pkey PRIMARY KEY (id);


--
-- Name: import_url_result_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_result
    ADD CONSTRAINT import_url_result_pkey PRIMARY KEY (import_url_id, created_at);


--
-- Name: imported_event_is_event_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event_is_event
    ADD CONSTRAINT imported_event_is_event_pkey PRIMARY KEY (imported_event_id, event_id);


--
-- Name: imported_event_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event
    ADD CONSTRAINT imported_event_pkey PRIMARY KEY (id);


--
-- Name: incoming_link_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.incoming_link
    ADD CONSTRAINT incoming_link_pkey PRIMARY KEY (id);


--
-- Name: legacy_location_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_location_information
    ADD CONSTRAINT legacy_location_information_pkey PRIMARY KEY (id);


--
-- Name: legacy_region_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_region_information
    ADD CONSTRAINT legacy_region_information_pkey PRIMARY KEY (id);


--
-- Name: media_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_history
    ADD CONSTRAINT media_history_pkey PRIMARY KEY (media_id, created_at);


--
-- Name: media_in_event_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_event
    ADD CONSTRAINT media_in_event_pkey PRIMARY KEY (event_id, media_id, added_at);


--
-- Name: media_in_group_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_group
    ADD CONSTRAINT media_in_group_pkey PRIMARY KEY (group_id, media_id, added_at);


--
-- Name: media_in_venue_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_venue
    ADD CONSTRAINT media_in_venue_pkey PRIMARY KEY (venue_id, media_id, added_at);


--
-- Name: media_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_information
    ADD CONSTRAINT media_information_pkey PRIMARY KEY (id);


--
-- Name: migration_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (id);


--
-- Name: new_event_draft_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_history
    ADD CONSTRAINT new_event_draft_history_pkey PRIMARY KEY (new_event_draft_id, created_at);


--
-- Name: new_event_draft_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information
    ADD CONSTRAINT new_event_draft_information_pkey PRIMARY KEY (id);


--
-- Name: permission_in_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.permission_in_user_group
    ADD CONSTRAINT permission_in_user_group_pkey PRIMARY KEY (user_group_id, extension_id, permission_key, added_at);


--
-- Name: site_feature_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_feature_history
    ADD CONSTRAINT site_feature_history_pkey PRIMARY KEY (site_id, extension_id, feature_id, created_at);


--
-- Name: site_feature_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_feature_information
    ADD CONSTRAINT site_feature_information_pkey PRIMARY KEY (site_id, extension_id, feature_id);


--
-- Name: site_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_history
    ADD CONSTRAINT site_history_pkey PRIMARY KEY (site_id, created_at);


--
-- Name: site_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_information
    ADD CONSTRAINT site_information_pkey PRIMARY KEY (id);


--
-- Name: site_profile_media_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_history
    ADD CONSTRAINT site_profile_media_history_pkey PRIMARY KEY (site_id, created_at);


--
-- Name: site_profile_media_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_information
    ADD CONSTRAINT site_profile_media_information_pkey PRIMARY KEY (site_id);


--
-- Name: site_quota_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_quota_information
    ADD CONSTRAINT site_quota_information_pkey PRIMARY KEY (id);


--
-- Name: sysadmin_comment_about_area_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_area
    ADD CONSTRAINT sysadmin_comment_about_area_pkey PRIMARY KEY (sysadmin_comment_id, area_id);


--
-- Name: sysadmin_comment_about_event_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_event
    ADD CONSTRAINT sysadmin_comment_about_event_pkey PRIMARY KEY (sysadmin_comment_id, event_id);


--
-- Name: sysadmin_comment_about_group_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_group
    ADD CONSTRAINT sysadmin_comment_about_group_pkey PRIMARY KEY (sysadmin_comment_id, group_id);


--
-- Name: sysadmin_comment_about_media_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_media
    ADD CONSTRAINT sysadmin_comment_about_media_pkey PRIMARY KEY (sysadmin_comment_id, media_id);


--
-- Name: sysadmin_comment_about_site_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_site
    ADD CONSTRAINT sysadmin_comment_about_site_pkey PRIMARY KEY (sysadmin_comment_id, site_id);


--
-- Name: sysadmin_comment_about_user_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_user
    ADD CONSTRAINT sysadmin_comment_about_user_pkey PRIMARY KEY (sysadmin_comment_id, user_account_id);


--
-- Name: sysadmin_comment_about_venue_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_venue
    ADD CONSTRAINT sysadmin_comment_about_venue_pkey PRIMARY KEY (sysadmin_comment_id, venue_id);


--
-- Name: sysadmin_comment_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_information
    ADD CONSTRAINT sysadmin_comment_information_pkey PRIMARY KEY (id);


--
-- Name: tag_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_history
    ADD CONSTRAINT tag_history_pkey PRIMARY KEY (tag_id, created_at);


--
-- Name: tag_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_information
    ADD CONSTRAINT tag_information_pkey PRIMARY KEY (id);


--
-- Name: user_account_general_security_key_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_general_security_key
    ADD CONSTRAINT user_account_general_security_key_pkey PRIMARY KEY (user_account_id, access_key);


--
-- Name: user_account_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_information
    ADD CONSTRAINT user_account_information_pkey PRIMARY KEY (id);


--
-- Name: user_account_private_feed_key_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_private_feed_key
    ADD CONSTRAINT user_account_private_feed_key_pkey PRIMARY KEY (user_account_id, access_key);


--
-- Name: user_account_remember_me_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_remember_me
    ADD CONSTRAINT user_account_remember_me_pkey PRIMARY KEY (user_account_id, access_key);


--
-- Name: user_account_reset_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_reset
    ADD CONSTRAINT user_account_reset_pkey PRIMARY KEY (user_account_id, access_key);


--
-- Name: user_account_verify_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_verify_email
    ADD CONSTRAINT user_account_verify_email_pkey PRIMARY KEY (user_account_id, access_key);


--
-- Name: user_at_event_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_at_event_information
    ADD CONSTRAINT user_at_event_information_pkey PRIMARY KEY (user_account_id, event_id);


--
-- Name: user_group_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_history
    ADD CONSTRAINT user_group_history_pkey PRIMARY KEY (user_group_id, created_at);


--
-- Name: user_group_in_site_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_in_site
    ADD CONSTRAINT user_group_in_site_pkey PRIMARY KEY (user_group_id, site_id, added_at);


--
-- Name: user_group_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_information
    ADD CONSTRAINT user_group_information_pkey PRIMARY KEY (id);


--
-- Name: user_has_no_editor_permissions_in_site_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_has_no_editor_permissions_in_site
    ADD CONSTRAINT user_has_no_editor_permissions_in_site_pkey PRIMARY KEY (site_id, user_account_id, added_at);


--
-- Name: user_in_curated_list_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_curated_list_information
    ADD CONSTRAINT user_in_curated_list_information_pkey PRIMARY KEY (user_account_id, curated_list_id);


--
-- Name: user_in_gist_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_gist_information
    ADD CONSTRAINT user_in_gist_information_pkey PRIMARY KEY (user_account_id, gist_id);


--
-- Name: user_in_site_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_site_information
    ADD CONSTRAINT user_in_site_information_pkey PRIMARY KEY (user_account_id, site_id);


--
-- Name: user_in_user_group_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_user_group
    ADD CONSTRAINT user_in_user_group_pkey PRIMARY KEY (user_group_id, user_account_id, added_at);


--
-- Name: user_interested_in_site_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_interested_in_site_information
    ADD CONSTRAINT user_interested_in_site_information_pkey PRIMARY KEY (user_account_id, site_id);


--
-- Name: user_notification_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification
    ADD CONSTRAINT user_notification_pkey PRIMARY KEY (id);


--
-- Name: user_notification_preference_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification_preference
    ADD CONSTRAINT user_notification_preference_pkey PRIMARY KEY (user_id, extension_id, user_notification_preference_type);


--
-- Name: user_watches_area_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_information
    ADD CONSTRAINT user_watches_area_information_pkey PRIMARY KEY (user_account_id, area_id);


--
-- Name: user_watches_area_stop_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_stop
    ADD CONSTRAINT user_watches_area_stop_pkey PRIMARY KEY (user_account_id, area_id, access_key);


--
-- Name: user_watches_group_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_information
    ADD CONSTRAINT user_watches_group_information_pkey PRIMARY KEY (user_account_id, group_id);


--
-- Name: user_watches_group_notify_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_notify_email
    ADD CONSTRAINT user_watches_group_notify_email_pkey PRIMARY KEY (user_account_id, group_id, sent_at);


--
-- Name: user_watches_group_prompt_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_prompt_email
    ADD CONSTRAINT user_watches_group_prompt_email_pkey PRIMARY KEY (user_account_id, group_id, sent_at);


--
-- Name: user_watches_group_stop_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_stop
    ADD CONSTRAINT user_watches_group_stop_pkey PRIMARY KEY (user_account_id, group_id, access_key);


--
-- Name: user_watches_site_group_prompt_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_group_prompt_email
    ADD CONSTRAINT user_watches_site_group_prompt_email_pkey PRIMARY KEY (user_account_id, group_id, sent_at);


--
-- Name: user_watches_site_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_information
    ADD CONSTRAINT user_watches_site_information_pkey PRIMARY KEY (user_account_id, site_id);


--
-- Name: user_watches_site_notify_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_notify_email
    ADD CONSTRAINT user_watches_site_notify_email_pkey PRIMARY KEY (user_account_id, site_id, sent_at);


--
-- Name: user_watches_site_prompt_email_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_prompt_email
    ADD CONSTRAINT user_watches_site_prompt_email_pkey PRIMARY KEY (user_account_id, site_id, sent_at);


--
-- Name: user_watches_site_stop_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_stop
    ADD CONSTRAINT user_watches_site_stop_pkey PRIMARY KEY (user_account_id, site_id, access_key);


--
-- Name: venue_history_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_history
    ADD CONSTRAINT venue_history_pkey PRIMARY KEY (venue_id, created_at);


--
-- Name: venue_information_pkey; Type: CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information
    ADD CONSTRAINT venue_information_pkey PRIMARY KEY (id);


--
-- Name: area_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX area_information_slug ON public.area_information USING btree (site_id, slug);


--
-- Name: country_two_char_code; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX country_two_char_code ON public.country USING btree (two_char_code);


--
-- Name: curated_list_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX curated_list_information_slug ON public.curated_list_information USING btree (site_id, slug);


--
-- Name: event_custom_field_definition_information_key_unique; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX event_custom_field_definition_information_key_unique ON public.event_custom_field_definition_information USING btree (site_id, key);


--
-- Name: event_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX event_information_slug ON public.event_information USING btree (site_id, slug);


--
-- Name: gist_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX gist_information_slug ON public.gist_information USING btree (site_id, slug);


--
-- Name: group_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX group_information_slug ON public.group_information USING btree (site_id, slug);


--
-- Name: import_url_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX import_url_information_slug ON public.venue_information USING btree (site_id, slug);


--
-- Name: media_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX media_information_slug ON public.media_information USING btree (site_id, slug);


--
-- Name: new_event_draft_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX new_event_draft_information_slug ON public.new_event_draft_information USING btree (site_id, slug);


--
-- Name: send_email_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX send_email_information_slug ON public.group_information USING btree (site_id, slug);


--
-- Name: site_quota_code; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX site_quota_code ON public.site_quota_information USING btree (code);


--
-- Name: site_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX site_slug ON public.site_information USING btree (slug);


--
-- Name: tag_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX tag_information_slug ON public.tag_information USING btree (site_id, slug);


--
-- Name: task_log_latest; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE INDEX task_log_latest ON public.task_log USING btree (started_at);


--
-- Name: task_log_task; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE INDEX task_log_task ON public.task_log USING btree (extension_id, task_id);


--
-- Name: user_account_information_email_canonical; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX user_account_information_email_canonical ON public.user_account_information USING btree (email_canonical);


--
-- Name: user_account_information_username_canonical; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX user_account_information_username_canonical ON public.user_account_information USING btree (username_canonical);


--
-- Name: venue_information_slug; Type: INDEX; Schema: public; Owner: opentechcalendar3
--

CREATE UNIQUE INDEX venue_information_slug ON public.venue_information USING btree (site_id, slug);


--
-- Name: area_history_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_history
    ADD CONSTRAINT area_history_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: area_history_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_history
    ADD CONSTRAINT area_history_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: area_history_parent_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_history
    ADD CONSTRAINT area_history_parent_area_id FOREIGN KEY (parent_area_id) REFERENCES public.area_information(id);


--
-- Name: area_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_history
    ADD CONSTRAINT area_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: area_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information
    ADD CONSTRAINT area_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: area_information_is_duplicate_of_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information
    ADD CONSTRAINT area_information_is_duplicate_of_id FOREIGN KEY (is_duplicate_of_id) REFERENCES public.area_information(id);


--
-- Name: area_information_parent_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information
    ADD CONSTRAINT area_information_parent_area_id FOREIGN KEY (parent_area_id) REFERENCES public.area_information(id);


--
-- Name: area_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.area_information
    ADD CONSTRAINT area_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: cached_area_has_parent_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.cached_area_has_parent
    ADD CONSTRAINT cached_area_has_parent_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: cached_area_has_parent_has_parent_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.cached_area_has_parent
    ADD CONSTRAINT cached_area_has_parent_has_parent_area_id FOREIGN KEY (has_parent_area_id) REFERENCES public.area_information(id);


--
-- Name: contact_support_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.contact_support
    ADD CONSTRAINT contact_support_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: country_in_site_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.country_in_site_information
    ADD CONSTRAINT country_in_site_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: country_in_site_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.country_in_site_information
    ADD CONSTRAINT country_in_site_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: curated_list_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_history
    ADD CONSTRAINT curated_list_history_id FOREIGN KEY (curated_list_id) REFERENCES public.curated_list_information(id);


--
-- Name: curated_list_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_history
    ADD CONSTRAINT curated_list_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: curated_list_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.curated_list_information
    ADD CONSTRAINT curated_list_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: event_custom_field_definition_history_event_custom_field_defini; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_history
    ADD CONSTRAINT event_custom_field_definition_history_event_custom_field_defini FOREIGN KEY (event_custom_field_definition_id) REFERENCES public.event_custom_field_definition_information(id);


--
-- Name: event_custom_field_definition_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_history
    ADD CONSTRAINT event_custom_field_definition_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_custom_field_definition_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_custom_field_definition_information
    ADD CONSTRAINT event_custom_field_definition_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: event_has_tag_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_has_tag
    ADD CONSTRAINT event_has_tag_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_has_tag_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_has_tag
    ADD CONSTRAINT event_has_tag_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: event_has_tag_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_has_tag
    ADD CONSTRAINT event_has_tag_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_has_tag_tag_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_has_tag
    ADD CONSTRAINT event_has_tag_tag_id FOREIGN KEY (tag_id) REFERENCES public.tag_information(id);


--
-- Name: event_history_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: event_history_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: event_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: event_history_legacy_location_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES public.legacy_location_information(id);


--
-- Name: event_history_user_account_d; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_user_account_d FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_history_venue_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_history
    ADD CONSTRAINT event_history_venue_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: event_in_curated_list_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_curated_list
    ADD CONSTRAINT event_in_curated_list_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_in_curated_list_curated_list_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_curated_list
    ADD CONSTRAINT event_in_curated_list_curated_list_id FOREIGN KEY (curated_list_id) REFERENCES public.curated_list_information(id);


--
-- Name: event_in_curated_list_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_curated_list
    ADD CONSTRAINT event_in_curated_list_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: event_in_curated_list_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_curated_list
    ADD CONSTRAINT event_in_curated_list_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_in_group_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_group
    ADD CONSTRAINT event_in_group_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_in_group_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_group
    ADD CONSTRAINT event_in_group_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: event_in_group_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_group
    ADD CONSTRAINT event_in_group_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: event_in_group_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_in_group
    ADD CONSTRAINT event_in_group_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: event_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: event_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: event_information_event_recur_set_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_event_recur_set_id FOREIGN KEY (event_recur_set_id) REFERENCES public.event_recur_set(id);


--
-- Name: event_information_import_url_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_import_url_id FOREIGN KEY (import_url_id) REFERENCES public.import_url_information(id);


--
-- Name: event_information_is_duplicate_of_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_is_duplicate_of_id FOREIGN KEY (is_duplicate_of_id) REFERENCES public.event_information(id);


--
-- Name: event_information_legacy_location_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES public.legacy_location_information(id);


--
-- Name: event_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: event_information_venue_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.event_information
    ADD CONSTRAINT event_information_venue_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: gist_content_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: gist_content_information_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: gist_content_information_gist_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_gist_id FOREIGN KEY (gist_id) REFERENCES public.gist_information(id);


--
-- Name: gist_content_information_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: gist_content_information_venue_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_content_information
    ADD CONSTRAINT gist_content_information_venue_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: gist_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.gist_information
    ADD CONSTRAINT gist_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: group_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_history
    ADD CONSTRAINT group_history_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: group_history_user_account_d; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_history
    ADD CONSTRAINT group_history_user_account_d FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: group_in_curated_list_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_in_curated_list
    ADD CONSTRAINT group_in_curated_list_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: group_in_curated_list_curated_list_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_in_curated_list
    ADD CONSTRAINT group_in_curated_list_curated_list_id FOREIGN KEY (curated_list_id) REFERENCES public.curated_list_information(id);


--
-- Name: group_in_curated_list_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_in_curated_list
    ADD CONSTRAINT group_in_curated_list_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: group_in_curated_list_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_in_curated_list
    ADD CONSTRAINT group_in_curated_list_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: group_information_is_duplicate_of_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_information
    ADD CONSTRAINT group_information_is_duplicate_of_id FOREIGN KEY (is_duplicate_of_id) REFERENCES public.group_information(id);


--
-- Name: group_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.group_information
    ADD CONSTRAINT group_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: import_url_history_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: import_url_history_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: import_url_history_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: import_url_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_id FOREIGN KEY (import_url_id) REFERENCES public.import_url_information(id);


--
-- Name: import_url_history_legacy_location_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES public.legacy_location_information(id);


--
-- Name: import_url_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_history
    ADD CONSTRAINT import_url_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: import_url_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: import_url_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: import_url_information_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: import_url_information_legacy_location_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_legacy_location_id FOREIGN KEY (legacy_location_id) REFERENCES public.legacy_location_information(id);


--
-- Name: import_url_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_information
    ADD CONSTRAINT import_url_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: import_url_result_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.import_url_result
    ADD CONSTRAINT import_url_result_id FOREIGN KEY (import_url_id) REFERENCES public.import_url_information(id);


--
-- Name: imported_event_import_url_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event
    ADD CONSTRAINT imported_event_import_url_id FOREIGN KEY (import_url_id) REFERENCES public.import_url_information(id);


--
-- Name: imported_event_is_event_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event_is_event
    ADD CONSTRAINT imported_event_is_event_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: imported_event_is_event_imported_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.imported_event_is_event
    ADD CONSTRAINT imported_event_is_event_imported_event_id FOREIGN KEY (imported_event_id) REFERENCES public.imported_event(id);


--
-- Name: incoming_link_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.incoming_link
    ADD CONSTRAINT incoming_link_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: legacy_location_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_location_information
    ADD CONSTRAINT legacy_location_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: legacy_location_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_location_information
    ADD CONSTRAINT legacy_location_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: legacy_location_information_legacy_region_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_location_information
    ADD CONSTRAINT legacy_location_information_legacy_region_id FOREIGN KEY (legacy_region_id) REFERENCES public.legacy_region_information(id);


--
-- Name: legacy_region_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_region_information
    ADD CONSTRAINT legacy_region_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: legacy_region_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.legacy_region_information
    ADD CONSTRAINT legacy_region_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: media_history_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_history
    ADD CONSTRAINT media_history_media_id FOREIGN KEY (media_id) REFERENCES public.media_information(id);


--
-- Name: media_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_history
    ADD CONSTRAINT media_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_event_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_event
    ADD CONSTRAINT media_in_event_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_event_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_event
    ADD CONSTRAINT media_in_event_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: media_in_event_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_event
    ADD CONSTRAINT media_in_event_media_id FOREIGN KEY (media_id) REFERENCES public.media_information(id);


--
-- Name: media_in_event_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_event
    ADD CONSTRAINT media_in_event_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_group_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_group
    ADD CONSTRAINT media_in_group_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_group_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_group
    ADD CONSTRAINT media_in_group_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: media_in_group_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_group
    ADD CONSTRAINT media_in_group_media_id FOREIGN KEY (media_id) REFERENCES public.media_information(id);


--
-- Name: media_in_group_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_group
    ADD CONSTRAINT media_in_group_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_venue_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_venue
    ADD CONSTRAINT media_in_venue_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_venue_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_venue
    ADD CONSTRAINT media_in_venue_media_id FOREIGN KEY (media_id) REFERENCES public.media_information(id);


--
-- Name: media_in_venue_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_venue
    ADD CONSTRAINT media_in_venue_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_in_venue_venue_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_in_venue
    ADD CONSTRAINT media_in_venue_venue_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: media_information_created_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_information
    ADD CONSTRAINT media_information_created_by_user_account_id FOREIGN KEY (created_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_information_deleted_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_information
    ADD CONSTRAINT media_information_deleted_by_user_account_id FOREIGN KEY (deleted_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: media_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.media_information
    ADD CONSTRAINT media_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: new_event_draft_history_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_history
    ADD CONSTRAINT new_event_draft_history_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: new_event_draft_history_new_event_draft_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_history
    ADD CONSTRAINT new_event_draft_history_new_event_draft_id FOREIGN KEY (new_event_draft_id) REFERENCES public.new_event_draft_information(id);


--
-- Name: new_event_draft_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_history
    ADD CONSTRAINT new_event_draft_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: new_event_draft_history_was_existing_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_history
    ADD CONSTRAINT new_event_draft_history_was_existing_event_id FOREIGN KEY (was_existing_event_id) REFERENCES public.event_information(id);


--
-- Name: new_event_draft_information_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information
    ADD CONSTRAINT new_event_draft_information_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: new_event_draft_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information
    ADD CONSTRAINT new_event_draft_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: new_event_draft_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information
    ADD CONSTRAINT new_event_draft_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: new_event_draft_information_was_existing_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.new_event_draft_information
    ADD CONSTRAINT new_event_draft_information_was_existing_event_id FOREIGN KEY (was_existing_event_id) REFERENCES public.event_information(id);


--
-- Name: permission_in_user_group_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.permission_in_user_group
    ADD CONSTRAINT permission_in_user_group_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: permission_in_user_group_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.permission_in_user_group
    ADD CONSTRAINT permission_in_user_group_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: permission_in_user_group_user_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.permission_in_user_group
    ADD CONSTRAINT permission_in_user_group_user_group_id FOREIGN KEY (user_group_id) REFERENCES public.user_group_information(id);


--
-- Name: site_feature_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_feature_information
    ADD CONSTRAINT site_feature_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: site_feature_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_feature_history
    ADD CONSTRAINT site_feature_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: site_feature_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_feature_history
    ADD CONSTRAINT site_feature_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: site_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_history
    ADD CONSTRAINT site_history_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: site_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_history
    ADD CONSTRAINT site_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: site_information_site_quota_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_information
    ADD CONSTRAINT site_information_site_quota_id FOREIGN KEY (site_quota_id) REFERENCES public.site_quota_information(id);


--
-- Name: site_profile_media_history_logo_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_history
    ADD CONSTRAINT site_profile_media_history_logo_media_id FOREIGN KEY (logo_media_id) REFERENCES public.media_information(id);


--
-- Name: site_profile_media_history_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_history
    ADD CONSTRAINT site_profile_media_history_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: site_profile_media_information_logo_media_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_information
    ADD CONSTRAINT site_profile_media_information_logo_media_id FOREIGN KEY (logo_media_id) REFERENCES public.media_information(id);


--
-- Name: site_profile_media_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_information
    ADD CONSTRAINT site_profile_media_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: ssite_profile_media_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.site_profile_media_history
    ADD CONSTRAINT ssite_profile_media_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: sysadmin_comment_about_area_area_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_area
    ADD CONSTRAINT sysadmin_comment_about_area_area_account_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: sysadmin_comment_about_area_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_area
    ADD CONSTRAINT sysadmin_comment_about_area_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_event_event_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_event
    ADD CONSTRAINT sysadmin_comment_about_event_event_account_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: sysadmin_comment_about_event_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_event
    ADD CONSTRAINT sysadmin_comment_about_event_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_group_group_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_group
    ADD CONSTRAINT sysadmin_comment_about_group_group_account_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: sysadmin_comment_about_group_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_group
    ADD CONSTRAINT sysadmin_comment_about_group_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_media_media_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_media
    ADD CONSTRAINT sysadmin_comment_about_media_media_account_id FOREIGN KEY (media_id) REFERENCES public.media_information(id);


--
-- Name: sysadmin_comment_about_media_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_media
    ADD CONSTRAINT sysadmin_comment_about_media_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_site_site_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_site
    ADD CONSTRAINT sysadmin_comment_about_site_site_account_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: sysadmin_comment_about_site_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_site
    ADD CONSTRAINT sysadmin_comment_about_site_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_user_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_user
    ADD CONSTRAINT sysadmin_comment_about_user_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_user_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_user
    ADD CONSTRAINT sysadmin_comment_about_user_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: sysadmin_comment_about_venue_sysadmin_comment_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_venue
    ADD CONSTRAINT sysadmin_comment_about_venue_sysadmin_comment_id FOREIGN KEY (sysadmin_comment_id) REFERENCES public.sysadmin_comment_information(id);


--
-- Name: sysadmin_comment_about_venue_venue_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_about_venue
    ADD CONSTRAINT sysadmin_comment_about_venue_venue_account_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: sysadmin_comment_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.sysadmin_comment_information
    ADD CONSTRAINT sysadmin_comment_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: tag_history_tag_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_history
    ADD CONSTRAINT tag_history_tag_id FOREIGN KEY (tag_id) REFERENCES public.tag_information(id);


--
-- Name: tag_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_history
    ADD CONSTRAINT tag_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: tag_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.tag_information
    ADD CONSTRAINT tag_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_account_general_security_key_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_general_security_key
    ADD CONSTRAINT user_account_general_security_key_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_account_private_feed_key_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_private_feed_key
    ADD CONSTRAINT user_account_private_feed_key_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_account_remember_me_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_remember_me
    ADD CONSTRAINT user_account_remember_me_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_account_reset_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_reset
    ADD CONSTRAINT user_account_reset_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_account_verify_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_account_verify_email
    ADD CONSTRAINT user_account_verify_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_at_event_information_event_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_at_event_information
    ADD CONSTRAINT user_at_event_information_event_id FOREIGN KEY (event_id) REFERENCES public.event_information(id);


--
-- Name: user_at_event_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_at_event_information
    ADD CONSTRAINT user_at_event_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_group_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_history
    ADD CONSTRAINT user_group_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_group_history_user_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_history
    ADD CONSTRAINT user_group_history_user_group_id FOREIGN KEY (user_group_id) REFERENCES public.user_group_information(id);


--
-- Name: user_group_in_site_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_in_site
    ADD CONSTRAINT user_group_in_site_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_group_in_site_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_in_site
    ADD CONSTRAINT user_group_in_site_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_group_in_site_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_in_site
    ADD CONSTRAINT user_group_in_site_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_group_in_site_user_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_group_in_site
    ADD CONSTRAINT user_group_in_site_user_group_id FOREIGN KEY (user_group_id) REFERENCES public.user_group_information(id);


--
-- Name: user_has_no_editor_permissions_in_site_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_has_no_editor_permissions_in_site
    ADD CONSTRAINT user_has_no_editor_permissions_in_site_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_has_no_editor_permissions_in_site_removed_by_user_account_; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_has_no_editor_permissions_in_site
    ADD CONSTRAINT user_has_no_editor_permissions_in_site_removed_by_user_account_ FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_has_no_editor_permissions_in_site_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_has_no_editor_permissions_in_site
    ADD CONSTRAINT user_has_no_editor_permissions_in_site_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_has_no_editor_permissions_in_site_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_has_no_editor_permissions_in_site
    ADD CONSTRAINT user_has_no_editor_permissions_in_site_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_curated_list_information_curated_list_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_curated_list_information
    ADD CONSTRAINT user_in_curated_list_information_curated_list_id FOREIGN KEY (curated_list_id) REFERENCES public.curated_list_information(id);


--
-- Name: user_in_curated_list_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_curated_list_information
    ADD CONSTRAINT user_in_curated_list_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_gist_information_gist_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_gist_information
    ADD CONSTRAINT user_in_gist_information_gist_id FOREIGN KEY (gist_id) REFERENCES public.gist_information(id);


--
-- Name: user_in_gist_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_gist_information
    ADD CONSTRAINT user_in_gist_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_site_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_site_information
    ADD CONSTRAINT user_in_site_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_in_site_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_site_information
    ADD CONSTRAINT user_in_site_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_user_group_added_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_user_group
    ADD CONSTRAINT user_in_user_group_added_by_user_account_id FOREIGN KEY (added_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_user_group_removed_by_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_user_group
    ADD CONSTRAINT user_in_user_group_removed_by_user_account_id FOREIGN KEY (removed_by_user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_user_group_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_user_group
    ADD CONSTRAINT user_in_user_group_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_in_user_group_user_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_in_user_group
    ADD CONSTRAINT user_in_user_group_user_group_id FOREIGN KEY (user_group_id) REFERENCES public.user_group_information(id);


--
-- Name: user_interested_in_site_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_interested_in_site_information
    ADD CONSTRAINT user_interested_in_site_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_interested_in_site_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_interested_in_site_information
    ADD CONSTRAINT user_interested_in_site_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_notification_preference_user_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification_preference
    ADD CONSTRAINT user_notification_preference_user_id FOREIGN KEY (user_id) REFERENCES public.user_account_information(id);


--
-- Name: user_notification_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification
    ADD CONSTRAINT user_notification_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_notification_user_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_notification
    ADD CONSTRAINT user_notification_user_id FOREIGN KEY (user_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_area_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_information
    ADD CONSTRAINT user_watches_area_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: user_watches_area_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_information
    ADD CONSTRAINT user_watches_area_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_area_stop_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_stop
    ADD CONSTRAINT user_watches_area_stop_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: user_watches_area_stop_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_area_stop
    ADD CONSTRAINT user_watches_area_stop_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_group_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_information
    ADD CONSTRAINT user_watches_group_information_site_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: user_watches_group_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_information
    ADD CONSTRAINT user_watches_group_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_group_notify_email_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_notify_email
    ADD CONSTRAINT user_watches_group_notify_email_site_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: user_watches_group_notify_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_notify_email
    ADD CONSTRAINT user_watches_group_notify_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_group_prompt_email_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_prompt_email
    ADD CONSTRAINT user_watches_group_prompt_email_site_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: user_watches_group_prompt_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_prompt_email
    ADD CONSTRAINT user_watches_group_prompt_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_group_stop_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_stop
    ADD CONSTRAINT user_watches_group_stop_site_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: user_watches_group_stop_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_group_stop
    ADD CONSTRAINT user_watches_group_stop_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_site_group_prompt_email_group_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_group_prompt_email
    ADD CONSTRAINT user_watches_site_group_prompt_email_group_id FOREIGN KEY (group_id) REFERENCES public.group_information(id);


--
-- Name: user_watches_site_group_prompt_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_group_prompt_email
    ADD CONSTRAINT user_watches_site_group_prompt_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_site_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_information
    ADD CONSTRAINT user_watches_site_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_watches_site_information_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_information
    ADD CONSTRAINT user_watches_site_information_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_site_notify_email_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_notify_email
    ADD CONSTRAINT user_watches_site_notify_email_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_watches_site_notify_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_notify_email
    ADD CONSTRAINT user_watches_site_notify_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_site_prompt_email_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_prompt_email
    ADD CONSTRAINT user_watches_site_prompt_email_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_watches_site_prompt_email_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_prompt_email
    ADD CONSTRAINT user_watches_site_prompt_email_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: user_watches_site_stop_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_stop
    ADD CONSTRAINT user_watches_site_stop_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: user_watches_site_stop_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.user_watches_site_stop
    ADD CONSTRAINT user_watches_site_stop_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: venue_history_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_history
    ADD CONSTRAINT venue_history_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: venue_history_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_history
    ADD CONSTRAINT venue_history_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: venue_history_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_history
    ADD CONSTRAINT venue_history_id FOREIGN KEY (venue_id) REFERENCES public.venue_information(id);


--
-- Name: venue_history_user_account_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_history
    ADD CONSTRAINT venue_history_user_account_id FOREIGN KEY (user_account_id) REFERENCES public.user_account_information(id);


--
-- Name: venue_information_area_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information
    ADD CONSTRAINT venue_information_area_id FOREIGN KEY (area_id) REFERENCES public.area_information(id);


--
-- Name: venue_information_country_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information
    ADD CONSTRAINT venue_information_country_id FOREIGN KEY (country_id) REFERENCES public.country(id);


--
-- Name: venue_information_is_duplicate_of_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information
    ADD CONSTRAINT venue_information_is_duplicate_of_id FOREIGN KEY (is_duplicate_of_id) REFERENCES public.venue_information(id);


--
-- Name: venue_information_site_id; Type: FK CONSTRAINT; Schema: public; Owner: opentechcalendar3
--

ALTER TABLE ONLY public.venue_information
    ADD CONSTRAINT venue_information_site_id FOREIGN KEY (site_id) REFERENCES public.site_information(id);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

