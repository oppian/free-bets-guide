<?php

// create a new site

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");
$OFFER_ID = getvar("OFFER_ID");

// check if not number or empty id
if (!is_numeric($OFFER_ID) || empty($OFFER_ID))
    redir("admin/offers_list.php?MESSAGE=".o("Invalid OFFER_ID"));

if (!empty($SUBMIT))
{
    offer_update($OFFER_ID);
    $site_name = getvar('NAME');
    $offer = getvar('OFFER');
    redir("admin/offers_list.php?MESSAGE=".urlencode("$OFFER_ID: $offer updated for $site_name"));
    exit(0);
}

$FORM = offer_details($OFFER_ID);
$CAT_IDS = cat_offer_id($OFFER_ID);

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/offer_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>