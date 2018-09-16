<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script src="cat_ajax.js"></script>
<?php
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
		include("include_admin/top.php");
		echo'<div id="main-content">
				<div id="contain-table">
				<div id="top-table">
					<h3>Quản lý chuyên mục</h3>
					<button id="add"><img src="../images/add-icon.png" width="16px" height="14px" style="float:left;"/>Thêm</button>	
					<div class="clear"></div>
				</div>
				<table class="table-manage">
				
					<tr>
						<th><a href="manage_cat.php?sort=name">Tên chuyên mục</a></th>
						<th><a href="manage_cat.php?sort=pri">Độ ưu tiên</a></th>
						<th><a href="manage_cat.php?sort=by">Người tạo</a></th>
						<th><a href="manage_cat.php?sort=date">Ngày tạo</a></th>
						<th>Chỉnh sửa</th>
						<th>Xóa</th>
					</tr>
				';
				 
					/*-------------sort----------*/
					if(isset($_GET['sort']))
					switch($_GET['sort'])
					{
						case 'name':
						$sort='cat_name';
						break;
						case 'pri':
						$sort='priority';
						break;
						case 'by':
						$sort='name';
						case 'date':
						$sort='date_create';
						break;
						default :
						$sort='date_create';
						break;
					} else $sort='date_create';
					/*--------------------*/
					$q="select c.cat_id, c.cat_name, c.priority, c.date_create, u.user_name as name";
					$q .=" from categories as c ";
					$q .=" join user as u using(user_id) ";
					$q .=" order by {$sort} asc";
					
					$db->query($q);
					
					while($result=$db->get())
						echo"<tr>                
							 <td>{$result['cat_name']}</td>
							 <td>{$result['priority']}</td>
							 <td>{$result['name']}</td> 
							 <td>{$result['date_create']}</td> 
							 <td><a href='' class='edit' cat_id='{$result['cat_id']}'>
							 <i class='far fa-edit'></i>Chỉnh sửa</a></td>
						 
							 <td><a href='' class='delete' cat_id='{$result['cat_id']}'>
							 <i class='fas fa-trash-alt'></i>Xóa</a></td>
							 </tr>";
				echo' 
				</table>
				</div>
				
				<div id="fixed" class="fixed-full">
					<div id="confirm">
					<div class="taskbar">
						<div class="flag-taskbar"></div>
						<div class="exit">Close</div>
					</div>
					<div class="clear"></div>
					<form id="new">	
						<table class="table-fill">
							<tr>
								<td>
									<label for="name">Tên:<span class="required">*</span>
									<p id="name_error"></p>
									</label>
								</td>
								<td> <input id="name" class="text-form" type="text" maxlength="200" tabindex="1" /></td>
							</tr>	
							<tr>
								<td><label for="select_priority">Mức ưu tiên:</label></td>
								<td>
									<select id="priority" tabindex="2">
									
									</select> 
								</td>
							</tr>
							
							<tr>
							<td><input type="submit" class="submit" /></td>
							
							</tr>
						</table>
					</form>
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
	} else
	{
		redirect_to("login/login.php","","");
	}
?>

<?php 
	include("include_admin/footer.php");
?>