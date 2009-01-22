<?php

// shows the view list

include("../include/config.php");

$QID = log_entry_list();

include(TEMPLATE_DIR."header.html");
include(TEMPLATE_DIR."admin/header.html");
include(TEMPLATE_DIR."admin/log_entry_list.html");
include(TEMPLATE_DIR."admin/footer.html");
include(TEMPLATE_DIR."footer.html");

?>