 <div class="clear"></div>
        <div id="suggest">
        	
        	<div id="contain-emergent">
            	<div id="emergent">
                <?php
                	
					$q='select * from truyen where suggest != 0 order by suggest limit 3';
					$db->query($q);
					for($i=1;$i<=3;$i++)
					{
						$result[$i]=$db->get();
					}
					for($i=1;$i<=3;$i++)
					{
						echo
						'
						<div class="emergent-content">
                    	<div class="full-relative">
                        	<!---->
                        	<div class="emergent-image">
							<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><img src="'.$result[$i]['thumbnail'].'" width="100%" height="100%" /></a>
                            </div>
                            <!---->
                            <div class="emergent-select">
                            	<div class="list-select">
                                	<p class="flag">TOP ĐỀ CỬ</p>
                                	<ul>
                                    	<li'; if($i==1) echo' id="select-1"'; echo' stt="0">1</li>
                                        <li'; if($i==2) echo' id="select-2"'; echo' stt="1">2</li>
                                        <li'; if($i==3) echo' id="select-3"'; echo' stt="2">3</li>
                                    </ul>
                                    
                                </div>
                            </div>
                            <!---->
                            <div class="emergent-info">
                            	<div class="info-content">
                                	<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><h3>'.$result[$i]['name'].translate('season',$result[$i]['season']).'</h3></a>
                                    <p class="p-info">
                                    	<span>Tác giả: </span>'.$result[$i]['author'].'<br /><br />
                                        <span>Trạng thái: </span>'.translate('status',$result[$i]['status']).'<br /><br />
                                        <span>Thể loại:</span>';
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
									echo'
                                    </p>
                                    <p class="p-intro">
                                    	<span>Giới thiệu: </span>'.quote_content($result[$i]['introduce'],200).'
                                    </p>
                                </div>
                            </div>
                            <!---->
                            <div class="emergent-item">
                            	<div class="read-now" truyen_id="'.$result[$i]['truyen_id'].'">
                                    <a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><img src="images/read-book-02.png" height="30"/> Đọc ngay</a>
                                </div>
                                <div class="like-now" truyen_id="'.$result[$i]['truyen_id'].'" value="'.$result[$i]['like_truyen'].'">
                                	<img src="images/heart-01.png" height="30"/>
                                    <a style="cursor:pointer;">'.$result[$i]['like_truyen'].'</a>
                                </div>
                            </div>
                        </div>
                    </div>
						';
					}
				?>     
                </div>
            <div class="clear"></div>
            </div>
            <!---->
            <div id="contain-tool">
            	<div id="tool">
                	<div id="tool-content">
                    	<div class="contain-search">
                        	 <input type="text" class="search-input" placeholder="Tìm kiếm ..." stt="2"/>
                             <button class="search-icon" stt="2">
                             	<img src="images/search_02.png" width="60%" height="60%" />
                             </button>
                             <div class="warning-search"></div>
                        </div>
                        <div class="contain-filter">
                        	
                            <div class="filter">
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
                            </div>
							<p>Chọn những mục bạn thích để lọc truyện</p>
                            <div class="filter-active">
                            	<div class="warning-filter"></div>
                            	<div class="filter-icon" stt="2">
                                	Lọc truyện
                                </div>
                            </div>
                        </div>
                    </div>
               
                </div>
            </div>
           <div class="clear"></div>
        </div>
        
        <div class="clear"></div>
        <!------>
        <div class="center">
        	<div class="flag-center">
            	<div class="flag"><a href="danh-sach/xem-nhieu/">top lượt xem</a></div>
            </div>
        	<?php 
				$q='select * from truyen ';
				$q.=' where suggest=0 order by view desc limit 6';
				$db->query($q);
				for($i=1;$i<=6;$i++)
				{
					$result[$i]=$db->get();
					$top_view[$i-1]=$result[$i]['truyen_id'];
				}
				for($i=1;$i<=6;$i++)
				{
				echo
				'
				<div class="contain-item">
            	<div class="item-content">
                <div class="full-relative">
				
					<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><img src="'.$result[$i]['thumbnail'].'" height="100%" width="100%"/></a>
                		
                
                    <div class="contain-info">
                        <h3><a href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'" title="'.$result[$i]['name'].translate('season',$result[$i]['season']).'">'
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
							<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><div class="item-active">Đọc truyện</div></a>
                        </div>
                    </div>
                    <div class="rank"><span>'.$i.'</span></div>
                    <div class="rank-frame"></div>
                </div>
                </div>
          	    </div>
				';
				}
			?>
           
            <div class="clear"></div>
            <div class="more">
            	<a href="danh-sach/xem-nhieu/">Xem thêm &rarr;</a>
            </div>
        </div>
        <div class="clear"></div>
        
        <div class="center">
        	<div class="flag-center">
            	<div class="flag"><a href="danh-sach/truyen-moi/">mới cập nhật</a></div>
            </div>
        	<?php 
				$q='select * from truyen ';
				$q.=' where suggest=0 order by date_change desc limit 12';
				$db->query($q);
				$index=1;
				for($i=1;$i<=12;$i++)
				{
					$tmp=$db->get();
					if(!in_array($tmp['truyen_id'],$top_view))
					{
						$result[$index]=$tmp;
						$index++;
					}
				}
				for($i=1;$i<=6;$i++)
				{
				echo
				'
				<div class="contain-item">
            	<div class="item-content">
                <div class="full-relative">
					<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><img src="'.$result[$i]['thumbnail'].'" height="100%" width="100%"/></a>
                	
                
                    <div class="contain-info">
                       <h3><a href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'" title="'.$result[$i]['name'].translate('season',$result[$i]['season']).'">'
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
                        	<a style="cursor:pointer;" href="'.strtolower(unicode_convert($result[$i]['name']))."/".$result[$i]['truyen_id'].".html".'"><div class="item-active">Đọc truyện</div></a>
                        </div>
                    </div>
                    <div class="rank"><span>'.$i.'</span></div>
                    <div class="rank-frame"></div>
                </div>
                </div>
          	    </div>
				';
				}
			?>

            <div class="clear"></div>
            <div class="more">
            	<a href="danh-sach/truyen-moi/">Xem thêm &rarr;</a>
            </div>
        </div>
        <div class="clear"></div>