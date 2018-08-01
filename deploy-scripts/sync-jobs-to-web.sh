#!/usr/bin/env bash

# Run remote commands on the job server to sync web1, web2, and web3
ssh beanstalk@54.87.4.54 'rsync -vrxc --delete --dry-run /var/www/html/wordpress/ web1:/var/www/html/wordpress/ --exclude=wp-content/uploads --exclude=wp-content/upgrade'
ssh beanstalk@54.87.4.54 'rsync -vrxc --delete --dry-run /var/www/html/wordpress/ web2:/var/www/html/wordpress/ --exclude=wp-content/uploads --exclude=wp-content/upgrade'
ssh beanstalk@54.87.4.54 'rsync -vrxc --delete --dry-run /var/www/html/wordpress/ web3:/var/www/html/wordpress/ --exclude=wp-content/uploads --exclude=wp-content/upgrade'
