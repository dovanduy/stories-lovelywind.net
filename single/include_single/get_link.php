<?php 
	include('../../include/function.php');
	$db= new database;
	$db->connect();
	if(isset($_POST['id']) && isset($_POST['chap']))
	{
		$id=$_POST['id'];
		$chap=$_POST['chap']-1;
		$q="select * from noidung join truyen using(truyen_id) where truyen_id='{$id}' order by chap asc , part asc limit {$chap},1";
		$db->query($q);
		if($db->num_rows()!=0)
		$tmp=$db->get();
		$url=strtolower(unicode_convert($tmp['name'])).'/'.'chuong-'.$tmp['chap'].season($tmp['part']).'/'.$tmp['content_id'].'.html';
		echo $url;
	}
?>