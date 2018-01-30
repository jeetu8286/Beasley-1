#!/usr/bin/env bash

build_assets() {
	pushd $1 > /dev/null

	if [[ -e Gruntfile.js ]]; then
		echo ""
		echo ":::::::::::::::::::::::::::::: $1 ::::::::::::::::::::::::::::::"

		if [ -d node_modules ]; then
			rm -rf node_modules
		fi

		if [ -L node_modules ]; then
			rm node_modules
		fi

		if [ -e package.json ] || [ -L package.json ]; then
			rm package.json
		fi

		ln -s ../node_modules
		ln -s ../package.json

		grunt

		rm package.json
		rm node_modules
	fi

	popd > /dev/null
}

if [ ! -d node_modules ]; then
	npm i
fi

if [ -z "$1" ]; then
	for d in */ ; do
		build_assets "$d"
	done
else
	build_assets "$1"
fi
