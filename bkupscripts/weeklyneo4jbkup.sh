#!/bin/sh

# ******************************************************
# Script: weeklyneo4jbkup.sh
#
# This script is used to backup the Neo4j database.
# It is meant to run once a day via CRON and keeps the
# last seven backups. 
#
# Author: Jason Ash
# Date: 08/25/2015
#
# Example CRON call for 4:00am every wednesday: 
#     0 4 * * 3 /var/www/weeklyneo4jbkup.sh  >/dev/null 2>&1
#
# ******************************************************


/etc/init.d/neo4j stop

cd /opt

/bin/rm /home/jasonash/neo4jbkups/weekly4.tar.gz

/bin/mv /home/jasonash/neo4jbkups/weekly3.tar.gz /home/jasonash/neo4jbkups/weekly4.tar.gz
/bin/mv /home/jasonash/neo4jbkups/weekly2.tar.gz /home/jasonash/neo4jbkups/weekly3.tar.gz
/bin/mv /home/jasonash/neo4jbkups/weekly1.tar.gz /home/jasonash/neo4jbkups/weekly2.tar.gz

/bin/tar -zcf /home/jasonash/neo4jbkups/weekly1.tar.gz graph.db/

/etc/init.d/neo4j start

#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/weekly3.tar.gz /home/jasonash/neo4jbkups/weekly4.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/weekly2.tar.gz /home/jasonash/neo4jbkups/weekly3.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/weekly1.tar.gz /home/jasonash/neo4jbkups/weekly2.tar.gz


#/usr/bin/scp /home/jasonash/neo4jbkups/weekly1.tar.gz hopper.kgs.ku.edu:/home/jasonash/neo4jbkups/weekly1.tar.gz




