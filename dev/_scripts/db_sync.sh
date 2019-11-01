#!/bin/bash

SSH_USER=kreisvie
SSH_HOST=s001.cyon.net
SSH_FOLDER=/home/kreisvie/public_html/kreisvier.ch/staging

cd "$(dirname "$0")"
BASE_DIR=$PWD
# Root directory to the project
ROOT_DIR=${BASE_DIR}/../

printf "LOGIN REMOTELY...\n"

ssh ${SSH_USER}@${SSH_HOST} SSH_FOLDER=$SSH_FOLDER 'bash -s' << 'ENDSSH' > $BASE_DIR/sync.sql
    cd $SSH_FOLDER

    read_var() {
        VAR=$(grep $1 $2 | xargs)
        IFS="=" read -ra VAR <<< "$VAR"
        echo ${VAR[1]}
    }
    DB_SERVER=$(read_var DB_SERVER .env)
    DB_PORT=$(read_var DB_PORT .env)
    DB_DATABASE=$(read_var DB_DATABASE .env)
    DB_USER=$(read_var DB_USER .env)
    DB_PASSWORD=$(read_var DB_PASSWORD .env)

    mysqldump --host=$DB_SERVER --port=$DB_PORT --user=$DB_USER --password=$DB_PASSWORD --no-create-db $DB_DATABASE
ENDSSH

printf "FETCHED REMOTE DB.\n"

read_var() {
    VAR=$(grep $1 $2 | xargs)
    IFS="=" read -ra VAR <<< "$VAR"
    echo ${VAR[1]}
}

DB_SERVER=$(read_var DB_SERVER $ROOT_DIR/.env)
DB_PORT=$(read_var DB_PORT $ROOT_DIR/.env)
DB_DATABASE=$(read_var DB_DATABASE $ROOT_DIR/.env)
DB_USER=$(read_var DB_USER $ROOT_DIR/.env)
DB_PASSWORD=$(read_var DB_PASSWORD $ROOT_DIR/.env)

/Applications/MAMP/Library/bin/mysql --host=$DB_SERVER --port=$DB_PORT --user=$DB_USER --password=$DB_PASSWORD $DB_DATABASE<<EOFMYSQL
    DROP DATABASE ${DB_DATABASE};
    CREATE DATABASE ${DB_DATABASE} CHARACTER SET UTF8mb4 COLLATE utf8mb4_bin
EOFMYSQL

/Applications/MAMP/Library/bin/mysql --host=$DB_SERVER --port=$DB_PORT --user=$DB_USER --password=$DB_PASSWORD $DB_DATABASE < $BASE_DIR/sync.sql

rm $BASE_DIR/sync.sql

printf "LOCAL DB SYNCED.\n"
