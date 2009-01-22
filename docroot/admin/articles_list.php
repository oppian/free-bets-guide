<?php

// shows a list of sites for the admin to edit

include("../include/config.php");

$QID = articles_list('datetimestamp', 1);


include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/articles_list.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>