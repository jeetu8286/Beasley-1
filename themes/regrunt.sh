#!/usr/bin/env bash

read -p "Remove all node_modules? (y/N): " -r
if [[ $REPLY =~ ^[Yy]$ ]]; then
	find . -name "node_modules" -type d -exec rm -r "{}" \;
fi

RUN_YARN=0
read -p "Install NPM dependencies using Yarn? (y/N): " -r
if [[ $REPLY =~ ^[Yy]$ ]]; then
	RUN_YARN=1
fi

for d in */ ; do
	cd $d

	if [[ -e Gruntfile.js ]]; then
		if [[ $RUN_YARN = 1 ]]; then
			yarn
		fi

		grunt
	fi


	cd ..
done
