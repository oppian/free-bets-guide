<?php

// urls and dirs
define("HOME_DIR", "/files/home3/dalore/fbg_dev/");
define("HOME_URL", "/fbg_dev/");
define("TEMPLATE_DIR", HOME_DIR."templates/");
define("INCLUDE_DIR", HOME_DIR."include/");
define("IMAGE_DIR", HOME_URL."images/");

// sql
define("DB_HOST", "rumpus");
define("DB_NAME", "dalore_pn");
define("DB_USER", "dalore");
define("DB_PASS", "090f710f99f416e6");

// general defines
define("ME", $_SERVER["PHP_SELF"]);

// includes
include(INCLUDE_DIR."db_mysql.php");
include(INCLUDE_DIR."functions.php");
include(INCLUDE_DIR."sites.php");
include(INCLUDE_DIR."log.php");
include(INCLUDE_DIR."articles.php");


// connect to db
$SQLID = db_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//get any message
$MESSAGE = getvar('MESSAGE');

// log request
log_entry();

?>