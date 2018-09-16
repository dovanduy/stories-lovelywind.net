<?php 
	session_start();
	include("../include/function.php");
	$db = new database;
	$db->connect();
	if(isset($_POST['action']))
	{
		if($_POST['action']=='login')
		{
			$password= md5($_POST['password']);
			$email=$_POST['email'];
			if(check('email',$email))
			{
				$q="select * from user where email='{$email}' and password='{$password}'";
				$db->query($q);
				if($db->num_rows()==1)
				{
					$result=$db->get();
					$level=$result['level'];
					$user_name=$result['user_name'];
					$user_id=$result['user_id'];
					$avatar=$result['avatar'];
					$_SESSION['p_user_id']=$user_id;
					$_SESSION['p_avatar']=$avatar;
					$_SESSION['p_email']=$email;
					$_SESSION['p_level']=$level;
					$_SESSION['p_user_name']=$user_name;
					$_SESSION['p_password']=$password;
					echo json_encode(
						array(
							"status"=>1,
							"password"=>"$password",
							"email"=>"$email",
							"mess"=>"$q"
						)
					);
				}
				else
				{
					echo json_encode(
						array(
							"status"=>0,
							"mess"=>"$q"
						)
					);
				}
			}
		}
		else if($_POST['action']=='register')
		{	
			$password= md5($_POST['password']);
			$email=$_POST['email'];
			$name=$_POST['name'];
			if(!check('name',$name) && check('email',$email))
			{
				$q="select * from user where email='{$email}'";
				$db->query($q);
				if($db->num_rows()==0)
				{
					$active_code=date("ymdHis");
					$date=date('Y-m-d');
					$q="insert into user (user_name,email,password,level,date_create,date_change,active_code) values";
					$q.=" ('{$name}','{$email}','{$password}','0','{$date}','{$date}','{$active_code}') ";
					$db->query($q);
					$q="select * from user where email='{$email}'";
					$db->query($q);
					if($db->num_rows()==1)
					{
						$body='Cảm ơn bạn đã đăng ký làm thành viên của Lovelywind.com. Bạn vui lòng click vào';
						$body.=' đường dẫn bên dưới để kích hoạt \n\n';
						$body.= BASE_URL."login/active.php?x=".urlencode($email)."&y={$active_code}";
						//mail($email,'Kích hoạt tài khoản Lovelywind',$body,'FROM: localhost');
   				    	//$title = 'Kích hoạt tài khoản Lovelywind';
   					   // $diachicc = 'xcc@gmail.com';
 						//sendMail($title, $body, $name, $email ,$diachicc='');
						echo json_encode(
							array(
							"status"=>1,
							"mess"=>"$body"
							)
						);
					}
				}
				else
				{
				echo json_encode(
					array(
						"status"=>0,
						"mess"=>"$q"
					)
				);
				}
			}
		}
	}
?>