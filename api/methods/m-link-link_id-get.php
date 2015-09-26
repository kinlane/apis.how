<?php
$route = '/link/:link_id/';
$app->get($route, function ($link_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$link_id = prepareIdIn($link_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM link WHERE link_id = " . $link_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

			$link_id = $Database['ID'];
			$created_date = $Database['created_date'];
			$url = $Database['url'];
			$short_url = $Database['short_url'];

			$TagQuery = "SELECT t.tag_id, t.tag from tags t";
			$TagQuery .= " INNER JOIN link_tag_pivot btp ON t.tag_id = btp.tag_id";
			$TagQuery .= " WHERE btp.Blog_ID = " . $link_id;
			$TagQuery .= " ORDER BY t.tag DESC";
			$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

			// manipulation zone
			$host = $_SERVER['HTTP_HOST'];
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

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
