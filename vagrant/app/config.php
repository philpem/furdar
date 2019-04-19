<?php

$CONFIG->isDebug = true;

$CONFIG->actuallySendEmail = true;
$CONFIG->SMTPPort = 1025;
$CONFIG->SMTPHost = "localhost";
$CONFIG->SMTPUsername = null;
$CONFIG->SMTPPassword = null;
$CONFIG->SMTPEncyption = null;
$CONFIG->SMTPAuthMode = null;

$CONFIG->databaseName = 'opentechcalendar3';
$CONFIG->databaseHost = 'localhost';
$CONFIG->databaseUser = 'opentechcalendar3';
$CONFIG->databasePassword = 'password';

$CONFIG->isSingleSiteMode = true;
$CONFIG->singleSiteID = 1;

$CONFIG->webIndexDomain = "localhost:8080";
$CONFIG->webSiteDomain = "localhost:8080";
$CONFIG->webSysAdminDomain = "localhost:8080";

$CONFIG->hasSSL = true;
$CONFIG->webIndexDomainSSL = "localhost:8443";
$CONFIG->webSiteDomainSSL = "localhost:8443";
$CONFIG->webSysAdminDomainSSL = "localhost:8443";
$CONFIG->forceSSL = true;

$CONFIG->webCommonSessionDomain = "localhost";

$CONFIG->siteTitle = "OTC 3 DEV";

$CONFIG->googleAnalyticsTracking = null;
$CONFIG->piwikSiteID = null;

$CONFIG->fileStoreLocation= '/fileStore';

$CONFIG->importURLSecondsBetweenImports= 60;

$CONFIG->extensions = array('OpenTechCalendar','Meetup','CuratedLists','DisplayBoard','Gists','Contact');

$CONFIG->logFile = '/logs/otc3.log';

$CONFIG->siteWideMessageText = 'Test Site Wide Message!';
$CONFIG->siteWideMessageHTML = 'Test <span style="font-weight: bolder;">Site Wide</span> message!';



$CONFIG->contactTwitter = "OpenTechCal";
$CONFIG->contactEmail = "hello@opentechcalendar.co.uk";
$CONFIG->contactEmailHTML = "hello at opentechcalendar dot co dot uk";
$CONFIG->facebookPage="https://www.facebook.com/OpenTechCalendar";
$CONFIG->ourBlog= "http://blog.opentechcalendar.co.uk/";

$CONFIG->useBeanstalkd = true;
$CONFIG->beanstalkdHost = 'localhost';
$CONFIG->beanstalkdPort = 11300;
$CONFIG->beanstalkdTube = 'opentechcalendar';

$CONFIG->actuallySendEmail = true;
$CONFIG->SMTPPort = 1025;
$CONFIG->SMTPHost = "localhost";
$CONFIG->SMTPUsername = null;
$CONFIG->SMTPPassword = null;
$CONFIG->SMTPEncyption = null;
$CONFIG->SMTPAuthMode = null;

$CONFIG->geoCountryDatabaseLocation = '/var/opentechcalendar/geo-country.mmdb';
