<?php

// create a new article

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");
$id = getvar("ARTICLE_ID");

// check if not number or empty id
if (!is_numeric($id) || empty($id))
    redir("admin/articles_list.php?MESSAGE=".o("Invalid ARTICLE_ID"));

if (!empty($SUBMIT))
{
    article_update($id);
    $article_name = getvar('TITLE');
    redir("admin/articles_list.php?MESSAGE=".urlencode("$id: $article_name updated"));
    exit(0);
}

$FORM = article_details($id);

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/article_form.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>