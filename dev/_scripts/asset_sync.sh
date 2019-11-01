#!/bin/bash

SSH_USER=kreisvie
SSH_HOST=s001.cyon.net
SSH_FOLDER=/home/kreisvie/public_html/kreisvier.ch/production

cd "$(dirname "$0")"
BASE_DIR=$PWD
# Root directory to the project
ROOT_DIR=${BASE_DIR}/../

printf "FETCHING FILES...\n"

rsync -chavzP --stats ${SSH_USER}@${SSH_HOST}:${SSH_FOLDER}/public/assets ${ROOT_DIR}/public/