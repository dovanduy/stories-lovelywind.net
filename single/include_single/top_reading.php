<?php 
	if(isset($_GET['id']))
	{ 	
		echo '<div id="type" val="reading"></div>';
		$id=$_GET['id'];
		$q="select * from noidung join truyen using(truyen_id) where content_id='{$id}' limit 1";
		$db->query($q);
		if($db->num_rows()==1)
		{
			$now=$db->get();
			$tmp='chuong-'.$now['chap'].season($now['part']);
			
			$url_truyen=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$now['truyen_id'].'.html';
			$url_content=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$id.'.html';
			
			//---Lấy 2 chương liền kề
			$check_id=$now['truyen_id'];
			$check_chap=$now['chap'];
			$check_part=$now['part'];
			$q="select * from noidung where truyen_id='{$check_id}' and chap='{$check_chap}'";
			$db->query($q);
			if($db->num_rows()>1)//-- Chương có nhiều phần
			{
				while($tmp=$db->get())
				{
					$part_max=$tmp['part'];
				}//lấy phần lớn nhất
				
				if($check_part>=2)//--đang không phải phần đầu
				{
					$part_pre=$check_part-1;
					$chap_pre=$check_chap;
					if($check_part<$part_max)//--đang không phải phần cuối
					{
						$part_next=$check_part+1;
						$chap_next=$check_chap;
					}
					else//--đang phần cuối
					{
						$chap_next=$check_chap+1;
						$part_next=0;//--tạm cho chương tới không có phần
					}
				}
				else//--đang là phần đầu
				{
					$part_next=$check_part+1;
					$chap_next=$check_chap;
					$part_pre=0;
					$chap_pre=$check_chap-1;
				}
			} else //--chương chỉ một phần
			{
				$chap_next=$check_chap+1;
				$chap_pre=$check_chap-1;
				$part_pre=0;
				$part_next=0;
			}
			
			if($part_next==0)//-chuyển qua chương kế
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_next}' order by part asc limit 1";
				$db->query($q);
				$next=$db->get();
			}
			else //-chuyển qua phần kế
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_next}' and part='{$part_next}'";
				$db->query($q);
				$next=$db->get();
			}
			
			if($part_pre==0)//-chuyển qua chương trước
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_pre}' order by part desc limit 1";
				$db->query($q);
				$pre=$db->get();
			}
			else //-chuyển qua phần trước
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_pre}' and part='{$part_pre}'";
				$db->query($q);
				$pre=$db->get();
			}
			
			if($next['part']!=0)
			{
				$tmp='-'.$next['part'];
				$tmp='chuong-'.$next['chap'].$tmp;
			} else 
			{
				$tmp='chuong-'.$next['chap'];
			}
			$url_next=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$next['content_id'].'.html';
			
			if($pre['part']!=0)
			{
				$tmp='-'.$pre['part'];
				$tmp='chuong-'.$pre['chap'].$tmp;
			} else 
			{
				$tmp='chuong-'.$pre['chap'];
			}
			$url_pre=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$pre['content_id'].'.html';
			
			$q="select count(truyen_id) as num from noidung where truyen_id='{$check_id}'";
			$db->query($q);
			$tmp=$db->get();
			$num_content=$tmp['num'];	
			
			$q="select count(truyen_id) as num from noidung where truyen_id='{$check_id}' and ";
			$q.=" (chap < '{$check_chap}' or (chap = '{$check_chap}' and part<'{$check_part}' ))";
			$db->query($q);
			$tmp=$db->get();
			$now_location=$tmp['num']+1;	
		}
		else redirect_to('','','');
	}
	else
	{
		redirect_to('','','');
	}
?>
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
										echo'<a href="<?php echo BASE_URL; ?>the-loai/'
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
                                    <a href="<?php echo BASE_URL; ?>danh-sach/xem-nhieu/"><li>Top xem nhiều</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/hoan-thanh/"><li>Đã hoàn thành</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/yeu-thich/"><li>Top yêu thích</li></a>
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
								echo'<a href="<?php echo BASE_URL; ?>the-loai/'
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
                                    <a href="<?php echo BASE_URL; ?>danh-sach/xem-nhieu/"><li>Xem nhiều</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/hoan-thanh/"><li>Hoàn thành</li></a>
                                    <a href="<?php echo BASE_URL; ?>danh-sach/yeu-thich/"><li>Yêu thích</li></a>
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
                  		<div class="clear"></div>
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