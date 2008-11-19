#!/bin/bash
FROM_DIR=`pwd`
DIR=`dirname $0`
cd $DIR;
phpunit --report ./../doc/cc_report itsy_framework_suite itsy_framework_suite.php
cd $FROM_DIR;
