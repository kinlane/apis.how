<?php
date_default_timezone_set('America/Los_Angeles');
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../libraries/common.php');
require_once('config.php');
require_once('../Slim/Slim.php');
require_once('../3scale/ThreeScaleClient.php');
require_once('../client/GitHubClient.php');
require_once('../parse/index.php');
require_once('/var/www/html/system/class-amazon-s3.php');

$url = "";
$Requested_URL = "http://apis.how" . $_SERVER['REQUEST_URI'];

echo $Requested_URL . "<br />";

$Query = "SELECT * FROM link WHERE short_url = '" . $Requested_URL . "'";
$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());
while ($Database = mysql_fetch_assoc($DatabaseResult))
  {
  $link_id = $Database['link_id'];
  $created_date = $Database['created_date'];
  $url = $Database['url'];
  $short_url = $Database['short_url'];
  }
echo $url;
?>
