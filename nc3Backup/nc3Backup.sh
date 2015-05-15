#!/bin/bash -ex
 
cd /var/www/
 
if [ ! -d /var/www/backup ]; then
	echo "mkdir /var/www/backup"
	mkdir /var/www/backup
fi
if [ ! -d /vagrant/backup ]; then
	echo "mkdir /vagrant/backup"
	mkdir /vagrant/backup
fi
 
EXECTYPE=$1
if [ "${EXECTYPE}" = "all" ]; then
	CURDIR=all-`date +%y%m%d%H%M%S`
else
	CURDIR=diff-`date +%y%m%d%H%M%S`
fi
 
echo "mkdir /var/www/backup/${CURDIR}"
mkdir /var/www/backup/${CURDIR}
 
echo "cd /var/www/backup/${CURDIR}"
cd /var/www/backup/${CURDIR}
 
echo "mysqldump -uroot -proot nc3 > nc3.sql"
mysqldump -uroot -proot nc3 > nc3.sql
 
if [ "${EXECTYPE}" = "all" ]; then
	echo "find /var/www/backup/ -type d -ctime +30 -exec rm -Rf {} \;"
	find /var/www/backup/ -type d -ctime +30 -exec rm -Rf {} \;
 
	echo "cp -rpf /var/www/app ./"
	cp -rpf /var/www/app ./
 
	if [ -d /var/www/docs ]; then
		echo "cp -rpf /var/www/docs ./"
		cp -rpf /var/www/docs ./
	fi
	if [ -d /var/www/NetCommons3Docs ]; then
		echo "cp -rpf /var/www/NetCommons3Docs ./docs"
		cp -rpf /var/www/NetCommons3Docs ./docs
	fi
	if [ -d /var/www/MyShell ]; then
		echo "cp -rpf /var/www/MyShell ./"
		cp -rpf /var/www/MyShell ./
	fi
 
else
	echo "cp -rpf /var/www/app/app/Config ./"
	cp -rpf /var/www/app/app/Config ./
 
	echo "cp -rpf /var/www/app/app/Plugin ./"
	cp -rpf /var/www/app/app/Plugin ./
fi
 
echo "cd /var/www/backup/"
cd /var/www/backup/
 
echo "tar czf ${CURDIR}.tar.gz ${CURDIR}"
tar czf ${CURDIR}.tar.gz ${CURDIR}
 
echo "mv ${CURDIR}.tar.gz /vagrant/backup/"
mv ${CURDIR}.tar.gz /vagrant/backup/
 
echo ""
 
#
#-- end of file --