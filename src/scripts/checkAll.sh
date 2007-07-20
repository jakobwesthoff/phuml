#!/bin/sh

find ./ -iname "*.php" -exec php -l {} \;
