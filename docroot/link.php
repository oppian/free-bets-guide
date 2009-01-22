<?php

// shows a sites details and offers

include("include/config.php");

$id = getvar("ID");

// check if not number or empty id
if (!is_numeric($id) || empty($id))
    redir("/index.php");

$SITE = site_details($id);

log_click($id);     // log the click thru

rediro($SITE['URL']);

?>
