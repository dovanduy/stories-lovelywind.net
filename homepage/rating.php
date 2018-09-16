<?php 
		if(isset($_POST['truyen_id']) && isset($_POST['value']))
		{
			$truyen_id=$_POST['truyen_id'];
			if(!isset($_COOKIE['like'.$truyen_id]) && $_POST['value']<=100000)
			{
				include('../include/function.php');
				$db = new database;
				$db->connect();
				$q="update truyen set like_truyen=like_truyen + 1 where truyen_id='{$truyen_id}'";
				$db->query($q);
				setcookie('like'.$truyen_id, 'like' , time() + 7200);
				echo $_POST['value']+1;
			}
			else echo $_POST['value'];
		} else echo $_POST['value'];
?>