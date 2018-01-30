#!/usr/bin/env bash

npm i

for d in */ ; do
	pushd $d > /dev/null

	if [[ -e Gruntfile.js ]]; then
		echo ""
		echo "============= $d ============="

		ln -s ../node_modules
		grunt
		rm node_modules
	fi

	popd > /dev/null
done
