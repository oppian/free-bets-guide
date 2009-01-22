<?php

// shows a sites details and offers

include("include/config.php");

$url = getvar("u");

// check if not number or empty id
if (empty($url))
    redir("/sites/");

$SITE = site_details_url($url);

$TITLE = "Free bets $SITE[NAME]";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."site_details.html");
include(TEMPLATE_DIR."footer.html");

?>