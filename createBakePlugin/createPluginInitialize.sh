#!/bin/bash

cd /var/www/app/

#設置場所チェック
SET_DIR="/var/www/app"
if [ ! -e $SET_DIR ]; then
	echo 'Installation location is different';
	exit 0
fi

#プラグイン名
echo "プラグイン名称を半角のキャメル記法で入力してください (例)HelloWorlds [$PLUGIN_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_NAME=${ANS}
fi
if [ "$PLUGIN_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi

echo "プラグイン名称(単数形)を半角のキャメル記法で入力してください (例)HelloWorld [$PLUGIN_SINGULAR_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_SINGULAR_NAME=${ANS}
fi
if [ "$PLUGIN_SINGULAR_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi

echo "プラグイン名称を半角小文字のスネーク記法で入力してください (例)hello_worlds [$PLUGIN_SNAKE_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_SNAKE_NAME=${ANS}
fi
if [ "$PLUGIN_SNAKE_NAME" = "" ]; then
	echo '引数が足りません'
	exit 0
fi

echo "パッケージ名称を半角英字小文字のハイフン記法で入力してください (例)hello-worlds [$PLUGIN_HAIFUN_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	PLUGIN_HAIFUN_NAME=${ANS}
fi
if [ "$PLUGIN_HAIFUN_NAME" = "" ]; then
	echo '引数が足りません'
	exit 0
fi

#プラグインパス
PUGLIN_PATH=${SET_DIR}/app/Plugin/${PLUGIN_NAME}

#作成者
echo "作成者を半角英語で入力してください [$AUTHOR_NAME]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	AUTHOR_NAME=${ANS}
fi
if [ "$AUTHOR_NAME" = "" ]; then
	echo "引数が足りません"
	exit 0
fi

echo "作成者のメールアドレスを入力してください。(例)netcommons3@example.com [$AUTHOR_EMAIL]"
echo -n "> "
read ANS
if [ ! "$ANS" = "" ]; then
	AUTHOR_EMAIL=${ANS}
fi
if [ "$AUTHOR_EMAIL" = "" ]; then
	echo "引数が足りません"
	exit 0
fi

echo "CREATE TABLEを実行してテーブルを作成していますか？ "
echo -n "y(es)/n(o)/q(uit) [y]> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="y"
	echo ${ANS}
fi
if [ "$ANS" = "q" ]; then
	exit 0
fi
