#!/bin/sh

# ******************************************************
# Script: weeklypostgresbkup.sh
#
# This script is used to backup the postgres database.
# It is meant to run once a day via CRON and keeps the
# last seven backups. 
#
# Author: Jason Ash
# Date: 09/15/2015
#
# Example CRON call for 5:00am every wednesday: 
#     0 5 * * 3 /home/jasonash/weeklypostgresbkup.sh  >/dev/null 2>&1
#
# ******************************************************



/bin/rm /home/jasonash/postgresbkups/weekly4.dump

/bin/mv /home/jasonash/postgresbkups/weekly3.dump /home/jasonash/postgresbkups/weekly4.dump
/bin/mv /home/jasonash/postgresbkups/weekly2.dump /home/jasonash/postgresbkups/weekly3.dump
/bin/mv /home/jasonash/postgresbkups/weekly1.dump /home/jasonash/postgresbkups/weekly2.dump

/bin/pg_dump -Upostgres --format=custom strabospot > /home/jasonash/postgresbkups/weekly1.dump

