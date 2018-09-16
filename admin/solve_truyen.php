<?php
	session_start();
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	include("../include/function.php");
	if(isset($_SESSION['p_user_id'])  && isset($_SESSION['p_level']))
	{
		if($_SESSION['p_level']==2 || $_SESSION['p_level']==3)
		{
			$user_id= $_SESSION['p_user_id'];
			$db = new database;
			$db->connect();
			
			if(isset($_POST['action']))
			{
				if($_POST['action']=='sub_add')
				{
					$author=strip_tags($_POST['author']);
					$author_unicode=strtolower(str_replace('-',' ',unicode_convert($author)));
					$serial=strip_tags($_POST['serial']);
					$season=strip_tags($_POST['season']);
					$name=strip_tags($_POST['name']);
					$name_unicode=strtolower(str_replace('-',' ',unicode_convert($name)));
					$status=strip_tags($_POST['status']);
					$country=strip_tags($_POST['country']);
					$num_cat=strip_tags($_POST['sum']);
					$intro=strip_tags($_POST['intro']);
					if(!check('name_extend',$author))
					{
						/*------------------*/
					$q="select * from truyen where name='{$name}' and season='{$season}'";
					$db->query($q);
					if($db->num_rows()==0)
					{
					/*Insert truyen*/
					$date= date("Y-m-d");
					$time= date("Y-m-d H:i:s"); 
					$q= "INSERT INTO truyen( user_id, name, author, serial, season , status , introduce , date_create, date_change, country, name_unicode, author_unicode)"; 
					$q.=" VALUES ('{$user_id}','{$name}', '{$author}', '{$serial}','{$season}', '{$status}', '{$intro}','{$date}', '{$time}', '{$country}' ,'{$name_unicode}','{$author_unicode}')";
					$db->query($q);
					/*Lấy id cat mới tạo*/
					$q="select t.truyen_id, u.user_name as name";
					$q.=" from truyen as t join user as u using(user_id)";
					$q.=" where t.name='{$name}' and t.season='{$season}'";
					$db->query($q);
					$result=$db->get();
					$now_id=$result['truyen_id'];
					$user_name=$result['name'];
					echo json_encode(
						array(
							"status"=>"true",
							"name"=>"$name",
							"date"=>"$date",
							"truyen_id"=>"$now_id",
							"user"=>"$user_name"
						)
					);
					/*Insert type*/
					for($i=0;$i<$num_cat;$i++)
					{
						$tmp="cat".$i;
						$tmp_cat=$_POST[$tmp];
						$q="insert into theloai (truyen_id, cat_id) values ('{$now_id}','{$tmp_cat}')";
						$db->query($q);
					}
					} else echo json_encode(
						array(
							"status"=>"false",
						)
					);
					}
				}
				else if($_POST['action']=='delete')
				{
					$truyen_id=strip_tags($_POST['truyen_id']);
					
					$q="select thumbnail from truyen where truyen_id='{$truyen_id}'";
					$db->query($q);
					$tmp=$db->get();
					if($tmp['thumbnail']!=THUMB_DEFAULT)
					{
				    	@unlink('../'.$tmp['thumbnail']);
					}
					
					$q="delete from truyen where truyen_id='{$truyen_id}'";
					$db->query($q);
					
					$q="delete from theloai where truyen_id='{$truyen_id}'";
					$db->query($q);
					
					$q="delete from noidung where truyen_id='{$truyen_id}'";
					$db->query($q);
					
			
					echo json_encode (
					array(
						"truyen_id"=>"$truyen_id",
						"mess"=>"Xóa thành công"
					)
					);
				}
				else if($_POST['action']=='edit')
				{
					$truyen_id=strip_tags($_POST['truyen_id']);
					$get_info="select t.name,t.num_chap,t.author,t.introduce,t.country,t.suggest,";
					$get_info.=" t.date_create,t.status,t.season,t.serial,t.thumbnail,t.date_change, u.user_name";
					$get_info.=" from truyen as t join user as u using(user_id)";
					$get_info.=" where truyen_id='{$truyen_id}'";
					$db->query($get_info);
					$result=$db->get();
					$name=$result['name'];
					$date=$result['date_create'];
					$date_change=$result['date_change'];
					$num_chap=$result['num_chap'];
					$author=$result['author'];
					$intro=$result['introduce'];
					$status=$result['status'];
					$season=$result['season'];
					$serial=$result['serial'];
					$country=$result['country'];
					$suggest=$result['suggest'];
					$user_name=$result['user_name'];
					$thumbnail=$result['thumbnail'];
					
					$get_info="select c.cat_name from categories as c join theloai as t using(cat_id) 
							   where truyen_id='{$truyen_id}'";
					$db->query($get_info);
					$kq=array(
							"name"=>"$name",
							"date"=>"$date",
							"date_change"=>"$date_change",
							"num_chap"=>"$num_chap",
							"author"=>"$author",
							"intro"=>"$intro",
							"status"=>"$status",
							"season"=>"$season",
							"country"=>"$country",
							"suggest"=>"$suggest",
							"serial"=>"$serial",
							"user_name"=>"$user_name",
							"thumbnail"=>"$thumbnail",
						);
					$tmp="";
					while($result=$db->get())
					{
						$tmp=$tmp.$result['cat_name'].', ';
					}	
					$kq['cat']=$tmp;
					echo json_encode($kq);
				}
				else if($_POST['action']=='sub_edit')
				{
					$truyen_id=strip_tags($_POST['truyen_id']);
					$author=strip_tags($_POST['author']);
					$author_unicode=strtolower(str_replace('-',' ',unicode_convert($author)));
					$serial=strip_tags($_POST['serial']);
					$season=strip_tags($_POST['season']);
					$name=strip_tags($_POST['name']);
					$name_unicode=strtolower(str_replace('-',' ',unicode_convert($name)));
					$status=strip_tags($_POST['status']);
					$suggest=strip_tags($_POST['suggest']);
					$country=strip_tags($_POST['country']);
					$num_chap=strip_tags($_POST['num_chap']);
					$intro=strip_tags($_POST['intro']);
					//----Suggest
					if($suggest!=0)
					{
						$q="update truyen set suggest=0 where suggest='{$suggest}'";
						$db->query($q);
					}
					/*------------------*/
					$q="select * from truyen where name='{$name}' and season='{$season}' and truyen_id!='{$truyen_id}'";
					$db->query($q);
					if($db->num_rows()==0)//Chua ton tai
					{
					/*Update*/
					$date= date("Y-m-d");
					$time= date("Y-m-d H:i:s");
					$q="update truyen set name ='{$name}', author='{$author}', serial='{$serial}', season='{$season}', num_chap='{$num_chap}', ";
					$q.=" status='{$status}', introduce='{$intro}', date_change='{$time}', country='{$country}', suggest='{$suggest}', name_unicode='{$name_unicode}', author_unicode='{$author_unicode}' ";
					$q.=" where truyen_id='{$truyen_id}'";
					$db->query($q);
					echo json_encode(
						array(
							"status"=>"true",
							"name"=>"$name",
							"num_chap"=>"$num_chap",
							"truyen_id"=>"$truyen_id"
							//"q"=>"$q"
						)
					);		
					} else echo json_encode(
						array(
							"status"=>"false",
							"mess"=>"Truyện đã tồn tại"
						)
					);
				}
				else if($_POST['action']=='delete-some')
			    {
				$result=array();
				$sum=$_POST['sum'];
				for($i=0;$i<$sum;$i++)
				{
					
					$index='id_'.$i;
					$truyen_id=$_POST[$index];
					
						$q="select thumbnail from truyen where truyen_id='{$truyen_id}' ";
						$db->query($q);
						$tmp=$db->get();
						
						if($tmp['thumbnail']!=THUMB_DEFAULT)
    					{
    				    	@unlink('../'.$tmp['thumbnail']);
    					}
    					
    					$q="delete from truyen where truyen_id='{$truyen_id}'";
    					$db->query($q);
    					
    					$q="delete from theloai where truyen_id='{$truyen_id}'";
    					$db->query($q);
    					
    					$q="delete from noidung where truyen_id='{$truyen_id}'";
    					$db->query($q);
						
						$result[$i]=$truyen_id;
					
				}
				$result['sum']=$sum;
				echo json_encode($result);
			}
			}
			
			if (isset($_FILES['myfile'])) 
			{	
				$truyen_id=$_POST['truyen_id'];
				$type=$_POST['type'];
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
					'message' => '',
					'ratio' => 0,
					'url' => '', 
				);
				
				if ($fileError== 1) 
				{ //Lỗi vượt dung lượng
					$fileStatus['message'] = 'Dung lượng quá giới hạn cho phép';
				} elseif (!in_array($fileType, $mimes)) 
				{ //Kiểm tra định dạng file
					$fileStatus['message'] = 'Không cho phép định dạng này';
				} else 
				{ //Không có lỗi nào
					move_uploaded_file($_FILES['myfile']['tmp_name'], '../img_thumbnail/'.$fileName);
					$fileStatus['status'] = 1;
					$fileStatus['message'] = "upload $fileName thành công";
				  
					$url="img_thumbnail/".$fileName;
					$q="insert into image (img_name, url) values ('{$fileName}','{$url}')";
					$db->query($q);
					//-Check crop 
					$fileStatus['url']="../".$url;
					$fileStatus['ratio']=ratio($fileName,'../'.$url);
					$q= "UPDATE truyen SET thumbnail='{$url}' where truyen_id='{$truyen_id}'";
					$db->query($q);
					echo json_encode($fileStatus);
					
				}
			}
		}
	}
?>