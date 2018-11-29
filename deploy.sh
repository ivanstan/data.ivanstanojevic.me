#!/usr/bin/env bash
set -e

export DEPLOY_PATH=/home/glutenfr/data.ivanstanojevic.me
export HOST=gluten-free.rs
export USER=glutenfr
export PORT=2233
export ARTIFACT_NAME=artifact.tar.gz;

composer install
yarn build

npm audit
bin/phpunit --coverage-text --testsuite Test

tar -czf ${ARTIFACT_NAME} -T deploy.list
scp -r -P ${PORT} ${ARTIFACT_NAME} ${USER}@${HOST}:/${DEPLOY_PATH}/
rm ${ARTIFACT_NAME}

ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && rm -rf ./assets ./bin ./config ./src ./templates ./vendor"
ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && tar -xf $ARTIFACT_NAME && rm $ARTIFACT_NAME"

ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console cache:clear --env=prod"
ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console doctrine:migrations:migrate --no-interaction"
