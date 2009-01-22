<?php

// redirs to an image

include("include/config.php");

$id = getvar("ID");

// check if not number or empty id
if (!is_numeric($id) || empty($id))
    redir("/index.php");

$SITE = site_details($id);

log_view($id);      // log the image request

rediro($SITE['IMAGE_URL']);

?>