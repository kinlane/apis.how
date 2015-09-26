<?php
$route = '/link/:link_id/tags/:tag/';
$app->delete($route, function ($link_id,$tag)  use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$link_id = prepareIdIn($link_id,$host);

	$ReturnObject = array();

 	$request = $app->request();
 	$param = $request->params();

	if($tag != '')
		{

		$link_id = trim(mysql_real_escape_string($link_id));
		$tag = trim(mysql_real_escape_string($tag));

		$CheckTagQuery = "SELECT Tag_ID FROM tags where tag = '" . $tag . "'";
		$CheckTagResults = mysql_query($CheckTagQuery) or die('Query failed: ' . mysql_error());
		if($CheckTagResults && mysql_num_rows($CheckTagResults))
			{
			$Tag = mysql_fetch_assoc($CheckTagResults);
			$tag_id = $Tag['Tag_ID'];

			$DeleteQuery = "DELETE FROM link_tag_pivot where tag_id = " . trim($tag_id) . " AND link_id = " . trim($link_id);
			$DeleteResult = mysql_query($DeleteQuery) or die('Query failed: ' . mysql_error());
			}

		$tag_id = prepareIdOut($tag_id,$host);

		$F = array();
		$F['tag_id'] = $tag_id;
		$F['tag'] = $tag;
		$F['link_count'] = 0;

		array_push($ReturnObject, $F);

		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
