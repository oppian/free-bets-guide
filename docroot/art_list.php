<?php

include("include/config.php");

$QID = articles_list('date');

$TITLE = "Free Bets Guide - Articles";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."articles.html");
include(TEMPLATE_DIR."footer.html");

?>