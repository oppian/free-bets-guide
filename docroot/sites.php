<?php

include("include/config.php");

$CAT = getvar('c');

if (empty($CAT))
    $QID = sites_list('name', 0, "enabled = 1");
else
{
    $QID = sites_list_cat('name', $CAT);
    $CAT_DESC = cat_desc($CAT);    
}

$TITLE = "$CAT Site List - $CAT_DESC";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."sites_list.html");
include(TEMPLATE_DIR."footer.html");

?>
