#!/bin/bash

. nc3profile

CURDIR=`pwd`
#NODEVELOP=1; export NODEVELOP
#LOCALDEV=1; export LOCALDEV
if [ "$1" = "mathjax" ]; then
	NORMALDEV=2; export NORMALDEV
elif [ "$1" = "develop" -o "$1" = "dev" ]; then
	NORMALDEV=0; export NORMALDEV
elif [ "$1" = "composer" -o "$1" = "comp" ]; then
	NORMALDEV=3; export NORMALDEV
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

OWNER=`ls -ld /var/www/app | awk '{ print $3 '}`
GROUP=`ls -ld /var/www/app | awk '{ print $4 '}`


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


echo "cp -Rpf ${NC3DIR} ./"
cp -Rpf ${NC3DIR} ./

echo "rm -Rf ${NC3DIR}/*"
rm -Rf ${NC3DIR}/*

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

echo "rm -Rf ${CURDIR}/NetCommons3"
rm -Rf ${CURDIR}/NetCommons3

echo "cd ${CURDIR}"
cd ${CURDIR}

echo "git clone ${GITURL}/NetCommons3.git"
git clone ${GITURL}/NetCommons3.git

echo "cd /var/www/"
cd /var/www/

if [ ! "${SKIPDOCS}" = "1" ]; then
	echo "git clone ${GITURL}/NetCommons3Docs.git docs"
	git clone ${GITURL}/NetCommons3Docs.git docs
fi

echo "cp -Rf ${CURDIR}/NetCommons3/* app/"
cp -Rf ${CURDIR}/NetCommons3/* app/

if [ ! "${SKIPDOCS}" = "1" ]; then
	echo "mv NetCommons3Docs docs"
	mv NetCommons3Docs docs
fi

echo "chown ${OWNER}:${GROUP} -R app"
chown ${OWNER}:${GROUP} -R app

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

#echo "bower --allow-root cache clean"
#bower --allow-root cache clean

#echo "alias bower='bower --allow-root'"
#alias bower='bower --allow-root'
echo "bower --allow-root update"
bower --allow-root update

if [ -d ${BKDIR}/app/nbproject ]; then
	echo "cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/"
	cp -Rpf ${BKDIR}/app/nbproject ${NC3DIR}/
fi

######################
# Githubから最新取得 #
######################
if [ "${NORMALDEV}" = "3" ]; then
	COMMAND="cd ${NC3DIR}/app/Plugin"
	echo ${COMMAND}
	${COMMAND}
	
	COMMAND="rm -Rf Install"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="`which git` clone https://github.com/s-nakajima/Install.git"
	echo ${COMMAND}
	${COMMAND}
else
	for sPlugin in "${NC3PLUGINS[@]}"
	do
		aPlugin=(${sPlugin})
		echo "==== ${aPlugin[0]} ===="

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

		case "${aPlugin[0]}" in
			"empty" ) continue ;;
			"BoostCake" ) continue ;;
			"DebugKit" ) continue ;;
			"HtmlPurifier" ) continue ;;
			"M17n" ) continue ;;
			"Migrations" ) continue ;;
			"MobileDetect" ) continue ;;
			"Sandbox" ) continue ;;
			"TinyMCE" ) continue ;;
			* )
			#NetCommons3プロジェクトから最新取得
			if [ "${aPlugin[2]}" = "" ]; then
				COMMAND="`which git` clone ${aPlugin[1]}/${aPlugin[0]}.git"
			else
				COMMAND="`which git` clone -b ${aPlugin[2]} ${aPlugin[1]}/${aPlugin[0]}.git"
			fi
		esac

		echo ${COMMAND}
		${COMMAND}

		#if [ "${aPlugin[0]}" = "NetCommons" ]; then
		#	COMMAND="rm -Rf ${aPlugin[0]}.bk"
		#	echo ${COMMAND}
		#	${COMMAND}
		#fi
	done
fi

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

echo "chown ${OWNER}:${GROUP} -R app"
chown ${OWNER}:${GROUP} -R app

if [ ! "${NORMALDEV}" = "0" ]; then
	echo "cd ${NC3DIR}/"
	cd ${NC3DIR}/
	exit
fi

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