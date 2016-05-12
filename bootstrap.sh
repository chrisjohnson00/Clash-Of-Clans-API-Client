#!/usr/bin/env bash

apt-get update

### install software
apt-get install -yf php5-dev php5-json php5-cli php5-curl

## install composer
if [ ! -f /usr/local/bin/composer ]; then
  curl -sS https://getcomposer.org/installer | php
  mv composer.phar /usr/local/bin/composer
fi
if [ ! -d "/home/vagrant/.composer" ]; then
  mkdir /home/vagrant/.composer
  chown -R vagrant:vagrant /home/vagrant/.composer
fi
  oauthToken=$(</vagrant/github-oauth-token.txt)
  composer config -g github-oauth.github.com $oauthToken

## install phpunit 4.8
if [ ! -f /usr/local/bin/phpunit ]; then
  wget --quiet https://phar.phpunit.de/phpunit-old.phar
  mv phpunit-old.phar phpunit.phar
  chmod +x phpunit.phar
  mv phpunit.phar /usr/local/bin/phpunit
fi