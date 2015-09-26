<?php
$route = '/url/:url_id/';
$app->get($route, function ($url_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$url_id = prepareIdIn($url_id,$host);

	$ReturnObject = array();

	$Query = "SELECT * FROM url WHERE url_id = " . $url_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

			$url_id = $Database['ID'];
			$pull_date = $Database['pull_date'];
			$title = $Database['title'];
			$content = $Database['content'];
			$url = $Database['url'];

			$TagQuery = "SELECT t.tag_id, t.tag from tags t";
			$TagQuery .= " INNER JOIN url_tag_pivot btp ON t.tag_id = btp.tag_id";
			$TagQuery .= " WHERE btp.Blog_ID = " . $url_id;
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

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
