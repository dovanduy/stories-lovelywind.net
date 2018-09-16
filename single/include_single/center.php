<div class="clear"></div>
		<div id="suggest">
        	<div class="sitemap">
            	<a href="<?php echo BASE_URL; ?>">Trang chủ </a>&rarr;
                <a href="<?php echo $url_truyen; ?>" title="<?php echo $truyen_now['name']; ?>"><span><?php echo quote_content($truyen_now['name'],30);?></span></a>
            </div>
            <div class="tool">
            	<div class="contain-search">
                     <input type="text" class="search-input" placeholder="Tìm kiếm ..." stt="2"/>
                     <button class="search-icon" stt="2"><img src="<?php echo BASE_URL; ?>images/search_02.png" width="60%" height="60%" /></button>
                      <div class="warning-search"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-------------------->	
        <div class="center">
        	<div class="thumbnail">
            	<div class="thumbnail-content">
                	<?php echo '<img src="'.BASE_URL.$truyen_now['thumbnail'].'" />'; ?>
                </div>
            </div>
            <div class="infomation">
            	<h1><?php echo $truyen_now['name'] ?></h1>
                <div class="p-info">
                    <div class="t-line"><span>Tác giả: </span><?php echo $truyen_now['author'] ?></div>
                    <div class="t-line"><span>Thể loại: </span><?php echo $tl; ?></div>
                    <div class="inline"><span>Trạng thái: </span><?php echo translate('status',$truyen_now['status']); ?></div>
                    <div class="inline"><span>Số chương: </span><?php echo $truyen_now['num_chap'] ?></div>
                 	<div class="inline"><span>Lượt xem: </span><?php echo $truyen_now['view'] ?></div>
                    <div class="inline"><span>Lượt thích: </span><?php echo '<span id="num-like" style="color:#414848">'.$truyen_now['like_truyen'].'</span>'; ?></div>
                    <div class="select-tool">
                        <div class="like" <?php echo 'truyen_id="'.$id.'"'; echo 'value="'.$truyen_now['like_truyen'].'"';?>>Yêu Thích</div>
                        <a href="<?php echo BASE_URL.$name_unicode.'/chuong-'.$frist['chap'].season($frist['part']).'/'.$frist['content_id'].'.html'; ?>">
                        	 <div class="read-frist">Đọc chương đầu</div>
                        </a>
                    </div>
					<div class="clear"></div>
                </div>
                <div class="p-intro">
                    <span>Giới thiệu: </span><p></p>
                    <?php 
						$intro=format_string($truyen_now['introduce']);
						if(strlen($intro)<=800)
						{
							echo '<div class="intro-show">'.$intro.'</div>';
						}
						else
						{
							$str=quote_paragraph($intro,800);
							$len=strlen($str);
							echo '<div id="intro-show">'.$str.'</div>';
							echo '<div id="intro-hide">'.substr($intro,$len).'</div>';
							echo '<div id="read-more">Đọc thêm</div><div class="clear"></div>';
						}
					?>
                    <div class="clear"></div>
                 </div>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="center" id="chap-list"
		<?php if(isset($_GET['page'])) echo 'value="true"'; else echo 'value="false"'; ?>>
        	<div class="title-center">
            	<div class="title">Danh sách chương </div>
            </div>
            <?php 
				$q="select * from noidung where truyen_id='{$id}' order by chap asc, part asc limit {$start},{$max_chap}";
				$db->query($q);
				$count=0;
				echo '<div class="list">';
				if($db->num_rows()>0)
				{
					while($tmp=$db->get())
					{
						if($count== $num_chap)
						{
							echo '</div><div class="list">';	
						}
						$count++;
						if($tmp['title']!="") $t=": ".$tmp['title'];
						else $t="";
						echo
						'
							<div class="list-div"><a href="'.BASE_URL.$name_unicode.'/chuong-'.$tmp['chap'].season($tmp['part']).'/'.$tmp['content_id'].'.html'.'">Chương '.$tmp['chap'].season($tmp['part']).$t.'</a></div>
						';
					}
				}
				echo '</div>';
			?>
            <div class="clear"></div>
            <?php 
			
			$q="select count(truyen_id) as num from noidung where truyen_id='{$id}'";
			$db->query($q);
			$tmp=$db->get();
			$total_chap=$tmp['num'];
			$unit=ceil($total_chap/$max_chap);
			$url=BASE_URL.$name_unicode.'/'.$id.'/'.'trang-';
            if($unit>1)
				{
					if($unit<=7)
					{
						echo'
							<div class="list-page">
								<ul>';
								for($i=1;$i<=$unit;$i++)
								{	
									echo '<a href="'.$url.$i.'.html"><li';
									if($page==$i) echo ' style="color:#092fbc;" ';
									echo'>'.$i.'</li></a>';
								}
						echo'	</ul>
							</div>';
					} 
					else
					{
						if($page<=3)
						{
							$pre_last=$unit-1;
							echo'
							<div class="list-page">
								<ul>';
								for($i=1;$i<=4;$i++)
								{	
									echo '<a href="'.$url.$i.'.html"><li';
									if($page==$i) echo ' style="color:#092fbc;" ';
									echo'>'.$i.'</li></a>';
								}
							echo'	<li>...</li>
									<a href="'.$url.$pre_last.'.html"><li>'.$pre_last.'</li></a>
									<a href="'.$url.$unit.'.html"><li>Cuối</li></a>
								</ul>
							</div>';
						}
						else if($page>=($unit-3))
						{
							$s4=$unit-4; $s3=$unit-3; $s2=$unit-2; $s1=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="'.$url.'1.html"><li>Đầu</li></a>
										<li>...</li>
										<a href="'.$url.$s4.'.html"><li';
										if($page==$s4) echo ' style="color:#092fbc;" ';
										echo'>'.$s4.'</li></a>
										
										<a href="'.$url.$s3.'.html"><li';
										if($page==$s3) echo ' style="color:#092fbc;" ';
										echo'>'.$s3.'</li></a>
										
										<a href="'.$url.$s2.'.html"><li';
										if($page==$s2) echo ' style="color:#092fbc;" ';
										echo'>'.$s2.'</li></a>
										
										<a href="'.$url.$s1.'.html"><li';
										if($page==$s1) echo ' style="color:#092fbc;" ';
										echo'>'.$s1.'</li></a>
										
										<a href="'.$url.$unit.'.html"><li';
										if($page==$unit) echo ' style="color:#092fbc;" ';
										echo'>Cuối</li></a>
									</ul>
								</div>';
						}
						else
						{
							$pre=$page-1; $next=$page+1; $pre_last=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="'.$url.'1.html"><li>Đầu</li></a>
										<a href="'.$url.$pre.'.html"><li>'.$pre.'</li></a>
										<a href="'.$url.$page.'.html"><li style="color:#092fbc;">'.$page.'</li></a>
										<a href="'.$url.$next.'.html"><li>'.$next.'</li></a>
										<li>...</li>
										<a href="'.$url.$pre_last.'.html"><li>'.$pre_last.'</li></a>
										<a href="'.$url.$unit.'.html"><li>Cuối</li></a>
									</ul>
								</div>';
						}
					}
				}
				?>
        </div>