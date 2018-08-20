<?php

date_default_timezone_set("Africa/Accra");

// define path constants
defined("DS") ?         null : define("DS", DIRECTORY_SEPARATOR);

defined("SITE_ROOT") ?  null : define("SITE_ROOT", dirname(dirname(__FILE__)).DS);

defined("CONNECT_PATH") ?   null : define("CONNECT_PATH", dirname(SITE_ROOT).DS);

defined("INC_PATH") ?   null : define("INC_PATH", dirname(__FILE__).DS);

defined("TEMP_PATH") ?   null : define("TEMP_PATH", SITE_ROOT."template". DS);



// General Constants
defined("IMG_EMOT") ?      null : define("IMG_EMOT", "img/emoticons");
defined("MB") ?            null : define("MB", 1048576);
defined("MAX_IMG_SIZE") ?  null : define("IMG_MAX_SIZE", MB);
defined("MAX_FILE_SIZE") ? null : define("MAX_FILE_SIZE", (MB/2)+MB);

// Include Files
require_once(INC_PATH."kme_connect.php");
require_once(INC_PATH."class.databaseObject.php");
require_once(INC_PATH."class.database.php");
require_once(INC_PATH."functions.php");

require_once(INC_PATH."class.session.php");
require_once(INC_PATH."class.siteinfo.php");
require_once(INC_PATH."class.template.php");
require_once(INC_PATH."class.session.php");
require_once(INC_PATH."class.user.php");
require_once(INC_PATH."class.post.php");
require_once(INC_PATH."class.chat.php");
require_once(INC_PATH."class.uploads.php");
require_once(INC_PATH."class.photos.php");
require_once(INC_PATH."class.validate.php");
require_once(INC_PATH."class.dates.php");
require_once(INC_PATH."class.texts.php");



?>