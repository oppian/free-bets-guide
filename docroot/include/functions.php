<?php
/******************************************************************************

    Module      : functions module source code


*******************************************************************************

    Description:		
		This module contains general purpose functions.
	
    Summary:


******************************************************************************/

function today()
{
	return date("l, jS of F, Y");
}


function now()
{
	return time();
}

/******************************************************************************

	Description:
		prints out a string for html usage
	
******************************************************************************/
function p($string) 
{
	return nl2br(htmlspecialchars(stripslashes($string)));
}



/* this is an internal function and normally isn't called by the user.  it
 * loops through the results of a select query $query and prints HTML
 * around it, for use by things like listboxes and radio selections
 */
function db_query_loop($query, $prefix, $suffix, $found_str, $default="") 
{

	$output = "";
	$result = db_query( $query);
	while (list($val, $label) = db_fetch($result)) 
	{
    	if (is_array($default))
			$selected = empty($default[$val]) ? "" : $found_str;
		else
			$selected = $val == $default ? $found_str : "";

		$output .= "$prefix value='$label' $selected>$val$suffix";
	}

	return $output;
}

/* generate the <option> statements for a <select> listbox, based on the
 * results of a SELECT query ($query).  any results that match $default
 * are pre-selected, $default can be a string or an array in the case of
 * multi-select listboxes.  $suffix is printed at the end of each <option>
 * statement, and normally is just a line break */
 function db_listbox($query, $default="", $suffix="\n") 
{
	return db_query_loop($query, "<option", $suffix, "selected", $default);
}

/*
 * returns true if posted
 */
function ispost()
{
    return (strcasecmp($_SERVER['REQUEST_METHOD'], "POST") == 0);
}

/*
 * this returns true if totally a number, no decimals
 */
function isnum($num)
{
    return preg_match ("/^([0-9]+)$/", $num);    
}


function error($e)
{
	if ($e)
	{
		$msg = '<code class="error">'.p('<<').'</code>';
		return $msg;
	}
}


function db_exists($value, $table, $column, $delete = "")
{
	$sql = "
		SELECT 1 as TEST
		FROM $table
		WHERE $column = '$value'
	";
	
	if ($delete == 1) $sql .= " AND DELETED = 'N' ";
	if ($delete == 2) $sql .= " AND DEL = 'N' ";
	
	$sqlid = db_do($sql);
	$row = db_fetch($sqlid);
	return $row['TEST'] == 1;
}

/******************************************************************************

db_keyvalue:
returns an sqlid with "$key" => "$value" for use in popups

******************************************************************************/

function db_keyvalue($key, $value, $table, $where)
{
    $sql = "
        select 
            $key as KEY,
            $value as VALUE
        from $table
    ";
    
    if (!empty($where))
    {
        $sql .= " WHERE $where";
    }
    
    return db_do($sql);
}

/******************************************************************************

checkEmail:
checks if valid email

******************************************************************************/
function checkEmail($Email) 
{

   //Do the basic Reg Exp Matching for simple validation
   if (eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $Email)) {
       return FALSE;
   }
   
   //split the Email Up for Server validation
   list($Username, $Domain) = split("@",$Email);
   
   if (empty($Domain)) return FALSE;
   
   //If you get an mx record then this is a valid email domain
   if(getmxrr($Domain, $MXHost)) {
       return TRUE;
   }
   else 
   {
        // get ip
        $ip = gethostbyname($Domain);
       
        // if no lookup
        if ($ip == $Domain)
        {
            // lookup failed
            return FALSE;
        };
        
       //else use the domain given to try and connect on port 25
       //if you can connect then it's a valid domain and that's good enough
       if(fsockopen($ip, 25, $errno, $errstr, 30)) 
       {
           return TRUE; 
       }
       else {
           return FALSE; 
       }
   }
} 

function db_delete_hard($table, $column, $value)
{
	if (empty($value)) return;
	
    $sql = "DELETE FROM $table WHERE $column = '$value'";
    db_do($sql);
}

function db_delete($table, $column, $value)
{
	if (empty($value)) return;
	return db_delete_hard($table, $column, $value);
}

function db_del($table, $column, $value)
{
	if (empty($value)) return;
	
    $sql = "UPDATE $table SET DEL = 'Y' WHERE $column = '$value' LIMIT 1";
    db_do($sql);
}

function sql_make_insert($tablename, $FORM)
{
	if (!is_array($FORM)) return FALSE;
	
    $sql = "INSERT INTO $tablename ( ";
    
    // loop thru for fieldnames
    $i = 1;
    foreach($FORM as $columnname => $value)
    {
        $sql .= " $columnname ";
        if ($i++ < count($FORM)) $sql .= ",";       // if more add a comma
    }

    // now for values
    $sql .= " ) VALUES ( ";    
    
    $i = 1;
    foreach($FORM as $columnname => $value)
    {
        $sql .= " '$value' ";
        if ($i++ < count($FORM)) $sql .= ",";       // if more add a comma
    }
    
    $sql .= " ) ";
    
    return $sql;
}

function sql_make_update($tablename, $FORM, $WHERE, $TIMESTAMP="")
{
    $sql = "UPDATE $tablename SET ";
    
    // loop thru for form
    $i = 1;
    foreach($FORM as $columnname => $value)
    {
        $sql .= " $columnname = '$value' ";
        if ($i++ < count($FORM)) $sql .= "\n,";       // if more add a comma
    }
    
    if (strlen($TIMESTAMP) > 0)
    	$sql .= " , $TIMESTAMP = $TIMESTAMP \n";

    // now for values
    $sql .= " WHERE $WHERE";
    
    return $sql;
}


function form_create_from_fields($fields)
{
  if (!is_array($fields)) return FALSE;
	
    // now get fields, walk thru array
    foreach($fields as $fieldname)
    {
        $val = db_escape(getvar($fieldname));
        $FORM[$fieldname] = $val;
        //if (empty($FORM[$fieldname])) unset($FORM[$fieldname]);
    };
    return $FORM;
}

function db_cgi_popupoption($table, $value, $display, $WHERE_CLAUSE = "", $default_value = "", $order_by_value=0)
{
    $sql = "
        SELECT
            $value as VALUE
            ,$display as DISPLAY
        FROM $table
    ";
    
    if (!(empty($WHERE_CLAUSE))) $sql .= " WHERE $WHERE_CLAUSE ";
    
    $sql .= $order_by_value ? " ORDER BY VALUE " : " ORDER BY DISPLAY ";
    
    $sqlid = db_do($sql);
    
    $return_string = "";
    while ($row = db_fetch($sqlid))
    {
        $return_string .= "<OPTION VALUE='$row[VALUE]' ".selected($row[VALUE], $default_value).">$row[DISPLAY]</OPTION>\n";
    }
    
    return $return_string;
}

/******************************************************************************

	Description:
		turns all associative array keys to lowercase
	
	Parameters:
		
	
	Return Code:
		
	
	Comments:
		
	
******************************************************************************/
function db_aa_lower(&$row) 
{
    foreach ($row as $sKey => $sVal) 
    {
        $row[strtolower($sKey)] = $sVal;
    }
}

/******************************************************************************

	Description:
		turns all associative array keys to upper
	
	Parameters:
		
	
	Return Code:
		
	
	Comments:
		
	
******************************************************************************/
function db_aa_upper(&$row) 
{
    foreach ($row as $sKey => $sVal) 
    {
        $row[strtolower($sKey)] = $sVal;
    }
}

/******************************************************************************

	Description:
		check if a given value exists in a specified column in a table
	
	Parameters:
		
	
	Return Code:
		
	
	Comments:
		
	
******************************************************************************/
function db_check_exists($table, $column, $value) 
{
    $sql = "
        SELECT 1 as TEST
        FROM $table
        WHERE $column = '$value'
    ";
    
    $sqlid = db_query( $sql);
    $row = db_fetch($sqlid);
    
    return $row[TEST];
}

function checked($test, $value)
{
    if ($test == $value) return "CHECKED";
}

function selected($test, $value)
{
    if (is_array($value))
    {
        foreach ($value as $val)
        {
            if ($test == $val) return "SELECTED";
        }
    }
    else
    {
        if ($test == $value) return "SELECTED";
    }
}

function datetime_null($date)
{
	if ($date == "0000-00-00 0:00")
	{
		return "";
	} else
	{
		return $date;
	}
}

function date_null($date)
{
	if ($date == "0000-00-00")
	{
		return "";
	} else
	{
		return $date;
	}
}

function time_null($date)
{
	if ($date == "0:00")
	{
		return "";
	} else
	{
		return $date;
	}
}


function o($text)
{
    return nl2br(htmlentities($text));
}


function on($text)
{
    return htmlentities($text);
}


function redir($url)
{
    header("Location: http://".$_SERVER['HTTP_HOST'].HOME_URL.$url);
}

function rediro($url)
{
    header("Location: $url");
}


function getvar($var)
{
    $ret = $_POST[$var];
    if (empty($ret))
    {
        $ret = $_GET[$var];
    }
    if (empty($ret))
    {
        $ret = $_COOKIE[$var];
    }
    
    return $ret;
}

function getvarn($var)
{
    $num = getvar($var);
    return str_replace(',', '.', $num);
}

function no($num, $dec=2)
{
    return o(number_format($num, $dec));
}


?>
