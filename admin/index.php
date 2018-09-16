<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script type="text/javascript" src="include_admin/crop_thumb/jquery-ui.js"></script>
	<script type="text/javascript" src="include_admin/crop_thumb/crop.js"></script>
	<script src="home.js"></script>
	<?php 
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
			echo '<div id="get_info" u_id="'.$_SESSION['p_user_id'].'" c_type="avatar"></div>';
			$user_id=$_SESSION['p_user_id'];
			$q="select * from user where user_id='{$user_id}' limit 1";
			$db->query($q);
			$user=$db->get();
			
			
			include("include_admin/top.php");
			echo'	
				<div id="main-content" >
					<input type="file" id="up-avatar"></input>
					<div class="show-avatar">
						<div class="absolute">
							<div class="relative">
							
								<img src="../'.$user["avatar"].'" class="img-center avatar"/>
								<div class="change-avatar">
									<div class="relative">
										<i class="fas fa-camera"></i>
									</div>
								</div>
								
							</div>
						</div>
					</div>
					<form>
					<div class="show-profile">
						<table class="table-profile">
							<tr>
								<td>Tên: </td>
								<td><input type="text" value="'.$user["user_name"].'" id="name"></input>
								    <p><span class="warning" id="name-error"></span></p>
								</td>
								
							</tr>
							<tr>
								<td>Email: </td>
								<td><input type="text" value="'.$user["email"].'" id="email"></input>
								    <p><span class="warning" id="email-error"></span></p>
								</td>
							</tr>';
							if($user["level"]==0){ $level="Đang chờ duyệt"; }
							else if($user["level"]==1){ $level="Thành viên"; }
							else if($user["level"]==2){ $level="Quản trị viên"; }
							else if($user["level"]==3){ $level="Người thành lập"; }
							else { $level="Lỗi"; }
						echo'<tr>
								<td>Cấp bậc: </td>
								<td>'.$level.'</td>
							</tr>
					 	</table>
					</div>
					
					<div class="show-profile">
						<table class="table-profile">
							<tr>
								<td>Mật khẩu hiện tại: </td>
								<td><input type="password" id="o-pass"></input>
								    <p><span class="warning" id="o-pass-error"></span></p>
								</td>
								
							</tr>
							<tr>
								<td>Mật khẩu mới: </td>
								<td><input type="password" id="n-pass"></input>
								    <p><span class="warning" id="n-pass-error"></span></p>
								</td>
								
							</tr>
							<tr>
								<td>Nhập lại: </td>
								<td><input type="password" id="c-pass"></input>
								    <p><span class="warning" id="c-pass-error"></span></p>
								</td>
							</tr>
					 	</table>
					</div>
					
					<div class="button-sub">
						<div class="div-center">
							<button type="button" class="update u-change">
								<span class="u-change-flag">
									Đổi mật khẩu
								</span>
							</button>
							<button type="submit" class="update u-profile" action="change-info">Cập Nhật</button>
						</div>
					</div>
					</form>
					
				</div>
				
				
				<div class="fixed-full" id="fixed-thumb">
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
						<span style="position:absolute; top:80px; right:74px;">Avatar hiện tại</span>
						<div id="thumb_curr"></div>
						<div id="crop">Đồng ý</div>
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
	include("../include_admin/footer.php");
?>