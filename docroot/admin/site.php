<?php

// shows a sites details and offers

include("../include/config.php");

$id = getvar("SITE_ID");

// check if not number or empty id
if (empty($id))
    redir("/admin/");

$SITE = site_details($id);

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/site_details.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>