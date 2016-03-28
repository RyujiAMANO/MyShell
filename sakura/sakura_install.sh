#!/bin/bash
if [ "$UID" -eq 0 ];then
  echo "Doing..."
else
  echo "Use \"sudo\""
  exit
fi

. nc3profile

CURDIR=`pwd`
NC3DIR=${CURDIR}/NetCommons3
NC3ORGDIR=/var/www/app

echo "開発環境を再構築しますか。[n(o)]"
echo -n "y(es)|n(o) > "
read ANS
if [ "$ANS" = "" ]; then
	ANS="n"
fi
RECONSTRUCT=$ANS

if [ "${RECONSTRUCT}" = "y" -o "${RECONSTRUCT}" = "yes" ]; then
	COMMAND="cd /var/www/MyShell/install"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="bash install.sh develop"
	echo ${COMMAND}
	${COMMAND}
fi

echo "インポートするデータベースを指定してください。[nc3]"
echo -n "> "
read ANS
if [ "$ANS" = "" ]; then
	ANS="nc3"
fi
DBNAME=$ANS

################
# ソースを取得 #
################

if [ -d ${NC3DIR} ]; then
	COMMAND="rm -Rf ${NC3DIR}"
	echo ${COMMAND}
	${COMMAND}
fi
COMMAND="mkdir ${NC3DIR}"
echo ${COMMAND}
${COMMAND}


COMMAND="cd ${NC3DIR}"
echo ${COMMAND}
${COMMAND}

COMMAND="cp -Rpf ${NC3ORGDIR} ./"
echo ${COMMAND}
${COMMAND}

COMMAND="mysqldump -uroot -proot ${DBNAME} > sakura-install.sql"
echo ${COMMAND}
mysqldump -uroot -proot ${DBNAME} > sakura-install.sql

if [ ! "${NC3URI}" = "" ]; then
	COMMAND="cd ${NC3DIR}/app/app/Config/"
	echo ${COMMAND}
	${COMMAND}

	## URLの変換
	FILENAME="application.yml"
	MATCHES="127.0.0.1:9090"
	REPLACE="${NC3URI}"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${FILENAME} > ${FILENAME}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${FILENAME} > ${FILENAME}2

	echo "mv ${FILENAME} ${FILENAME}.org"
	mv ${FILENAME} ${FILENAME}.org

	echo "mv ${FILENAME}2 ${FILENAME}"
	mv ${FILENAME}2 ${FILENAME}

	## DBのHOST変換
	FILENAME="database.php"
	MATCHES="'host' => 'localhost'"
	REPLACE="'host' => '${DBHOST}'"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${FILENAME} > ${FILENAME}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${FILENAME} > ${FILENAME}2

	echo "mv ${FILENAME} ${FILENAME}.org"
	mv ${FILENAME} ${FILENAME}.org

	echo "mv ${FILENAME}2 ${FILENAME}"
	mv ${FILENAME}2 ${FILENAME}
	
	## DBのNAME変換
	FILENAME="database.php"
	MATCHES="'database' => '${DBNAME}'"
	REPLACE="'database' => '${DBPREFIX}${DBNAME}'"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${FILENAME} > ${FILENAME}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${FILENAME} > ${FILENAME}2

	echo "mv ${FILENAME} ${FILENAME}.org"
	mv ${FILENAME} ${FILENAME}.org

	echo "mv ${FILENAME}2 ${FILENAME}"
	mv ${FILENAME}2 ${FILENAME}

	## DBのUSER変換
	FILENAME="database.php"
	MATCHES="'login' => 'root'"
	REPLACE="'login' => '${DBUSER}'"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${FILENAME} > ${FILENAME}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${FILENAME} > ${FILENAME}2

	echo "mv ${FILENAME} ${FILENAME}.org"
	mv ${FILENAME} ${FILENAME}.org

	echo "mv ${FILENAME}2 ${FILENAME}"
	mv ${FILENAME}2 ${FILENAME}

	## DBのPASS変換
	FILENAME="database.php"
	MATCHES="'password' => 'root'"
	REPLACE="'password' => '${DBPASS}'"

	echo "sed -e \"s/${MATCHES}$/${REPLACE}/g\" ${FILENAME} > ${FILENAME}2"
	sed -e "s/${MATCHES}/${REPLACE}/g" ${FILENAME} > ${FILENAME}2

	echo "mv ${FILENAME} ${FILENAME}.org"
	mv ${FILENAME} ${FILENAME}.org

	echo "mv ${FILENAME}2 ${FILENAME}"
	mv ${FILENAME}2 ${FILENAME}
fi


################
# ソースの圧縮 #
################

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
	COMMAND="cd ${CURDIR}/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="scp -i /root/.ssh/id_rsa NetCommons3.tar.gz ${SAKURA_USER}@${SAKURA_HOST}:~/tmp/"
	echo ${COMMAND}
	${COMMAND}

	COMMAND="ssh -i /root/.ssh/id_rsa ${SAKURA_USER}@${SAKURA_HOST} ${SAKURA_SHELL}/sakura-install.sh '${SAKURA_DIR}' '${DBPREFIX}${DBNAME}'"
	echo ${COMMAND}
	${COMMAND}
fi

exit

#-- end --