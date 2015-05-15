#!/bin/bash

echo "cd ${SET_DIR}/app/Console/"
cd ${SET_DIR}/app/Console/

table=`grep -e "public \\$.\+ = array" ${PUGLIN_PATH}/Config/Schema/schema.php`
for tbl in ${table}; do
	case "${tbl}" in
		"public" ) continue ;;
		"=" ) continue ;;
		"array(" ) continue ;;
		* )
		
		CAKE_BAKE="bake model ${PLUGIN_NAME}.`echo $tbl | cut -b 2-` -c master"
		echo "./cake ${CAKE_BAKE}"
		./cake ${CAKE_BAKE}
	esac
done


for model in `ls ${PUGLIN_PATH}/Model/`; do
	modelFile=${PUGLIN_PATH}/Model/${model}

	if [ -f $modelFile ]; then
		funcCleanPHPDoc ${modelFile}
	fi
done
