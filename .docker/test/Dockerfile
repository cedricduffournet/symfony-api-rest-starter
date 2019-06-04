FROM docker.io/dakodapp/php-7:latest

ARG WORK_DIR
ARG ENV
WORKDIR $WORK_DIR

COPY app/composer.lock $WORK_DIR
COPY app/composer.json $WORK_DIR

COPY app/behat.yml.dist $WORK_DIR
COPY app/bin $WORK_DIR/bin
COPY app/config $WORK_DIR/config
COPY app/features $WORK_DIR/features
COPY app/public $WORK_DIR/public
COPY app/src $WORK_DIR/src
COPY app/templates $WORK_DIR/templates
COPY app/tests $WORK_DIR/tests
COPY app/translations $WORK_DIR/translations

COPY app/.env $WORK_DIR/.env 
COPY app/.env.test $WORK_DIR/.env.test

COPY .docker/set_env.sh $WORK_DIR
RUN chmod u+x $WORK_DIR/set_env.sh && $WORK_DIR/set_env.sh test

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install --prefer-dist --optimize-autoloader --no-scripts
RUN chown -R www-data:www-data $WORK_DIR

USER www-data