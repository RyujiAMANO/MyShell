#!/bin/bash -ex

cd /var/www/app/
#cd /var/www/NetCommons3/

PLUGIN_NAME=$1
CHECKEXEC=$2
if [ "${PLUGIN_NAME}" = "" ]; then
	echo "please input plugin."
	exit 1
fi

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

	execCommand="pear install --force --alldeps cakephp/CakePHP_CodeSniffer"
	echo ${execCommand}
	${execCommand}

	execCommand="pear install --force --alldeps phpmd/PHP_PMD"
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
	if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "remove" ]; then
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
		"M17n" ) continue ;;
		"Migrations" ) continue ;;
		"MobileDetect" ) continue ;;
		"Sandbox" ) continue ;;
		"Install" ) continue ;;
		"TinyMCE" ) continue ;;
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
				if [ "${plugin}" = "NetCommons" ]; then
					execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app/Plugin/NetCommons/webroot/base"
				else
					execCommand="${CMD_GJSLINT} --strict -x jquery.js,jquery.cookie.js,js_debug_toolbar.js -e jasmine_examples,HTMLPurifier/Printer -r app/Plugin/${plugin}"
				fi
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
				execCommand="sh lib/Cake/Console/cake test AllTest --coverage-html app/webroot/coverage/${plugin} --stderr"
			else
				#if [ -f app/Plugin/${plugin}/phpunit.xml.dist ]; then
				#	execOption="--bootstrap ./ --configuration app/Plugin/${plugin}/phpunit.xml.dist --coverage-html app/webroot/coverage/${plugin} --stderr"
				#else
					execOption="--coverage-html app/webroot/coverage/${plugin} --stderr"
				#fi
				execCommand="sh lib/Cake/Console/cake test ${plugin} All${plugin} ${execOption}"
			fi
			echo ${execCommand}
			${execCommand}
		fi

		if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
			echo ""
			echo "##################################"
			echo "Coverage report"
			echo "##################################"
			if [ "${CHECKEXEC}" = "all" -o "${CHECKEXEC}" = "phpunit" ]; then
				echo ""
				echo "[MySQL coverage report]"
				echo "http://app.local:9090/coverage/${plugin}/Plugin.html"

				php -q << _EOF_
<?php
function html_truncate(\$html) {
	\$html = strip_tags(\$html);
	\$html = preg_replace("/[ ]{2,}/ius", "", \$html);

	\$html = preg_replace('/&nbsp;' . preg_quote('/', '/') . '&nbsp;/iu', '/', \$html);
	\$html = preg_replace("/&nbsp;\n\n&nbsp;/iu", '-.--% (-/-)', \$html);

	\$html = preg_replace('/&nbsp;/iu', '', \$html);
	\$html = preg_replace("/\n+/ius", "\n", \$html);

	\$html = preg_replace("/\n([0-9]+)" . preg_quote('/', '/') . "([0-9]+)/ius", " (\\\\1/\\\\2)", \$html);
	\$html = preg_replace("/\n([0-9]+)/ius", "    \\\\1", \$html);
	\$html = preg_replace("/\n-/ius", "    -", \$html);

	return trim(\$html);
}

define('PAD_SPACE_LEN', 21);

\$file = file_get_contents('/var/www/app/app/webroot/coverage/${plugin}/Plugin_${plugin}.html'); 

\$matches = array();
\$title = preg_match('/<title.+title>/iUus', \$file, \$matches);
\$title = \$matches[0];
\$title = html_truncate(\$title);

\$matches = array();
\$head = preg_match('/<thead.+thead>/iUus', \$file, \$matches);
\$head = \$matches[0];
\$head = html_truncate(\$head);

\$hashHead = explode("\n", \$head);
\$headValue1 = '';
\$headValue2 = '';
\$headValue3 = '';

foreach (\$hashHead as \$i => \$value) {
	\$headValue1 .= '+-' . str_pad('', PAD_SPACE_LEN, '-') . '-';
	\$headValue2 .= '| ' . str_pad(\$value, PAD_SPACE_LEN) . ' ';
	\$headValue3 .= '+-' . str_pad('', PAD_SPACE_LEN, '-') . '-';
}
\$head = \$headValue1 . "-+\n" . \$headValue2 . " |\n" . \$headValue3 . "-+";
\$footValue = \$headValue1;

\$file = preg_replace('/<title.+title>/iUus', '', \$file);
\$file = preg_replace('/<header.+header>/iUus', '', \$file);
\$file = preg_replace('/<footer.+footer>/iUus', '', \$file);
\$file = preg_replace('/<thead.+thead>/iUus', '', \$file);

\$file = html_truncate(\$file);

\$hashFile = explode("\n", \$file);
foreach (\$hashFile as \$i => \$value) {
	\$hashFile2 = explode('    ', \$value);
	foreach (\$hashFile2 as \$j => \$value2) {
		\$hashFile2[\$j] = '| ' . str_pad(\$value2, PAD_SPACE_LEN) . ' ';
	}
	\$hashFile[\$i] = implode('', \$hashFile2);
}
\$file = implode(" |\n", \$hashFile) . ' ';

echo(\$title . "\n" . \$head . "\n" . \$file . "|\n" . \$footValue . "-+" . "\n");
_EOF_

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