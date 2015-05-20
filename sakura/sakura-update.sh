#!/bin/bash

. nc3profile

CURDIR=`pwd`
NC3DIR=${CURDIR}/NetCommons3; export NC3DIR

#NODEVELOP=1; export NODEVELOP
LOCALDEV=1; export LOCALDEV
#if [ "$1" = "mathjax" ]; then
#	NORMALDEV=2; export NORMALDEV
#elif [ "$1" = "develop" -o "$1" = "dev" ]; then
	NORMALDEV=0; export NORMALDEV
#else
#	NORMALDEV=1; export NORMALDEV
#fi


############
# 環境構築 #
############

if [ ! -d NetCommons3 ]; then
	COMMAND="git clone ${GITURL}/NetCommons3.git"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="chown www-data:www-data -R NetCommons3"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cd ${NC3DIR}/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="git config --global url.'https://'.insteadOf git://"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="composer self-update"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="`which composer` update"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="cp -pf ./tools/build/app/cakephp/composer.json ./"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="`which composer` update"
	echo ${COMMAND}
	${COMMAND}
fi

#Githubから最新取得
if [ -f ${CURDIR}/.nc3plugins ]; then
	COMMAND="rm -f ${CURDIR}/.nc3plugins"
	echo ${COMMAND}
	${COMMAND}
fi

COMMAND="cd ${CURDIR}"
echo ${COMMAND}
${COMMAND}

COMMAND="`which curl` -O https://raw.githubusercontent.com/s-nakajima/MyShell/master/.nc3plugins"
echo ${COMMAND}
${COMMAND}

. ${CURDIR}/.nc3plugins


COMMAND="cd ${NC3DIR}/"
echo ${COMMAND}
${COMMAND}

COMMAND="`which composer` update"
echo ${COMMAND}
${COMMAND}

#COMMAND="bower --allow-root cache clean"
#echo ${COMMAND}
#${COMMAND}

COMMAND="bower --allow-root update"
echo ${COMMAND}
${COMMAND}

######################
# Githubから最新取得 #
######################
if [ ! "${NORMALDEV}" = "1" ]; then
	for sPlugin in "${NC3PLUGINS[@]}"
	do
		aPlugin=(${sPlugin})
		echo "==== ${aPlugin[0]} ===="

		COMMAND="cd ${NC3DIR}/app/Plugin"
		echo ${COMMAND}
		${COMMAND}

		if [ "${aPlugin[0]}" = "NetCommons" ]; then
			COMMAND="rm -Rf ${aPlugin[0]}.bk"
			echo ${COMMAND}
			${COMMAND}

			COMMAND="mv ${aPlugin[0]} ${aPlugin[0]}.bk"
			echo ${COMMAND}
			${COMMAND}
		else
			COMMAND="rm -Rf ${aPlugin[0]}"
			echo ${COMMAND}
			${COMMAND}
		fi

		if [ "${aPlugin[1]}" = "DELETE" ]; then
			continue
		fi

		#NetCommons3プロジェクトから最新取得
		if [ "${aPlugin[2]}" = "" ]; then
			COMMAND="`which git` clone ${aPlugin[1]}/${aPlugin[0]}.git"
		else
			COMMAND="`which git` clone -b ${aPlugin[2]} ${aPlugin[1]}/${aPlugin[0]}.git"
		fi
		echo ${COMMAND}
		${COMMAND}

		if [ "${aPlugin[0]}" = "NetCommons" ]; then
			COMMAND="rm -Rf ${aPlugin[0]}.bk"
			echo ${COMMAND}
			${COMMAND}
		fi
	done
fi

##############################
# インストーラファイルの修正 #
##############################

#COMMAND="cd ${NC3DIR}/app/Plugin/Install/Controller/"
#echo ${COMMAND}
#${COMMAND}

#MATCHES="private function __installPackages() {"
#REPLACE="private function __installPackages() { return true;"

#echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" InstallController.php > InstallController.php2"
#sed -e "s/${MATCHES}/${REPLACE}/g" InstallController.php > InstallController.php2

#echo "mv InstallController.php InstallController.php.org"
#mv InstallController.php InstallController.php.org

#echo "mv InstallController.php2 InstallController.php"
#mv InstallController.php2 InstallController.php

COMMAND="cd ${CURDIR}/"
echo ${COMMAND}
${COMMAND}

COMMAND="rm -f NetCommons3.tar.gz"
echo ${COMMAND}
${COMMAND}

COMMAND="tar czf NetCommons3.tar.gz NetCommons3"
echo ${COMMAND}
${COMMAND}

if [ ! "${SAKURA_USER}" = "" ]; then
	COMMAND="scp -i /root/.ssh/id_rsa NetCommons3.tar.gz ${SAKURA_USER}@${SAKURA_HOST}:~/tmp/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ssh -i /root/.ssh/id_rsa ${SAKURA_USER}@${SAKURA_HOST} ${SAKURA_SHELL}/sakura-nc3-plugin-update.sh"
	echo ${COMMAND}
	${COMMAND}
fi

exit

#-- end --