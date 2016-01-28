#!/bin/bash -ex
CURDIR=`pwd`
cd /var/www/app/

export PLUGIN_NAME=$1; export PLUGIN_NAME
if [ "${PLUGIN_NAME}" = "" ]; then
	echo "エラー：プラグインを入力してください。"
	exit 1
fi
if [ ! -d /var/www/app/app/Plugin/${PLUGIN_NAME} ] ; then
	echo "/var/www/app/app/Plugin/${PLUGIN_NAME}"
	echo "エラー：プラグインがありません。"
	exit 1
fi

#
# プラグインの種類
#
echo "プラグインの種類を選んでください"
echo "[1] 一般プラグイン"
echo "[2] 管理プラグイン"
echo "[3] コアプラグイン"
echo -n "> "
read ANS
if [ ! "$ANS" = "1" -a ! "$ANS" = "2" -a ! "$ANS" = "3" ]; then
	echo "エラー：プラグインの種類がありません。"
	exit 1
fi
export PLUGIN_TYPE=$ANS; export PLUGIN_TYPE
if [ "$PLUGIN_TYPE" = "1" ]; then
	echo "[1] 一般プラグイン"
fi
if [ "$PLUGIN_TYPE" = "2" ]; then
	echo "[2] 管理プラグイン"
fi
if [ "$PLUGIN_TYPE" = "3" ]; then
	echo "[3] コアプラグイン"
fi
echo ""

#
# 作成者
#
echo "作成者を半角英語で入力してください。[Shohei Nakajima]"
echo -n "> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="Shohei Nakajima"
fi
AUTHOR_NAME=$ANS; export AUTHOR_NAME
echo "${AUTHOR_NAME}"
echo ""

#
# 作成者メールアドレス
#
echo "作成者のメールアドレスを入力してください。[nakajimashouhei@gmail.com]"
echo -n "> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="nakajimashouhei@gmail.com"
fi
AUTHOR_EMAIL=$ANS; export AUTHOR_EMAIL
echo "${AUTHOR_EMAIL}"
echo ""

if [ "$PLUGIN_TYPE" = "1" ]; then
	php ${CURDIR}/createGeneralPlugin.php
fi
if [ "$PLUGIN_TYPE" = "2" ]; then
	php ${CURDIR}/createSystemPlugin.php
fi
if [ "$PLUGIN_TYPE" = "3" ]; then
	php ${CURDIR}/createCorePlugin.php
fi

#chown www-data:www-data -R /var/www/app/*

#
#-- end of file --
