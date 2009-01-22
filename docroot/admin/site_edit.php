<?php

// edit a site

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");
$id = getvar("SITE_ID");

// check if not number or empty id
if (!is_numeric($id) || empty($id))
    redir("admin/sites_list.php?MESSAGE=".o("Invalid SITE_ID: $id"));



if (!empty($SUBMIT))
{
    site_update($id);
    $site_name = getvar('NAME');
    redir("admin/sites_list.php?MESSAGE=".o("$id: $site_name updated"));
    exit(0);
}

$FORM = site_details($id);

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/site_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>
