<?php

// matching calculator

include("../include/config.php");

$SUBMIT = getvar("SUBMIT");

if (!empty($SUBMIT))
{
    do_calcs();
    $TEMPLATE_FILE = "calc_results.html";
}
else
{
    $TEMPLATE_FILE = "calc.html";
    
    // set defaults
    $FORM['COMMISSION'] = 3;
    
}

$TITLE = "Free Cash Guide - Calculator";
$SUB_MENU = "/members/topside.html";

include(TEMPLATE_DIR."header.html");
if (!empty($TEMPLATE_FILE)) include(TEMPLATE_DIR."members/$TEMPLATE_FILE");
include(TEMPLATE_DIR."footer.html");

// functions

function do_calcs()
{
    global$BACK_RETURN, $LAY_STAKE, $LAY_RISK, $OUTCOME, $BET_SIZE;
    $BET_SIZE = getvarn('BET_SIZE');
    $BACK_ODDS = getvarn('BACK_ODDS');
    $STAKE_RET = getvarn('STAKE_RET');
    $STAKE_FORFEIT = $STAKE_RET ? 0 : $BET_SIZE;
    $LAY_ODDS = getvarn('LAY_ODDS');
    $LAY_COMMISSION = getvarn('COMMISSION');

    $BACK_RETURN = $BACK_ODDS * $BET_SIZE - $STAKE_FORFEIT;
    $LAY_STAKE = $BACK_RETURN / ($LAY_ODDS - $LAY_COMMISSION / 100);
    $LAY_RISK = $LAY_STAKE * ($LAY_ODDS - 1);
    $OUTCOME = $BACK_RETURN - $LAY_RISK;
}


?>