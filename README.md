# Open Tech Calendar

## Vagrant box

Place the SQL data file in the root folder with the filename "import.sql" BEFORE first use of vagrant box. (If already used, run vagrant destroy)

    vagrant up

After first import, or changing areas, a cache file needs to be run:

    vagrant ssh
    php extension/OpenTechCalendar/cli/buildAreaTree.php 

Sometimes when you start the box the apache server doesn't start properly. Just:


    vagrant ssh
    sudo /etc/init.d/apache2 restart


### Browse App    
    
App is available on https://localhost:8443/ - note SSL! Certificate is self-signed.

A test data set is automatically imported. 
To log in as a user with sysadmin privileges, use hello@opentechcalendar.co.uk and password.

To see the sys admin UI, go to https://localhost:8443/sysadmin - the second password is 1234.

### Database

To access database via command line:

    vagrant ssh
    db

To access database via web, go to http://localhost:8081/

### Emails

All emails are caught and not really sent, and are viewable.

Go to http://localhost:8025/

### Tests

To run all tests:

    phpunit -c core/tests/
    
To run a individual test file

    phpunit -c core/tests/ core/tests/EventCreateTest.php 

### JS, CSS, Images

These should we worked on in the folders

* /core/theme/default
* /extension/?????/theme/default

To then build these into the web folder for serving, ssh into the vagrant box and run

   php buildWebAssets.php 

(You will need to run `composer install --dev` to get the required libraries - this is already done in the Vagrant box.)

CSS uses the Less pre-processor system.

Check both the source changes and the compiled changes in webSingleSite into git, as this process is not run when deploying the site.
