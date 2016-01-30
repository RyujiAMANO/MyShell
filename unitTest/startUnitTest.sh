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
defaultAns="2"
echo "プラグインの種類を選んでください。[${defaultAns}]"
echo "[1] 一般プラグイン"
echo "[2] 管理プラグイン"
echo "[3] コアプラグイン"
echo -n "> "
#read ANS
if [ "$ANS" = "" ]; then
	ANS=${defaultAns}
fi
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
#read ANS
ANS=""
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
#read ANS
ANS=""
if [ "$ANS" = "" ]; then
	ANS="nakajimashouhei@gmail.com"
fi
AUTHOR_EMAIL=$ANS; export AUTHOR_EMAIL
echo "${AUTHOR_EMAIL}"
echo ""

#
# 既存ファイルに対するふるまい
#
echo "既存ファイルに対してどうしますか。"
echo "[y] 全て上書きする"
echo "[n] 全て上書きしない"
echo "[c] 確認する(デフォルト)"
echo -n "> "
read ANS
#ANS=""
if [ "$ANS" = "" ]; then
	ANS="c"
fi
EXISTING_FILE=$ANS; export EXISTING_FILE
echo "${EXISTING_FILE}"
echo ""

#
# 全ファイルに対してデフォルトコメントを使用する？
#
echo "全ファイルに対してデフォルトコメントを使用しますか。"
echo "[y] 使用する"
echo "[n] 使用しない(確認する)"
echo -n "> "
read ANS
#ANS=""
if [ "$ANS" = "" ]; then
	ANS="n"
fi
USE_DEFAULT_COMMENT=$ANS; export USE_DEFAULT_COMMENT
echo "${USE_DEFAULT_COMMENT}"
echo ""

php -c -f ${CURDIR}/startUnitTest.php

#chown www-data:www-data -R /var/www/app/*

#
#-- end of file --
