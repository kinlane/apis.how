<?php		
$route = '/link/:link_id/';
$app->delete($route, function ($link_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$link_id = prepareIdIn($link_id,$host);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM link WHERE ID = " . $link_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
