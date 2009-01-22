<?php

// create a new article

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");

if (!empty($SUBMIT))
{
    $id = article_update();
    $article_name = getvar('NAME');
    redir("admin/articles_list.php?MESSAGE=".o("$id: $article_name added"));
    exit(0);
}

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/article_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>