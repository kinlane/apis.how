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

//echo $Requested_URL . "<br />";

if(strlen($Requested_URL) > 4)
  {
  $Query = "SELECT * FROM link WHERE short_url = '" . $Requested_URL . "'";
  $DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());
  while ($Database = mysql_fetch_assoc($DatabaseResult))
    {
    $link_id = $Database['link_id'];
    $created_date = $Database['created_date'];
    $url = $Database['url'];
    $short_url = $Database['short_url'];
    }
  //echo $url;

  $table_name = "track_url_" . $link_id;

  $checkLikeTableQuery = "show tables from `stack_network_kinlane_apishow` like " . chr(34) . $table_name . chr(34);
  $checkLikeTableResult = mysql_query($checkLikeTableQuery) or die('Query failed: ' . mysql_error());

  if($checkLikeTableResult && mysql_num_rows($checkLikeTableResult))
    {
    $checkLikeTableResult = mysql_fetch_assoc($checkLikeTableResult);
    }
  else
    {
    $CreateTableQuery = "CREATE TABLE  `stack_network_kinlane_apishow`.`" . $table_name . "` (";
    $CreateTableQuery .= "`track_id` int(10) unsigned NOT NULL AUTO_INCREMENT,";
    $CreateTableQuery .= "`click_date` datetime NOT NULL,";
    $CreateTableQuery .= "PRIMARY KEY (`track_id`)";
    $CreateTableQuery .= ") ENGINE=InnoDB DEFAULT CHARSET=latin1;  ";
    //echo "<br />" . $CreateTableQuery . "<br />";
    mysql_query($CreateTableQuery) or die('Query failed: ' . mysql_error());

    }

  $click_date = date('Y-m-d H:i:s');
  $query = "INSERT INTO " . $table_name . "(click_date) VALUES('" . mysql_real_escape_string($click_date) . "')";
  //echo $query . "<br />";
  mysql_query($query) or die('Query failed: ' . mysql_error());

  header('Location: ' . $url);
  }
else
  {
  echo '<br /><br /><br /><p align="center">apis.how</p>';
  }
?>
