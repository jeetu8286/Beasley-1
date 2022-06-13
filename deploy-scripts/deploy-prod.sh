#!/bin/bash

SLACK_WEBHOOK="https://hooks.slack.com/services/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"

# Print commands to the screen
set -x

# Send Slack Notification about Pipeline deployment on production
curl -X POST -H 'Content-type: application/json' --data "{\"username\":\"Gitlab Pipeline\", \"text\":\"Gitlab pipeline is running the Production Deployment.\"}" $SLACK_WEBHOOK

# Catch Errors
set -euo pipefail

# Pushes code to job server
rsync -vrxc --delete --delay-updates --delete-after ./ beanstalk@52.0.13.41:/var/www/html/wordpress/wp-content/ --exclude-from=./deploy-scripts/rsync-excludes.txt

set +x
