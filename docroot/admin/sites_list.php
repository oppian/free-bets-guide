<?php

// shows a list of sites for the admin to edit

include("../include/config.php");

$QID = sites_list('name');


include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/sites_list.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>