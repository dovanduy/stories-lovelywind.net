$(document).ready(function(e) {
	var http_arr = new Array();
    var id=$('#get_info').attr('u_id');
	
	$('.show-profile').eq(0).show();
	var turn = 0;
	$('.u-change').click(function(){
		var flag='', action='';
		turn++;
		{
			if(turn==1) 
			{
				flag='Đổi thông tin';
				action='change-pass';
			}
			else 
			{
				flag='Đổi mật khẩu';
				action='change-info';	
			}
			$('.show-profile').hide();
			$('.show-profile').eq(turn).show();
			$('.u-change-flag').html(flag);
			$('.u-profile').attr('action',action);
			if(turn>=1) turn=-1;	
		}
	})
	
	$('.u-profile').click(function(){
		var action=$(this).attr('action');
		ajax(action,id);	
		return false;
	})
	$('input').click(function(){
		$('.warning').html('');	
	})
	$('.show-avatar').click(function(){
		$('#up-avatar').click();
	})
	$('.exit:eq(0)').click(function(){
		$('#fixed-thumb').hide();
		$('#manage_thumb').hide();
		//ajax('remove',id);
	})
	$('.close').click(function(){
		$('#add_status').hide();
		$('#fixed_notifi').hide();	
	})
	$('#up-avatar').change( function(event) {
		$('#fixed_notifi').show();
		$('#add_status').show();
		$('.close').hide();
		$('.mess').html('Vui lòng đợi...');
		upload(event.target.files[0],'tmp',id);
	});
	function upload(file,type,id)
	{
		var http = new XMLHttpRequest();
		http_arr.push(http);
		var data = new FormData();
		data.append('id',id);
		data.append('type',type);
		data.append('myfile', file);
		http.open('POST', 'solve_index.php', true);
		http.send(data);
 
		//Nhận dữ liệu trả về
	
		http.onreadystatechange = function() 
		{
		//Kiểm tra điều kiện
			if (http.readyState == 4 && http.status == 200) 
			{
				//console.log(http.responseText);
				var server = JSON.parse(http.responseText);
				if (server.status==1) //Thanh cong
				{
						$('.close').show();
						$('#fixed_notifi').hide();
						$('#add_status').hide();
						get_thumb(server.src, server.ratio, id);
				}
				else if(server.status==0)//That bai
				{
					$('.close').show();
					$('.mess').html(server.message);
				}
			}
		}
	}
	function ajax(action,id)
	{
		if(action=="get_avt")
		{
			var data="action=get_avt&id="+id;
			var data_type="json";
		}
		else if(action=='change-info')
		{
			var name=$('#name').val();
			var email=$('#email').val();
			if(!warning('info',name,email,'','','')) return false;
			var data="action=change-info&name="+name+"&email="+email+"&id="+id;
			var data_type="json";
			
		}
		else if(action=='change-pass')
		{
			var o_pass=$('#o-pass').val();
			var n_pass=$('#n-pass').val();
			var c_pass=$('#c-pass').val();
			
			if(!warning('pass','','',o_pass,n_pass,c_pass)) return false;
			
			var data="action=change-pass&o-pass="+o_pass+"&n-pass="+n_pass+"&id="+id;
			var data_type="json";
		}
		$.ajax({
		url:"solve_index.php",
		async:true,
		type:"post",
		data:data,
		dataType:data_type,
		success: function(kq){
				if(action=='get_avt')
				{
					$('#thumb_curr').html('<img src="../'+kq.avt+'" />');
				}
				else if(action=='change-info')
				{
					if(kq.status==1)
					{
						window.location.reload();
					}
					else
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.mess').html('Email đã tồn tại');
					}
				}
				else if(action=='change-pass')
				{
					if(kq.status==0)
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.mess').html('Mật khẩu không chính xác! Xin kiểm tra lại');
					}
					else(kq.status==1)
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.mess').html('Đổi mật khẩu thành công!');
					}
				}
			}
		})
	}
	
	
	function get_thumb(src, ratio, id)
	{
		ajax('get_avt',id);
		if(ratio>1) ratio= 'width-frame';
		else ratio= 'height-frame';
		
		$('#type-frame').attr('class',ratio);
		if(ratio == 'height-frame') $('#limit').css('height','100%');
		
		$('#img-need-crop').html('<img src="'+src+'"/>');
		$('.fixed-full').show();
		$('#manage_thumb').show();
		$('.flag-taskbar').html('Đổi avatar');
		$('#crop').attr('get_id', id);
	}
	
	function warning(type, user_name ,email, o_pass, n_pass , c_pass)
	{
		result=true;
		if(type=='info')
		{		
			if(user_name=="")
			{
				 $('#name-error').html('Thiếu tên');
				 result=false;
			}
			else 
			{
				check_user_name=/[\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/;
				//Không nhận kí tự đặc biệt- Nhận tiếng việt
				if(check_user_name.test(user_name))
				{
					$('#name-error').html('Có ký tự đặc biệt');
					result=false;
				}
				else
				{
					$('#name-error').html('');
				}
			}
			//-------------------
			if(email=="") 
			{
				$('#email-error').html('Thiếu email');
				result=false;
			}
			else 
			{
				check_mail=/^[0-9A-Za-z]+[0-9A-Za-z_]*@[\w\d.]+.\w{2,4}$/;
				if(!check_mail.test(email))
				{
					$('#email-error').html('Định dạng email sai');
					result=false;
				}
				else
				{
					$('#email-error').html('');
				}	
			}
		}
		else if(type=='pass')
		{
			if(n_pass=="") 
			{
				 $('#n-pass-error').html('Mật khẩu trống');
				 result=false;
			}
			else
			{
				 if(n_pass.length<6)
				 {
					 $('#n-pass-error').html('Mật khẩu quá ngắn');
					 return false;
				 }
				 else
				 {
					 check_pass=/[^a-zA-Z0-9_]/;
					 if(check_pass.test(n_pass))
					 {
						 $('#n-pass-error').html('Mật khẩu không hợp lệ');
						 return false;
					 } 
					 else
					 {
						 $('#n-pass-error').html('');
					 }
				 }
				
			}
			//-----
			if(n_pass!=c_pass)
			{
				$('#c-pass-error').html('Mật khẩu không khớp');
				return false;
			} 
			else 
			{
				$('#c-pass-error').html('');
			}	
		}
		return result;
	}
});