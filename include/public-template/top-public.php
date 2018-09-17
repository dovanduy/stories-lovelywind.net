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