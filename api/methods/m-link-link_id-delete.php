<?php		
$route = '/url/:url_id/';
$app->delete($route, function ($url_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$url_id = prepareIdIn($url_id,$host);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM url WHERE ID = " . $url_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
