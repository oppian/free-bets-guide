<?php

// deletes a site

include("../include/config.php");

$SITE_ID = getvar('SITE_ID');

if (is_numeric($SITE_ID))
{
    #site_delete($SITE_ID);
    #$MESSAGE = "Site deleted site_id: $SITE_ID";
  $MESSAGE = "Delete has been disabled for now";
}
else
{
    $MESSAGE = "Invalid site_id";
}

redir("/admin/sites_list.php?MESSAGE=".o($MESSAGE));

?>
