#!/usr/bin/env bash

npm i

for d in */ ; do
	pushd $d

	if [[ -e Gruntfile.js ]]; then
		ln -s ../node_modules
		grunt
		rm node_modules
	fi

	popd
done
