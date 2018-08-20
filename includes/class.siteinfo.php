<?php
require_connection();

class SiteInfo {
    
}

$Info = new SiteInfo();

// Define URLS and Other Constants
defined("SITE_URL") ?  null : define("SITE_URL", "http://localhost/all/knowsme");
defined("SITE_NAME") ? null : define("SITE_NAME", "KnowsMe");

// Define Email Constants
defined("SITE_ADMIN_EMAIL") ? null : define("SITE_ADMIN_EMAIL", "knowsmeadmin@mail.com");
defined("SITE_PUBLIC_EMAIL") ? null : define("SITE_PUBLIC_EMAIL", "knowsmepublic@mail.com");

//SMTP Constants
defined("SMTP_HOST") ?   null : define("SMTP_HOST", "smtp.knowsme.com");
defined("SMTP_PORT") ?   null : define("SMTP_PORT", "smtp.knowsme.com");
defined("SMTP_UNAME") ?  null : define("SMTP_UNAME", "smtp.knowsme.com");
defined("SMTP_UPASS") ?  null : define("SMTP_UPASS", "smtp.knowsme.com");
?>