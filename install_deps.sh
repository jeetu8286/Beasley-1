#!/bin/bash

pushd .
cd plugins/greatermedia-livefyre
composer install --no-dev --optimize-autoloader --no-interaction
popd
