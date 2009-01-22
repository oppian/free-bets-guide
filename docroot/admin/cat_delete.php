<?php

// deletes an offer

include("../include/config.php");

$ID = getvar('CAT_ID');

if (is_numeric($ID))
{
    cat_delete($ID);
    $MESSAGE = "Category deleted cat_id: $ID";
}
else
{
    $MESSAGE = "Invalid CAT_ID";
}

redir("/admin/cat_list.php?MESSAGE=".o($MESSAGE));

?>