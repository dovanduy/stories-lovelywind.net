<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script src="truyen_ajax.js"></script>
    <script src="tool_ajax.js"></script>
<?php
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
		ob_start();
		include("include_admin/top.php");	
		echo'
			<div id="main-content">
			
				<div id="contain-table">
				<div id="top-table">
					<h3>Kết quả tìm kiếm: ';
					if(isset($_GET['keyword']))
					{
						echo $_GET['keyword'];
					}
					else if(isset($_GET['num_cat']))
					{
						for($i=1;$i<=$_GET['num_cat'];$i++)
						{
							$tmp='cat_'.$i;
							if(isset($_GET[$tmp]))
							{
								$q="select cat_name from categories where cat_id='{$_GET[$tmp]}'";
								$db->query($q);
								if($db->num_rows()==1)
								{
									$now_cat=$db->get();
									if($i==$_GET['num_cat'])
									echo $now_cat['cat_name'];
									else echo $now_cat['cat_name'].", ";
								}
							}
						}
					}
					echo'</h3>
					<button id="add"><img src="../images/add-icon.png" width="16px" height="14px" style="float:left;"/>Thêm</button>	
					<div class="clear"></div>
				</div>';
				if(isset($_GET['page']))
				{
					$start=($_GET['page']-1)*2;
					$page=$_GET['page'];
				} else
				{
					$start=0;
					$page=1;
				}
				echo'<table class="table-manage">
				
					<tr>
						<th><a href="">Tên tuyện</a></th>
						<th><a href="">Số chương</a></th>
						<th><a href="">Người tạo</a></th>
						<th><a href="">Ngày tạo</a></th>
						<th>Chi tiết</th>
						<th>Nội dung</th>
						<th>Xóa</th>
					</tr>
					';
					
				/*--------------------*/
				if(isset($_GET['action']))
				{
				   
					if($_GET['action']=='search' && isset($_GET['keyword']))
					{
						
						if(isset($_GET['page']))
						{
							$start=($_GET['page']-1)*20;
							$page=$_GET['page'];
						} else
						{
							$start=0;
							$page=1;
						}
						
						$keyword=strip_tags($_GET['keyword']);
						
						$q="select * from truyen join user using(user_id) where name like '%{$keyword}%'";
						$q.=" or author like '%{$keyword}%' or author_unicode like '%{$keyword}%'";
						$q.=" or name_unicode like '%{$keyword}%'";
						$q.=" limit {$start},20";
						
						$db->query($q);
						if($db->num_rows()>0)
						{
						   
							while($result=$db->get())
							{
								echo"
								<tr>                
									 <td>".quote_content($result['name'],35)."</td>
									 <td>{$result['num_chap']}</td>
									 <td>{$result['user_name']}</td> 
									 <td>{$result['date_create']}</td> 
									 <td><a href='#' class='edit' truyen_id='{$result['truyen_id']}'><img src='../images/icon-edit.png' width='16px' height='16px' style='margin-right:5px;'/>Chi tiết</a></td>
									 <td><a href='manage_content.php?truyen_id={$result['truyen_id']}' truyen_id='{$result['truyen_id']}'><img src='../images/add-icon.png' width='16px' height='16px' style='margin-right:5px;'/>Nội dung</a></td>
									 <td><a href='#' class='delete' truyen_id='{$result['truyen_id']}'><img src='../images/remove-icon.png' width='16px' height='16px' style='margin-right:5px;'/>Xóa</a></td>
								</tr>
								";
							}
						}
						else
						{
							echo'<tr><td>Không có kết quả phù hợp<td><tr>';
						}
					} 
					else if($_GET['action']=='filter'  && isset($_GET['num_cat']))
					{
						$num_cat=$_GET['num_cat'];
						if($num_cat>0)
						{
							$format=true;
							for($i=0;$i<$num_cat;$i++)
							{
								$tmp='cat_'.($i+1);
								if(isset($_GET[$tmp]))
								{
									$cat_id[$i]=$_GET[$tmp];
								} 
								else 
								{
									$format=false;
									break;
								}
							}
							if($format==true)
							{	
								$result=filter($cat_id,$num_cat);
								if($result!=NULL)
								{
									if(isset($_GET['page']))
									{
										$start=($_GET['page']-1)*20;
										$page=$_GET['page'];
									} else
									{
										$start=0;
										$page=1;
									}
									$check_start=0; $check_finish=0;
									foreach($result as $truyen_id)
									{
										if($check_finish<20 && $check_start>=$start)
										{
											$q="select * from truyen join user using(user_id) ";
											$q.=" where truyen_id='{$truyen_id}' limit 1";
											$db->query($q);
											if($db->num_rows()>0)
											{
												$r=$db->get();
												echo
												"<tr>                
												 <td>".quote_content($r['name'],35)."</td>
												 <td>{$r['num_chap']}</td>
												 <td>{$r['user_name']}</td> 
												 <td>{$r['date_create']}</td> 
												 <td><a href='#' class='edit' truyen_id='{$r['truyen_id']}'><img src='../images/icon-edit.png' width='16px' height='16px' style='margin-right:5px;'/>Chi tiết</a></td>
												 <td><a href='manage_content.php?truyen_id={$r['truyen_id']}' truyen_id='{$r['truyen_id']}'><img src='../images/add-icon.png' width='16px' height='16px' style='margin-right:5px;'/>Nội dung</a></td>
												 <td><a href='#' class='delete' truyen_id='{$r['truyen_id']}'><img src='../images/remove-icon.png' width='16px' height='16px' style='margin-right:5px;'/>Xóa</a></td>
												</tr>";		
											}
											$check_finish++;
										}
										$check_start++;
									}
								}
								else
								{
									echo '<tr><td>Không có kết quả phù hợp</td></tr>';
								}
							}
							else redirect_to('admin/manage_truyen.php','','');
						}
						else redirect_to('admin/manage_truyen.php','','');
					}
					else redirect_to('admin/manage_truyen.php','','');
				} 
				else redirect_to('admin/manage_truyen.php','','');
				/*--------------------*/
				
				echo'
				</table>';
				
				if($_GET['action'] == 'search')
				{
					$q="select count(truyen_id) as num from truyen where name like '%{$keyword}%' or author like '%{$keyword}%'";
					$db->query($q);
					$result=$db->get();
					$num_truyen=$result['num'];
					$unit=ceil($num_truyen/20);
					if(isset($_GET['page']))
					{
						$clear='&page='.$_GET['page'];
						$fixed=getCurrentPageURL();
						$fixed=str_replace($clear,"",$fixed);
					}
					else
					{
						$fixed=getCurrentPageURL();
					}
				} 
				else if($_GET['action']=='filter')
				{
					$unit=ceil($check_start/20);
					if(isset($_GET['page']))
					{
						$clear='&page='.$_GET['page'];
						$fixed=getCurrentPageURL();
						$fixed=str_replace($clear,"",$fixed);
					}
					else
					{
						$fixed=getCurrentPageURL();
					}
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
									echo '<a href="'.$fixed.'&page='.$i.'"><li>'.$i.'</li></a>';
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
									echo '<a href="'.$fixed.'&page='.$i.'"><li>'.$i.'</li></a>';
								}
							echo'	<li>...</li>
									<a href="'.$fixed.'&page='.$pre_last.'"><li>'.$pre_last.'</li></a>
									<a href="'.$fixed.'&page='.$unit.'"><li>Cuối</li></a>
								</ul>
							</div>';
						}
						else if($page>=($unit-3))
						{
							$s4=$unit-4; $s3=$unit-3; $s2=$unit-2; $s1=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="'.$fixed.'&page=1"><li>Đầu</li></a>
										<li>...</li>
										<a href="'.$fixed.'&page='.$s4.'"><li>'.$s4.'</li></a>
										<a href="'.$fixed.'&page='.$s3.'"><li>'.$s3.'</li></a>
										<a href="'.$fixed.'&page='.$s2.'"><li>'.$s2.'</li></a>
										<a href="'.$fixed.'&page='.$s1.'"><li>'.$s1.'</li></a>
										<a href="'.$fixed.'&page='.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
						}
						else
						{
							$pre=$page-1; $next=$page+1; $pre_last=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="'.$fixed.'&page=1"><li>Đầu</li></a>
										<a href="'.$fixed.'&page='.$pre.'"><li>'.$pre.'</li></a>
										<a href="'.$fixed.'&page='.$page.'"><li>'.$page.'</li></a>
										<a href="'.$fixed.'&page='.$next.'"><li>'.$next.'</li></a>
										<li>...</li>
										<a href="'.$fixed.'&page='.$pre_last.'"><li>'.$pre_last.'</li></a>
										<a href="'.$fixed.'&page='.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
						}
					}
				}
				echo'</div>
				
				<div id="fixed">
					<div id="confirm" style="height:580px;">
					<img class="exit" src="../images/DeleteRed.png" width="25px" height="25px"/>
					<div class="clear"></div>
					
					<form id="new" enctype="multipart/form-data">
						<fieldset style="margin:0px 10px 10px 10px ; padding-bottom:20px;">
						<legend style="text-align:center;"></legend>
						<div style="width:100%; height:540px; overflow:auto; margin:auto; margin-top:10px; ">
						<table class="table_fill">
							<!--Tên-->
							<tr>
								<td>
									<label for="name">Tên truyện:<span class="required">*</span>
									<p id="name_error"></p>
									</label>
								</td>
								<td> <input id="name" type="text" size="20" maxlength="200" tabindex="1" /></td>
							</tr>
							
							<tr>
								<td>
									<label for="serial">Hệ liệt ( nếu có ):</label>
								</td>
								<td> <input id="serial" type="text" size="20" maxlength="200" tabindex="1" /></td>
							</tr>
							
							<tr>
								<td>
									<label for="author">Tác giả:<span class="required">*</span>
									<p id="author_error"></p>
									</label>
								</td>
								<td> <input id="author" type="text" size="20" maxlength="200" tabindex="1" /></td>
							</tr>
							<!--Season-->
							<tr>
								<td><label for="season">Chọn phần ( nếu có ):</label></td>
								<td><select id="season" tabindex="2">
									<option value="0">Không có</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select> 
								</td>
							</tr>
							
							<!--status-->
							<tr>
								<td><label for="status">Trạng thái:</label></td>
								<td><select id="status" tabindex="2">
									<option value="0">Đang cập nhật</option>
									<option value="1">Đã hoàn thành</option>
									<option value="2">Đã dừng</option>
								</select> 
								</td>
							</tr>
							<!--country-->
							<tr>
								<td><label for="country">Quốc gia:</label></td>
								<td><select id="country" tabindex="2">
									<option value="1">Trung Quốc</option>
									<option value="2">Nhật Bản</option>
									<option value="0">Việt Nam</option>
									<option value="3">Phương Tây</option>
								</select> 
								</td>
							</tr>
							<!--Thể loại-->
							<tr>
								<td colspan="2">
									<label for="type">Thể loại<span class="required">*</span>
									<p id="type_error"></p>
									</label>                            
								</td>
							</tr>
					';
					
									$q="select count(cat_id) as numcat from categories";
									$db->query($q);
									$result=$db->get();
									echo'<tr id="num_cat" value="'.$result['numcat'].'"></tr>';
									$num_td=ceil($result['numcat']/3);
									$position=0;
									for($i=1;$i<=$num_td;$i++)

									{
										echo'<tr><td colspan="2" style="padding-left:30px;">';
											$position=($i-1)*3;
											$q="select cat_id,cat_name from categories limit {$position},3";
											$db->query($q);
											$id=($i-1)*3;
											while($data=$db->get())
											{
												$id++;
												echo'<label for="check'.$id.'" style="color:#F90">'.$data['cat_name'].'</label>';
												echo'<input id="check'.$id.'" type="checkbox" value="'.$data['cat_id'].'" />';
											}
										echo'</td></tr>';
									}
						echo'		
							<!--Introduce-->    
							<tr>
								<td colspan="2"> 
									<label for="introduce">Giới thiệu</label>
								</td>
							</tr>    	
							<tr>
								<td colspan="2">
								<textarea id="introduce" style="height:250px; width:100%" ></textarea> 
								</td>                   
							</tr>
							<!--Hình đại diện-->
							<tr>
								<td> 
									<label for="img_thumbnail">Hình đại diện</label>
								</td>
							</tr>  
							<tr>
								<td style="position:relative;">
												<input type="file" id="thumbnail" style="position:absolute; top:0px; width:70px; height:25px; opacity:0; padding:0px; margin:0px; left:20px; "/>
												<img src="../images/add-icon-white.png" width="25px" height="25px" style="position:absolute; top:0px;  left:20px; cursor:pointer;" id="open_thumb"/>
												<p class="name_thumb" style="margin-left:46px;">Chưa chọn ảnh</p>
								</td>
							</tr>
							<!--submit-->
							<tr>
							<td><input class="submit" type="submit" style=" margin-top:20px;" /></td>
							</tr>
						</table>
						</div>
						</fieldset>
					</form>
					</div>
				</div>
				
				<div id="fixed_detail">
					<div id="detail">
						<img class="exit" src="../images/DeleteRed.png" width="30px" height="30px"/>
						<div class="clear"></div>
						
						<div id="content_detail" style="width:100%;">
							<div id="topic"></div>
							
							<div id="update" style="width:100%; float:left;">
							<div style="width:100%; height:540px; overflow:auto; margin:auto;">
								<form id="manage" enctype="multipart/form-data">
									<table class="table_fill">
										<!--Tên-->
										<tr>
											<td style="width:130px;">
											<label for="name">Tên truyện:<p id="edit_name_error"></p></label>
											</td>
											<td style="width:200px;" id="info_name">
												<input id="edit_name" type="text" size="20" maxlength="200" tabindex="1"/>
											</td>
											<td rowspan="9" id="show_thumbnail"></td>
										</tr>
										<!--serial-->
										<tr>
											<td><label for="serial">Hệ liệt: </label></td>
											<td id="info_serial">
												<input id="edit_serial" type="text" size="20" maxlength="200" tabindex="1" />
											</td>
										</tr>
										<!--Author-->
										<tr>
											<td><label for="author">Tác giả:<p id="edit_author_error"></p> </label></td>
											<td id="info_author">
												<input id="edit_author" type="text" size="20" maxlength="200" tabindex="1" />
											</td>
										</tr>
										<!--Season-->
										<tr>
											<td><label for="season">Phần: </label></td>
											<td id="info_season">
											<select id="edit_season" tabindex="2">
												
											</select>
											</td>
										</tr>
										<!--Num chap-->
										<tr>
											<td><label for="num_chap">Số chương: </label></td>
											<td id="info_numchap">
												<input id="edit_numchap" type="number" size="5" maxlength="50" tabindex="1" />
											</td>
										</tr>
										<!--status-->
										<tr>
											<td><label for="status">Trạng thái: </label></td>
											<td id="info_status">
											<select id="edit_status" tabindex="2">
												
											</select> 
											</td> 
										</tr>
										<!--country-->
										<tr>
											<td><label for="country">Quốc gia: </label></td>
											<td id="info_country">
											<select id="edit_country" tabindex="2">
												
											</select> 
											</td> 
										</tr>
										<!--suggest-->
										<tr>
											<td><label for="suggest">Top đề cử: </label></td>
											<td id="info_suggest">
											<select id="edit_suggest" tabindex="2">
												
											</select> 
											</td> 
										</tr>
										<!--Post on-->
										<tr>
											<td><label for="date">Ngày tạo: </label></td>
											<td id="info_date"></td>
										</tr>
										<!--Last change-->
										<tr>
											<td><label for="date">Sửa lần cuối: </label></td>
											<td id="info_datechange"></td>
											<td style="position:relative;">
												<input type="file" id="edit_thumbnail" style="position:absolute; top:0px; width:70px; height:25px; opacity:0; padding:0px; margin:0px; left:20px; "/>
												<img src="../images/icon-edit.png" width="25px" height="25px" style="position:absolute; top:0px;  left:20px; cursor:pointer;" id="open_editthumb"/>
												<p class="name_thumb" style="margin-left:46px;">Chưa chọn ảnh</p>
											</td>
										</tr>
										<!--User-->
										<tr>
											<td><label for="user">Người tạo: </label></td>
											<td id="info_user"></td>
										</tr>
										<!--Thể loại-->
										<tr>
											<td><label for="cat">Thể loại: </label></td>
											<td id="info_cat"></td>
										</tr>
										<!--Introduce-->   
										<tr>
											<td colspan="3" style=" border-top:#ccc 2px solid; text-align:center;"><label>Giới thiệu</label></td>
										</tr> 
										<tr>
										<td id="info_introduce" colspan="3">
											<textarea id="edit_introduce" style="width:100%; height:250px;" ></textarea> 
										</td>
										</tr>   	
										<!--submit--> 
										<tr>
											<td><input class="submit" type="submit" /></td>
											<td><button class="cancle" type="">Hủy</button>
										</tr>
									</table>
								</form>
							</div>
							</div>
							<div class="clear"></div>
						</div>
					
					</div>
				</div>
				
	   
			</div>
			<div class="clear"></div>
		</div>
							';
		}
		else
		{
			redirect_to("login/login.php","","");
		}
	}
	else
	{
		redirect_to("login/login.php","","");
	}
?>
<?php 
	include("include_admin/footer.php");
?>