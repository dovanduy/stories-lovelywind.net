<?php 
	if(isset($_GET['id']))
	{
		echo '<div id="type" val="single"></div>';
		$id=$_GET['id'];
		
		$id=$_GET['id'];
		if (!isset($_COOKIE['view'.$id]))
		{
			setcookie('view'.$id, 'true', time() + 7200);
			$q="update truyen set view = view + 1 where truyen_id='{$id}'";
			$db->query($q);	
		}
		
		$q="select * from truyen where truyen_id='{$id}' limit 1";
		$db->query($q);
		if($db->num_rows()==1)
		{
			$truyen_now=$db->get();
			$name_unicode=strtolower(unicode_convert($truyen_now['name']));
			$url_truyen=BASE_URL.$name_unicode.'/'.$id.'.html';
			$q="select * from noidung where truyen_id='{$id}' order by chap asc , part asc limit 1";
			$db->query($q);
			$frist=$db->get();
			
			$q="select c.cat_name from theloai as t join categories as c using(cat_id) where truyen_id = '{$id}'";
			$db->query($q);
			$tl = "";
			while($tmp=$db->get())
			{
				$tl.=", ".$tmp['cat_name'];
			}
			$tl=substr($tl,2);
			
		}
		else redirect_to('','','');
	}
	else
	{
		redirect_to('','','');
	}
	
	//----Init
	$num_chap = 20;
	$max_chap = $num_chap*2;
	if(isset($_GET['page']))
	{
		$page=$_GET['page'];
		$start=($page-1)*$max_chap;	
	}
	else
	{
		$page=1;
		$start=0;
	}
	echo '<title>'.$truyen_now['name'].'</title>';
?>
<body>
	<?php 
		include('../include/public-template/top-public.php');
	?>