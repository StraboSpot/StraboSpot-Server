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


## Config Files ##

All configuration variables are stored in includes/config.inc.php.
Mail functions require a gmail account.
~~~ php
<?php

/*
config.inc.php
Config Variables for connection to databases and email
*/

$neousername = "myneo4jusername"; 			//Neo4j username
$neopassword = "myneo4jpassword"; 			//Neo4j password
$neohost = "neo4jhostname"; 				//Neo4j host
$neoport = 7687; 							//Neo4j port
$neomode = "bolt"; 							//Neo4j connection mode
$dbusername = "mydbusername"; 				//Postgres username
$dbpassword = "mydbpassword"; 				//Postgres password
$dbname = "mydbname"; 						//Postgres database name
$dbhost = "mydbhost"; 						//Postgres database host
$straboemailaddress = "myemail"; 			//Gmail address
$straboemailpassword = "myemailpassword" 	//Gmail password
$mailchimpAPIkey = "mailchimpapikey"; 		//For maintaining mailchimp mailing list
$captchasecret="googlecaptchakey"; 			//Google captcha key
$jwtsecret = "jwtsigningkey"; 				//JWT signing key
$pushover_token = "pushovertoken"; 			//For alerting about new user registrations
$pushover_user = "pushoveruser"; 			//Pushover user token
$vloc="/var/www/versions"; 					//location to store versions

?>
~~~








