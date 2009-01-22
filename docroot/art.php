<?php

// shows an article

include("include/config.php");

$title = getvar("t");
$id = getvar("id");

// check if not number or empty id
if (empty($title) && empty($id))
{
    echo "Title empty";
    //redir("articles.php");
    exit(0);
}

if (!(empty($title)))
{
    $ART = article_details_title($title);
}
else
{
    $ART = article_details($id);
}

$TITLE = "$ART[TITLE]";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."article_details.html");
include(TEMPLATE_DIR."footer.html");

?>
