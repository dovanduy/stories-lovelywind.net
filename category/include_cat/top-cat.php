<?php 
	ob_start();
	$check=getCurrentPageURL();
	if(substr($check,strlen($check)-1,1)!='/')
	{
		$check.='/';
		header("Location: $check");
	}
	$num_post=20;
	$url=BASE_URL;
	if(isset($_GET['status']))
	{
		$status=solve_get_string($_GET['status']);
		echo '<div id="old_status" value="true"></div>'; //đã được sx
		if(decode('status',$status)!=-1)
		{
			$status=decode('status',$status);
		} else redirect_to('','','');
	} 
	else 
	{
		echo '<div id="old_status" value="false"></div>'; //chưa được sx
		$status=2;
	}
	if(isset($_GET['page']))
	{
		$page=solve_get_string($_GET['page']);
		echo '<div id="send_page value="true" page="'.$page.'"></div>';//đã định hướng tới trang số $page
	} 
	else 
	{
		$page=1;
		echo '<div id="send_page value="false"></div>'; //chưa định hướng
	}
	if(isset($_GET['action']))
	{
		if(solve_get_string($_GET['action'])=='search')
		{
			if(isset($_GET['keyword']))
			{
				$keyword=$_GET['keyword'];
				$flag="Kết quả tìm: ".$keyword;
				$url.='tim-kiem/?keyword='.$keyword.'&action=search&';
				$link="tìm kiếm";
				echo '<div id="location" value="find" type="search"></div>'; //đang là trang tìm kiếm
				echo '<div id="keyword" value="'.$keyword.'"></div>'; //trả lại keyword
			}
			else redirect_to('','','');
		} 
		else if(solve_get_string($_GET['action'])=='filter')
		{
			if(isset($_GET['num_cat']))
			{
				$num_cat=solve_get_string($_GET['num_cat']);
				if($num_cat>0)
				{
					$format=true;
					$json_cat="";
					for($i=0;$i<$num_cat;$i++)
					{
						$tmp='cat_'.($i+1);
						if(isset($_GET[$tmp]))
						{
							$list_cat_id[$i]=$_GET[$tmp];
							$json_cat.='&'.$tmp.'='.$_GET[$tmp];
						} 
						else 
						{
							$format=false;
							break;
						}
					}
					if($format==true)
					{	
						$filter=filter($list_cat_id,$num_cat);
						$json_cat.='&num_cat='.$num_cat;
						$url.='loc-truyen/?action=filter'.$json_cat.'&';
						$link='lọc nâng cao';
						$flag='Kết quả lọc truyện';
						echo '<div id="location" value="find" type="filter"></div>'; //đang là trang tìm kiếm
						echo '<div id="list_cat" value="'.$json_cat.'"></div>'; //trả về json
					}
					else redirect_to('admin/manage_truyen.php','','');
				}
				else redirect_to('admin/manage_truyen.php','','');
			}
			else redirect_to('','','');
		} 
		else if($_GET['action']=='type')
		{
			if(isset($_GET['id']))
			{
				$cat_id=0;
				$name_get=solve_get_string($_GET['id']);
				$url.='the-loai/'.$name_get.'/';
				$q='select * from categories';
				$db->query($q);
				while($tmp=$db->get())
				{
					if($name_get==strtolower(unicode_convert($tmp['cat_name'])))
					{
						$cat_id=$tmp['cat_id'];
						$flag='Truyện '.$tmp['cat_name'];
						$link=$tmp['cat_name'];
						echo '<div id="location" value="list" type="type"></div>'; //đang là trang liệt kê
						echo'<div id="link_rule" value="the-loai/'.$name_get.'"></div>';
					}
				}
				if($cat_id==0) redirect_to('','','');
			}
			else redirect_to('','','');
		} 
		else if($_GET['action']=='country')
		{
			if(isset($_GET['id']))
			{
				if(encode('country',$_GET['id']))
				{
					$link=encode('country',$_GET['id']);//tên
					$flag='Truyện '.$link;
					$country=decode('country',$_GET['id']);//mã
					$url.='quoc-gia/'.$_GET['id'].'/';
					echo'<div id="link_rule" value="quoc-gia/'.$_GET['id'].'"></div>';
					echo'<div id="location" value="list" type="country"></div>'; //đang là trang liệt kê
				}
				else redirect_to('','','');
			}
			else redirect_to('','','');
		}
		else if($_GET['action']=='list')
		{
			if(isset($_GET['id']))
			{
				if(encode('list',$_GET['id']))
				{
					$link=encode('list',$_GET['id']);//tên
					$flag='Truyện '.$link;
					$list=decode('list',$_GET['id']);//mã
					if($list==2 || $list==3)
					{
						echo '<div id="special" value="none"></div>';
					}
					$url.='danh-sach/'.$_GET['id'].'/';
					echo'<div id="link_rule" value="danh-sach/'.$_GET['id'].'"></div>';
					echo '<div id="location" value="list" type="list"></div>'; //đang là trang liệt kê
				}
				else redirect_to('','','');
			}
			else redirect_to('','','');
		}
		else redirect_to('','','');
	} else redirect_to('','','');
	ob_flush();
?>
<title><?php echo ucwords($flag); ?></title>
<body>
	<?php 
		require('../include/public-template/top-public.php');
	?>