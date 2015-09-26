<?php
$route = '/link/tags/:tag/link/';
$app->get($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();


	$Query = "SELECT l.* from tags t";
	$Query .= " JOIN link_tag_pivot ltp ON t.tag_id = ltp.tag_id";
	$Query .= " JOIN link  ON ltp.link_id = l.link_id";
	$Query .= " WHERE tag = '" . $tag . "'";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$link_id = $Database['link_id'];
		$created_date = $Database['created_date'];
		$url = $Database['url'];
		$short_url = $Database['short_url'];

		// manipulation zone

		$TagQuery = "SELECT t.tag_id, t.tag from tags t";
		$TagQuery .= " INNER JOIN link_tag_pivot ltp ON t.tag_id = ltp.tag_id";
		$TagQuery .= " WHERE ltp.link_id = " . $link_id;
		$TagQuery .= " ORDER BY t.tag DESC";
		$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

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
			//echo $thistag . "<br />";
			if($thistag=='Archive')
				{
				$archive = 1;
				}
			}

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
