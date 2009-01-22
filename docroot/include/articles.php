<?php

// Articles module


function articles_list($SORT_BY="", $DESC=0)
{
    $SQL = "
        SELECT 
             DATE_FORMAT(datetimestamp, '%Y-%m-%d') as date
            ,id
            ,short
            ,title
        FROM articles
    ";
    
    if (!empty($SORT_BY))
    {
        $SQL .= " ORDER BY $SORT_BY ";
        $SQL .= $DESC ? "DESC\n" : "ASC\n";
    }
    
    return db_query($SQL);
}

function article_delete($article_id)
{
    return db_delete('articles', 'id', $article_id);
}

// Adds or updates a articles details
function article_update($id = '')
{
	// get fields
	$FIELDS = array(
	     'TITLE'
	    ,'SHORT'
	    ,'CONTENTS'
	);
    
    // get form fields
    $FORM = form_create_from_fields($FIELDS);
    
    // build sql query based on form
    if (empty($id))
    {
        // insert
        $sql = sql_make_insert("articles", $FORM);
        $LAST_ID = TRUE;
    } else
    {
        // update
    	$sql = sql_make_update("articles", $FORM, "id = '$id'");
    	$LAST_ID = FALSE;
    }   
    
    db_query($sql);			// execute
    
    if ($LAST_ID)
        $id =db_last_id();	// return last id
        
   	return $id;
}

function article_details($id)
{
    $SQL = "
        SELECT 
             DATE_FORMAT(datetimestamp, '%Y-%m-%d') as date
            ,id
            ,short
            ,title
            ,contents
        FROM articles
        WHERE id='$id'
    ";
    
    $qid = db_query($SQL);
    return db_fetch($qid);
}

function article_details_title($title)
{
    $SQL = "
        SELECT 
             DATE_FORMAT(datetimestamp, '%Y-%m-%d') as date
            ,id
            ,short
            ,title
            ,contents
        FROM articles
        WHERE title='$title'
    ";
    
    $qid = db_query($SQL);
    return db_fetch($qid);
}
?>