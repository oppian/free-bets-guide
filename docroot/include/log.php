<?php
/******************************************************************************

    Module      : log functions module source code

******************************************************************************/

define(ADMIN_IP, "87.194.58.90");         // the admins ip, so u dont fill your logs up with your own browsing

function log_click($site_id)
{
    $ip = $_SERVER["REMOTE_ADDR"];
    if ($ip == ADMIN_IP)
       return;
    $referer = $_SERVER["HTTP_REFERER"];
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    $offer_id = getvar("OFFER_ID");
    
    $SQL = "
        INSERT INTO log_click
        (
             site_id
            ,ip
            ,referer
            ,useragent
            ,offer_id
        ) VALUES (
             '$site_id'
            ,'$ip'
            ,'$referer'
            ,'$useragent'
            ,'$offer_id'
        )
   ";
   db_query($SQL);
   
   if ($offer_id > 0)
   {
    rating_update($offer_id);
    click_update($offer_id);
   }
}

function log_view($site_id)
{
    $ip = $_SERVER["REMOTE_ADDR"];
    if ($ip == ADMIN_IP)
        return;
    $referer = $_SERVER["HTTP_REFERER"];
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    
    $SQL = "
        INSERT INTO log_view
        (
             site_id
            ,ip
            ,referer
            ,useragent
        ) VALUES (
             '$site_id'
            ,'$ip'
            ,'$referer'
            ,'$useragent'
        )
   ";
   db_query($SQL);
}

function log_entry()
{
    if (preg_match ( '/^\/admin\//', $_SERVER["REQUEST_URI"] ))
        return; // dont log admin
    $ip = $_SERVER["REMOTE_ADDR"];
    if ($ip == ADMIN_IP)
        return;
    $referer = $_SERVER["HTTP_REFERER"];
    $useragent = $_SERVER["HTTP_USER_AGENT"];
    $url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    
    
    
    $SQL = "
        INSERT INTO log_entry
        (
             ip
            ,url
            ,referer
            ,useragent
        ) VALUES (
             '$ip'
            ,'$url'
            ,'$referer'
            ,'$useragent'
        )
   ";
   db_query($SQL);
}

function log_view_list()
{
    $SQL = "
        SELECT
             site_id
            ,name
            ,DATE_FORMAT(v.datetimestamp, '%Y-%m-%d %T') as date
            ,ip
            ,referer
            ,useragent
        FROM log_view v, sites s
        WHERE v.site_id = s.id
        AND ip != '".ADMIN_IP."'
        ORDER BY date DESC
        LIMIT 0, 100
    ";
    
    return db_query($SQL);
}

function log_click_list()
{
    $SQL = "
        SELECT 
          c.site_id, 
          name, 
          DATE_FORMAT( c.datetimestamp,  '%Y-%m-%d %T'  )  AS date, 
          ip, 
          referer, 
          useragent,
          o.offer_id,
          offer
        FROM  sites s inner join (
           offers o right join log_click c
           on c.offer_id = o.offer_id
        )
        on c.site_id = s.id
        WHERE ip != '".ADMIN_IP."'
        ORDER  BY date DESC
        LIMIT 0, 100
    ";
    
    return db_query($SQL);
}

function log_entry_list()
{
    $SQL = "
        SELECT
             DATE_FORMAT(datetimestamp, '%Y-%m-%d %T') as date
            ,ip
            ,url
            ,referer
            ,useragent
        FROM log_entry
        WHERE ip != '".ADMIN_IP."'
        ORDER BY date DESC
        LIMIT 0, 100
    "; // 
    return db_query($SQL);
}

function rating_update($offer_id)
{
   
    $offer = offer_details($offer_id);
    if ($offer['RATING'] != 1)
    {
        $SQL = "
            UPDATE offers
            SET rating = rating - 1
            	 ,datetimestamp = datetimestamp
            WHERE offer_id = '$offer_id'
        ";
    } 
    else
    {
        $SQL = "
            UPDATE offers
            SET rating = 2
               ,datetimestamp = datetimestamp
            WHERE offer_id != '$offer_id'
            AND rating = 1
        ";
    }
    db_query($SQL);
}

function click_update($offer_id)
{
    $SQL = "
        UPDATE offers
        SET clicks = clicks + 1
            ,datetimestamp = datetimestamp
        WHERE offer_id = '$offer_id'
    ";
    db_query($SQL);
}

?>
