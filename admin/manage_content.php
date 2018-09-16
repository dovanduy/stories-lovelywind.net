<?php 
	include("../include/function.php");
	include("include_admin/header.php");
?>
	<script src="content_ajax.js"></script>
<?php
	if(isset($_SESSION['p_email']) && isset($_SESSION['p_level']))
	{ 
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
			include("include_admin/top.php");
			echo'
				<div id="main-content">
				';
						if(isset($_GET['page']))
						{
							$start=($_GET['page']-1)*20;
							$page=$_GET['page'];
						} else
						{
							$start=0;
							$page=1;
						}
					
						if(isset($_GET['truyen_id']))
						{
							$truyen_id=$_GET['truyen_id'];
							echo '<div id="get_id" value="'.$truyen_id.'"></div>';
							$q="select name from truyen where truyen_id='{$truyen_id}'";
							$db->query($q);
							$result=$db->get();
		
							echo 
								'
								<div id="contain-table">
									<div id="top-table">
										<h3>Quản lý nội dung truyện: '.$result['name'].'</h3>
										<button id="add"><img src="../images/add-icon.png" width="16px" height="14px"
										style="float:left;"/>Thêm</button>
										<div class="clear"></div>
									</div>
									<table class="table-manage">
										<tr>
											<th>Danh sách chương</th>
											<th>Tiêu đề</th>
											<th>Sửa</th>
											<th>Xóa</th>
										</tr>
								';
							$q="select content_id, title , chap , part from noidung where truyen_id='{$truyen_id}'";
							$q.=" order by chap, part asc limit {$start},20";
							$db->query($q);
								while($result = $db->get())
								{
									if($result['part']!=0) 
										{
											$part=".".$result['part'];
										}
									else $part="";
									
									echo 
									'
										<tr>
											<td> Chương: '.$result['chap'].$part.'</td>
											<td>'.$result['title'].'</td>
											<td><a href="#" class="edit" content_id="'.$result['content_id'].'">
											<i class="far fa-edit"></i>Sửa</a></td>
											<td><a href="#" class="delete" content_id="'.$result['content_id'].'">
											<i class="fas fa-trash-alt"></i>Xóa</a>
											</td>
										</tr>
									';
								}
							
							echo		
								'
									</table>
								';
						}
					$q="select count(content_id) as num from noidung where truyen_id='{$truyen_id}'";
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
										echo '<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$i.'"><li>'.$i.'</li></a>';
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
										echo '<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$i.'"><li>'.$i.'</li></a>';
									}
								echo'	<li>...</li>
										<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$pre_last.'"><li>'.$pre_last.'</li></a>
										<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$unit.'"><li>Cuối</li></a>
									</ul>
								</div>';
							}
							else if($page>=($unit-3))
							{
								$s4=$unit-4; $s3=$unit-3; $s2=$unit-2; $s1=$unit-1;
								echo'
									<div class="list-page">
										<ul>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page=1"><li>Đầu</li></a>
											<li>...</li>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$s4.'"><li>'.$s4.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$s3.'"><li>'.$s3.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$s2.'"><li>'.$s2.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$s1.'"><li>'.$s1.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$unit.'"><li>Cuối</li></a>
										</ul>
									</div>';
							}
							else
							{
								$pre=$page-1; $next=$page+1; $pre_last=$unit-1;
								echo'
									<div class="list-page">
										<ul>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page=1"><li>Đầu</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$pre.'"><li>'.$pre.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$page.'"><li>'.$page.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$next.'"><li>'.$next.'</li></a>
											<li>...</li>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$pre_last.'"><li>'.$pre_last.'</li></a>
											<a href="manage_content.php?truyen_id='.$truyen_id.'&page='.$unit.'"><li>Cuối</li></a>
										</ul>
									</div>';
							}
						}
					}
					
					echo'</div>';
					echo'
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
									<td colspan="2" width="200px"><label for="name">Tên chương (nếu có):</label></td>
									<td colspan="2"><input id="name" class="text-form" type="text" size="26" maxlength="300" tabindex="1" /></td>
								</tr>
								
								<tr id="chap_part">
									<td>
										<label for="chap">Chương:<span class="required">*</span>
										 <p id="chap_error"></p>
										</label>
									</td>
									<td>
										<input id="chap" type="number" size="5" maxlength="6" tabindex="1" style="width:5em;" />
									</td>
									<td>
										<label for="part">Phần (nếu có):</label> 
									</td>
									<td>
										<select id="part" tabindex="2">
											<option value="0">Không có</option>
						';
											
												for($i=1;$i<=10;$i++)
													echo
													'
														<option value="'.$i.'">'.$i.'</option>
													';
								echo'           
										</select> 
									</td>
								</tr>	
								
								<tr>
									<td colspan="4">
										<label for="link">Nhập nội dung<span class="required">*</span>
										<p id="content_error"></p>
										</label>
									</td>
								</tr>
								<tr>
									<td colspan="4">
										<textarea id="content" style="height:250px; width:100%" ></textarea> 
									</td>                   
								</tr>
								
								<tr>
									<td colspan="2"><input type="submit" class="submit" /></td>
									<td colspan="2"><button id="clear_form" type="button" style="float:right; margin-right:0px;">Clear</button></td>
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
	}
	else
	{
		redirect_to("login/login.php","","");
	}
?>
<?php 
	include("include_admin/footer.php");
?>