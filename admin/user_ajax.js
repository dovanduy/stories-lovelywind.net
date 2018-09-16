$(document).ready(function(){
	/* Khởi tạo toàn cục */
	var http_arr = new Array();
	var user_id="";
	/*-Xác nhận thông báo-*/
	$('.close').click(function(){
		$('#add_status').hide();
		$('#fixed_notifi').hide();	
		$('.name_thumb').html('Chưa chọn ảnh');
		$('#new')[0].reset();
	})
	/*--Click thêm--*/
	$('#add').click(function(){
		$('#new legend').html('Thêm thành viên mới');
		$('option').removeAttr('selected');
		$('#user_name').removeAttr('value');
		$('.submit').attr(
		{
			value:"Thêm",
			id:"sub_add"
		});
		$('#fixed').show();
		$('#confirm').show();	
	})
	/*Click xóa */
	$('.table_manage').delegate('.delete','click',function(){
	if(confirm("Chắc chắn xóa?")){
		user_id=$(this).attr('user_id');
		ajax("delete",user_id);
		return false;
	}
	});
	/*--Edit--*/
	$('.table_manage').delegate('.edit','click',function(){
			$('.submit').attr(
			{
				value:"Lưu thay đổi",
				id:"sub_edit"
			});
			$('#fixed_detail').show();
			$('#detail').show();
			user_id=$(this).attr('user_id');	
			ajax("edit",user_id);
			return false;
	})
	/*Thoát khung thao tác*/
	$('.exit:eq(0)').click(function(){
		$('#fixed').hide();
		$('#confirm').hide();
		reset_warning();
		$('#new')[0].reset();
		$('.name_thumb').html('Chưa chọn ảnh');
		
	})
	
	$('.exit:eq(1)').click(function(){
		$('#fixed_detail').hide();
		$('#detail').hide();
		reset_warning();
		$('#manage')[0].reset();
		$('.name_thumb').html('Chưa chọn ảnh');
	})
	/*--Submit--*/
	$('.submit').click(function(){
		action=$(this).attr('id');//lấy id hành động submit
		ajax(action,user_id);//submit bằng ajax
	return false;
	});
	/*Thêm user bằng ajax với jquery*/
	function ajax(action,id)
	{
		if(action=="sub_add")
		{
			user_name=$('#user_name').val();
			email=$('#email').val();
			password=$('#password').val();
			confirm_password=$('#confirm_password').val();
			level=$('#level').val();
			
			if(warning(user_name,email,password,confirm_password)==false) //kiểm tra thông tin cần thiết
			{
				return false;
			}
			else
			{
			data="user_name="+user_name+"&email="+email+"&password="+password+"&level="+level+"&action=sub_add";//json data gửi đi
			data_type="json";//data type nhận về
			}
		} 
		else if(action=="delete")
		{
			data="user_id="+id+"&action=delete";
			data_type="json";
			
		}
		else if(action=="edit")
		{
			data="user_id="+id+"&action=edit";
			data_type="json";
		}
		else if(action=="sub_edit")
		{
			user_name=$('#edit_user_name').val();
			email=$('#edit_email').val();
			level=$('#edit_level').val();
			if(warning_edit(user_name,email)==false) //kiểm tra thông tin cần thiết
			{
				return false;
			}
			else
			{
			data="user_id="+id+"&user_name="+user_name+"&email="+email+"&level="+level+"&action=sub_edit";//json data gửi đi
			data_type="json";//data type nhận về
			console.log(data);
			}
		} 
		$.ajax({
		url:"solve_user.php",
		async:true,
		type:"post",
		data:data,
		dataType:data_type,
		success: function(kq){
			if(action=="sub_add")
			{
				if(kq.status!="false")
				{
					$('.table_manage').append('<tr><td>'+kq.user_name+'</td><td>'+kq.email+'</td><td>'+kq.level+'</td><td>'+kq.date+'</td><td><a href="#" class="edit" user_id="'+kq.user_id+'"><img src="../images/icon-edit.png" width="16px" height="16px" style="margin-right:5px;"/>Chi tiết</a></td><td><a href="#" class="delete" user_id="'+kq.user_id+'"><img src="../images/remove-icon.png" width="16px" height="16px" style="margin-right:5px;"/>Xóa</a></td>');
					var files;
					files = document.getElementById('thumbnail').files;					
					if(files.length==0) 
					{
						$('#fixed_notifi').show();	
						$('#add_status').show();
						$('.mess').html("Thêm thành công");
					} else
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.close').hide();
						$('.mess').html('Vui lòng đợi, đang xử lý...');
						upload(files[0],0,0,kq.user_id,"thumbnail");
					}
				} else 
				{
					alert("Email đã tồn tại, vui lòng kiểm tra lại");
				}
			} 
			else if(action=="delete")
			{
				//$('#add_status').show();
				//$('.mess').html(kq.mess);
				$("a[user_id='"+kq.user_id+"']").closest("tr").hide();
			}
			else if(action=="edit")
			{
				$('#topic').html('<h2 style="text-align:center; margin-bottom:10px; color:#FFF;">'+kq.user_name+'</h2>');
				$('#show_avatar').html('<img src="../'+kq.avatar+'" width="94%" style="float:right"/>');
				$('#edit_user_name').attr('value',kq.user_name);
				$('#edit_email').attr('value',kq.email);
				reset_level(kq.level);
				$('#info_level').attr('value',kq.level)
				$('#info_date').html(kq.date);
				$('#info_datechange').html(kq.date_change);
			}
			else if(action=="sub_edit")
			{
				console.log(kq.status);
				if(kq.status=="true")
				{
					var files;
					files = document.getElementById('edit_thumbnail').files;
					upload(files[0],0,0,kq.user_id,"edit_thumbnail");	
					if(files.length==0) 
					{
						$('.exit').eq(1).click();
					} else
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.close').hide();
						$('.mess').html('Vui lòng đợi, đang xử lý...');
						upload(files[0],0,0,kq.user_id,"edit_thumbnail");
					}								
					$("a[user_id='"+kq.user_id+"']").closest("tr").find("td:eq(0)").html(kq.user_name);	
					$("a[user_id='"+kq.user_id+"']").closest("tr").find("td:eq(1)").html(kq.email);
					$("a[user_id='"+kq.user_id+"']").closest("tr").find("td:eq(2)").html(kq.level);
				}
				else
				{
					alert("Email đã tồn tại, vui lòng kiểm tra lại");
				}
			}
		},
	});
	}
	
	function upload(file,index,maxl,id,type)
	{
			var http = new XMLHttpRequest();
			http_arr.push(http);
			var data = new FormData();
			data.append('user_id',id);
			data.append('myfile', file);
			http.open('POST', 'solve_user.php', true);
			http.send(data);
	 
			//Nhận dữ liệu trả về
		
			http.onreadystatechange = function() 
			{
			//Kiểm tra điều kiện
				if (http.readyState == 4 && http.status == 200) 
				{
						console.log(http.responseText);
						var server = JSON.parse(http.responseText);
						if (server.status==1) //Thanh cong
						{
							if(type=="thumbnail" || type=="content")
							{
								if(index==maxl)
								{
									$('.close').show();
									$('.mess').html('Thêm thành công');
								}
							} 
							else
							{
								$('#fixed_notifi').hide();
								$('#add_status').hide();
								$('.exit').eq(1).click();
							}
						}
						else if(server.status==0)//That bai
						{
							alert(server.message);
						}
				}
			}
	} 
	
	function warning(user_name,email,password,confirm_password)
	{
		result=true;
				
		if(user_name=="")
		{
			 $('#user_name_error').html('<span class="warning">Thiếu tên</span>');
			 result=false;
		}
		else 
		{
			check_user_name=/[0-9\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/;
			//Không nhận kí tự đặc biệt- Nhận tiếng việt
			if(check_user_name.test(user_name))
			{
				$('#user_name_error').html('<span class="warning">Có ký tự đặc biệt</span>');
				result=false;
			}
			else
			{
				$('#user_name_error').html('');
			}
		}
		if(email=="") 
		{
			$('#email_error').html('<span class="warning">Thiếu email</span>');
			result=false;
		}
		else 
		{
			check_mail=/^[0-9A-Za-z]+[0-9A-Za-z_]*@[\w\d.]+.\w{2,4}$/;
       	    if(!check_mail.test(email))
			{
				$('#email_error').html('<span class="warning">Định dạng email sai</span>');
				result=false;
			}
			else
			{
				$('#email_error').html('');
			}	
		}
		if(password=="") 
		{
			$('#password_error').html('<span class="warning">Thiếu mật khẩu</span>');
			result=false;
		}
		else
		{
			 if(password.length<6)
			 {
				 $('#password_error').html('<span class="warning">Mật khẩu quá ngắn</span>');
				 result=false;
			 }
			 else
			 {
				 check_pass=/[0-9a-zA-Z_]/;
				 if(!check_pass.test(password))
				 {
					$('#password_error').html('<span class="warning">Có ký tự đặc biệt</span>');
					result=false;
				 } 
				 else
				 {
					 $('#password_error').html('');
				 }
			 }
		}
		if(password!=confirm_password)
		{
			$('#confirm_password_error').html('<span class="warning">Mật khẩu không khớp</span>');
			result = false;
		} 
		else 
		{
			$('#confirm_password_error').html('');
		}
		return result;
	}
	function reset_warning()
	{
		$('#user_name_error').html('');	
		$('#email_error').html('');
		$('#password_error').html('');
		$('#confirm_password_error').html('');
		$('#edit_email_error').html('');
		$('#edit_user_name_error').html('');
	} 
	
	function warning_edit(user_name,email)
	{
		result=true;
				
		if(user_name=="")
		{
			 $('#edit_user_name_error').html('<span class="warning">Thiếu tên</span>');
			 result=false;
		}
		else 
		{
			check_user_name=/[0-9\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/;
			//Không nhận kí tự đặc biệt- Nhận tiếng việt
			if(check_user_name.test(user_name))
			{
				$('#edit_user_name_error').html('<span class="warning">Có ký tự đặc biệt</span>');
				result=false;
			}
			else
			{
				$('#edit_user_name_error').html('');
			}
		}
		if(email=="") 
		{
			$('#edit_email_error').html('<span class="warning">Thiếu email</span>');
			result=false;
		}
		else 
		{
			check_mail=/^[0-9A-Za-z]+[0-9A-Za-z_]*@[\w\d.]+.\w{2,4}$/;
       	    if(!check_mail.test(email))
			{
				$('#edit_email_error').html('<span class="warning">Định dạng email sai</span>');
				result=false;
			}
			else
			{
				$('#edit_email_error').html('');
			}	
		}

		return result;
	}
	
	
	$('#open_editthumb').click(function(){
		$('#edit_thumbnail').click();	
	})
	$('#open_thumb').click(function(){
		$('#thumbnail').click();	
	})
	
	function quote(str)
	{
		if(str.length>=20)
		{
			tmp=str.substr(0,8)+"..."+str.substr(-8,8);
			return tmp;
		} else return str;
	}
	function selectFiles(event)
    {
 	 	var files = event.target.files;
 		$('.name_thumb').html(quote(files[0].name));
	}

// Lắng nghe sự kiện `change` của input và gọi hàm `handleFileSelect` để xử lý
	document.getElementById("thumbnail").addEventListener('change', selectFiles, false);
	document.getElementById("edit_thumbnail").addEventListener('change', selectFiles, false);
	
	
	//----------------------------
	function find_level(index)
	{
		if(index==0)
		{
			return "Đang chờ duyệt";
		} else if(index==1)
		{
			return "Thành viên";
		} else if(index==2)
		{
			return "Quản trị viên";
		}  
		else return "Lỗi";
	}
	function reset_level(selected)
	{
		if(selected==3)
		{
			$('#info_level').html('');
			$('#info_level').html('Người thành lập');	
		}
		else
		{
			$('#info_level').html('<select id="edit_level"></select>');
			$('#edit_level').html('');
			$('#edit_level').append('<option value="'+selected+'">'+find_level(selected)+'</option>');
			for(i=0;i<=2;i++)
			{
				if(i!=selected)
				{
					$('#edit_level').append('<option value="'+i+'">'+find_level(i)+'</option>');
				}
			}
		}
	}
}); 
