<?php

// sites module

function sites_list($SORT_BY="", $DESC=0, $WHERE="", $LIMIT=0)
{
    $SQL = "
        SELECT *
        FROM sites
    ";
    if (!empty($WHERE))
        $SQL .= " WHERE $WHERE \n";

    if (!empty($SORT_BY))
    {
        $SQL .= " ORDER BY $SORT_BY ";
        $SQL .= $DESC ? "DESC\n" : "ASC\n";
    }

    if ($LIMIT > 0)
        $SQL .= " LIMIT 0,$LIMIT\n";
    
    return db_query($SQL);
}

function site_delete($site_id)
{
    return db_delete('sites', 'id', $site_id);
}

// Adds or updates a sites details
function site_update($id = '')
{
	// get fields
	$FIELDS = array(
	     'NAME'
	    ,'DISPLAY_URL'
	    ,'URL'
	    ,'IMAGE_URL'
        ,'ENABLED'
	    ,'DESCRIPTION'
	    ,'LONG_DESCRIPTION'
	    ,'NOTES'
	);
    
    // get form fields
    $FORM = form_create_from_fields($FIELDS);
    
    // build sql query based on form
    if (empty($id))
    {
        // insert
        $sql = sql_make_insert("sites", $FORM);
        $LAST_ID = TRUE;
    } else
    {
        // update
    	$sql = sql_make_update("sites", $FORM, "id = '$id'");
    	$LAST_ID = FALSE;
    }   
    
    db_query($sql);			// execute
    
    if ($LAST_ID)
        $id =db_last_id();	// return last id
        
    // do categories
    $cat_ids = getvar('CAT_ID');
    if (is_array($cat_ids))
    {
        // first delete all entries for this site
        db_delete('cat_sites', 'site_id', $id);
        
        $count = count($cat_ids);
        $SQL = "INSERT INTO cat_sites (site_id, cat_id) VALUES ('$id', '";
        $SQL .= implode("'), ('$id', '", $cat_ids);
        $SQL .= "')";
        
        db_query($SQL);
        
    }
       
   	return $id;
}

function site_details($id)
{
    $SQL = "
        SELECT 
            id,
            name,
            url,
            display_url,
            image_url,
            enabled,
            description,
            long_description,
            notes
        FROM sites
        WHERE id='$id'
    ";
    
    $qid = db_query($SQL);
    return db_fetch($qid);
}

function site_details_url($url)
{
    $SQL = "
        SELECT id
        FROM sites
        WHERE display_url='$url'
    ";
    
    $qid = db_query($SQL);
    $row = db_fetch($qid);
    return site_details($row['ID']);
}

function site_details_name($name)
{
    $SQL = "
        SELECT id
        FROM sites
        WHERE name='$name'
    ";
    
    $qid = db_query($SQL);
    $row = db_fetch($qid);
    return site_details($row['ID']);
}

function site_offers_list($site_id)
{
    $SQL = "
        SELECT 
            offer_id, 
            site_id, 
            offer, 
            amt_gbp, 
            rating, 
            description,
            long_description,
            expiry
        FROM offers
        WHERE site_id = '$site_id'
        AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
        ORDER BY rating
    ";
    return db_query($SQL);
}

function sites_cat($site_id)
{
    $SQL = 
    "
        SELECT
             img
            ,cat_desc
            ,cat_sites.cat_id as cat_id
            ,category
        FROM cat_sites, categories
        WHERE cat_sites.cat_id = categories.cat_id
        AND site_id = '$site_id'
    ";
    
    return db_query($SQL);
}

function offers_cat($offer_id)
{
    $SQL = 
    "
        SELECT
             img
            ,cat_desc
            ,cat_offers.cat_id as cat_id
            ,category
        FROM cat_offers, categories
        WHERE cat_offers.cat_id = categories.cat_id
        AND offer_id = '$offer_id'
    ";
    return db_query($SQL);
}

function cat_site_id($site_id)
{
    $SQL =
    "
        SELECT cat_id
        FROM cat_sites
        WHERE site_id = '$site_id'
    ";
    $qid = db_query($SQL);
    while ($row = db_fetch($qid))
        $cat_ids[] = $row[CAT_ID];
        
    return $cat_ids;
}

function cat_offer_id($offer_id)
{
    $SQL =
    "
        SELECT cat_id
        FROM cat_offers
        WHERE offer_id = '$offer_id'
    ";
    $qid = db_query($SQL);
    while ($row = db_fetch($qid))
        $cat_ids[] = $row[CAT_ID];
        
    return $cat_ids;
}

function cat_list($SORT_BY="")
{
    $SQL = "
        SELECT
             cat_id
            ,category
            ,img
            ,cat_desc
        FROM categories
    ";
    
    if (!empty($SORT_BY))
        $SQL .= " ORDER BY $SORT_BY ";
        
    return db_query($SQL);
}

function cat_list_sites($SORT_BY="", $DESC=0)
{
    $SQL = "
        SELECT  
             c.cat_id as cat_id
            ,count(site_id) as count
            ,category
            ,img
            ,cat_desc
        FROM categories c 
        LEFT JOIN cat_sites s
            ON c.cat_id = s.cat_id
        LEFT JOIN sites 
            ON sites.id = s.site_id
        WHERE sites.enabled=1
        GROUP BY cat_id
    ";
    
    if (!empty($SORT_BY))
    {
        $SQL .= " ORDER BY $SORT_BY ";
        $SQL .= $DESC ? "DESC\n" : "ASC\n";
    }
    
    return db_query($SQL);
}

function cat_list_offers($SORT_BY="", $DESC=0)
{
    $SQL = "
       SELECT 
        	c.cat_id AS cat_id,
        	count( o.offer_id ) AS count,
        	category,
        	img,
        	cat_desc,
        	sum( amt_gbp ) AS total,
		sum( amt_gbp ) / count( o.offer_id ) AS avg_offer
				FROM 
					categories c LEFT  JOIN cat_offers o ON c.cat_id = o.cat_id, 
					offers f, sites s
				WHERE o.offer_id = f.offer_id
				AND f.site_id = s.id
				AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
				AND s.enabled = 1
				GROUP  BY cat_id
				HAVING count > 0
    ";
    
    if (!empty($SORT_BY))
    {
        $SQL .= " ORDER BY $SORT_BY ";
        $SQL .= $DESC ? "DESC\n" : "ASC\n";
    }
    
    return db_query($SQL);
}

function cat_desc($cat)
{
    $SQL = "
        SELECT cat_desc
        FROM categories
        WHERE category='$cat'
    ";
    
    $qid = db_query($SQL);
    $row = db_fetch($qid);
    $ret = $row[CAT_DESC];
    return $ret;
}

function cat_details($cat_id)
{
    $SQL = "
        SELECT *
        FROM categories
        WHERE cat_id = '$cat_id'
   ";
   $qid = db_query($SQL);
   return db_fetch($qid);
}

// Adds or updates an category
function cat_update($id = '')
{
	// get fields
	$FIELDS = array(
	     'CATEGORY'
	    ,'CAT_DESC'
	    ,'IMG'
	);
    
    // get form fields
    $FORM = form_create_from_fields($FIELDS);
    
    // build sql query based on form
    if (empty($id))
    {
        // insert
        $sql = sql_make_insert("categories", $FORM);
        $LAST_ID = TRUE;
    } else
    {
        // update
    	$sql = sql_make_update("categories", $FORM, "cat_id = '$id'");
    	$LAST_ID = FALSE;
    }   
    
    db_query($sql);			// execute
    
    // return id
    if ($LAST_ID)
    	$id = db_last_id();	// return last id

  	return $id;
}

function cat_delete($cat_id)
{
    return db_delete('categories', 'cat_id', $cat_id);
}

function sites_count()
{
    $SQL = "
        SELECT count(*) as count
        FROM sites
        WHERE sites.enabled = 1
    ";
    
    $qid = db_query($SQL);
    $row = db_fetch($qid);
    return $row[COUNT];
}

function offers_count()
{
    $SQL = "
        SELECT count(*) as count
        FROM offers o LEFT JOIN sites s ON o.site_id = s.id
        WHERE ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
        AND s.enabled = 1
    ";
    
    $qid = db_query($SQL);
    $row = db_fetch($qid);
    return $row[COUNT];
}

function sites_list_cat($SORT_BY, $CAT)
{
    $SQL = "
        SELECT
             id
            ,name
            ,url
            ,display_url 
            ,image_url
            ,description
        FROM sites s, cat_sites c, categories d
        WHERE d.category = '$CAT'
        AND d.cat_id = c.cat_id
        AND s.id = c.site_id
        AND s.enabled = 1
    ";
    
    if (!empty($SORT_BY))
        $SQL .= " ORDER BY $SORT_BY ";
    
    return db_query($SQL);
}

function offers_list_cat($SORT_BY, $CAT, $DESC=0)
{
    $SQL = "
        SELECT
             id
            ,name
            ,url
            ,display_url 
            ,image_url
            ,o.offer_id as offer_id
            ,o.site_id as site_id 
            ,offer
            ,amt_gbp
            ,rating
            ,o.description as offer_description
            ,s.description as site_description
        FROM sites s, cat_offers c, categories d, offers o
        WHERE d.category = '$CAT'
        AND d.cat_id = c.cat_id
        AND s.id = o.site_id
        AND c.offer_id = o.offer_id
        AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
        AND s.enabled = 1
    ";
    
    $SQL .= " ORDER BY $SORT_BY ";
    $SQL .= $DESC ? "DESC\n" : "ASC\n";
    
    return db_query($SQL);
}

function offers_list($SORT_BY="", $DESC=0, $WHERE="", $LIMIT=0)
{
    $SQL = "
        SELECT 
            offer_id, 
            site_id, 
            offer, 
            amt_gbp, 
            rating,
            expiry,
            o.description as offer_description, 
            s.description as site_description,
            name,
            url,
            id,
            display_url,
            image_url,
            o.datetimestamp as created
        FROM offers o, sites s
        WHERE site_id = s.id
        AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
    ";
    if (!empty($WHERE))
        $SQL .= " AND $WHERE \n";
        
    if (!empty($SORT_BY))
    {
        $SQL .= " ORDER BY $SORT_BY ";
        $SQL .= $DESC ? "DESC\n" : "ASC\n";
   }
   
   if ($LIMIT > 0)
   		$SQL .= " LIMIT 0,$LIMIT\n";

    return db_query($SQL);
}

function offer_details($id)
{
    $SQL = "
        SELECT 
            offer_id, 
            site_id, 
            offer, 
            amt_gbp, 
            rating,
            expiry,
            o.description as description, 
            o.long_description as long_description,
            name,
            id,
            image_url
        FROM offers o, sites s
        WHERE site_id = s.id
        AND offer_id='$id'
        AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
    ";
    
    $qid = db_query($SQL);
    return db_fetch($qid);
}

// Adds or updates an offer
function offer_update($id = '')
{
	// get fields
	$FIELDS = array(
	     'SITE_ID'
	    ,'OFFER'
	    ,'AMT_GBP'
	    ,'RATING'
	    ,'EXPIRY'
	    ,'DESCRIPTION'
	    ,'LONG_DESCRIPTION'
	);
    
    // get form fields
    $FORM = form_create_from_fields($FIELDS);
    
    // build sql query based on form
    if (empty($id))
    {
        // insert
        $sql = sql_make_insert("offers", $FORM);
        $LAST_ID = TRUE;
    } else
    {
        // update
    	$sql = sql_make_update("offers", $FORM, "offer_id = '$id'", 'datetimestamp');
    	$LAST_ID = FALSE;
    }   
    
    db_query($sql);			// execute
    
    // return id
    if ($LAST_ID)
    	$id = db_last_id();	// return last id
    	
    // do categories
    $cat_ids = getvar('CAT_ID');
    if (is_array($cat_ids))
    {
        // first delete all entries for this offer
        db_delete('cat_offers', 'offer_id', $id);
        
        $count = count($cat_ids);
        $SQL = "INSERT INTO cat_offers (offer_id, cat_id) VALUES ('$id', '";
        $SQL .= implode("'), ('$id', '", $cat_ids);
        $SQL .= "')";
        
        db_query($SQL);        
    }	
    
    return $id;
}

function offer_delete($id)
{
    return db_delete('offers', 'offer_id', $id);
}

function links_list()
{
    $SQL = "
        SELECT *
        FROM links
    ";
    return db_query($SQL);
}

function list_popular($days = 30, $limit = 5)
{
	$sql = "
		SELECT 
			c.offer_id, 
			count(*) AS total, 
			offer, 
			amt_gbp, 
			o.description AS offer_description, 
			o.site_id, 
			name
		FROM 
			log_click c, 
			offers o, 
			sites s
		WHERE c.offer_id = o.offer_id
		AND 	o.site_id = s.id
		AND DATE_SUB(CURDATE(),INTERVAL $days DAY) <= c.datetimestamp
		AND ( NOW() < expiry OR expiry is NULL OR expiry = '0000-00-00 00:00:00' )
        AND s.enabled = 1
		GROUP BY c.offer_id
		ORDER by total desc
		LIMIT 0,$limit
	";
	
	return db_query($sql);
}

?>
