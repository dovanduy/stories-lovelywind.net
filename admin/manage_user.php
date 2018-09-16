<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script src="user_ajax.js"></script>
<?php
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{
	if($_SESSION['p_level']==3)
		{
		include("include_admin/top.php");
		echo'
			<div id="main-content">
			
				<div id="contain-table">
				<div id="top-table">
					<h3>Quản lý user</h3>
					<button id="add"><img src="../images/add-icon.png" width="16px" height="14px" style="float:left;"/>Thêm</button>	
					<div class="clear"></div>
				</div>
				<table class="table-manage">
					 <tr>
						<th><a href="manage_user.php?sort=name">Tên</a></th>
						<th><a href="manage_user.php?sort=email">Email</a></th>
						<th><a href="manage_user.php?sort=level">Cấp</a></th>
						<th><a href="manage_user.php?sort=date">Ngày tạo</a></th>
						<th>Chi tiết</th>
						<th>Xóa</th>
					</tr>
			';
				  
					/*-------------sort----------*/
					if(isset($_GET['sort']))
					switch($_GET['sort'])
					{
						case 'name':
						$sort='name';
						break;
						case 'email':
						$sort='email';
						break;
						case 'level':
						$sort='level';
						case 'date':
						$sort='date_create';
						break;
						default :
						$sort='date_create';
						break;
					} else $sort='date_create';
					/*--------------------*/
					$q="select user_id, date_create, email, level, user_name as name";
					$q .=" from user where level < 3 ";
					$q .=" order by {$sort} asc";
					$db->query($q);
					while($result=$db->get())
					{
						$level='Không xác định';
						if($result['level']==3)
						{
							$level='Người thành lập';
						} else if($result['level']==2)
						{
							$level='Quản trị viên';
						} else if($result['level']==1)
						{
							$level='Thành viên';
						} else if($result['level']==0)
						{
							$level='Chờ xác nhận';
						}
						echo"<tr>                
							 <td>{$result['name']}</td>
							 <td>{$result['email']}</td>
							 <td>{$level}</td> 
							 <td>{$result['date_create']}</td> 
							 <td><a href='#' class='edit' user_id='{$result['user_id']}'><img src='../images/icon-edit.png' width='16px' height='16px' style='margin-right:5px;'/>Chi tiết</a></td>
							 <td><a href='#' class='delete' user_id='{$result['user_id']}'><img src='../images/remove-icon.png' width='16px' height='16px' style='margin-right:5px;'/>Xóa</a></td>
							 </tr>";
					}
				echo'
				</table>
				</div>
				
				<div id="fixed">
					<div id="confirm" style="width:460px;">
					<img class="exit" src="../images/DeleteRed.png" width="25px" height="25px"/>
					<div class="clear"></div>
					
					<form id="new" enctype="multipart/form-data">
						<fieldset style="margin:0px 10px 10px 10px ; padding-bottom:20px;">
						<legend style="text-align:center;"></legend>
						<div style="width:100%; overflow:auto; margin:auto; margin-top:10px; ">
						<table class="table-fill">
							<!--Tên-->
							<tr>
								<td>
									<label for="user_name">Họ và tên:<span class="required">*</span>
									<p id="user_name_error"></p>
									</label>
								</td>
								<td> <input id="user_name" type="text" size="18" maxlength="200" tabindex="1"/></td> 
							</tr>
							<!--Mail-->
							<tr>
								<td>
									<label for="email">Email:<span class="required">*</span>
									<p id="email_error"></p>
									</label>
								</td >
								<td colspan="2"> <input id="email" type="text" size="18" maxlength="200" tabindex="1" /></td>
							</tr>
							<!--Password-->
							<tr>
								<td>
									<label for="password">Mật khẩu:<span class="required">*</span>
									<p id="password_error"></p>
									</label>
								</td>
								<td> <input id="password" type="password" size="18" maxlength="200" tabindex="1" /></td>
							</tr>
							<!--Confirm-->
							<tr>
								<td>
									<label for="confirm_password">Xác nhận mật khẩu:<span class="required">*</span>
									<p id="confirm_password_error"></p>
									</label>
								</td >
								<td> <input id="confirm_password" type="password" size="18" maxlength="200" tabindex="1" /></td>
							</tr>
							<!--Level-->
							<tr>
								<td><label for="level">Cấp quyền:</label></td>
								<td> 
									<select id="level" tabindex="2">
										<option value="0">Chờ duyệt</option>
										<option value="1">Thành viên</option>
										<option value="2">Quản trị viên</option>
									</select> 
								</td>
							</tr>
							<!--Hình đại diện-->
							<tr>
								<td> 
									<label for="img_thumbnail">Hình đại diện</label>
								</td>
							</tr>  
							<tr>
								<td style="position:relative;" colspan="2">
												<input type="file" id="thumbnail" style="position:absolute; top:0px; width:70px; height:25px; opacity:0; padding:0px; margin:0px; left:20px; "/>
												<img src="../images/add-icon.png" width="25px" height="25px" style="position:absolute; top:0px;  left:20px; cursor:pointer;" id="open_thumb"/>
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
				
				<div id="fixed-detail">
					<div id="detail" style="width:500px;">
						<img class="exit" src="../images/DeleteRed.png" width="30px" height="30px"/>
						<div class="clear"></div>
						
						<div id="content-detail" style="width:100%;">
							<div id="topic"></div>
							
							<div id="update" style="width:100%; float:left;">
							<div style="width:100%; overflow:auto; margin:auto;">
								<form id="manage" enctype="multipart/form-data">
									<table class="table-fill">
										<!--Name-->
										<tr>
											<td style="min-width:120px;">
												<label for="user_name">Họ và tên:<p id="edit_user_name_error"></p></label>
											</td>
											<td id="info_user_name">
												<input id="edit_user_name" type="text" size="20" maxlength="200" tabindex="1"/>
											</td>
											<td rowspan="4" id="show_avatar"></td>
										</tr>
										<!--Email-->
										<tr>
											<td><label for="email">Email: <p id="edit_email_error"></p></label></td>
											<td id="info_email">
												<input id="edit_email" type="text" size="20" maxlength="200" tabindex="1" />
											</td>
										</tr>
										<!--Level-->
										<tr>
											<td><label for="level">Cấp bậc: </label></td>
											<td id="info_level">
											<select id="edit_level" tabindex="2">     
											</select>
											</td>
										</tr>
										<!--Create date-->
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
			redirect_to("admin","","");
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