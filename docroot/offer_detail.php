<?php

// shows details of an offer and t&c

include("include/config.php");

$id = getvar("OFFER_ID");

// check if not number or empty id
if (!is_numeric($id) || empty($id))
    redir("");

$OFFER = offer_details($id);
$SITE =  site_details($OFFER[SITE_ID]);

include(TEMPLATE_DIR."offer_details.html");


?>