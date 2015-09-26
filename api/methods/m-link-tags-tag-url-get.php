<?php
$route = '/url/tags/:tag/url/';
$app->get($route, function ($tag)  use ($app){

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($_REQUEST['week'])){ $week = $params['week']; } else { $week = date('W'); }
	if(isset($_REQUEST['year'])){ $year = $params['year']; } else { $year = date('Y'); }

	$Query = "SELECT b.* from tags t";
	$Query .= " JOIN url_tag_pivot utp ON t.Tag_ID = utp.Tag_ID";
	$Query .= " JOIN url b ON utp.url_id = b.url_id";
	$Query .= " WHERE Tag = '" . $tag . "'";

	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$url_id = $Database['ID'];
		$post_date = $Database['Post_Date'];
		$title = $Database['Title'];
		$author = $Database['Author'];
		$summary = $Database['Summary'];
		$body = $Database['Body'];
		$footer = $Database['Footer'];
		$status = $Database['Status'];
		$buildpage = $Database['Build_Page'];
		$githubbuild = $Database['Github_Build'];
		$twitterbuild = $Database['Twitter_Build'];
		$linkedinbuild = $Database['LinkedIn_Build'];
		$showonsite = $Database['Show_On_Site'];
		$image = $Database['Feature_Image'];

		// manipulation zone

		$TagQuery = "SELECT t.tag_id, t.tag from tags t";
		$TagQuery .= " INNER JOIN url_tag_pivot utp ON t.tag_id = utp.tag_id";
		$TagQuery .= " WHERE utp.Blog_ID = " . $url_id;
		$TagQuery .= " ORDER BY t.tag DESC";
		$TagResult = mysql_query($TagQuery) or die('Query failed: ' . mysql_error());

		$host = $_SERVER['HTTP_HOST'];
		$url_id = prepareIdOut($url_id,$host);

		$F = array();
		$F['url_id'] = $url_id;
		$F['post_date'] = $post_date;
		$F['title'] = $title;
		$F['author'] = $author;
		$F['summary'] = $summary;
		$F['body'] = $body;
		$F['footer'] = $footer;
		$F['status'] = $status;
		$F['image'] = $image;
		$F['build_page'] = $buildpage;
		$F['github_build'] = $githubbuild;
		$F['twitter_build'] = $twitterbuild;
		$F['linkedin_build'] = $linkedinbuild;
		$F['show_on_site'] = $showonsite;

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
