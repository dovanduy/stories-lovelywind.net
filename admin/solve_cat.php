<?php
	session_start();
	include("../include/function.php");
	if(isset($_SESSION['p_user_id']) && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
			$user_id=$_SESSION['p_user_id'];
			$db = new database;
			$db->connect();
			
			if($_POST['action']=="sub_add")
			{
				/*Kiểm tra trùng tên*/
				$cat_name=strip_tags($_POST['cat_name']);
				$priority=strip_tags($_POST['pri']);
				if(!check('name',$cat_name))
				{
					$q="select * from categories where cat_name='{$cat_name}'";
					$db->query($q);
					if($db->num_rows()==0)
					{
					/*Insert*/
					$date= date("Y-m-d");
					$q= "INSERT INTO categories ( user_id, cat_name, priority, date_create) VALUES";
					$q.="('{$user_id}','{$cat_name}','{$priority}','{$date}')";
					$db->query($q);
					
					/*Lấy id cat mới tạo*/
					$q="select c.cat_id, u.user_name as name";
					$q.=" from categories as c join user as u using(user_id)";
					$q.=" where cat_name='{$cat_name}' ";
					$db->query($q);
					
					$result=$db->get();
					$user_name=$result['name'];
					$cat_id=$result['cat_id'];
					echo json_encode(
						array(
							"status"=>1,
							"cat_name"=>"$cat_name",
							"user_name"=>"$user_name",
							"date"=>"$date",
							"priority"=>"$priority",
							"cat_id"=>"$cat_id"
						)
					);
					} 
					else 
					{
						echo json_encode(
							array(
								"status"=>0,
							)
						);
					}
				}
			}
			else if($_POST['action']=="delete")
			{
				$cat_id=strip_tags($_POST['cat_id']);
				$q="delete from categories where cat_id='{$cat_id}'";
				$db->query($q);
				$q="delete from theloai where cat_id='{$cat_id}'";
				$db->query($q);
				echo json_encode(
					array(
						"cat_id"=>"$cat_id"
					)
				);
			}
			else if($_POST['action']=="edit")
			{
				$cat_id=strip_tags($_POST['cat_id']);
				$q="select cat_name,priority from categories where cat_id='{$cat_id}'";
				$db->query($q);
				$result=$db->get();
				$name=$result['cat_name'];
				$pri=$result['priority'];
				echo json_encode(
					array(
						"cat_name"=>"$name",
						"priority"=>"$pri"
					)
				);
			}
			else if($_POST['action']=="sub_edit")
			{
				/*Kiểm tra trùng tên*/
				$cat_id=strip_tags($_POST['cat_id']);
				$cat_name=strip_tags($_POST['cat_name']);
				$priority=strip_tags($_POST['pri']);
				if(!check('name',$cat_name))
				{		
					$q="select * from categories where cat_name='{$cat_name}' and priority='{$priority}'";
				$db->query($q);
				if($db->num_rows()==0)
				{
					$q="update categories set cat_name='{$cat_name}',priority={$priority} where cat_id={$cat_id} limit 1";
					$db->query($q);
					echo json_encode(
						array(
							"status"=>1,
							"cat_id"=>"$cat_id",
							"cat_name"=>"$cat_name",
							"priority"=>"$priority"
						)
					);
				}
				else
				{
					echo json_encode(
						array(
							"status"=>0
							)
					);
				}
				}
			}
		}
	}
?>