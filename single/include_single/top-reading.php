<?php 
	if(isset($_GET['id']))
	{ 	
		echo '<div id="type" val="reading"></div>';
		$id=$_GET['id'];
		$q="select * from noidung join truyen using(truyen_id) where content_id='{$id}' limit 1";
		$db->query($q);
		if($db->num_rows()==1)
		{
			$now=$db->get();
			$tmp='chuong-'.$now['chap'].season($now['part']);
			
			$url_truyen=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$now['truyen_id'].'.html';
			$url_content=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$id.'.html';
			
			//---Lấy 2 chương liền kề
			$check_id=$now['truyen_id'];
			$check_chap=$now['chap'];
			$check_part=$now['part'];
			$q="select * from noidung where truyen_id='{$check_id}' and chap='{$check_chap}'";
			$db->query($q);
			if($db->num_rows()>1)//-- Chương có nhiều phần
			{
				while($tmp=$db->get())
				{
					$part_max=$tmp['part'];
				}//lấy phần lớn nhất
				
				if($check_part>=2)//--đang không phải phần đầu
				{
					$part_pre=$check_part-1;
					$chap_pre=$check_chap;
					if($check_part<$part_max)//--đang không phải phần cuối
					{
						$part_next=$check_part+1;
						$chap_next=$check_chap;
					}
					else//--đang phần cuối
					{
						$chap_next=$check_chap+1;
						$part_next=0;//--tạm cho chương tới không có phần
					}
				}
				else//--đang là phần đầu
				{
					$part_next=$check_part+1;
					$chap_next=$check_chap;
					$part_pre=0;
					$chap_pre=$check_chap-1;
				}
			} else //--chương chỉ một phần
			{
				$chap_next=$check_chap+1;
				$chap_pre=$check_chap-1;
				$part_pre=0;
				$part_next=0;
			}
			
			if($part_next==0)//-chuyển qua chương kế
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_next}' order by part asc limit 1";
				$db->query($q);
				$next=$db->get();
			}
			else //-chuyển qua phần kế
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_next}' and part='{$part_next}'";
				$db->query($q);
				$next=$db->get();
			}
			
			if($part_pre==0)//-chuyển qua chương trước
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_pre}' order by part desc limit 1";
				$db->query($q);
				$pre=$db->get();
			}
			else //-chuyển qua phần trước
			{
				$q="select * from noidung where truyen_id='{$check_id}' and chap='{$chap_pre}' and part='{$part_pre}'";
				$db->query($q);
				$pre=$db->get();
			}
			
			if($next['part']!=0)
			{
				$tmp='-'.$next['part'];
				$tmp='chuong-'.$next['chap'].$tmp;
			} else 
			{
				$tmp='chuong-'.$next['chap'];
			}
			$url_next=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$next['content_id'].'.html';
			
			if($pre['part']!=0)
			{
				$tmp='-'.$pre['part'];
				$tmp='chuong-'.$pre['chap'].$tmp;
			} else 
			{
				$tmp='chuong-'.$pre['chap'];
			}
			$url_pre=BASE_URL.strtolower(unicode_convert($now['name'])).'/'.$tmp.'/'.$pre['content_id'].'.html';
			
			$q="select count(truyen_id) as num from noidung where truyen_id='{$check_id}'";
			$db->query($q);
			$tmp=$db->get();
			$num_content=$tmp['num'];	
			
			$q="select count(truyen_id) as num from noidung where truyen_id='{$check_id}' and ";
			$q.=" (chap < '{$check_chap}' or (chap = '{$check_chap}' and part<'{$check_part}' ))";
			$db->query($q);
			$tmp=$db->get();
			$now_location=$tmp['num']+1;	
		}
		else redirect_to('','','');
	}
	else
	{
		redirect_to('','','');
	}
?>
<body>
	<?php 
		include('../include/public-template/top-public.php');
	?>