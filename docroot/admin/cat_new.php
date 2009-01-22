<?php

// create a new cat

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");

if (!empty($SUBMIT))
{
    $id = cat_update();
    $cat = getvar('CATEGORY');
    redir("admin/cat_list.php?MESSAGE=".o("$id: Category $cat added"));
    exit(0);
}

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/cat_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>