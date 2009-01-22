<?php

// create a new offer

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");

if (!empty($SUBMIT))
{
    $id = offer_update();
    $site_name = getvar('NAME');
    $offer = getvar('OFFER');
    redir("admin/offers_list.php?MESSAGE=".o("$id: $offer added for $site_name"));
}
else
{
    $TEMPLATE_FILE = "offer_form.html";
}

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
if (!empty($TEMPLATE_FILE)) include(TEMPLATE_DIR."admin/$TEMPLATE_FILE");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>