# Web Assets

### JS, CSS, Images

These should we worked on in the folders

* /core/theme/default
* /extension/?????/theme/default

To then build these into the web folder for serving, ssh into the vagrant box and run

   php buildWebAssets.php 

(You will need to run `composer install --dev` to get the required libraries - this is already done in the Vagrant box.)

CSS uses the Less pre-processor system.

Check both the source changes and the compiled changes in webSingleSite into git, as this process is not run when deploying the site.
