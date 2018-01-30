#!/usr/bin/env bash

build_assets() {
	pushd $1 > /dev/null

	if [[ -e Gruntfile.js ]]; then
		echo ""
		echo "============= $1 ============="

		ln -s ../node_modules
		grunt
		rm node_modules
	fi

	popd > /dev/null
}

if [ -z "$1" ]; then
	for d in */ ; do
		build_assets "$d"
	done
else
	build_assets "$1"
fi
