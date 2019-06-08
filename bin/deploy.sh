#!/usr/bin/env bash
set -e

print () {
    tput setab 2
    tput setaf 0
    echo "$1"
    tput setab 0
    tput setaf 7
}

export DEPLOY_PATH=/home/glutenfr/data.ivanstanojevic.me
export HOST=gluten-free.rs
export USER=glutenfr
export PORT=2233
export ARTIFACT_NAME=artifact-`date '+%Y%m%d%H%M%S'`.tar.gz;

print "composer install"
composer install
yarn build

#npm audit
#bin/phpunit --coverage-text --testsuite Test

tar -czf ${ARTIFACT_NAME} -T ./bin/deploy.list
scp -r -P ${PORT} ${ARTIFACT_NAME} ${USER}@${HOST}:/${DEPLOY_PATH}/
rm ${ARTIFACT_NAME}

ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && rm -rf ./assets ./bin ./config ./public/build ./src ./templates ./tests ./translations ./vendor"
ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && tar -xf $ARTIFACT_NAME && rm $ARTIFACT_NAME"

ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console cache:clear --env=prod"
ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console doctrine:migrations:migrate --no-interaction"
