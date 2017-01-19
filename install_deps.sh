#!/bin/bash

pushd .
cd plugins/greatermedia-gigya
composer install --no-dev --optimize-autoloader --no-interaction
popd