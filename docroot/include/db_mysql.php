<?php

// mysql database abstraction library


function db_connect($server, $username, $password, $db_name)
{
    $SQLID = mysql_connect ($server, $username, $password);
    if (!$SQLID)
    {
        die('Not connected : ' . mysql_error());
    }
    
    $db_selected = mysql_select_db($db_name, $SQLID);
    if (!$db_selected) 
    {
       die ("Can't use $db_name : " . mysql_error());
    }
    
    return $SQLID;    
}

function db_query($SQL)
{
    global $SQLID;
    $result = mysql_query($SQL, $SQLID);
    
    if (!$result) 
    {
        die('Invalid query: ' . $SQL . "\n" . "Error: " . mysql_error());
    }

    return $result;
}

function db_do($sql)
{
    return db_query($sql);
}

function db_fetch($QID)
{
    $row = mysql_fetch_array($QID);
    
    if ($row)
    {
        foreach ($row as $key => $value)
        {
            $row[strtoupper($key)] = $value;
        }
    }
    
    return $row;
}

/*
 * db_last_id()
 * returns the last auto inc id
 */
function db_last_id()
{
    return mysql_insert_id();
}

function db_escape($text)
{
  return mysql_escape_string($text);
}

?>
