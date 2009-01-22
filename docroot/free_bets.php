<?php

// shows a sites details and offers

include("include/config.php");

$name = getvar("n");
$id = getvar("i");

// check if empty 
if (empty($name) && empty($id))
    redir("/sites/");

if (!(empty($name)))
{
    $SITE = site_details_name($name);
}
else
{
    $SITE = site_details($id);
}

$TITLE = "Free bets $SITE[NAME]";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."site_details.html");
include(TEMPLATE_DIR."footer.html");

?>
