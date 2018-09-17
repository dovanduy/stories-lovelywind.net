<div class="clear"></div>
		<div id="suggest">
        	<div class="sitemap">
            	<a href="<?php echo BASE_URL; ?>">Trang chủ</a> &rarr; 
                <a href="<?php echo getCurrentPageURL(); ?>"><span><?php echo $link ?></span></a>
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
        <!--div class="fil-icon">Lọc truyện &rarr;</div>
        <div class="clear"></div>
        <!-------------------->
        <div class="fil-desk">
        <div class="contain-filter-cat">
        	<?php 
				$q='select * from categories limit 12';
				$db->query($q);
				while($cat=$db->get())
				{
					echo'<div class="filter-tag" value="'.$cat['cat_id'].'"
					bg="rgba(255,255,255,0.7)" color="#4aaaba">'
					.$cat['cat_name'].'</div>';
				}
			?>
            <div class="filter-active">
            
            	<div class="warning-filter"></div>
            	<p>Chọn những mục bạn thích để </p>
                
                <div class="filter-icon" stt="2">
                    Lọc truyện
                </div>
            </div>
        </div>
        <div class="clear"></div>
        </div>
        <!-------------------->	
        <div class="center">
        	<div class="flag-center">
            	<div class="flag"><h1><?php echo $flag; ?></h1></div>
                <div class="contain-filter">
                	<span>Tình trạng: </span>
                	<select id='status'>
                    	<option value="2" <?php if($status==2) echo 'selected="selected"'; ?>>mọi trạng thái</option>
                        <option value="1" <?php if($status==1) echo 'selected="selected"'; ?>>hoàn thành</option>
                        <option value="0" <?php if($status==0) echo 'selected="selected"'; ?>>đang cập nhật</option>
                    </select>
                </div>
            </div>
        	<?php 
			
			if(isset($keyword))
			{
				if($status!=2)
				{
					
					$q="select count(truyen_id) as num from truyen where (name like '%{$keyword}%' or author like '%{$keyword}%'";	
					$q.=" or name_unicode like '%{$keyword}%' or author_unicode like '%{$keyword}%') and status='{$status}'";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where (name like '%{$keyword}%' or author like '%{$keyword}%'";	
					$q.=" or name_unicode like '%{$keyword}%' or author_unicode like '%{$keyword}%') and status='{$status}'";
					$q.=" limit {$start},{$num_post}"; 
				} else
				{
					
					$q="select count(truyen_id) as num from truyen where (name like '%{$keyword}%' or author like '%{$keyword}%'";	
					$q.=" or name_unicode like '%{$keyword}%' or author_unicode like '%{$keyword}%')";
					
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where (name like '%{$keyword}%' or author like '%{$keyword}%'";	
					$q.=" or name_unicode like '%{$keyword}%' or author_unicode like '%{$keyword}%')";
					$q.=" limit {$start},{$num_post}"; 
				}
			}
			else if(isset($cat_id))
			{
				if($status!=2)
				{
					$q="select count(truyen_id) as num from truyen join theloai";
					$q.=" using(truyen_id) where cat_id='{$cat_id}' and status='{$status}'";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen join theloai using(truyen_id)";
					$q.=" where cat_id='{$cat_id}' and status='{$status}' limit {$start},{$num_post}";
				} else
				{
					$q="select count(truyen_id) as num from truyen join theloai";
					$q.=" using(truyen_id) where cat_id='{$cat_id}'";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);

					$q="select * from truyen join theloai using(truyen_id) where cat_id='{$cat_id}' limit {$start},{$num_post}";
				}
			}
			else if(isset($country))
			{
				if($status!=2)
				{
					$q="select count(truyen_id) as num from truyen where country = '{$country}' and status='{$status}'";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where country = '{$country}' and status='{$status}' limit {$start},{$num_post}";
				} else
				{
					$q="select count(truyen_id) as num from truyen where country = '{$country}'";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where country = '{$country}' limit {$start},{$num_post}";
				}
			}
			else if(isset($list))
			{
				if($list==0)
				{
					if($status!=2)
					{
						$q="select count(truyen_id) as num from truyen where status='{$status}'";
						$db->query($q);
						$count=$db->get();
						$num_truyen=$count['num'];
						$unit=ceil($num_truyen/$num_post);
						if($page>$unit) $page=1;
						$start=($page-1)*($num_post);
						$q="select * from truyen where status='{$status}' ";
						$q.=" order by like_truyen desc limit {$start},{$num_post}";
					} else
					{
						$q="select count(truyen_id) as num from truyen";
						$db->query($q);
						$count=$db->get();
						$num_truyen=$count['num'];
						$unit=ceil($num_truyen/$num_post);
						if($page>$unit) $page=1;
						$start=($page-1)*($num_post);
						$q="select * from truyen order by like_truyen desc limit {$start},{$num_post}";
					}
				}
				else if($list==1)
				{
					if($status!=2)
					{
						$q="select count(truyen_id) as num from truyen where status='{$status}'";
						$db->query($q);
						$count=$db->get();
						$num_truyen=$count['num'];
						$unit=ceil($num_truyen/$num_post);
						if($page>$unit) $page=1;
						$start=($page-1)*($num_post);
						$q="select * from truyen where status='{$status}' ";
						$q.=" order by view desc limit {$start},{$num_post}";
					} else
					{
						$q="select count(truyen_id) as num from truyen";
						$db->query($q);
						$count=$db->get();
						$num_truyen=$count['num'];
						$unit=ceil($num_truyen/$num_post);
						if($page>$unit) $page=1;
						$start=($page-1)*($num_post);
						$q="select * from truyen order by view desc limit {$start},{$num_post}";
					}
				}
				else if($list==2)
				{
					$q="select  count(truyen_id) as num from truyen where status=1";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where status=1 order by view desc limit {$start},{$num_post}";
				}
				else 
				{
					$q="select  count(truyen_id) as num from truyen where status=0";
					$db->query($q);
					$count=$db->get();
					$num_truyen=$count['num'];
					$unit=ceil($num_truyen/$num_post);
					if($page>$unit) $page=1;
					$start=($page-1)*($num_post);
					$q="select * from truyen where status=0 order by date_change desc limit {$start},{$num_post}";
				}
			}
			//-------------------------------
			if($_GET['action']=='filter')
			{
				$max=0;
				if(isset($filter))
				{
					$start=($page-1)*($num_post);
					$check_start=0; $check_finish=0;
					$check_fil_cat=false;
					foreach($filter as $tmp)
					{
						 
						if($check_finish<$num_post && $check_start>=$start)
						{
							if($status!=2)
							{
								$q="select * from truyen where truyen_id='{$tmp}' and status='{$status}'";
							} else
							{
								$q="select * from truyen where truyen_id='{$tmp}'";
							}
							$db->query($q);
							if($db->num_rows()>0)
							{
								$check_finish++;
								$result[$check_finish]=$db->get();	
							}
						}
						$check_start++;
					}
					$max=$check_finish;	
					$unit=ceil($check_start/$num_post);
				}
				else
				{
					$unit=0;
				}
				
			}
			else
			{
				
				$db->query($q);
				$max=0;
				if($db->num_rows()>0)
				{
					$max=$db->num_rows();
					for($i=1;$i<=$max;$i++)
					{
						$result[$i]=$db->get();
					}
				}
			}
			
			if($max<=0) echo '<div style="text-align:center; color:#4aaaba;">Không có kết quả truyện phù hợp</div>';
			for($i=1;$i<=$max;$i++)
			{
				echo
				'
				<div class="contain-item">
            	<div class="item-content">
                <div class="full-relative">
					<a style="cursor:pointer;" href="'.BASE_URL.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><img src="'.BASE_URL.$result[$i]['thumbnail'].'"/></a>
                
                
                    <div class="contain-info">
                       <h3><a href="'.BASE_URL.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'" title="'.$result[$i]['name'].translate('season',$result[$i]['season']).'">'
						.quote_content($result[$i]['name'],30).translate('season',$result[$i]['season']).
						'</a></h3> 
                        <div class="detail-info">
                        	<span>Tác giả: </span>'.$result[$i]['author'].'<br /><br />
                            <span>Thể loại: </span>';
								$truyen_id=$result[$i]['truyen_id'];
								$type="select c.cat_name from ";
								$type.=" categories as c join theloai using(cat_id)";
								$type.=" where truyen_id='{$truyen_id}' limit 3";
								$db->query($type);
								$j=1;
								while($get_type=$db->get())
								{
									if($j==1)
									{
										echo $get_type['cat_name'];
									}
									else
									{
										echo ", ".$get_type['cat_name'];
									}
									$j++;
								}
							echo'<br /><br />
                            <span>Lượt xem: </span>'.$result[$i]['view'].'
                        	<a style="cursor:pointer;" href="'.BASE_URL.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><div class="item-active">Đọc truyện</div></a>
                        </div>
                    </div>';
					if($i<=9 && $page==1)
					{
                    	echo'<div class="rank"><span>'.$i.'</span></div>
						<div class="rank-frame"></div>';
					}
                echo'  
                </div>
                </div>
          	    </div>
				';
			}
			
			echo'<div class="clear"></div>';
			if(isset($_GET['status']))
			{
				$url.=$_GET['status'].'/';
			}
			if(solve_get_string($_GET['action'])=='filter' || solve_get_string($_GET['action'])=='search')
			{
				$url.='page=';
			}
			else
			{
				$url.='trang-';
			}
			if($unit>1)
			{
					if($unit<=7)
					{
						echo'
							<div class="list-page">
								<ul>';
								for($i=1;$i<=$unit;$i++)
								{	
									echo '<a href="'.$url.$i.'"><li';
									if($page==$i) echo ' style="background:#4aaaba;" ';
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
									echo '<a href="'.$url.$i.'"><li';
									if($page==$i) echo ' style="background:#4aaaba;" ';
									echo'>'.$i.'</li></a>';
								}
							echo'	<li>...</li>
									<a href="'.$url.$pre_last.'"><li>'.$pre_last.'</li></a>
									<a href="'.$url.$unit.'"><li>Cuối</li></a>
								</ul>
							</div>';
						}
						else if($page>=($unit-3))
						{
							$s4=$unit-4; $s3=$unit-3; $s2=$unit-2; $s1=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="'.$url.'1"><li>Đầu</li></a>
										<li>...</li>
										<a href="'.$url.$s4.'"><li';
										if($page==$s4) echo ' style="background:#4aaaba;" ';
										echo'>'.$s4.'</li></a>
										
										<a href="'.$url.$s3.'"><li';
										if($page==$s3) echo ' style="background:#4aaaba;" ';
										echo'>'.$s3.'</li></a>
										
										<a href="'.$url.$s2.'"><li';
										if($page==$s2) echo ' style="background:#4aaaba;" ';
										echo'>'.$s2.'</li></a>
										
										<a href="'.$url.$s1.'"><li';
										if($page==$s1) echo ' style="background:#4aaaba;" ';
										echo'>'.$s1.'</li></a>
										
										<a href="'.$url.$unit.'"><li';
										if($page==$unit) echo ' style="background:#4aaaba;" ';
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
										<a href="'.$url.'1"><li>Đầu</li></a>
										<a href="'.$url.$pre.'"><li>'.$pre.'</li></a>
										<a href="'.$url.$page.'"><li style="background:#4aaaba;">'.$page.'</li></a>
										<a href="'.$url.$next.'"><li>'.$next.'</li></a>
										<li>...</li>
										<a href="'.$url.$pre_last.'"><li>'.$pre_last.'</li></a>
										<a href="'.$url.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
						}
					}
			}
			
			?>    
        </div>