#!/bin/bash

# @todo move all plugins to repo/composer combination, and add --delete flag to plugins
rsync -vrxc --delete ./themes/ beanstalk@54.87.4.54:/var/www/html/wordpress/wp-content/themes/ --exclude-from=./deploy-scripts/rsync-excludes.txt
rsync -vrxc ./plugins/ beanstalk@54.87.4.54:/var/www/html/wordpress/wp-content/plugins/ --exclude-from=./deploy-scripts/rsync-excludes.txt
