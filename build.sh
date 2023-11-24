#!/bin/sh
set -e

export $(egrep -v '^#' .env | xargs)

docker build \
    -t php:"${PHP_VERSION}" \
    -t php:latest \
    --build-arg PHP_BASE_IMAGE_VERSION="${PHP_VERSION}" \
    ./docker/php/
