<?php

// deletes an offer

include("../include/config.php");

$ID = getvar('OFFER_ID');

if (is_numeric($ID))
{
    offer_delete($ID);
    $MESSAGE = "Offer deleted offer_id: $ID";
}
else
{
    $MESSAGE = "Invalid OFFER_ID";
}

redir("/admin/offers_list.php?MESSAGE=".o($MESSAGE));

?>