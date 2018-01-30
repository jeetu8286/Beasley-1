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

npm i

for d in */ ; do
	build_assets "$d"
done
