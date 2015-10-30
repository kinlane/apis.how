<?php
$route = '/link/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['created_date'])){ $created_date = mysql_real_escape_string($params['created_date']); } else { $created_date = date('Y-m-d H:i:s'); }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = 'no url'; }
	if(isset($params['short_url'])){ $short_url = mysql_real_escape_string($params['short_url']); } else { $short_url = "http://apis.how/" . strtolower(getToken(10)); }

  $Query = "SELECT * FROM link WHERE url = '" . $url . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisBlog = mysql_fetch_assoc($Database);
		$link_id = $ThisBlog['link_id'];
		}
	else
		{
		$short_url = "http://apis.how/" . strtolower(getToken(10));

		// Check 3 times if unique (funcationalize this)
		$short_url_exist = 0;
	  	$shortquery = "SELECT * FROM link WHERE url = '" . $url . "'";
		$shortqueryresult = mysql_query($shortquery) or die('Query failed: ' . mysql_error());
		if($shortqueryresult && mysql_num_rows($shortqueryresult))
			{
			$short_url_exist = 1;
			$short_url = "http://apis.how/" . strtolower(getToken(10));
			$short_url_exist = 0;
		  	$shortquery = "SELECT * FROM link WHERE url = '" . $url . "'";
			$shortqueryresult = mysql_query($shortquery) or die('Query failed: ' . mysql_error());
			if($shortqueryresult && mysql_num_rows($shortqueryresult))
				{
				$short_url_exist = 1;
				$short_url = "http://apis.how/" . strtolower(getToken(10));
				$short_url_exist = 0;
			  	$shortquery = "SELECT * FROM link WHERE url = '" . $url . "'";
				$shortqueryresult = mysql_query($shortquery) or die('Query failed: ' . mysql_error());
				if($shortqueryresult && mysql_num_rows($shortqueryresult))
					{
					$short_url_exist = 1;
					$short_url = "http://apis.how/" . strtolower(getToken(10));
					}
				else
					{
					$short_url_exist = 0;
					}
				}
			else
				{
				$short_url_exist = 0;
				}
			}
		else
			{
			$short_url_exist = 0;
			}

		$Query = "INSERT INTO link(created_date,url,short_url)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($created_date) . "',";
		$Query .= "'" . mysql_real_escape_string($url) . "',";
		$Query .= "'" . mysql_real_escape_string($short_url) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$link_id = mysql_insert_id();
		}

	$host = $_SERVER['HTTP_HOST'];
   	$link_id = prepareIdOut($link_id,$host);

	$ReturnObject['link_id'] = $link_id;
	$ReturnObject['short_url'] = $short_url;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
