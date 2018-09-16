<?php 
	session_start();
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	include("../include/function.php");
	if(isset($_SESSION['p_user_id'])  && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
			$user_id=$_SESSION['p_user_id'];
			$db = new database;
			$db->connect();
			if(isset($_POST['action']))
			{
				if($_POST['action']=="sub_add")
				{
					$truyen_id=strip_tags($_POST['truyen_id']);
					$content=strip_tags($_POST['content']);
					$title=strip_tags($_POST['title']);
					$chap=strip_tags($_POST['chap']);
					$part=strip_tags($_POST['part']);
					/*--------------*/
					$check="select content_id from noidung where truyen_id='{$truyen_id}' and chap='{$chap}' and part='{$part}'";
					$db->query($check);
					
					if($db->num_rows()==0)
					{
						$check="select content_id from noidung where truyen_id='{$truyen_id}' and chap='{$chap}'";
						$db->query($check);
						if($db->num_rows()==0)// Chua co chuong nay trong database
						{
							$q="select num_chap from truyen where truyen_id='{$truyen_id}'";
							$db->query($q);
							$result=$db->get();
							$num_chap=$result['num_chap']+1;
							$update="update truyen set num_chap='{$num_chap}' where truyen_id='{$truyen_id}'";
							$db->query($update);
						}
						$date= date("Y-m-d");
						$time= date("Y-m-d H:i:s");
						$insert="insert into noidung (user_id,truyen_id,content,chap,part,title,date_create,date_change) values";
						$insert.=" ('{$user_id}','{$truyen_id}','{$content}','{$chap}','{$part}','{$title}','{$date}','{$time}')";
						$db->query($insert);
						$update="update truyen set date_change='{$time}' where truyen_id='{$truyen_id}'";
						$db->query($update);
						$now="select content_id from noidung where truyen_id='{$truyen_id}' and chap='{$chap}' and part='{$part}'";
						$db->query($now);
						$result=$db->get();
						$now_id=$result['content_id'];
						if($part!=0) 
							{
								$part=".".$part;
							}
						else $part="";
						echo json_encode
						(
							array
							(
								"status"=>"1",
								"chap"=>"$chap",
								"part"=>"$part",
								"title"=>"$title",
								"content_id"=>"$now_id"
							)
						);
						
					} 
					else
					{
						$result=$db->get();
						$content_id=$result['content_id'];
						if($part!=0) 
							{
								$part=".".$part;
							}
						else $part="";
						
						$mess="Chương ".$chap.$part." đã có dữ liệu. Nếu tiếp tục, dữ liệu mới sẽ được ghi đè lên!";
						echo json_encode
						(
							array
							(
								"content_id"=>"$content_id",
								"status"=>"0",
								"mess"=>"$mess"
							)			
						);
					}
				}
				else if($_POST['action']=="delete")
				{
					$content_id=strip_tags($_POST['content_id']);
					$q="select chap,truyen_id from noidung where content_id='{$content_id}'";
					$db->query($q);
					$result=$db->get();
					$chap=$result['chap'];
					$truyen_id=$result['truyen_id'];//Lay thong tin truoc khi xoa
					
					$q="delete from noidung where content_id='{$content_id}'";
					$db->query($q);
					
					
					$check="select * from noidung where truyen_id='{$truyen_id}' and chap='{$chap}'";
					$db->query($check);
					if($db->num_rows()==0)// Chua co chuong nay trong database
						{
							$q="select num_chap from truyen where truyen_id='{$truyen_id}'";
							$db->query($q);
							$result=$db->get();
							$num_chap=$result['num_chap']-1;//giam so chuong dang co di mot
							$update="update truyen set num_chap='{$num_chap}' where truyen_id='{$truyen_id}'";
							$db->query($update);
						}
					echo json_encode(
						array(
							"status"=>"0",
							"content_id"=>"$content_id"
						)
					);
				}
				else if($_POST['action']=="edit")
				{
					$content_id=strip_tags($_POST['content_id']);
					$q="select title,content,chap,part from noidung where content_id='{$content_id}'";
					$db->query($q);
					$result=$db->get();
					$title=$result['title'];
					$content=$result['content'];
					$chap=$result['chap'];
					$part=$result['part'];
					if($part==0)
					{
						$mess=$chap;
					} else
					{
						$mess=$chap.".".$part;
					}
					echo json_encode(
						array(
							"title"=>"$title",
							"content"=>"$content",
							"chap"=>"$chap",
							"part"=>"$part",
							"mess"=>"$mess"
						)
					);
				}
				else if($_POST['action']=="sub_edit")
				{
					$content_id=strip_tags($_POST['content_id']);
					$content=strip_tags($_POST['content']);
					$title=strip_tags($_POST['title']);
					$date= date("Y-m-d");
					$q="update noidung set title='{$title}',content='{$content}',date_change='{$date}'"; 
					$q.=" where content_id={$content_id} limit 1";
					$r=$db->query($q);
					echo json_encode(
						array(
							"content_id"=>"$content_id",
							"title"=>"$title"
						)
					);
				}
			}
		}
	}
?>