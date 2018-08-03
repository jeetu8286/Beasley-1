#!/bin/bash

# Pushes code to job server
rsync -vrxc --delete ./ beanstalk@52.0.13.41:/var/www/html/wordpress/wp-content/ --exclude-from=./deploy-scripts/rsync-excludes.txt
