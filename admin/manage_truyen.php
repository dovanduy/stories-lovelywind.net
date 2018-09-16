<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script type="text/javascript" src="include_admin/crop_thumb/jquery-ui.js"></script>
	<script type="text/javascript" src="include_admin/crop_thumb/crop.js"></script>
	<script src="truyen_ajax.js"></script>
    <script src="tool_ajax.js"></script>
    
<?php
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
		include("include_admin/top.php");	
		echo '<div id="get_info" c_type="thumbnail"></div>';
		echo'
			<div id="main-content">
			
				<div id="contain-table">
				<div id="top-table">
					<h3>Quản lý truyện</h3>
					<button id="add"><img src="../images/add-icon.png" width="16px" height="14px" style="float:left;"/>Thêm</button>	
					<div class="clear"></div>
				</div>';
				if(isset($_GET['page']))
				{
					$start=($_GET['page']-1)*20;
					$page=$_GET['page'];
				} else
				{
					$start=0;
					$page=1;
				}
				echo'<table class="table-manage">
				
					<tr id="first-tr">
						<th><a href="manage_truyen.php?sort=name&page='.$page.'">Tên tuyện</a></th>
						<th><a href="manage_truyen.php?sort=num&page='.$page.'">Số chương</a></th>
						<th><a href="manage_truyen.php?sort=by&page='.$page.'">Người tạo</a></th>
						<th><a href="manage_truyen.php?sort=date&page='.$page.'">Ngày tạo</a></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					';
		  
					/*-----sort----------*/
					if(isset($_GET['sort']))
					switch($_GET['sort'])
					{
						case 'name':
						$sort='name';
						break;
						case 'num':
						$sort='num_chap';
						break;
						case 'by':
						$sort='user_name';
						case 'date':
						$sort='date_create';
						break;
						default :
						$sort='date_create';
						break;
					} else $sort='date_create';
					/*--------------------*/
					$q="select t.truyen_id, t.name, t.num_chap, t.date_create, u.user_name";
					$q .=" from truyen as t ";
					$q .=" join user as u using(user_id) ";
					$q .=" order by {$sort} desc limit {$start},20";
					
					$db->query($q);
					$tmp=$db->num_rows();
					echo"<div id='get-numrows' numrows='{$tmp}'></div>";
					while($result=$db->get())
						echo"
							 <tr>                
							 <td>".quote_content($result['name'],35)."</td>
							 <td>{$result['num_chap']}</td>
							 <td>{$result['user_name']}</td> 
							 <td>{$result['date_create']}</td> 
							 <td><a href='#' class='edit' truyen_id='{$result['truyen_id']}'>
							 <i class='far fa-edit'></i>Chi tiết</a></td>
							 <td><a href='manage_content.php?truyen_id={$result['truyen_id']}' truyen_id='{$result['truyen_id']}'>
							 <i class='fas fa-book-open'></i>Nội dung</a></td>
							 <td><a href='#' class='delete' truyen_id='{$result['truyen_id']}'>
							 <i class='fas fa-trash-alt'></i>Xóa</a></td>
							 <td><input type='checkbox' check='0' class='select-a' value='{$result['truyen_id']}'></td>
							 </tr>
							 ";
				echo'
				</table>
				    <div style="float:right">
						<button id="delete-some">Xóa dòng đã chọn</button>
						<button class="check-all" action="all">Chọn hết</button>
					</div>
					<div class="clear"></div>
				';
				$q='select count(truyen_id) as num from truyen';
				$db->query($q);
				$result=$db->get();
				$num_truyen=$result['num'];
				$unit=ceil($num_truyen/20);
				if($unit>1)
				{
					if($unit<=7)
					{
						echo'
							<div class="list-page">
								<ul>';
								for($i=1;$i<=$unit;$i++)
								{	
									echo '<a href="manage_truyen.php?page='.$i.'"><li>'.$i.'</li></a>';
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
									echo '<a href="manage_truyen.php?page='.$i.'"><li>'.$i.'</li></a>';
								}
							echo'	<li>...</li>
									<a href="manage_truyen.php?page='.$pre_last.'"><li>'.$pre_last.'</li></a>
									<a href="manage_truyen.php?page='.$unit.'"><li>Cuối</li></a>
								</ul>
							</div>';
						}
						else if($page>=($unit-3))
						{
							$s4=$unit-4; $s3=$unit-3; $s2=$unit-2; $s1=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="manage_truyen.php?page=1"><li>Đầu</li></a>
										<li>...</li>
										<a href="manage_truyen.php?page='.$s4.'"><li>'.$s4.'</li></a>
										<a href="manage_truyen.php?page='.$s3.'"><li>'.$s3.'</li></a>
										<a href="manage_truyen.php?page='.$s2.'"><li>'.$s2.'</li></a>
										<a href="manage_truyen.php?page='.$s1.'"><li>'.$s1.'</li></a>
										<a href="manage_truyen.php?page='.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
						}
						else
						{
							$pre=$page-1; $next=$page+1; $pre_last=$unit-1;
							echo'
								<div class="list-page">
									<ul>
										<a href="manage_truyen.php?page=1"><li>Đầu</li></a>
										<a href="manage_truyen.php?page='.$pre.'"><li>'.$pre.'</li></a>
										<a href="manage_truyen.php?page='.$page.'"><li>'.$page.'</li></a>
										<a href="manage_truyen.php?page='.$next.'"><li>'.$next.'</li></a>
										<li>...</li>
										<a href="manage_truyen.php?page='.$pre_last.'"><li>'.$pre_last.'</li></a>
										<a href="manage_truyen.php?page='.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
						}
					}
				}
				echo'</div>
				
				<div id="fixed" class="fixed-full">
					<div id="confirm">
					<div class="taskbar">
						<div class="flag-taskbar"></div>
						<div class="exit">Close</div>
					</div>
					<div class="clear"></div>
					
					<form id="new" enctype="multipart/form-data">
						<div style="width:100%; overflow:auto; max-height:541px;">
						<table class="table-fill">
							<!--Tên-->
							<tr>
								<td>
									<label for="name">Tên truyện:<span class="required">*</span>
									<p id="name_error"></p>
									</label>
								</td>
								<td> <input id="name" class="text-form" type="text" size="20" maxlength="200" tabindex="1" /></td>
							</tr>
							
							<tr>
								<td>
									<label for="serial">Hệ liệt ( nếu có ):</label>
								</td>
								<td> <input id="serial" class="text-form" type="text" size="20" maxlength="200" tabindex="1" /></td>
							</tr>
							
							<tr>
								<td>
									<label for="author">Tác giả:<span class="required">*</span>
									<p id="author_error"></p>
									</label>
								</td>
								<td> <input id="author" class="text-form" type="text" size="20" maxlength="200" tabindex="1" /></td>
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
									/*$num_td=ceil($result['numcat']/3);
									$position=0;
									for($i=1;$i<=$num_td;$i++)
									{
										echo'<tr><td colspan="2" style="position:relative;">';
											$position=($i-1)*3;
											$q="select cat_id,cat_name from categories limit {$position},3";
											$db->query($q);
											$id=($i-1)*3;
											while($data=$db->get())
											{
												$id++;
												echo'<label for="check'.$id.'" style="color:#4aaaba;  margin-left:2.1em;">'.$data['cat_name'].'</label>';
												echo'<input id="check'.$id.'" type="checkbox" value="'.$data['cat_id'].'" />';
											}
										echo'</td></tr>';
									}*/
									echo'<tr><td colspan="2"><div class="list-cat">';
									$q='select * from categories';
									$db->query($q);
									while($tmp=$db->get())
									{
										echo '<div class="cat-item" value="'.$tmp['cat_id'].'">'.$tmp['cat_name'].'</div>';
									}
									echo'<div></td></tr>';
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
												<i class="fas fa-camera" style="position:absolute; top:8px;  left:20px; cursor:pointer;" id="open_thumb"></i>
												<p class="name_thumb" style="margin-left:46px;">Chưa chọn ảnh</p>
								</td>
							</tr>
							<!--submit-->
							<tr>
							<td><input class="submit" type="submit" style=" margin-top:11px;" /></td>
							</tr>
						</table>
						</div>
						
					</form>
					</div>
				</div>
				
				<div id="fixed_detail" class="fixed-full">
					<div id="detail">
						<div class="taskbar">
							<div class="flag-taskbar"></div>
							<div class="exit">Close</div>
						</div>
						<div class="clear"></div>
						
						<div id="content_detail" style="width:100%;">
							
							<div id="update" style="width:100%; float:left;">
							<div style="width:100%; height:541px; overflow:auto; margin:auto;">
								<form id="manage" enctype="multipart/form-data">
									<table class="table-fill">
										<!--Tên-->
										<tr>
											<td style="width:130px;">
											<label for="name">Tên truyện:<p id="edit_name_error"></p></label>
											</td>
											<td style="width:200px;" id="info_name">
												<input id="edit_name" class="text-form" type="text" size="20" maxlength="200" tabindex="1"/>
											</td>
											<td rowspan="9" id="show_thumbnail"></td>
										</tr>
										<!--serial-->
										<tr>
											<td><label for="serial">Hệ liệt: </label></td>
											<td id="info_serial">
												<input id="edit_serial"  class="text-form" type="text" size="20" maxlength="200" tabindex="1" />
											</td>
										</tr>
										<!--Author-->
										<tr>
											<td><label for="author">Tác giả:<p id="edit_author_error"></p> </label></td>
											<td id="info_author">
												<input id="edit_author" class="text-form" type="text" size="20" maxlength="200" tabindex="1" />
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
												<input type="file" id="edit_thumbnail" style="position:absolute; top:0px; width:0.1px; height:0.1px; opacity:0; padding:0px; margin:0px; left:20px; "/>
												<i class="fas fa-camera" style="position:absolute; top:0px;  left:30px; cursor:pointer;" id="open_editthumb"></i> 
												<p class="name_thumb" style="margin:-1em 0 0 46px;">Chưa chọn ảnh</p>
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
											<td colspan="3" style=" border-top:#ccc 1px solid; text-align:center;"><label>Giới thiệu</label></td>
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
				
				<div class="fixed-full" id="fixed-crop">
					<div id="manage_thumb">
						<div class="taskbar">
							<div class="flag-taskbar"></div>
							<div class="exit">Close</div>
						</div>
					 	<div id="area-crop">
							<div id="type-frame">
								<div class="relative" id="limit">
									<div class="blur left"></div>
									<div class="blur right"></div>
									<div class="blur top"></div>
									<div class="blur bottom"></div>
									<div id="frame_crop">
									    <div class="relative">
                                    		<button class="button-resize resizew"></button>
                                            <button class="button-resize resizeh"></button>
                                            <button class="button-resize resizec"></button>
                                        </div>
									</div>
									<div id="img-need-crop"></div>
								</div>
							</div>
						</div>
						<div id="thumb_curr"></div>
						<div id="crop">Crop</div>
					</div>
				</div>
				
	   
			</div>
			<div class="clear"></div>
		</div>
							';
		}
		else
		{
			redirect_to("../login/login.php","","");
		}
	}
	else
	{
		redirect_to("../login/login.php","","");
	}
?>
<?php 
	include("include_admin/footer.php");
?>