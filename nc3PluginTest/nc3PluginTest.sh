#!/bin/bash -ex
CURDIR=`pwd`

cd /var/www/app/
#cd /var/www/NetCommons3/
export PATH=$PATH:./vendors/bin

PLUGIN_NAME=$1
CHECKEXEC=$2
if [ "${PLUGIN_NAME}" = "" ]; then
	echo "please input plugin."
	exit 1
fi
CMDPARAM=$3

if [ "${PLUGIN_NAME}" = "pear_install" ]; then
	#Javascript checker install
	checkIns=`which gjslint`
	if [ $? -eq 1 ]; then
		echo "pip install http://closure-linter.googlecode.com/files/closure_linter-latest.tar.gz"
		pip install http://closure-linter.googlecode.com/files/closure_linter-latest.tar.gz
	fi

	checkIns=`phpdoc --version`
	if [ $? -eq 1 ]; then
		execCommand="pear channel-discover pear.phpdoc.org"
		echo ${execCommand}
		${execCommand}

		execCommand="pear install phpdoc/phpDocumentor-2.7.0"
		echo ${execCommand}
		${execCommand}
	fi

	execCommand="pear install --force --alldeps PHP_CodeSniffer-1.5.4"
	echo ${execCommand}
	${execCommand}

	execCommand="pear channel-discover pear.cakephp.org"
	echo ${execCommand}
	${execCommand}

	execCommand="pear install --force --alldeps cakephp/CakePHP_CodeSniffer"
	echo ${execCommand}
	${execCommand}

	execCommand="pear channel-discover pear.phpmd.org"
	echo ${execCommand}
	${execCommand}

	execCommand="pear install --force --alldeps phpmd/PHP_PMD"
	echo ${execCommand}
	${execCommand}

	execCommand="pear channel-discover pear.pdepend.org"
	echo ${execCommand}
	${execCommand}

	execCommand="pear install --force --alldeps pdepend/PHP_Depend"
	echo ${execCommand}
	${execCommand}

	execCommand="pear list -a"
	echo ${execCommand}
	${execCommand}

	exit 0
fi

BINDIR=/var/www/app/vendors/bin

if [ "${PLUGIN_NAME}" = "All.Plugin" ]; then
	PLUGIN_NAME=`ls app/Plugin`
else
	if [ "${CHECKEXEC}" = "remove" ]; then
		echo ""
		echo "##################################"
		echo "Line feed remove"
		echo "##################################"

		cd /var/www/app/app/Plugin/${PLUGIN_NAME}

		find . -name "*.php" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.ctp" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.js" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.json" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.css" | xargs perl -i.bak -pe "s/\r\n/\n/"
		find . -name "*.yml" | xargs perl -i.bak -pe "s/\r\n/\n/"

		find . -name "*.bak" -type f -exec rm -f {} \;

		cd /var/www/app/
	fi
fi
if [ "${CHECKEXEC}" = "" ]; then
	CHECKEXEC=all
fi

for plugin in ${PLUGIN_NAME}
do
	case "${plugin}" in
		"empty" ) continue ;;
		"BoostCake" ) continue ;;
		"DebugKit" ) continue ;;
		"HtmlPurifier" ) continue ;;
		#"M17n" ) continue ;;
		"Migrations" ) continue ;;
		"MobileDetect" ) continue ;;
		"Sandbox" ) continue ;;
		"Install" ) continue ;;
		"TinyMCE" ) continue ;;
		"Upload" ) continue ;;
		* )
		echo ""
		echo ""
		echo "//////////////////////////////////"
		echo "// Checked ${plugin}"
		echo "//////////////////////////////////"
		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpcs" ]; then
			echo ""
			echo "##################################"
			echo "PHP CodeSniffer(phpcs)"
			echo "##################################"
			#if [ -f ${BINDIR}/phpcs ]; then
			#	CMD_PHPCS=${BINDIR}/phpcs
			#else
				CMD_PHPCS=`which phpcs`
			#fi
			
			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_PHPCS} -p --extensions=php,ctp --standard=CakePHP app"
			else
				execCommand="${CMD_PHPCS} -p --extensions=php,ctp --standard=CakePHP app/Plugin/${plugin}"
			fi
			
			echo ${execCommand}
			${execCommand}
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpmd" ]; then
			echo ""
			echo "##################################"
			echo "PHP Mess Detector(phpmd)"
			echo "##################################"
			if [ -f ${BINDIR}/phpmd ]; then
				CMD_PHPMD=${BINDIR}/phpmd
			else
				CMD_PHPMD=`which phpmd`
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				if [ -f /etc/phpmd.xml ]; then
					execCommand="${CMD_PHPMD} app text /etc/phpmd.xml"
				else
					execCommand="${CMD_PHPMD} app text /etc/phpmd/rules.xml"
				fi
			else
				if [ -f /etc/phpmd.xml ]; then
					execCommand="${CMD_PHPMD} app/Plugin/${plugin} text /etc/phpmd.xml"
				else
					execCommand="${CMD_PHPMD} app/Plugin/${plugin} text /etc/phpmd/rules.xml"
				fi
			fi
			echo ${execCommand}
			${execCommand}
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpcpd" ]; then
			echo ""
			echo "##################################"
			echo "PHP Copy/Paste Detector (PHPCPD)"
			echo "##################################"
			if [ -f ${BINDIR}/phpcpd ]; then
				CMD_PHPCPD=${BINDIR}/phpcpd
			else
				CMD_PHPCPD=`which phpcpd`
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_PHPCPD} --exclude Test --exclude Config app"
			else
				execCommand="${CMD_PHPCPD} --exclude Test --exclude Config app/Plugin/${plugin}"
			fi
			echo ${execCommand}
			${execCommand}
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "gjslint" ]; then
			echo ""
			echo "##################################"
			echo "JavaScript Style Check(gjslint)"
			echo "##################################"
			if [ -f ${BINDIR}/gjslint ]; then
				CMD_GJSLINT=${BINDIR}/gjslint
			else
				CMD_GJSLINT=`which gjslint`
			fi

			if [ -d app/Plugin/${plugin}/JavascriptTest/coverage ]; then
				execCommand="rm -Rf app/Plugin/${plugin}/JavascriptTest/coverage"
				echo ${execCommand}
				${execCommand}
			fi

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app"
			else
				#if [ "${plugin}" = "NetCommons" ]; then
				#	execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app/Plugin/NetCommons/webroot/base"
				#else
					execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app/Plugin/${plugin}"
				#fi
			fi
			echo ${execCommand}
			${execCommand}
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "jsunit" ]; then
			if [ -d app/Plugin/${plugin}/JavascriptTest/ ]; then
				echo ""
				echo "##################################"
				echo "JavScript unit test Karma(jsunit)"
				echo "##################################"
				
				if [ -f app/Plugin/${plugin}/JavascriptTest/my.karma.conf.js ]; then
					execCommand="/usr/lib/node_modules/karma/bin/karma start app/Plugin/${plugin}/JavascriptTest/my.karma.conf.js --single-run --browsers PhantomJS"
				else
					execCommand="/usr/lib/node_modules/karma/bin/karma start app/Plugin/${plugin}/JavascriptTest/travis.karma.conf.js --single-run --browsers PhantomJS"
				fi
				echo ${execCommand}
				${execCommand}

				#execCommand="rm -Rf app/Plugin/${plugin}/JavascriptTest/coverage"
				#echo ${execCommand}
				#${execCommand}
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpdoc" ]; then
			echo ""
			echo "##################################"
			echo "PHP Documentor(phpdoc)"
			echo "##################################"
			if [ -f ${BINDIR}/phpdoc ]; then
				CMD_PHPDOC=${BINDIR}/phpdoc
			else
				CMD_PHPDOC=`which phpdoc`
			fi
			
			echo ""
			execCommand="rm -Rf app/webroot/phpdoc/${plugin}"
			echo ${execCommand}
			${execCommand}

			if [ "${plugin}" = "NetCommons3" ]; then
				PHPDOCPARAM="-d app -t app/webroot/phpdoc/${plugin} --force --ansi"
			else
				PHPDOCPARAM="-d app/Plugin/${plugin} -t app/webroot/phpdoc/${plugin} --force --ansi"
			fi

			execCommand="${CMD_PHPDOC} run ${PHPDOCPARAM}"
			echo ${execCommand}
			${execCommand} > /var/log/phpdoc.log

			phpdocerr=`grep -c "\[37;41m" /var/log/phpdoc.log`
			if [ $phpdocerr -ne 0 ]; then
				cat /var/log/phpdoc.log
				echo ""
				echo "$phpdocerr errors."
				echo ""
			else
				echo ""
				echo "phpdoc no error."
				echo ""
			fi
		fi

		if [ "${CHECKEXEC}" = "test.mysql" ]; then
			CHECKEXEC=phpunit
		fi
		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
			echo ""
			echo "##################################"
			echo "PHP UnitTest (phpunit)"
			echo "##################################"

			execCommand="rm -Rf app/webroot/coverage/${plugin}"
			echo ${execCommand}
			${execCommand}

			if [ "${plugin}" = "NetCommons3" ]; then
				execCommand="sh app/Console/cake test AllTest --coverage-html app/webroot/coverage/${plugin} --stderr"
				echo ${execCommand}
				${execCommand}
			else
				if [ -f app/Plugin/${plugin}/phpunit.xml.dist ]; then
					execCommand="cp -pf app/Plugin/${plugin}/phpunit.xml.dist ./${plugin}-phpunit.xml.dist"
					echo ${execCommand}
					${execCommand}

					execOption="--coverage-html app/webroot/coverage/${plugin} --stderr --configuration ${plugin}-phpunit.xml.dist"
				else
					execOption="--coverage-html app/webroot/coverage/${plugin} --stderr"
				fi

				if [ "${CHECKEXEC}" = "phpunit" ]; then
					if [ "${CMDPARAM}" = "list" -o "${CMDPARAM}" = "list.caverageAll" ]; then
						execCommand="sh app/Console/cake test ${plugin} ${execOption}"
					elif [ ! "${CMDPARAM}" = "" -a ! "${CMDPARAM}" = "caverageAll" ]; then
						execCommand="sh app/Console/cake test ${plugin} ${CMDPARAM} ${execOption}"
					else
						execCommand="sh app/Console/cake test ${plugin} All${plugin} ${execOption}"
					fi
				else
					execCommand="sh app/Console/cake test ${plugin} All${plugin} ${execOption}"
				fi
				echo ${execCommand}
				${execCommand}

				if [ -f app/Plugin/${plugin}/phpunit.xml.dist ]; then
					execCommand="rm -f ./${plugin}-phpunit.xml.dist"
					echo ${execCommand}
					${execCommand}
				fi
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
			echo ""
			echo "##################################"
			echo "Coverage report"
			echo "##################################"
			if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
				echo ""
				echo "[MySQL coverage report]"
				#if [ -f app/Plugin/${plugin}/phpunit.xml.dist ]; then
				#	echo "http://app.local:9090/coverage/${plugin}/index.html"
				#else
				#	echo "http://app.local:9090/coverage/${plugin}/Plugin.html"
				#fi
				#echo ""
				echo "php ${CURDIR}/parse_caverage.php ${plugin} Plugin_${plugin}.html"
				php ${CURDIR}/parse_caverage.php ${plugin} Plugin_${plugin}.html 0

				if [ "${CMDPARAM}" = "caverageAll" -o "${CMDPARAM}" = "list.caverageAll" ]; then
					for act1 in [`ls app` "Error"]
					do
echo "app/Plugin/${plugin}/${act1}"
						if [ -d app/Plugin/${plugin}/${act1} ]; then
							if [ "${act1}" = "Test" ]; then
								continue;
							fi

							fileName="Plugin_${plugin}_${act1}.html"
							if [ -f app/webroot/coverage/${plugin}/${fileName} ]; then
								php ${CURDIR}/parse_caverage.php ${plugin} ${fileName} 4
								for act2 in `ls app/Plugin/${plugin}/${act1}`
								do
									if [ -d app/Plugin/${plugin}/${act1}/${act2} ] ; then
										fileName="Plugin_${plugin}_${act1}_${act2}.html"
										if [ -f app/webroot/coverage/${plugin}/${fileName} ]; then
											php ${CURDIR}/parse_caverage.php ${plugin} ${fileName} 8

											for act3 in `ls app/Plugin/${plugin}/${act1}/${act2}`
											do
												if [ -d app/Plugin/${plugin}/${act1}/${act2}/${act3} ] ; then
													fileName="Plugin_${plugin}_${act1}_${act2}_${act3}.html"
													if [ -f app/webroot/coverage/${plugin}/${fileName} ]; then
														php ${CURDIR}/parse_caverage.php ${plugin} ${fileName} 12
													fi
												fi
											done


										fi
									fi
								done
							fi
						fi
					done
				fi
			fi
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpdoc" ]; then
			echo ""
			echo "##################################"
			echo "PHP Documentor report"
			echo "##################################"
			echo ""
			if [ $phpdocerr -ne 0 ]; then
				echo "http://app.local:9090/phpdoc/${plugin}/reports/errors.html"
			else
				echo "http://app.local:9090/phpdoc/${plugin}/index.html"
			fi
			echo ""
		fi
	esac
done

chown www-data:www-data -R /var/www/app/*

#
#-- end of file --
