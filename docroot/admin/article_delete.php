<?php

// deletes a article

include("../include/config.php");

$ARTICLE_ID = getvar('ARTICLE_ID');

if (is_numeric($ARTICLE_ID))
{
    article_delete($ARTICLE_ID);
    $MESSAGE = "article deleted article_id: $ARTICLE_ID";
}
else
{
    $MESSAGE = "Invalid ARTICLE_ID";
}

redir("/admin/articles_list.php?MESSAGE=".o($MESSAGE));

?>