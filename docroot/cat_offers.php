<?php

include("include/config.php");


$QID = cat_list_offers('count desc,total', 1);

$TITLE = "Free Bets Category List";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."cat_list_offers.html");
include(TEMPLATE_DIR."footer.html");

?>