<?php
$route = '/link/tags/:tag';
$app->delete($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	$Query = "SELECT t.tag_id, t.tag FROM tags WHERE tag = '" . trim(mysql_real_escape_string($tag)) . "'";

	$TagResult = mysql_query($LinkQuery) or die('Query failed: ' . mysql_error());

	if($TagResult && mysql_num_rows($TagResult))
		{
		$Tag = mysql_fetch_assoc($TagResult);
		$tag_id = $Tag['Tag_ID'];
		$tag = $Tag['Tag'];

		$DeleteQuery = "DELETE FROM link_tag_pivot WHERE tag_id = " . $tag_id;
		$DeleteResult = mysql_query($DeleteQuery) or die('Query failed: ' . mysql_error());

		$host = $_SERVER['HTTP_HOST'];
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
