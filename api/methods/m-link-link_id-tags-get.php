<?php
$route = '/link/:link_id/tags/';
$app->get($route, function ($link_id)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$link_id = prepareIdIn($link_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$param = $request->params();

	$Query = "SELECT t.tag_id, t.tag FROM tags t";
	$Query .= " JOIN link_tag_pivot utp ON t.tag_id = utp.tag_id";
	$Query .= " WHERE utp.link_id = " . $link_id;

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$tag_id = $Database['tag_id'];
		$tag = $Database['tag'];

		$tag_id = prepareIdOut($tag_id,$host);

		$F = array();
		$F['tag_id'] = $tag_id;
		$F['tag'] = $tag;

		array_push($ReturnObject, $F);
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
