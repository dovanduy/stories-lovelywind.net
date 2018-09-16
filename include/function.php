<?php	
	include('database-class.php');
	include('class.smtp.php');
    include('class.phpmailer.php'); 
	define('BASE_URL', 'http://localhost/truyen/');
	define('AVATAR_DEFAULT', 'img_avatar/no-avatar.png');
	define('THUMB_DEFAULT', 'img_thumbnail/df.jpg');
	define('SL_footer', 'Copyright © 2017 Lovelywind.net. All Rights Reserved. 
            Nội dung được chia sẻ miễn phí giữa các thành viên.');
	/*Hàm tái định hướng người dùng tổng quát*/
	function redirect_to( $web='',$id='', $get='')
	{
		if($id != '') { $id='?'.$id.'='; }
		$url=$web.$id.$get;
		header("Location: $url");
		exit();
	}
	/*----Lấy url hiện tại----*/
	function getCurrentPageURL() 
	{
		$pageURL = 'http';
	 
		if (!empty($_SERVER['HTTPS'])) {
		  if ($_SERVER['HTTPS'] == 'on') {
			$pageURL .= "s";
		  }
		}
	 
		$pageURL .= "://";
	 
		if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
		  $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
	 
		return $pageURL;
    }
	/*Hàm định dạng xuống dòng*/
	function format_content ($text)
	{
		$safe_text= htmlentities($text, ENT_COMPAT , 'UTF-8');
		return str_replace(array("\r\n","\n"), array("<p>","</p>"), $safe_text);
	}
	/*--Bỏ dấu--*/
	function unicode_convert($str)
	{

  	    if(!$str) return false;

  	    $unicode = array(

        		'a'=>array('á','à','ả','ã','ạ','ă','ắ','ặ','ằ','ẳ','ẵ','â','ấ','ầ','ẩ','ẫ','ậ'),

  	            'A'=>array('Á','À','Ả','Ã','Ạ','Ă','Ắ','Ặ','Ằ','Ẳ','Ẵ','Â','Ấ','Ầ','Ẩ','Ẫ','Ậ'),

  	            'd'=>array('đ'),

  	            'D'=>array('Đ'),

  	            'e'=>array('é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ'),

  	            'E'=>array('É','È','Ẻ','Ẽ','Ẹ','Ê','Ế','Ề','Ể','Ễ','Ệ'),

  	            'i'=>array('í','ì','ỉ','ĩ','ị'),

  	            'I'=>array('Í','Ì','Ỉ','Ĩ','Ị'),

  	            'o'=>array('ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ'),

  	            '0'=>array('Ó','Ò','Ỏ','Õ','Ọ','Ô','Ố','Ồ','Ổ','Ỗ','Ộ','Ơ','Ớ','Ờ','Ở','Ỡ','Ợ'),

  	            'u'=>array('ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự'),

  	            'U'=>array('Ú','Ù','Ủ','Ũ','Ụ','Ư','Ứ','Ừ','Ử','Ữ','Ự'),

  	            'y'=>array('ý','ỳ','ỷ','ỹ','ỵ'),

  	            'Y'=>array('Ý','Ỳ','Ỷ','Ỹ','Ỵ'),

  	            '-'=>array(' ','&quot;','.','?',':','/'),

				''=>array('(',')',',')

  	        );



  	        foreach($unicode as $nonUnicode=>$uni){

  	        	foreach($uni as $value)

            	$str = str_replace($value,$nonUnicode,$str);

  	        }

    	return $str;

  	}
	
	/*Hàm trích dẫn từ $text ra $num_words chữ cái, được làm tròn chữ kề cuối*/
	function  quote_content ($text, $num_words)
	{
		if(strlen($text)>$num_words)
		{
			$quote=substr($text,0,$num_words);//trích dẫn $num_words chữ cái
			$num_words=strrpos($quote,' ');//tìm vị trí làm tròn chữ kề cuối
			$quote=substr($text,0,$num_words)."...";// trích dẫn thực làm tròn
			return $quote;
		} else return $text;
	}
	/*Hàm trích dẫn từ $text ra $num_words chữ cái, được làm tròn câu kề cuối*/
	function  quote_paragraph($text, $num_words)
	{
		if(strlen($text)>$num_words)
		{
			$quote=substr($text,0,$num_words);//trích dẫn $num_words chữ cái
			$num_words=strrpos($quote,'</p>');//tìm vị trí làm tròn câu cuối
			$quote=substr($text,0,$num_words);// trích dẫn thực làm tròn
			return $quote;
		} else return $text;
	}
	/*Giữ dịnh dạng đoạn văn*/
	function format_string($str)
	{
		$tmp=htmlentities($str, ENT_COMPAT, 'UTF-8');
		return str_replace(array("\r\n","\n"),array("<p>","</p>"),$tmp);
	}
	/*-Translate- số->chữ*/
	function translate($type, $code)
	{
		if($type=='status')
		{
			if($code==0) return 'Đang tiến hành';
			else if($code==1) return 'Đã hoàn thành';
			else if($code==2) return 'Đã dừng';
			else return 'Lỗi';
		}
		else if($type=='season')
		{
			if($code==0) return '';
			else if($code==1) return ' I';
			else if($code==2) return ' II';
			else return $code;
		}
		else if($type=='country')
		{
			if($code==0) return 'việt nam';
			else if($code==1) return 'trung quốc';
			else if($code==2) return 'nhật bản';
			else if($code==3) return 'phương tây';
			else return false;
		}
		else
		{
			return false;
		}
	}
	/*Kết nối phần*/
	function season($season)
	{
		if($season==0) return "";
		else return "-".$season;
	}
	/*-Decode- chữ->số*/
	function decode($type,$string)
	{
		$code=-1;
		if($type=='country')
		{
			if($string=='viet-nam') $code = 0;
			if($string=='trung-quoc') $code = 1;
			if($string=='nhat-ban') $code = 2;
			if($string=='phuong-tay') $code = 3;
		}
		else if($type=='list')
		{
			if($string=='yeu-thich') $code = 0;
			if($string=='xem-nhieu') $code = 1;
			if($string=='hoan-thanh') $code = 2;
			if($string=='truyen-moi') $code = 3;
		}
		else if($type=='status')
		{
			if($string=='hoan-thanh') $code = 1;
			if($string=='dang-cap-nhat') $code = 0;
			if($string=='tat-ca') $code = 2;
		}
		return $code;
	}
	/*-Encode- chữ->chữ*/
	function encode($type,$code)
	{
		$string= false;
		if($type=='country')
		{
			if($code=='viet-nam') $string = 'việt nam';
			if($code=='trung-quoc') $string = 'trung quốc';
			if($code=='nhat-ban') $string = 'nhật bản';
			if($code=='phuong-tay') $string = 'phương tây';
		}
		else if($type=='list')
		{
			if($code=='yeu-thich') $string = 'yêu thích';
			if($code=='xem-nhieu') $string = 'xem nhiều';
			if($code=='hoan-thanh') $string = 'hoàn thành';
			if($code=='truyen-moi') $string = 'mới cập nhật';
		}
		return $string;
	}
	/*Hàm preg_match*/
	function  check ($type, $string)
	{
		if($type=='email')
		{
			$check='/^[0-9A-Za-z]+[0-9A-Za-z_]*@[\w\d.]+.\w{2,4}$/';
		} 
		else if ($type=='password')
		{
			 $check='/^[0-9a-zA-Z_]$/';
		}
		else if ($type=='name')
		{
			 $check='/[0-9\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/';
		}
		else
		{
			return false;
		}
		return(preg_match($check,$string,$matches));
	}
	/*Xóa dấu '/' */
	function solve_get_string($str)
	{
		$s=str_replace('/','',$str);
		return $s;
	}
	/*---Filter--*/
	function filter($cat,$num_cat)
	{
		$db = new database;
		$db->connect();
		//--Init--
		$select="select t.truyen_id from truyen as t join theloai as th using(truyen_id)";
		$select.=" join categories as c using(cat_id) where c.cat_id= "; 
		$q=$select.$cat[0];
		$db->query($q);
		if($db->num_rows()==0) return NULL;
		else
		{
			$length=$db->num_rows();
			for($i=0;$i<$length;$i++)
			{
				$tmp=$db->get();
				$result[$i]=$tmp['truyen_id'];
			}	
		}
		//Solve
		for($j=1;$j<$num_cat;$j++)
		{
			$q=$select.$cat[$j];
			$db->query($q);
			if($db->num_rows()!=0)
			{
				$length=$db->num_rows();
				for($i=0;$i<$length;$i++)
				{
					$tmp=$db->get();
					$new[$i]=$tmp['truyen_id'];
				}
				$result=array_intersect($new,$result);
			} else return NULL;
		}
		return $result;
	}
	/*Lấy tỉ lệ ảnh*/
	function ratio($name, $url)
	{
		if($name=="" || $url=="") return 0;
		$file_ext=strstr($name,'.');
		$tmp_image=$url;
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
		if (!$image) return 0;
		return(round(@imagesy($image) / @imagesx($image) ,2));
	}
	/*Lay anh*/
	function get_img($name, $url)
	{
		$file_ext=strstr($name,'.');
		$tmp_image=$url;
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
		return $image;
	}
	/*Trả về ảnh*/
	function image($name, $url)
	{
		$file_ext=strstr($name,'.');
		$tmp_image=$url;
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
		return $image;
	}
	//Resize image
	function crop_img($src, $height, $width, $ratio_w, $ratio_h, $ratio_x, $ratio_y)
	{
		$extension = strtolower(substr($src,strrpos($src,'.')));
		if (in_array($extension,array('.jpg','.jpeg'))) { $image = @imagecreatefromjpeg($src); }
		elseif ($extension == '.png') { $image = @imagecreatefrompng($src); }
		elseif ($extension == '.gif') { $image = @imagecreatefromgif($src); }
		elseif ($extension == '.bmp') { $image = @imagecreatefromwbmp($src); }
		
		$curr_w= imagesx($image)*$ratio_w;
		$curr_h= imagesy($image)*$ratio_h;
		$x=imagesx($image)*$ratio_x;
		$y=imagesy($image)*$ratio_y;
		
		//Tạo ảnh mới height - width
		$new_image = imagecreatetruecolor ($height,$width);
		imagecopyresampled($new_image,$image, 0, 0, $x, $y, $height, $width, $curr_w, $curr_h);
		
		return $new_image;
	}
	function resize_image_max($src,$max_width,$max_height)
	{
	    $extension = strtolower(substr($src,strrpos($src,'.')));
		if (in_array($extension,array('.jpg','.jpeg'))) { $image = @imagecreatefromjpeg($src); }
		elseif ($extension == '.png') { $image = @imagecreatefrompng($src); }
		elseif ($extension == '.gif') { $image = @imagecreatefromgif($src); }
		elseif ($extension == '.bmp') { $image = @imagecreatefromwbmp($src); }
	    
        $w = imagesx($image); //current width
        $h = imagesy($image); //current height
        if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
     
        //try max width first...
        $ratio = $max_width / $w;
        $new_w = $max_width;
        $new_h = $h * $ratio;
     
        //if that didn't work
        if ($new_h > $max_height) {
            $ratio = $max_height / $h;
            $new_h = $max_height;
            $new_w = $w * $ratio;
        }
     
        $new_image = imagecreatetruecolor ($new_w, $new_h);
        imagecopyresampled($new_image,$image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);
        return $new_image;
    }
	//Save img
	function save_img($new_loc, $new_image)
	{
		$extension = strtolower(substr($new_loc,strrpos($new_loc,'.')));
		$save = true;
		if (in_array($extension,array('.jpg','.jpeg'))) { imagejpeg($new_image,$new_loc) or ($save = false); }
		elseif ($extension == '.png') { imagepng($new_image,$new_loc) or ($save = false); }
		elseif ($extension == '.gif') { imagegif($new_image,$new_loc) or ($save = false); }
		elseif ($extension == '.bmp') { imagewbmp($new_image,$new_loc) or ($save = false); }
		return $save;
	}
	//Send mail
	function sendMail($title, $content, $nTo, $mTo, $diachicc='')
	{
		$nFrom = 'Lovelywind.com';
		$mFrom = 'dtphuoc97@gmail.com';  //dia chi email cua ban 
		$mPass = 'trinhgia64';       //mat khau email cua ban
		$mail             = new PHPMailer();
		$body             = $content;
		$mail->IsSMTP(); 
		$mail->CharSet   = "utf-8";
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPAuth   = true;                    // enable SMTP authentication
		$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$mail->Host       = "smtp.gmail.com";        
		$mail->Port       = 465;
		$mail->Username   = $mFrom;  // GMAIL username
		$mail->Password   = $mPass;               // GMAIL password
		$mail->SetFrom($mFrom, $nFrom);
		//chuyen chuoi thanh mang
		$ccmail = explode(',', $diachicc);
		$ccmail = array_filter($ccmail);
		if(!empty($ccmail)){
			foreach ($ccmail as $k => $v) {
				$mail->AddCC($v);
			}
		}
		$mail->Subject    = $title;
		$mail->MsgHTML($body);
		$address = $mTo;
		$mail->AddAddress($address, $nTo);
		$mail->AddReplyTo('dtphuoc97@gmail.com', 'Lovelywind.com');
		if(!$mail->Send()) {
			echo $mail->ErrorInfo;
		} else {
			echo 1;
		}
	}
	function echo_errors() {}
?>