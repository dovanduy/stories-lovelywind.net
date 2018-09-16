<?php 
	session_start();
	include("../include/function.php");
	if(isset($_SESSION['p_user_id'])   && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==3)
		{
			$db = new database;
			$db->connect();
			
			if(isset($_POST['action']))
			{
				if($_POST['action']=='sub_add')
				{
					$user_name=strip_tags($_POST['user_name']);
					$email=strip_tags($_POST['email']);
					$level=strip_tags($_POST['level']);
					$password=md5(strip_tags($_POST['password']));
					if(!check('name',$user_name) && check('email',$email))
					{
						/*------------------*/
					$q="select * from user where email='{$email}'";
					$db->query($q);
					if($db->num_rows()==0)
					{
					/*Insert truyen*/
					$date= date("Y-m-d");
					$q= "INSERT INTO user (user_name, email, level , password , date_create, date_change)"; 
					$q.=" VALUES ('{$user_name}','{$email}','{$level}','{$password}','{$date}','{$date}')";
					$db->query($q);
					/*Lấy id mới tạo*/
					$q="select user_id from user where email='{$email}'";
					$db->query($q);
					$result=$db->get();
					$now_id=$result['user_id'];
					$lvl='Không xác định';
							if($level==3)
							{
								$lvl='Người thành lập';
							} else if($level==2)
							{
								$lvl='Quản trị viên';
							} else if($level==1)
							{
								$lvl='Thành viên';
							} else if($level==0)
							{
								$lvl='Chờ xác nhận';
							}
					echo json_encode(
						array(
							"status"=>"true",
							"user_name"=>"$user_name",
							"user_id"=>"$now_id",
							"level"=>"$lvl",
							"email"=>"$email",
							"date"=>"$date"
						)
					);
					} else echo json_encode(
						array(
							"status"=>"false",
						)
					);
					}
				}
				else if($_POST['action']=='delete')
				{
					$user_id=strip_tags($_POST['user_id']);
					
					$q="delete from user where user_id='{$user_id}'";
					$db->query($q);
					
					$q="update categories set user_id='1' where user_id='{$user_id}'";
					$db->query($q);
					
					$q="update truyen set user_id='1' where user_id='{$user_id}'";
					$db->query($q);
					
					$q="update noidung set user_id='1' where user_id='{$user_id}'";
					$db->query($q);
			
					echo json_encode (
					array(
						"user_id"=>"$user_id",
						"mess"=>"Xóa thành công"
					)
					);
				}
				else if($_POST['action']=='edit')
				{
					$user_id=strip_tags($_POST['user_id']);
					$get_info="select user_name, email, level, date_create, avatar, date_change ";
					$get_info.=" from user where user_id='{$user_id}'";
					$db->query($get_info);
					$result=$db->get();
					$user_name=$result['user_name'];
					$date=$result['date_create'];
					$date_change=$result['date_change'];
					$email=$result['email'];
					$avatar=$result['avatar'];
					$level=$result['level'];
					
					$db->query($get_info);
					$kq=array(
							"user_name"=>"$user_name",
							"date"=>"$date",
							"date_change"=>"$date_change",
							"level"=>"$level",
							"email"=>"$email",
							"avatar"=>"$avatar",
						);
					echo json_encode($kq);
				}
				else if($_POST['action']=='sub_edit')
				{
					$user_id=strip_tags($_POST['user_id']);
					$user_name=strip_tags($_POST['user_name']);
					$email=strip_tags($_POST['email']);
					$level=strip_tags($_POST['level']);
					if(!check('name',$user_name) && check('email',$email) && $level < 3)
					{
						/*------------------*/
					$q="select * from user where email='{$email}' and user_id!='{$user_id}'";
					$db->query($q);
					if($db->num_rows()==0)
					{
					/*Insert truyen*/
					$date= date("Y-m-d");
					$q= "update user set user_name='{$user_name}',";
					$q.=" email='{$email}', level='{$level}' ,date_change='{$date}'"; 
					$q.=" where user_id='{$user_id}'";
					$db->query($q);
					/*Lấy id cat mới tạo*/
					$lvl='Không xác định';
							if($level==3)
							{
								$lvl='Người thành lập';
							} else if($level==2)
							{
								$lvl='Quản trị viên';
							} else if($level==1)
							{
								$lvl='Thành viên';
							} else if($level==0)
							{
								$lvl='Chờ xác nhận';
							}
					echo json_encode(
						array(
							"status"=>"true",
							"user_name"=>"$user_name",
							"user_id"=>"$user_id",
							"level"=>"$lvl",
							"email"=>"$email"
						)
					);
					} else echo json_encode(
						array(
							"status"=>"false",
						)
					);
					}
				}
			}
			
			if (isset($_FILES['myfile'])) 
			{	
				$user_id=strip_tags($_POST['user_id']);
				$mimes = array(
				'image/jpeg', 'image/png', 'image/gif'
				);
				$code_name=date("ymdHis");
				$tmp=$_FILES['myfile']['name'];
				$file_ext=strstr($tmp,'.');
				$fileName =$code_name.$file_ext;
				$fileType = $_FILES['myfile']['type'];
				$fileError = $_FILES['myfile']['error'];
				$fileStatus = array(
					'status' => 0,
					'message' => '' 
				);
				
				if ($fileError== 1) 
				{ //Lỗi vượt dung lượng
					$fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
				} elseif (!in_array($fileType, $mimes)) 
				{ //Kiểm tra định dạng file
					$fileStatus['message'] = 'Không cho phép định dạng này';
				} else 
				{ //Không có lỗi nào
					move_uploaded_file($_FILES['myfile']['tmp_name'], '../img_avatar/'.$fileName);
					$fileStatus['status'] = 1;
					$fileStatus['message'] = "upload $fileName thành công";
				  
					$url="img_avatar/".$fileName;
					$q="insert into image (img_name, url) values ('{$fileName}','{$url}')";
					$db->query($q);
					//-Check crop 
					$tmp_image="../img_avatar/".$fileName;
					if($file_ext=='.jpg' || $file_ext=='.jpeg')
					{
						$image=imagecreatefromjpeg($tmp_image);
					}
					else if($file_ext=='.png')
					{
						$image=imagecreatefrompng($tmp_image);
					}
					else if($file_ext=='.gif')
					{
						$image = imagecreatefromgif($tmp_image);
					}
					if( @imagesx($image) > 400 || @imagesy($image) > 400)
					{
						$fileName="crop_".$fileName;
						$crop="../img_avatar/".$fileName;
						resize_image('crop',$tmp_image,$crop,400,400);
						$url="img_avatar/".$fileName;
						$q= "UPDATE user SET avatar='{$url}' where user_id='{$user_id}'";
						$db->query($q);
						echo json_encode($fileStatus);
						$q="insert into image (img_name, url) values ('{$fileName}','{$url}')";
						$db->query($q);
					}
					else
					{
						$q= "UPDATE user SET avatar='{$url}' where user_id='{$user_id}'";
						$db->query($q);
						echo json_encode($fileStatus);
					}
				}
			}
		}
	}
?>