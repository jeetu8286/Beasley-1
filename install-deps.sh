#!/bin/bash

composer install --no-dev -o

pushd plugins/greatermedia-content-syndication
composer install --no-dev -o
popd
