#!/bin/bash

if [ "`which multitail`" = "" ]; then
	EXECCMD="sudo apt-get install multitail"
	echo $EXECCMD
	$EXECCMD
else
	echo "`which multitail`"
fi

sudo multitail -n 3 /var/log/mysql/mysql.log /var/www/app/app/tmp/logs/error.log /var/www/app/app/tmp/logs/debug.log
