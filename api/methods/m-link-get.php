<?php
$route = '/url/';
$app->get($route, function ()  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
	if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
	if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 250;}
	if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
	if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'DESC';}

	// Pull from MySQL
	if($query!='')
		{
		$Query = "SELECT * FROM url WHERE Title LIKE '%" . $query . "%' OR Content LIKE '%" . $query . "%'";
		}
	else
		{
		$Query = "SELECT * FROM url";
		}
	$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
	//echo $Query . "<br />";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$url_id = $Database['url_id'];
		$pull_date = $Database['pull_date'];
		$title = $Database['title'];
		$content = $Database['content'];
		$url = $Database['url'];

		$TagQuery = "SELECT t.tag_id, t.tag from tags t";
		$TagQuery .= " INNER JOIN url_tag_pivot utp ON t.tag_id = utp.tag_id";
		$TagQuery .= " WHERE utp.url_id = " . $url_id;
		$TagQuery .= " ORDER BY t.tag DESC";
		$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

		// manipulation zone
		$host = $_SERVER['HTTP_HOST'];
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
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
