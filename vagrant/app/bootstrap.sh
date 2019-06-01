#!/usr/bin/env bash

#-------------------------------------------- Good Bash Script
set -e

#-------------------------------------------- Locales
echo "en_GB.UTF-8 UTF-8" >> /etc/locale.gen
locale-gen

#-------------------------------------------- Ubuntu libraries
apt-get update
apt-get install -y postgresql apache2 php-gd php php-curl php-pgsql git php-intl curl zip  phpunit libapache2-mod-php beanstalkd phpunit

#--------------------------------------------  Dirs; Filestore, logs, etc
mkdir -p /var/opentechcalendar

mkdir -p /fileStore
chown www-data:www-data  /fileStore

mkdir -p /logs
chown www-data:www-data  /logs

#-------------------------------------------- Config files
cp /vagrant/vagrant/app/apache.conf /etc/apache2/sites-enabled/000-default.conf
cp /vagrant/vagrant/app/config.php /vagrant/config.php
cp /vagrant/vagrant/app/config.test.php /vagrant/config.test.php
cp /vagrant/vagrant/app/99-custom.ini /etc/php/7.0/apache2/conf.d/

#--------------------------------------------  MailHog Service
wget -O /bin/mailhog https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64
chmod a+x /bin/mailhog
cp /vagrant/vagrant/app/mailhog.service /etc/systemd/system/mailhog.service
chmod u+x /etc/systemd/system/mailhog.service
systemctl enable mailhog
systemctl start mailhog

#--------------------------------------------  Create Database
su --login -c "psql -c \"CREATE USER opentechcalendar3 WITH PASSWORD 'password';\"" postgres
su --login -c "psql -c \"CREATE DATABASE opentechcalendar3 WITH OWNER opentechcalendar3 ENCODING 'UTF8'  LC_COLLATE='en_GB.UTF-8' LC_CTYPE='en_GB.UTF-8'  TEMPLATE=template0 ;\"" postgres
su --login -c "psql -c \"CREATE DATABASE opentechcalendar3test WITH OWNER opentechcalendar3 ENCODING 'UTF8'  LC_COLLATE='en_GB.UTF-8' LC_CTYPE='en_GB.UTF-8'  TEMPLATE=template0 ;\"" postgres

#-------------------------------------------- PGWeb Service
# We have to create the database before we setup pgweb, or pgweb won't start
wget -O /tmp/pgweb.zip https://github.com/sosedoff/pgweb/releases/download/v0.11.2/pgweb_linux_amd64.zip
cd /tmp
unzip /tmp/pgweb.zip
cp /tmp/pgweb_linux_amd64 /bin/pgweb
cp /vagrant/vagrant/app/pgweb.service /etc/systemd/system/pgweb.service
chmod u+x /etc/systemd/system/pgweb.service
systemctl enable pgweb
systemctl start pgweb


#--------------------------------------------  Composer
mkdir -p /bin
wget -O /bin/composer.phar -q https://getcomposer.org/composer.phar
cd /vagrant
php /bin/composer.phar install --dev

#-------------------------------------------- Apache
a2enmod rewrite
a2enmod ssl
/etc/init.d/apache2 restart

#--------------------------------------------  Database, import, upgrade
export PGPASSWORD=password
if [ -f /vagrant/import.sql ]
then
  psql -U opentechcalendar3  -hlocalhost opentechcalendar3 -f /vagrant/import.sql
else
  psql -U opentechcalendar3  -hlocalhost opentechcalendar3 -f /vagrant/import-base.sql
fi
php /vagrant/core/cli/upgradeDatabase.php
php /vagrant/core/cli/loadStaticData.php

#-------------------------------------------- Bash Helpers
echo "alias db='psql -U opentechcalendar3 opentechcalendar3 -hlocalhost'" >> /home/vagrant/.bashrc
echo "localhost:5432:opentechcalendar3:opentechcalendar3:password" > /home/vagrant/.pgpass
chown vagrant:vagrant /home/vagrant/.pgpass
chmod 0600 /home/vagrant/.pgpass

echo "cd /vagrant" >> /home/vagrant/.bashrc

#-------------------------------------------- Populate Cache
cd /vagrant
php extension/OpenTechCalendar/cli/buildAreaTree.php

#-------------------------------------------- Geo Database
mkdir -p /tmp/geo
rm -r /tmp/geo/*
wget -O /tmp/geo/geo.tar.gz https://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.tar.gz
cd /tmp/geo/
tar xvzf geo.tar.gz
cp /tmp/geo/GeoLite2-Country_*/GeoLite2-Country.mmdb /var/opentechcalendar/geo-country.mmdb

