# StraboSpot Server-Side Repository

This repository contains code necessary for the server-side functions of the StraboSpot project, including the strabospot.org web site, the database API, and the shapefile parser.



## Server Requirements: ##

* Apache web server
* PostgreSQL database server (for authentication and spatial functions)
* PostGIS (for spatial functions)
* Neo4j Database


## Apache Config: ##

The following should be included in the Apache config file:

CORS Headers:
~~~ bash
Header always set Access-Control-Allow-Origin "*"
Header always set Access-Control-Allow-Methods "POST, GET, OPTIONS, DELETE, PUT"
Header always set Access-Control-Max-Age "1000"
Header always set Access-Control-Allow-Headers "x-requested-with, Content-Type, origin, authorization, accept, client-security-token"
~~~

Postgres Authentication:
~~~ bash
        <Directory /var/www/db/>
                AuthName "Password Required."
                AuthType Basic
                AuthBasicAuthoritative Off
                Auth_PG_host localhost
                Auth_PG_port 5432
                Auth_PG_user readonly
                Auth_PG_pwd =postgrespasswordhere
                Auth_PG_database strabospot
                Auth_PG_pwd_table users
                Auth_PG_uid_field email
                Auth_PG_pwd_field password
                Auth_PG_encrypted on
                Auth_PG_pwd_whereclause " and active = TRUE "
                require valid-user
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
~~~


## PostgreSQL Config: ##

For user authentication, the following table needs to be created to store user information:
~~~ sql
CREATE TABLE users (
    pkey integer NOT NULL,
    firstname character varying(255) NOT NULL,
    lastname character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    hash character varying(255) NOT NULL,
    active boolean DEFAULT false NOT NULL
);

CREATE SEQUENCE users_pkey_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER TABLE ONLY users ALTER COLUMN pkey SET DEFAULT nextval('users_pkey_seq'::regclass);
~~~


## Neo4j Config: ##
In order to correctly deal with spatial features, the Neo4j spatial must be installed.

It is available here: [https://github.com/neo4j-contrib/spatial](https://github.com/neo4j-contrib/spatial)

An editable layer must be created:

http://neo4j.strabospot.org:80/db/data/ext/SpatialPlugin/graphdb/addEditableLayer

layer:strabospot
format:WKT
nodePropertyName:wkt

## Kobo Tools: ##
Tools necessary to parse Kobo XML docs are found in cv/

## Config Files ##

All configuration variables are stored in includes/config.inc.php.
Mail functions require a gmail account.
~~~ php
<?php

/*
config.inc.php
Config Variables for connection to databases and email
*/

$neousername = "neo4jusername";
$neopassword = "neo4jpassword";
$dbusername = "postgresqlusername";
$dbpassword = "postgresqlpassword";
$dbname = "postgresqldbname";
$dbhost = "postgresqlhost";
$straboemailaddress = "myusername@gmail.com";
$straboemailpassword = "mygmailpassword"
?>
~~~










