<?php
$route = '/url/:url_id/';
$app->put($route, function ($url_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$url_id = prepareIdIn($url_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['pull_date'])){ $pull_date = mysql_real_escape_string($params['pull_date']); } else { $pull_date = date('Y-m-d H:i:s'); }
	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = 'No Title'; }
	if(isset($params['content'])){ $content = mysql_real_escape_string($params['content']); } else { $content = ''; }
	if(isset($params['url'])){ $url = mysql_real_escape_string($params['url']); } else { $url = ''; }

  	$Query = "SELECT * FROM url WHERE ID = " . $url_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{

		$query = "UPDATE url SET";

		if($title!='') { $title .= " title = '" . $title . "'"; }
		if($body!='') { $query .= ", content = '" . $content . "'"; }
		if($author!='') { $query .= ", url = '" . $url . "'"; }

		$query .= " WHERE url_id = " . $url_id;

		echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

	$TagQuery = "SELECT t.tag_id, t.tag from tags t";
	$TagQuery .= " INNER JOIN url_tag_pivot btp ON t.tag_id = btp.tag_id";
	$TagQuery .= " WHERE btp.Blog_ID = " . $url_id;
	$TagQuery .= " ORDER BY t.tag DESC";
	$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

	$url_id = prepareIdOut($url_id,$host);

	$F = array();
	$F['url_id'] = $url_id;
	$F['pull_date'] = $pull_date;
	$F['title'] = $title;
	$F['content'] = $content;
	$F['url'] = $url;

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
