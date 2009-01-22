<?php

include("include/config.php");

//log_entry();

$QID = offers_list('rating', 0, 's.enabled = 1', 10);
$ART = article_details(4);

$TITLE = "Free Bets Guide";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."index.html");
include(TEMPLATE_DIR."list.html");
include(TEMPLATE_DIR."footer.html");

?>
