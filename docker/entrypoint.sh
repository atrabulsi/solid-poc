#!/bin/bash

mkdir -p /var/www/html/public

echo "Start nginx now!!!"
service nginx start
if [ $? -ne 0 ]; then
  echo "Failed to start nginx, exit code: $exitCode"
  exit $exitCode
fi

echo "Start php fpm now!!!"
php-fpm &
if [ $? -ne 0 ]; then
  echo "Failed to start php-fpm, exit code: $exitCode"
  exit $exitCode
fi

while sleep 60; do
  ps aux | grep /usr/sbin/nginx | grep -q -v grep
  if [ $? -ne 0 ]; then
    echo "nginx has crashed, exit the container now!"
    exit 1
  fi
  ps aux | grep "php-fpm: master process" |grep -q -v grep
  if [ $? -ne 0 ]; then
    echo "php fpm has crashed, exit the container now!"
    exit 1
  fi
  # we will not check if remote syslog is running, because we may restart it from time to time
  # and we don't want the container to crash when that happens
done
