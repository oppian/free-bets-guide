<?php

if ($QUICKLIST == "popular")
{
	//$QUICKLIST_QID = list_popular();
	$QUICKLIST_QID = offers_list('clicks', 1, "s.enabled = 1", 5);
	$QL_TITLE = "Most Popular";
} 
elseif ($QUICKLIST == "new")
{
	$QUICKLIST_QID = offers_list('created', 1, "s.enabled = 1", 5);
	$QL_TITLE = "Newest";
}
elseif ($QUICKLIST == "expire")
{
	$QUICKLIST_QID = offers_list('expiry', 0, "NOW() < expiry AND s.enabled = 1", 5);
	$QL_TITLE = "Almost Expired";
}
elseif ($QUICKLIST == "biggest")
{
	$QUICKLIST_QID = offers_list('amt_gbp', 1, "s.enabled = 1", 5);
	$QL_TITLE = "Biggest";
}
else
{
	$QUICKLIST_QID = offers_list('AMT_GBP', 1, 'AMT_GBP > 0 AND s.enabled = 1', 5);
	$QL_TITLE = "Quick";
}

include(TEMPLATE_DIR."quicklist.html");

$QUICKLIST="";
?>
