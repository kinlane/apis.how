<?php
$route = '/link/:link_id/';
$app->put($route, function ($link_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$link_id = prepareIdIn($link_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['created_date'])){ $created_date = mysql_real_escape_string($params['created_date']); } else { $created_date = date('Y-m-d H:i:s'); }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = 'No Title'; }
	if(isset($params['short_url'])){ $short_url = mysql_real_escape_string($params['short_url']); } else { $short_url = ''; }

  	$Query = "SELECT * FROM link WHERE ID = " . $link_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{

		$query = "UPDATE link SET";

		if($url!='') { $url .= " url = '" . $url . "'"; }
		if($short_url!='') { $query .= ", short_url = '" . $short_url . "'"; }

		$query .= " WHERE link_id = " . $link_id;

		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$TagQuery = "SELECT t.tag_id, t.tag from tags t";
	$TagQuery .= " INNER JOIN link_tag_pivot btp ON t.tag_id = btp.tag_id";
	$TagQuery .= " WHERE btp.Blog_ID = " . $link_id;
	$TagQuery .= " ORDER BY t.tag DESC";
	$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

	$link_id = prepareIdOut($link_id,$host);

	$F = array();
	$F['link_id'] = $link_id;
	$F['created_date'] = $created_date;
	$F['url'] = $url;
	$F['short_url'] = $short_url;

	$F['tags'] = array();

	while ($Tag = mysql_fetch_assoc($TagResult))
		{
		$thistag = $Tag['tag'];

		$T = array();
		$T = $thistag;
		array_push($F['tags'], $T);
		}

	array_push($ReturnObject, $F);

	$app->response()->header("Content-Type", "application/json");
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
