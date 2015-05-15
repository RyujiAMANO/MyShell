#!/bin/bash

##############
# デフォルト #
##############
PLUGIN_NAME="AccessCounters"
PLUGIN_SINGULAR_NAME="AccessCounter"
PLUGIN_SNAKE_NAME="access_counters"
PLUGIN_HAIFUN_NAME="access-counters"
CREATE_MODELS="AccessCounterFormats AccessCounterPartSettings AccessCounterSettings AccessCounters AccessCountersBlocks"

AUTHOR_NAME="Shohei Nakajima"
AUTHOR_EMAIL="nakajimashouhei@gmail.com"
BOOTSTRAP=/var/www/app/app/Config/bootstrap.php

CURDIR=`pwd`

########
# 関数 #
########

funcCleanPHPDoc() {
	sedFile=$1

	PATTERN="^* @author.\\+"
	REPLACE=" * @author ${AUTHOR_NAME} <${AUTHOR_EMAIL}>"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="^* @link \\+"
	REPLACE=" * @link "
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="^* @license \\+"
	REPLACE=" * @license "
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="${PLUGIN_SINGULAR_NAME}Block"
	REPLACE="${PLUGIN_NAME}Block"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN=" \* @author"
	REPLACE=" \* @author Noriko Arai <arai@nii.ac.jp>\n \* @author"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN=" \* @license.\\+"
	REPLACE=" \* @license http:\/\/www.netcommons.org\/license.txt NetCommons License\n \* @copyright Copyright 2014, NetCommons Project"
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}

	PATTERN="Summary for "
	REPLACE=""
	echo "sed -i -e \"s/${PATTERN}/${REPLACE}/g\" ${sedFile}"
	sed -i -e "s/${PATTERN}/${REPLACE}/g" ${sedFile}
}


cd /var/www/app/

##
# イニシャライズ
##

. ${CURDIR}/createPluginInitialize.sh


##
# bake pluginの実行
##
echo "bake pluginの実行します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginBakePlugin.sh
fi


##
# configファイルを作成
##
##
# configファイルを作成
##
#configRemove=0
#CONFIG_FILE=${PUGLIN_PATH}/Config/config.php
#if [ ! -f ${CONFIG_FILE} ]; then
#	echo "${CONFIG_FILE}を作成します。"
#	echo "cat $CONFIG_FILE"
#	cat << _EOF_ > $CONFIG_FILE
#<?php
#\$config = array();
#return \$config;
#_EOF_
#
#	configRemove=1
#fi

##
# Migrationファイルを作成
##
echo "Migrationファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginMigration.sh
fi


##
# Modelファイルを作成
##
echo "Modelファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginModel.sh
fi


##
# Controllerファイルを作成
##
echo "Controllerファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginController.sh
fi

##
# Viewファイルを作成
##
echo "Viewファイルを作成します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginView.sh
fi

##
# Testファイルを作成
##
echo "Testファイルを整形します。よろしいですか。"
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
if [ "$ANS" = "y" ]; then
	. ${CURDIR}/createPluginTest.sh
fi

cd ${CURDIR}

if [ $configRemove -eq 1 -a -f ${CONFIG_FILE} ]; then
	echo "rm ${CONFIG_FILE}"
	rm ${CONFIG_FILE}
fi

cd ${CURDIR}

echo ""
echo "${PLUGIN_NAME}プラグインを作成しました。"
echo ""

#-- end --