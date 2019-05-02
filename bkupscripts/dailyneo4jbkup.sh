#!/bin/sh

# ******************************************************
# Script: dailyneo4jbkup.sh
#
# This script is used to backup the Neo4j database.
# It is meant to run once a day via CRON and keeps the
# last seven backups. 
#
# Author: Jason Ash
# Date: 08/25/2015
#
# Example CRON call for 3:00am every day: 
#     0 3 * * * /var/www/dailyneo4jbkup.sh  >/dev/null 2>&1
#
# ******************************************************


/etc/init.d/neo4j stop

cd /opt

/bin/rm /home/jasonash/neo4jbkups/daily7.tar.gz

/bin/mv /home/jasonash/neo4jbkups/daily6.tar.gz /home/jasonash/neo4jbkups/daily7.tar.gz
/bin/mv /home/jasonash/neo4jbkups/daily5.tar.gz /home/jasonash/neo4jbkups/daily6.tar.gz
/bin/mv /home/jasonash/neo4jbkups/daily4.tar.gz /home/jasonash/neo4jbkups/daily5.tar.gz
/bin/mv /home/jasonash/neo4jbkups/daily3.tar.gz /home/jasonash/neo4jbkups/daily4.tar.gz
/bin/mv /home/jasonash/neo4jbkups/daily2.tar.gz /home/jasonash/neo4jbkups/daily3.tar.gz
/bin/mv /home/jasonash/neo4jbkups/daily1.tar.gz /home/jasonash/neo4jbkups/daily2.tar.gz

/bin/tar -zcf /home/jasonash/neo4jbkups/daily1.tar.gz graph.db/

# ******************************************************
#      rsync neo4j to hopper to keep things mirrored
# ******************************************************

# first, shut donw remote neo4j
#/usr/bin/ssh hopper.kgs.ku.edu /etc/init.d/neo4j stop

# rsync local neo4j to remote (hopper)
#/usr/bin/rsync -az --delete /usr/share/neo4j/data/graph.db/ hopper.kgs.ku.edu:/usr/share/neo4j/data/graph.db

# start remote neo4j
#/usr/bin/ssh hopper.kgs.ku.edu /etc/init.d/neo4j start

/etc/init.d/neo4j start



#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily6.tar.gz /home/jasonash/neo4jbkups/daily7.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily5.tar.gz /home/jasonash/neo4jbkups/daily6.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily4.tar.gz /home/jasonash/neo4jbkups/daily5.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily3.tar.gz /home/jasonash/neo4jbkups/daily4.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily2.tar.gz /home/jasonash/neo4jbkups/daily3.tar.gz
#/usr/bin/ssh hopper.kgs.ku.edu /bin/mv /home/jasonash/neo4jbkups/daily1.tar.gz /home/jasonash/neo4jbkups/daily2.tar.gz

#/usr/bin/scp /home/jasonash/neo4jbkups/daily1.tar.gz hopper.kgs.ku.edu:/home/jasonash/neo4jbkups/daily1.tar.gz


#also rsync /var/www
#/usr/bin/rsync -az --delete /var/www/ hopper.kgs.ku.edu:/var/www

# ******************************************************
#      also backup let's encrypt
# ******************************************************

/bin/rm /home/jasonash/letsencryptbkups/daily7.tar.gz

/bin/mv /home/jasonash/letsencryptbkups/daily6.tar.gz /home/jasonash/letsencryptbkups/daily7.tar.gz
/bin/mv /home/jasonash/letsencryptbkups/daily5.tar.gz /home/jasonash/letsencryptbkups/daily6.tar.gz
/bin/mv /home/jasonash/letsencryptbkups/daily4.tar.gz /home/jasonash/letsencryptbkups/daily5.tar.gz
/bin/mv /home/jasonash/letsencryptbkups/daily3.tar.gz /home/jasonash/letsencryptbkups/daily4.tar.gz
/bin/mv /home/jasonash/letsencryptbkups/daily2.tar.gz /home/jasonash/letsencryptbkups/daily3.tar.gz
/bin/mv /home/jasonash/letsencryptbkups/daily1.tar.gz /home/jasonash/letsencryptbkups/daily2.tar.gz

/bin/tar -zcf /home/jasonash/letsencryptbkups/daily1.tar.gz /etc/letsencrypt/


# ******************************************************
#      also copy neo4j file to BackBlaze folder
# ******************************************************
/bin/rm /home/jasonash/backblaze_holdings/neo4j.tar.gz
/bin/cp /home/jasonash/neo4jbkups/daily1.tar.gz /home/jasonash/backblaze_holdings/neo4j.tar.gz

# ******************************************************
#      also copy let's encrypt file to BackBlaze folder
# ******************************************************
/bin/rm /home/jasonash/backblaze_holdings/letsencryptbkup.tar.gz
/bin/cp /home/jasonash/letsencryptbkups/daily1.tar.gz /home/jasonash/backblaze_holdings/letsencryptbkup.tar.gz







