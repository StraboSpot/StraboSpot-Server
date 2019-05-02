#!/bin/sh

# ******************************************************
# Script: dailypostgresbkup.sh
#
# This script is used to backup the Postgres database.
# It is meant to run once a day via CRON and keeps the
# last seven backups. 
#
# Author: Jason Ash
# Date: 09/15/2015
#
# Example CRON call for 1:00am every day: 
#     0 1 * * * /var/www/dailypostgresbkup.sh  >/dev/null 2>&1
#
# ******************************************************



/bin/rm /home/jasonash/postgresbkups/daily7.dump

/bin/mv /home/jasonash/postgresbkups/daily6.dump /home/jasonash/postgresbkups/daily7.dump
/bin/mv /home/jasonash/postgresbkups/daily5.dump /home/jasonash/postgresbkups/daily6.dump
/bin/mv /home/jasonash/postgresbkups/daily4.dump /home/jasonash/postgresbkups/daily5.dump
/bin/mv /home/jasonash/postgresbkups/daily3.dump /home/jasonash/postgresbkups/daily4.dump
/bin/mv /home/jasonash/postgresbkups/daily2.dump /home/jasonash/postgresbkups/daily3.dump
/bin/mv /home/jasonash/postgresbkups/daily1.dump /home/jasonash/postgresbkups/daily2.dump



/bin/pg_dump -Upostgres --format=custom strabospot > /home/jasonash/postgresbkups/daily1.dump



# ******************************************************
#      also copy let's encrypt file to BackBlaze folder
# ******************************************************
/bin/rm /home/jasonash/backblaze_holdings/postgres.dump
/bin/cp /home/jasonash/postgresbkups/daily1.dump /home/jasonash/backblaze_holdings/postgres.dump



