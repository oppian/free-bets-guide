<?php

// urls and dirs
define("HOME_DIR", "/www/free-bets-guide/docroot/");
define("HOME_URL", "/");
define("TEMPLATE_DIR", HOME_DIR."templates/");
define("INCLUDE_DIR", HOME_DIR."include");
define("IMAGE_DIR", HOME_URL."images/");

// sql
define("DB_HOST", "localhost");
define("DB_NAME", "free_bets_guide");
define("DB_USER", "free_bets_guide");
define("DB_PASS", "M,Jre.uyZ83my:Rx");

// general defines
define("ME", $_SERVER["PHP_SELF"]);

// includes
include(INCLUDE_DIR."/db_mysql.php");
include(INCLUDE_DIR."/functions.php");
include(INCLUDE_DIR."/sites.php");
include(INCLUDE_DIR."/log.php");
include(INCLUDE_DIR."/articles.php");


// connect to db
$SQLID = db_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

//get any message
$MESSAGE = getvar('MESSAGE');

// log request
log_entry();

?>
