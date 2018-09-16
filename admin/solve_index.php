<?php 
	include("../include/function.php");
	$db = new database;
	$db->connect();
	if(isset($_POST['action']))
	{
		if($_POST['action']=='get_avt')
		{
			$user_id=$_POST['id'];
			$q="select avatar from user where user_id='{$user_id}' limit 1";
			$db->query($q);
			$tmp=$db->get();
			$a=$tmp['avatar'];
			echo json_encode(
				array(
					'status'=>1,
					'avt'=>$a
				)
			);
		}
		else if($_POST['action']=='change-info')
		{
			$user_id=$_POST['id'];
			$user_name=$_POST['name'];
			$email=$_POST['email'];
			
			$q="select * from user where user_id!='{$user_id}' and email='{$email}' limit 1";
			$db->query($q);
			if($db->num_rows()==0)
			{
				$q="update user set user_name='{$user_name}', email='{$email}' where user_id='{$user_id}'";
				$db->query($q);
				echo json_encode(
				array(
					'status'=>1,
				)
				);
				
			}
			else
			{
				echo json_encode(
				array(
					'status'=>0,
				)
			);
			}
			
		}
		else if($_POST['action']=='change-pass')
		{
			$user_id=$_POST['id'];
			$o_pass=$_POST['o-pass'];
			$n_pass=md5($_POST['n-pass']);
			
			$q="select password from user where user_id='{$user_id}' limit 1";
			$db->query($q);
			$tmp=$db->get();
			if(md5($o_pass)==$tmp['password'])
			{
				$q="update user set password='{$n_pass}' where user_id='{$user_id}' limit 1";
				$db->query($q);
				echo json_encode(
					array(
						'status'=>1,
						'q'=>$n_pass
					)
				);
				
			}
			else
			{
				echo json_encode(
					array(
						'status'=>0,
						'q'=>$q
					)
				);
			}
		}
	}
	if (isset($_FILES['myfile'])) 
	{	
		$user_id=$_POST['id'];
		$mimes = array(
		'image/jpeg', 'image/png', 'image/gif'
		);
		$tmp=$_FILES['myfile']['name'];
		$file_ext=strstr($tmp,'.');
		$fileName = 'a-'.date("ymdHis").$file_ext;
		
		$fileType = $_FILES['myfile']['type'];
		$fileError = $_FILES['myfile']['error'];
		$fileStatus = array(
			'status' => 0,
			'message' => '', 
			'src' => '',
			'ratio' => 0,
		);
		$date=date("Y-m-d H:i:s");
		if ($fileError== 1) 
		{ //Lỗi vượt dung lượng
			$fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
		} elseif (!in_array($fileType, $mimes)) 
		{ //Kiểm tra định dạng file
			$fileStatus['message'] = 'Không cho phép định dạng này';
		} else 
		{ //Không có lỗi nào
			
			
			move_uploaded_file($_FILES['myfile']['tmp_name'], '../upload_tmp/'.$fileName);
			$fileStatus['status'] = 1;
			$fileStatus['message'] = "upload $fileName thành công";
			
			$q="select avatar_tmp from user where user_id='{$user_id}' limit 1";
			$db->query($q);
			$tmp=$db->get();
			if($tmp['avatar_tmp']!='0')
			{
				@unlink('../'.$tmp['avatar_tmp']);
			}
			
			$src='upload_tmp/'.$fileName;
			$q="update user set avatar_tmp='{$src}' where user_id='{$user_id}' limit 1";
			$db->query($q);
			$src='../'.$src;
			
			if (in_array($file_ext,array('.jpg','.jpeg'))) { $image = @imagecreatefromjpeg($src); }
			elseif ($file_ext == '.png') { $image = @imagecreatefrompng($src); }
			elseif ($file_ext == '.gif') { $image = @imagecreatefromgif($src); }
			elseif ($file_ext == '.bmp') { $image = @imagecreatefromwbmp($src); }
			
			$ratio= imagesx($image)/imagesy($image);
			
			$fileStatus['src']=$src;
			$fileStatus['ratio']=$ratio;
		}
		echo json_encode($fileStatus);
	}
?>