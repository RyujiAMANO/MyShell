#!/bin/bash

##
# bake pluginの実行
##
PATTERN="CakePlugin::load('NetCommons', array('bootstrap' => true));"
REPLACE="\/\/CakePlugin::load('NetCommons', array('bootstrap' => true));"
echo "sed ${BOOTSTRAP}"
sed -i -e "s/${PATTERN}/${REPLACE}/g" ${BOOTSTRAP}

echo "cd ${SET_DIR}/app/Console/"
cd ${SET_DIR}/app/Console/

echo "./cake bake plugin ${PLUGIN_NAME}"
./cake bake plugin ${PLUGIN_NAME}

echo "cd ${SET_DIR}/"
cd ${SET_DIR}/

echo "git checkout HEAD ${BOOTSTRAP}"
git checkout HEAD ${BOOTSTRAP}

##
# README.mdの作成
##
createFile=${PUGLIN_PATH}/README.md
echo "${createFile}を作成します。"
echo "cat $createFile"
cat << _EOF_ > $createFile
${PLUGIN_NAME}
==============

${PLUGIN_NAME} for NetComomns3

[![Build Status](https://api.travis-ci.org/NetCommons3/${PLUGIN_NAME}.png?branch=master)](https://travis-ci.org/NetCommons3/${PLUGIN_NAME})
[![Coverage Status](https://coveralls.io/repos/NetCommons3/${PLUGIN_NAME}/badge.png?branch=master)](https://coveralls.io/r/NetCommons3/${PLUGIN_NAME}?branch=master)

| dependencies  | status |
| ------------- | ------ |
| composer.json | [![Dependency Status](https://www.versioneye.com/user/projects/(versioneye_project_ID)/badge.png)](https://www.versioneye.com/user/projects/(versioneye_project_ID)) |
_EOF_


##
# .travis.ymlの作成
##
createFile=${PUGLIN_PATH}/.travis.yml
echo "${createFile}を作成します。"
echo "cat $createFile"
cat << _EOF_ > $createFile
language: php

php:
  - 5.4
  - 5.5
  - 5.6

sudo: required

env:
  - NETCOMMONS_VERSION=master DB=mysql

before_script:
  - export NETCOMMONS_BUILD_DIR=\`dirname \$TRAVIS_BUILD_DIR\`/NetCommons3
  - git clone git://github.com/NetCommons3/NetCommons3 \$NETCOMMONS_BUILD_DIR
  - cd \$NETCOMMONS_BUILD_DIR
  - git checkout \$NETCOMMONS_VERSION
  - travis_wait . tools/build/plugins/cakephp/travis/pre.sh
  - . tools/build/plugins/cakephp/travis/environment.sh

script:
  - . tools/build/plugins/cakephp/travis/main.sh

after_script:
  - . tools/build/plugins/cakephp/travis/post.sh

notifications:
  email:
    recipients:
      - netcommons3@googlegroups.com
    on_success: never  # default: change
    on_failure: always # default: always
_EOF_


##
# composer.jsonの作成
##
createFile=${PUGLIN_PATH}/composer.json
echo "${createFile}を作成します。"
echo "cat $createFile"
cat << _EOF_ > $createFile
{
    "name": "netcommons/${PLUGIN_HAIFUN_NAME}",
    "description": "${PLUGIN_NAME} for NetCommons Plugin",
    "homepage": "http://www.netcommons.org/",
    "extra": {
        "installer-paths": {
            "app/Plugin/{\$name}": ["type:cakephp-plugin"]
        }
    },
    "require": {
        "cakephp/cakephp":              "~2.6.9",
        "cakephp/debug_kit":            "~2.2",
        "cakedc/migrations":            "~2.2",
        "phpunit/phpunit":              "~3.7.38",
        "sebastian/phpcpd":             "~2.0"
    },
    "require-dev": {
        "mustangostang/spyc":           "dev-master",
        "netcommons/auth":              "dev-master",
        "netcommons/frames":            "dev-master",
        "netcommons/m17n":              "dev-master",
        "netcommons/net-commons":       "dev-master",
        "netcommons/pages":             "dev-master",
        "netcommons/plugin-manager":    "dev-master",
        "netcommons/roles":             "dev-master",
        "netcommons/users":             "dev-master",
        "satooshi/php-coveralls":       "dev-master"
    },
    "license": "NetCommons License",
    "authors": [
        {
            "name": "NetCommons Community",
            "homepage": "https://github.com/NetCommons3/${PLUGIN_NAME}/graphs/contributors"
        }
    ],
    "type":        "cakephp-plugin",
    "keywords":    ["cakephp", "${PLUGIN_SNAKE_NAME}"],
    "config": {
        "vendor-dir": "vendors"
    }
}

_EOF_
