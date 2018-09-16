<?php 
	/*OOP*/
	class database
	{
		private $hostname="localhost";
		private $userhost="root";
		private $passhost="";
		private $db_name="lovelywi_truyen";
		private $dbc=NULL;
		private $result=NULL;
		public $error=NULL;
		
		public function setvalue($hname,$user,$pass,$dbname)
		{
			$this->hostname=$hname;
			$this->userhost=$user;
			$this->passhost=$pass;
			$this->db_name=$dbname;
		}
		public function connect()
		{
			$this->dbc=mysqli_connect($this->hostname,$this->userhost,$this->passhost,$this->db_name);
			if($this->dbc)
			{
				mysqli_set_charset($this->dbc,'utf-8');
			}
		}
		public function disconnect()
		{
			if($this->dbc)
			{
				mysqli_close($this->dbc);
			}
		}
		public function query($query)
		{
			$this->result=mysqli_query($this->dbc,$query);
			if(mysqli_error($this->dbc)) $this->error = mysqli_error($this->dbc);
		}
		public function num_rows()
		{
			if($this->result)
			{
				$rows=mysqli_num_rows($this->result);
			} else
			{
				$rows=NULL;
			}
			return $rows;
		}
		public function get()
		{
			if($this->result)
			{
				$data=mysqli_fetch_assoc($this->result);
			}
			else $data=NULL;
			return $data;
		}
	}
?>