<?php

// create a new site

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");

if (!empty($SUBMIT))
{
    $id = site_update();
    $site_name = getvar('NAME');
    redir("admin/sites_list.php?MESSAGE=".o("$id: $site_name added"));
}
else
{
    $TEMPLATE_FILE = "site_form.html";
}

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
if (!empty($TEMPLATE_FILE)) include(TEMPLATE_DIR."admin/$TEMPLATE_FILE");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>