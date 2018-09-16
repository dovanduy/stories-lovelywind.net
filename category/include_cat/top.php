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
	<div class="switch">
    	<div class="switch-center">
        	<div class="switch-control">&uarr;</div>
        </div>
    </div>
	<div id="global">
    
		<div id="top">
        <!------------------------->
        	<div id="toggle-menu">
                <div class="icon-menu-center">
                     <i class="fas fa-bars"></i>
                </div>
				<!----->
                 <div class="fixed-menu">
                    <div class="taskbar">
                        <div class="close">
                            <img src="<?php echo BASE_URL; ?>images/close-orange.png" width="100%" height="100%">
                        </div>
                        <div class="home">
                            <a href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-home"></i>
                            </a>
                        </div>
                    </div>
                    <div class="list-cat">
                        <div class="full-relative">
                        <!-------------------->
                            
								<?php 
									$q='select * from categories';
									$db->query($q);
									$i=0; $close=true;
									while($result=$db->get())
									{
										if($i==0)
										{
											echo'<ul>';
											$close=false;
										}
										echo'<a href="'.BASE_URL.'the-loai/'
										.strtolower(unicode_convert($result['cat_name'])).'/">
										<li>'.$result['cat_name'].'</li></a>';
										$i++;
										if($i==3)
										{
											echo'<div class="clear"></div></ul>';
											$i=0;
											$close=true;
										}
									}
									if($close==false)
									{
										echo'<div class="clear"></div></ul>';
									}
                                ?>
                            <div class="clear"></div>
                            <div class="flag-mini"></div>
                            <!-------------------->
                            
                                <ul>
                                    <a href="<?php echo BASE_URL; ?>quoc-gia/trung-quoc/"><li>Trung Quốc</li></a>
                                    <a href="<?php echo BASE_URL; ?>quoc-gia/nhat-ban/"><li>Nhật Bản</li></a>
                                    <a href="<?php echo BASE_URL; ?>quoc-gia/viet-nam/"><li>Việt Nam</li></a>
                                <div class="clear"></div>
                                </ul>
                                <ul>
                                     <a href="<?php echo BASE_URL; ?>quoc-gia/phuong-tay/"><li>Phương Tây</li></a>
                                     <div class="clear"></div>
                                </ul>
                            <div class="clear"></div>
                            <div class="flag-mini"></div>
                            <!-------------------->
                            
                                <ul>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/xem-nhieu/"><li>Xem nhiều</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/hoan-thanh/"><li>Hoàn thành</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/yeu-thich/"><li>Yêu thích</li></a>
                                    <div class="clear"></div>
                                </ul>
                                <ul>
                                     <a href="<?php echo BASE_URL; ?>danh-sach/truyen-moi/"><li>Mới cập nhật</li></a>
                                    <div class="clear"></div>
                                </ul>
                            <div class="clear"></div>
                            <div class="flag-mini"></div>
                        <!-------------------->
                        </div>
                    </div>
                     
                    <div class="contain-search">
                     <input type="text" class="search-input" placeholder="Tìm kiếm ..." stt="0" />
                     <button class="search-icon" stt="0"><img src="<?php echo BASE_URL; ?>images/search_01.png" width="60%" height="60%" /></button>
                     <div class="warning-search"></div>
                	</div>
                    <div class="contain-filter-cat">
                    	<p>Hãy ấn vào những mục bạn thích rồi chọn "Lọc truyện" để lọc truyện nâng cao</p>
                        <div class="filter">
                            <?php 
                                $q='select * from categories limit 12';
                                $db->query($q);
                                while($cat=$db->get())
                                {
                                    echo'<div class="filter-tag" value="'.$cat['cat_id'].'"
                                    bg="rgba(255,255,255,0.9)" color="#4aaaba">'
                                    .$cat['cat_name'].'</div>';
                                }
                            ?>
                            <div class="clear"></div>
                            <div class="filter-active">
                                <div class="warning-filter"></div>
                                <div class="filter-icon" stt="0">
                                    Lọc truyện
                                </div>
                            </div>
                        <div class="clear"></div>
                        </div>
                    </div>
                 </div>    
                <!----->             
                <div class="logo">
                    <a><img src="<?php echo BASE_URL; ?>images/LovelyNew2.png" height="100%"/></a>
                </div>
                
                <div class="clear"></div>
            </div>
            <!--------------------------->
       	 	<div id="contain-main-menu">
           		<ul id="main-menu">
                	<li><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                    <li style="background:#54c6f0">
                    	Thể loại
                    	<div class="main-menu-hide hide-1">
                        <?php 
							$q='select * from categories';
							$db->query($q);
							$i=0; $close=true;
							while($result=$db->get())
							{
								if($i==0)
								{
									echo'<ul>';
									$close=false;
								}
								echo'<a href="'.BASE_URL.'the-loai/'
								.strtolower(unicode_convert($result['cat_name'])).'/">
								<li>'.$result['cat_name'].'</li></a>';
								$i++;
								if($i==3)
								{
									echo'<div class="clear"></div></ul>';
									$i=0;
									$close=true;
								}
							}
							if($close==false)
							{
								echo'<div class="clear"></div></ul>';
							}
						?>
                        </div>
                    </li>
                    <li style="background:#4dc1eb">
                    	Quốc gia
                    	<div class="main-menu-hide hide-2">
                        	<ul>
                                <a href="<?php echo BASE_URL; ?>quoc-gia/trung-quoc/"><li>Trung Quốc</li></a>
                                <a href="<?php echo BASE_URL; ?>quoc-gia/nhat-ban/"><li>Nhật Bản</li></a>
                                <a href="<?php echo BASE_URL; ?>quoc-gia/viet-nam/"><li>Việt Nam</li></a>
                            <div class="clear"></div>
                            </ul>
                            <ul>
                                 <a href="<?php echo BASE_URL; ?>quoc-gia/phuong-tay/"><li>Phương Tây</li></a>
                                 <div class="clear"></div>
                            </ul>
                        </div>
                    </li>
                    <li style="background:#47bbe5">
                    	Danh sách
                    	<div class="main-menu-hide hide-3">
                        	<ul>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/xem-nhieu/"><li>Top xem nhiều</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/hoan-thanh/"><li>Đã hoàn thành</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/yeu-thich/"><li>Top yêu thích</li></a>
                                    <div class="clear"></div>
                                </ul>
                                <ul>
                                     <a href="<?php echo BASE_URL; ?>danh-sach/truyen-moi/"><li>Mới cập nhật</li></a>
                                    <div class="clear"></div>
                                </ul>
                        </div>
                    </li>
                    <div class="clear"></div>
            	</ul>
                <div id="tool-hide">
                	<img src="<?php echo BASE_URL; ?>images/search_01.png"/>
                    <div id="tool-show">
                    	<div class="contain-search">
                        	 <input type="text" class="search-input" placeholder="Tìm kiếm ..." stt="1"/>
                             <button class="search-icon" stt="1"><img src="<?php echo BASE_URL; ?>images/search_01.png" width="60%" height="60%" /></button>
                              <div class="warning-search"></div>
                        </div>
                        
                        <div class="contain-filter-cat">
                        	<div class="filter">
								<?php 
                                    $q='select * from categories limit 12';
                                    $db->query($q);
                                    while($cat=$db->get())
                                    {
                                        echo'<div class="filter-tag" value="'.$cat['cat_id'].'"
                                        bg="rgba(255,255,255,0.9)" color="#4aaaba">'
                                        .$cat['cat_name'].'</div>';
                                    }
                                ?>
                                <p>Chọn những mục bạn thích để lọc truyện</p>  
                                <div class="filter-active">
                                    <div class="warning-filter"></div>
                                    <div class="filter-icon" stt="1">
                                        Lọc truyện
                                    </div>
                                </div>
                            <div class="clear"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    
                </div>
          	</div>
            <!--------------------------->
            <div id="contain-main-logo">
                <div id="main-logo">
                    <img src="<?php echo BASE_URL; ?>images/LovelyNew2.png" height="100%"/>
                </div>
            </div>
        <!------------------------->
        </div>  