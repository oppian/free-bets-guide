<?php

// edits a category

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");
$CAT_ID = getvar("CAT_ID");

// check if not number or empty id
if (!is_numeric($CAT_ID) || empty($CAT_ID))
    redir("admin/cat_list.php?MESSAGE=".o("Invalid CAT_ID"));

if (!empty($SUBMIT))
{
    cat_update($CAT_ID);
    $cat = getvar('CATEGORY');
    redir("admin/cat_list.php?MESSAGE=".urlencode("$CAT_ID: $cat updated"));
    exit(0);
}

$FORM = cat_details($CAT_ID);

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/cat_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>