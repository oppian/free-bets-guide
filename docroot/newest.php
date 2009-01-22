<?php

include("include/config.php");

//log_entry();

$QID = offers_list('created', 1, 's.enabled = 1', 20);
$ART = article_details(4);

$TITLE = "20 Newest Free Bets";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."newest.html");
include(TEMPLATE_DIR."list.html");
include(TEMPLATE_DIR."footer.html");

?>
