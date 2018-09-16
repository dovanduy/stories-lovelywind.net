<?php 
	session_start(); 
	$db = new database;
	$db->connect();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="style.css" type="text/css" rel="stylesheet" />
<link href="../include/css/all.min.css" type="text/css" rel="stylesheet" />
<script src="jquery.js"></script>
<title>Admin page</title>
</head>