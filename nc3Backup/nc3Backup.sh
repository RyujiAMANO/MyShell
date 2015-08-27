#!/bin/bash -ex

CURDIR=`pwd`

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
	BACKUPDIR=all-`date +%y%m%d%H%M%S`
else
	BACKUPDIR=diff-`date +%y%m%d%H%M%S`
fi

echo "mkdir /var/www/backup/${BACKUPDIR}"
mkdir /var/www/backup/${BACKUPDIR}

echo "cd /var/www/backup/${BACKUPDIR}"
cd /var/www/backup/${BACKUPDIR}


echo "mysqldump -uroot -proot nc3 > nc3.sql"
mysqldump -uroot -proot nc3 > nc3.sql

if [ "${EXECTYPE}" = "all" ]; then
	#echo "find /var/www/backup/ -type d -ctime +30 -exec rm -Rf {} \;"
	#find /var/www/backup/ -type d -ctime +30 -exec rm -Rf {} \;

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

	if [ -d ${CURDIR}/github-cmd ]; then
		echo "rm -Rf cd ${CURDIR}/github-cmd/issues/*"
		rm -Rf ${CURDIR}/github-cmd/issues/*

		echo "cd ${CURDIR}/github-cmd"
		cd ${CURDIR}/github-cmd

		echo "./github-issues NetCommons3 --all | tee issues/github-issues.log"
		./github-issues NetCommons3 --all | tee issues/github-issues.log

		for url in `cat issues_url.txt`
		do
			filename=`echo $url | cut -c 32-`
			filename=`echo $filename | sed -e "s#/#_#g"`

			echo "curl $url > issues/$filename.html"
			curl $url > issues/$filename.html
		done

		echo "cd /var/www/backup/${BACKUPDIR}"
		cd /var/www/backup/${BACKUPDIR}

		echo "cp -rpf ${CURDIR}/github-cmd/issues ./"
		cp -rpf ${CURDIR}/github-cmd/issues ./
	fi
else
	echo "cp -rpf /var/www/app/app/Config ./"
	cp -rpf /var/www/app/app/Config ./

	echo "cp -rpf /var/www/app/app/Plugin ./"
	cp -rpf /var/www/app/app/Plugin ./
fi

echo "cd /var/www/backup/"
cd /var/www/backup/

echo "tar czf ${BACKUPDIR}.tar.gz ${BACKUPDIR}"
tar czf ${BACKUPDIR}.tar.gz ${BACKUPDIR}

echo "mv ${BACKUPDIR}.tar.gz /vagrant/backup/"
mv ${BACKUPDIR}.tar.gz /vagrant/backup/

echo ""

#
#-- end of file --
