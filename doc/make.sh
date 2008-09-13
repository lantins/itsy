#!/bin/bash
FROM_DIR=`pwd`
DIR=`dirname $0`
cd $DIR;
phpdoc -c phpdoc.ini
cd $FROM_DIR;