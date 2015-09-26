<?php
$route = '/url/';
$app->post($route, function () use ($app){

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['pull_date'])){ $pull_date = mysql_real_escape_string($params['pull_date']); } else { $pull_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['content'])){ $content = mysql_real_escape_string($params['content']); } else { $content = ''; }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = ''; }

  $Query = "SELECT * FROM url WHERE url = '" . $url . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$ThisBlog = mysql_fetch_assoc($Database);
		$url_id = $ThisBlog['url_id'];
		}
	else
		{
		$Query = "INSERT INTO url(pull_date,title,content,url)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($pull_date) . "',";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($content) . "',";
		$Query .= "'" . mysql_real_escape_string($url) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$url_id = mysql_insert_id();
		}

	 $host = $_SERVER['HTTP_HOST'];
   $url_id = prepareIdOut($url_id,$host);

	$ReturnObject['url_id'] = $url_id;

	$app->response()->header("Content-Type", "application/json");
	echo format_json(json_encode($ReturnObject));

	});
?>
