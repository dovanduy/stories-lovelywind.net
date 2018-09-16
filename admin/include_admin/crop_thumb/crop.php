<?php 
	include("../../../include/function.php");
	$db = new database;
	$db->connect();	
	
	if(isset($_POST['id']) && isset($_POST['action']))
	{
		$id=$_POST['id'];
		if($_POST['action']=="crop")
		{
			$type=$_POST['type'];
			$ratio_w=$_POST['ratio_w'];
			$ratio_h=$_POST['ratio_h'];
			$ratio_x=$_POST['ratio_x'];
			$ratio_y=$_POST['ratio_y'];
			
			if($type=='avatar')
			{
				$user_id=$_POST['id'];
				$q="select avatar_tmp, user_name ,avatar from user where user_id='{$user_id}' limit 1";
				$db->query($q);
				$tmp=$db->get();
				if($tmp['avatar_tmp']!='0')
				{
					$src='../../../'.$tmp['avatar_tmp'];
					$new_image= crop_img($src,240,240,$ratio_w,$ratio_h,$ratio_x,$ratio_y);	
					
					$ext = strtolower(substr($tmp['avatar_tmp'],strrpos($tmp['avatar_tmp'],'.')));
					$code=date('YmdHis');
					$name=$code.'_'.unicode_convert($tmp['user_name']).$ext;
					$new_loc='../../../img_avatar/'.$name;
					if(save_img($new_loc,$new_image) == true)// không có lỗi
					{
						$src='img_avatar/'.$name;
						$q="update user set avatar='{$src}' where user_id='{$user_id}' limit 1";
						$db->query($q);
						if($tmp['avatar']!=AVATAR_DEFAULT)
						{
							@unlink('../../../'.$tmp['avatar']);
						}
						echo json_encode(
							array(
								'status'=>1,
								'url'=>$src
							)
						);	
					}
				}
			}
			else if($type=='thumbnail' || $type=='e-thumbnail')
			{
				$q="select thumbnail, name from truyen where truyen_id={$id} ";
				$db->query($q);
				$tmp=$db->get();
				$src='../../../'.$tmp['thumbnail'];
				$file_ext=strstr($tmp['thumbnail'],'.');
				
				$new_image= crop_img($src,200,300,$ratio_w,$ratio_h,$ratio_x,$ratio_y);	
				$code=date('YmdHis');
				$thumb_name=unicode_convert($tmp['name']).'_'.$code.$file_ext;
				$new_loc='../../../img_thumbnail/'.$thumb_name;
				if(save_img($new_loc,$new_image) == true)// không có lỗi
				{
					@unlink($src);
					
					$url='img_thumbnail/'.$thumb_name;
					
					$q= "UPDATE truyen SET thumbnail='{$url}' where truyen_id='{$id}'";
					$db->query($q);
					echo json_encode(
						array(
							"status" => "1",
							"url" => "$url"
						)
					);
				} 
				else
				{
					echo json_encode(
						array(
							"status" => "0",
							"q" => "$new_loc"							
						)
					);
				}
			}
		}
		else if($_POST['action']=="get_thumb")
		{
			$q="select * from thumbnail where img_id = {$id} limit 1";
			$db->query($q);
			if($db->num_rows()==1)
			{
				$tmp=$db->get();
				$url=$tmp['url'];
				echo json_encode(
					array(
						"status"=>1,
						"url"=>"$url",
						"q"=>"$q"
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
?>