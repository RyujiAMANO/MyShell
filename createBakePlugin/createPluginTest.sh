#!/bin/bash

##
# Testファイルを作成
##
TEST_PATH=${PUGLIN_PATH}/Test

for path1 in `ls ${TEST_PATH}/`; do
	if [ -f ${TEST_PATH}/$path1 ]; then
		funcCleanPHPDoc ${TEST_PATH}/$path1
		continue
	fi
	for path2 in `ls ${TEST_PATH}/$path1/`; do
		if [ -f ${TEST_PATH}/$path1/$path2 ]; then
			funcCleanPHPDoc ${TEST_PATH}/$path1/$path2
			continue
		fi
		for path3 in `ls ${TEST_PATH}/$path1/$path2`; do
			if [ -f ${TEST_PATH}/$path1/$path2/$path3 ]; then
				funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3
				continue
			fi
			for path4 in `ls ${TEST_PATH}/$path1/$path2/$path3`; do
				if [ -f ${TEST_PATH}/$path1/$path2/$path3/$path4 ]; then
					funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3/$path4
					continue
				fi
				for path5 in `ls ${TEST_PATH}/$path1/$path2/$path3/$path4`; do
					if [ -f ${TEST_PATH}/$path1/$path2/$path3/$path4/$path5 ]; then
						funcCleanPHPDoc ${TEST_PATH}/$path1/$path2/$path3/$path4/$path5
						continue
					fi
				done
			done
		done
	done
done

