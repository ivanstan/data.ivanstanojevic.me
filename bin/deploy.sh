#!/usr/bin/env bash
set -e

export DEPLOY_PATH=/home/example/
export HOST=example.com
export USER=root
export PORT=23
export ARTIFACT_NAME=artifact-`date '+%Y%m%d%H%M%S'`.tar.gz;

composer install
yarn build

npm audit
phpunit --coverage-text --testsuite Test

tar -czf ${ARTIFACT_NAME} -T deploy.list
scp -r -P ${PORT} ${ARTIFACT_NAME} ${USER}@${HOST}:/${DEPLOY_PATH}/
rm ${ARTIFACT_NAME}

ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && rm -rf ./bin ./config ./src ./templates ./vendor ./translations"
ssh ${USER}@${HOST} -p${PORT} "cd $DEPLOY_PATH && tar -xf $ARTIFACT_NAME && rm $ARTIFACT_NAME"

ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console cache:clear --env=prod"
ssh ${USER}@${HOST} -p${PORT} "$DEPLOY_PATH/bin/console doctrine:migrations:migrate --no-interaction"
