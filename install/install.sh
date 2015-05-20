#!/bin/bash

. nc3profile

CURDIR=`pwd`
#NODEVELOP=1; export NODEVELOP
LOCALDEV=1; export LOCALDEV
if [ "$1" = "mathjax" ]; then
	NORMALDEV=2; export NORMALDEV
elif [ "$1" = "develop" -o "$1" = "dev" ]; then
	NORMALDEV=0; export NORMALDEV
else
	NORMALDEV=1; export NORMALDEV
fi
if [ "$2" = "docs" ]; then
	SKIPDOCS=0; export SKIPDOCS
else
	SKIPDOCS=1; export SKIPDOCS
fi


ymdhis=`date +%y%m%d%H%M%S`
BKFILE=all-${ymdhis}
BKDIR=/var/www/backup/${BKFILE}

################
# バックアップ #
################

if [ ! -d /var/www/backup ]; then
	echo "mkdir /var/www/backup"
	mkdir /var/www/backup
fi
if [ ! -d /vagrant/backup ]; then
	echo "mkdir /vagrant/backup"
	mkdir /vagrant/backup
fi

echo "mkdir ${BKDIR}"
mkdir ${BKDIR}

echo "cd ${BKDIR}"
cd ${BKDIR}

echo "mysqldump -u${DBUSER} -p${DBPASS} ${DBNAME} > ${DBNAME}.sql"
mysqldump -u${DBUSER} -p${DBPASS} ${DBNAME} > ${DBNAME}.sql

echo "mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'"
mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'

echo "mysql -u${DBUSER} -p${DBPASS} -e \"drop database ${DBNAME};\""
mysql -u${DBUSER} -p${DBPASS} -e "drop database ${DBNAME};"

echo "mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'"
mysql -u${DBUSER} -p${DBPASS} -e 'show databases;'


echo "mv ${NC3DIR} ./"
mv ${NC3DIR} ./

if [ ! "${SKIPDOCS}" = "1" ]; then
	if [ -d /var/www/docs ]; then
		echo "mv /var/www/docs ./"
		mv /var/www/docs ./
	fi
	if [ -d /var/www/NetCommons3Docs ]; then
		echo "mv /var/www/NetCommons3Docs ./"
		mv /var/www/NetCommons3Docs ./
	fi
fi

echo "cd /var/www/backup/"
cd /var/www/backup/

echo "tar czf ${BKFILE}.tar.gz ${BKFILE}"
tar czf ${BKFILE}.tar.gz ${BKFILE}

echo "mv ${BKFILE}.tar.gz /vagrant/backup/"
mv ${BKFILE}.tar.gz /vagrant/backup/

############
# 環境構築 #
############

echo "cd /var/www/"
cd /var/www/

echo "git clone ${GITURL}/NetCommons3.git"
git clone ${GITURL}/NetCommons3.git

if [ ! "${SKIPDOCS}" = "1" ]; then
	echo "git clone ${GITURL}/NetCommons3Docs.git"
	git clone ${GITURL}/NetCommons3Docs.git
fi

echo "mv NetCommons3 app"
mv NetCommons3 app

if [ ! "${SKIPDOCS}" = "1" ]; then
	echo "mv NetCommons3Docs docs"
	mv NetCommons3Docs docs
fi

echo "chown www-data:www-data -R app"
chown www-data:www-data -R app

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

echo "git config --global url.'https://'.insteadOf git://"
git config --global url."https://".insteadOf git://

if [ -f ${BKDIR}/app/composer.phar ]; then
	echo "cp ${BKDIR}/app/composer.phar ${NC3DIR}/"
	cp ${BKDIR}/app/composer.phar ${NC3DIR}/

	echo "php composer.phar self-update"
	php composer.phar self-update
else
	echo "curl -s http://getcomposer.org/installer | php"
	curl -s http://getcomposer.org/installer | php
fi

echo "composer self-update"
composer self-update

#echo "hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc `which composer` update"
#hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc `which composer` update
echo "`which composer` update"
`which composer` update

echo "cp -pf ./tools/build/app/cakephp/composer.json ./"
cp -pf ./tools/build/app/cakephp/composer.json ./

#echo "hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc `which composer` update"
#hhvm -vRepo.Central.Path=/var/run/hhvm/hhvm.hhbc `which composer` update
echo "`which composer` update"
`which composer` update

#Githubから最新取得
if [ -f ${CURDIR}/.nc3plugins ]; then
	COMMAND="rm -f ${CURDIR}/.nc3plugins"
	echo ${COMMAND}
	${COMMAND}
fi


GITURL=https://github.com/NetCommons3; export GITURL

COMMAND="cd ${CURDIR}"
echo ${COMMAND}
${COMMAND}

COMMAND="`which curl` -O https://raw.githubusercontent.com/s-nakajima/MyShell/master/.nc3plugins"
echo ${COMMAND}
${COMMAND}

. ${CURDIR}/.nc3plugins

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

echo "`which composer` update"
`which composer` update

echo "bower --allow-root cache clean"
bower --allow-root cache clean

#echo "alias bower='bower --allow-root'"
#alias bower='bower --allow-root'
echo "bower --allow-root update"
bower --allow-root update

if [ -d ${NC3DIR}/vendors/bower_components/mathjax ]; then
	COMMAND="cd ${NC3DIR}/app/webroot"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ln -s ../../vendors/bower_components/mathjax mathjax"
	echo ${COMMAND}
	${COMMAND}
fi

if [ -d ${BKDIR}/app/nbproject ]; then
	echo "cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/"
	cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/
fi

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

##########################
# フレームファイルの修正 #
##########################

if [ -d ${NC3DIR}/vendors/bower_components/angular-dialog-service ]; then
	COMMAND="cd ${NC3DIR}/app/Plugin/NetCommons/webroot"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ln -s ../../../../vendors/bower_components/angular-dialog-service angular-dialog-service"
	echo ${COMMAND}
	${COMMAND}
fi

if [ -d ${NC3DIR}/vendors/bower_components/angular-sanitize ]; then
	COMMAND="cd ${NC3DIR}/app/Plugin/NetCommons/webroot"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ln -s ../../../../vendors/bower_components/angular-sanitize angular-sanitize"
	echo ${COMMAND}
	${COMMAND}
fi

if [ -d ${NC3DIR}/vendors/bower_components/angular-sanitize ]; then
	COMMAND="cd ${NC3DIR}/app/Plugin/Frames/View/Elements"
	echo ${COMMAND}
	${COMMAND}

	MATCHES="echo \$this->Html->script('http:\\/\\/rawgit.com\\/angular\\/bower-angular-sanitize\\/v1.2.25\\/angular-sanitize.js', false);"
	REPLACE="echo \$this->Html->script('\\/net_commons\\/angular-sanitize\\/angular-sanitize.min.js', false);"
	REPLACE_FILE="render_frames.ctp"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${REPLACE_FILE} > ${REPLACE_FILE}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${REPLACE_FILE} > ${REPLACE_FILE}2

	echo "mv ${REPLACE_FILE} ${REPLACE_FILE}.org.1"
	mv ${REPLACE_FILE} ${REPLACE_FILE}.org.1

	echo "mv ${REPLACE_FILE}2 ${REPLACE_FILE}"
	mv ${REPLACE_FILE}2 ${REPLACE_FILE}
fi

if [ -d ${NC3DIR}/vendors/bower_components/angular-dialog-service ]; then
	COMMAND="cd ${NC3DIR}/app/Plugin/Frames/View/Elements"
	echo ${COMMAND}
	${COMMAND}

	MATCHES="echo \$this->Html->script('http:\\/\\/rawgit.com\\/m-e-conroy\\/angular-dialog-service\\/v5.2.0\\/src\\/dialogs.js', false);"
	REPLACE="echo \$this->Html->script('\\/net_commons\\/angular-dialog-service\\/dist\\/dialogs.min.js', false);"
	REPLACE_FILE="render_frames.ctp"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${REPLACE_FILE} > ${REPLACE_FILE}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${REPLACE_FILE} > ${REPLACE_FILE}2

	echo "mv ${REPLACE_FILE} ${REPLACE_FILE}.org.2"
	mv ${REPLACE_FILE} ${REPLACE_FILE}.org.2

	echo "mv ${REPLACE_FILE}2 ${REPLACE_FILE}"
	mv ${REPLACE_FILE}2 ${REPLACE_FILE}
fi

##############################
# インストーラファイルの修正 #
##############################

#echo "cd ${NC3DIR}/app/Plugin/Install/Controller/"
#cd ${NC3DIR}/app/Plugin/Install/Controller/

#MATCHES="private function __installPackages() {"
#REPLACE="private function __installPackages() { return true;"

#echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" InstallController.php > InstallController.php2"
#sed -e "s/${MATCHES}/${REPLACE}/g" InstallController.php > InstallController.php2

#echo "mv InstallController.php InstallController.php.org"
#mv InstallController.php InstallController.php.org

#echo "mv InstallController.php2 InstallController.php"
#mv InstallController.php2 InstallController.php

################
# インストール #
################

echo "`which node` ${CURDIR}/install.js"
`which node` ${CURDIR}/install.js

#########################
# application.ymlの修正 #
#########################

echo "cd ${NC3DIR}/app/Config"
cd ${NC3DIR}/app/Config

MATCHES=${NC3URI}
REPLACE="${NC3URI}:${NC3PORT}"

echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" application.yml > application.yml2"
sed -e "s/${MATCHES}$/${REPLACE}/g" application.yml > application.yml2

echo "mv application.yml application.yml.org"
mv application.yml application.yml.org

echo "mv application.yml2 application.yml"
mv application.yml2 application.yml

MATCHES="debug: 0"
REPLACE="debug: 2"

echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" application.yml > application.yml2"
sed -e "s/${MATCHES}$/${REPLACE}/g" application.yml > application.yml2

echo "mv application.yml application.yml.org2"
mv application.yml application.yml.org2

echo "mv application.yml2 application.yml"
mv application.yml2 application.yml

#########################
# Admin以外のユーザ作成 #
#########################

echo "mysql -u${DBUSER} -p${DBPASS} ${DBNAME} < ${CURDIR}/insert_user.sql"
mysql -u${DBUSER} -p${DBPASS} ${DBNAME} < ${CURDIR}/insert_user.sql

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

echo "chown www-data:www-data -R app"
chown www-data:www-data -R app


########################
# 旧NetCommons3環境構築
########################
APPNAME="ryu818"

if [ ! -d /var/www/${APPNAME} ]; then
	echo "旧NetCommons3(ryu818/NetCommons3)環境を構築しますか。"
	echo -n "y(es)/n(o) [n]> "
#	read ANS
ANS="n"
	if [ "$ANS" = "" ]; then
		ANS="n"
	fi
	if [ "$ANS" = "y" ]; then
		echo "cd /var/www/"
		cd /var/www/

		echo "git clone https://github.com/ryu818/NetCommons3.git"
		git clone https://github.com/ryu818/NetCommons3.git

		echo "mv NetCommons3 ${APPNAME}"
		mv NetCommons3 ${APPNAME}

		if [ ! -f /etc/apache2/sites-available/${APPNAME}.conf ]; then
			echo "cd /etc/apache2/sites-available/"
			cd /etc/apache2/sites-available/

			echo "cat /etc/apache2/sites-available/${APPNAME}.conf"
			cat << _EOF_ > /etc/apache2/sites-available/${APPNAME}.conf
<VirtualHost *:80>
  ServerName ${APPNAME}.local
  ServerAdmin webmaster@localhost

  DocumentRoot /var/www/${APPNAME}
  <Directory />
    Options FollowSymLinks
    AllowOverride All
  </Directory>

  ErrorLog \${APACHE_LOG_DIR}/error.log
  CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
_EOF_
			
			echo "cd /etc/apache2/sites-enabled"
			cd /etc/apache2/sites-enabled
			
			echo "ln -s ../sites-available/${APPNAME}.conf ${APPNAME}.conf"
			ln -s ../sites-available/${APPNAME}.conf ${APPNAME}.conf

			echo "ls -al"
			ls -al

			echo "/etc/init.d/apache2 restart"
			/etc/init.d/apache2 restart

			echo "chown www-data:www-data -R /var/www/${APPNAME}/"
			chown www-data:www-data -R /var/www/${APPNAME}/

			echo "#################################"
			echo "hostsファイルに追記してください。"
			echo "#################################"
			echo "127.0.0.1    ${APPNAME}.local"
			echo ""
		fi
	fi
fi


########################
# NetCommons2の環境構築
########################
APPNAME="nc2"

if [ ! -d /var/www/${APPNAME} ]; then
	echo "NetCommons2環境を構築しますか。ただしPHP5.3以降は動作しません。"
	echo "php --version"
	php --version
	echo -n "y(es)/n(o) [n]> "
#	read ANS
ANS="n"
	if [ "$ANS" = "" ]; then
		ANS="n"
	fi
	if [ "$ANS" = "y" ]; then
		echo "cd /var/www/"
		cd /var/www/

		echo "git clone https://github.com/netcommons/NetCommons2.git"
		git clone https://github.com/netcommons/NetCommons2.git

		echo "mv NetCommons2 ${APPNAME}"
		mv NetCommons2 ${APPNAME}

		if [ ! -f /etc/apache2/sites-available/${APPNAME}.conf ]; then
			echo "cd /etc/apache2/sites-available/"
			cd /etc/apache2/sites-available/

			echo "cat /etc/apache2/sites-available/${APPNAME}.conf"
			cat << _EOF_ > /etc/apache2/sites-available/${APPNAME}.conf
<VirtualHost *:80>
  ServerName ${APPNAME}.local
  ServerAdmin webmaster@localhost

  DocumentRoot /var/www/${APPNAME}/html/htdocs
  <Directory />
    Options FollowSymLinks
    AllowOverride All
  </Directory>

  ErrorLog \${APACHE_LOG_DIR}/error.log
  CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
_EOF_
			
			echo "cd /etc/apache2/sites-enabled"
			cd /etc/apache2/sites-enabled
			
			echo "ln -s ../sites-available/${APPNAME}.conf ${APPNAME}.conf"
			ln -s ../sites-available/${APPNAME}.conf ${APPNAME}.conf

			echo "ls -al"
			ls -al

			echo "/etc/init.d/apache2 restart"
			/etc/init.d/apache2 restart

			echo "chown www-data:www-data -R /var/www/${APPNAME}/"
			chown www-data:www-data -R /var/www/${APPNAME}/

			echo "#################################"
			echo "hostsファイルに追記してください。"
			echo "#################################"
			echo "127.0.0.1    ${APPNAME}.local"
			echo ""
		fi
	fi
fi

echo "cd ${NC3DIR}/"
cd ${NC3DIR}/

exit

#-- end --