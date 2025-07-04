<?php 

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "pif_center";

$connect = mysqli_connect($dbServername , $dbUsername , $dbPassword , $dbName) or die ('Failed to connect to db...');

?>