<?php

include("include/config.php");

$SORT_BY = getvar('SORT_BY');
if (empty($SORT_BY))
    $SORT_BY = 'AMT_GBP';

$CAT = getvar('c');

if (empty($CAT))
    $QID = offers_list($SORT_BY, 1, "s.enabled = 1");
else
{
    $QID = offers_list_cat($SORT_BY, $CAT, 1);
    $CAT_DESC = cat_desc($CAT);
}  

$TITLE = "$CAT Free Bets and Bonuses List";

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."cat_list_title.html");
include(TEMPLATE_DIR."list.html");
include(TEMPLATE_DIR."footer.html");

?>
