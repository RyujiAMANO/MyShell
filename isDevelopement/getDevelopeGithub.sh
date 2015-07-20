#!/bin/bash -ex

NC3SHELL=/var/www/MyShell; export NC3DIR
NC3DIR=/var/www/app; export NC3DIR

TMPDIR=${NC3SHELL}/tmp; export TMPDIR
if [ ! -d ${TMPDIR} ]; then
	COMMAND="mkdir ${TMPDIR}"
	echo ${COMMAND}
	${COMMAND}
fi

GITURL=https://github.com/NetCommons3; export GITURL

NORMALDEV=0; export NORMALDEV

ALL_YES=$1

echo "==== Initialize ===="

#外部接続チェック
echo "ping github.com -c 5"
ping github.com -c 3

echo ""
echo '処理を実行しますか？ "y(es)" or "n(o)" [y]'
if [ "${ALL_YES}" = "yes" -o "${ALL_YES}" = "y" ]; then
	ANS="y"
	echo "y"
else
	read ANS
fi
if [ ! "${ANS}" = "y" -a ! "${ANS}" = "" ]; then
	exit 0
fi

#バックアップ
if [ -f ${NC3SHELL}/nc3Backup/nc3Backup.sh ]; then
	COMMAND="bash ${NC3SHELL}/nc3Backup/nc3Backup.sh"
	echo ${COMMAND}
	${COMMAND}
fi

COMMAND="cd ${NC3DIR}/app/Plugin"
echo ${COMMAND}
${COMMAND}


#Githubから最新取得
if [ -f ${TMPDIR}/.nc3plugins ]; then
	COMMAND="rm -f ${TMPDIR}/.nc3plugins"
	echo ${COMMAND}
	${COMMAND}
fi


COMMAND="cd ${TMPDIR}"
echo ${COMMAND}
${COMMAND}

COMMAND="`which curl` -O https://raw.githubusercontent.com/s-nakajima/MyShell/master/.nc3plugins"
echo ${COMMAND}
${COMMAND}

. ${TMPDIR}/.nc3plugins

for sPlugin in "${NC3PLUGINS[@]}"
do
	aPlugin=(${sPlugin})
	echo "==== ${aPlugin[0]} ===="

	if [ -d ${NC3DIR}/app/Plugin/${aPlugin[0]} ]; then
		COMMAND="cd ${NC3DIR}/app/Plugin/${aPlugin[0]}"
		echo ${COMMAND}
		${COMMAND}

		isGitModify=`git status -s`
		if [ ! "${isGitModify}" = "" ]; then
			echo '変更点をコミットしていますか？ "y(es)" or "n(o)" [n]'
			if [ ! "${ALL_YES}" = "yes" -a ! "${ALL_YES}" = "y" ]; then
				read ANS
			else
				echo "y"
			fi
			if [ ! "${ANS}" = "y" ]; then
				continue
			fi
		fi
	fi

	COMMAND="cd ${NC3DIR}/app/Plugin"
	echo ${COMMAND}
	${COMMAND}

	#if [ "${aPlugin[0]}" = "NetCommons" ]; then
	#	COMMAND="rm -Rf ${aPlugin[0]}.bk"
	#	echo ${COMMAND}
	#	${COMMAND}
	#
	#	COMMAND="mv ${aPlugin[0]} ${aPlugin[0]}.bk"
	#	echo ${COMMAND}
	#	${COMMAND}
	#else
		COMMAND="rm -Rf ${aPlugin[0]}"
		echo ${COMMAND}
		${COMMAND}
	#fi

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

	#if [ "${aPlugin[0]}" = "NetCommons" ]; then
	#	COMMAND="rm -Rf ${aPlugin[0]}.bk"
	#	echo ${COMMAND}
	#	${COMMAND}
	#fi
done

#後処理
echo "==== Terminate ===="

COMMAND="cd ${NC3DIR}/app"
echo ${COMMAND}
${COMMAND}

COMMAND="chown www-data:www-data -R ./"
echo ${COMMAND}
${COMMAND}

echo "====   end git clone `date '+%Y-%m-%d.%H:%M:%S'` ===="
echo ""
