$(document).ready(function(){
	cat_id="";
	$('#add').click(function(){
		$('.flag-taskbar').html('Thêm chuyên mục');
		reset_option(0);
		$('#name').removeAttr('value');
		$('.submit').attr(
		{
			value:"Thêm",
			id:"sub_add"
		});
		$('#fixed').show();
		$('#confirm').show();	
	})
	$('.table-manage').delegate('.edit','click',function(){
		
		$('.submit').attr(
		{
			value:"Sửa",
			id:"sub_edit"
		});
		$('#fixed').show();
		$('#confirm').show();
		cat_id=$(this).attr('cat_id');	
		ajax("edit",cat_id);
		return false;
	})
	/*Thoát khung thao tác*/
	$('.exit:eq(0)').click(function(){
		$('#fixed').hide();
		$('#confirm').hide();
		//console.log($('#new')[0][2]);
		$('#new')[0].reset();
	})

	$('.submit').click(function(){
		action=$(this).attr('id');
		name=$('#name').val();
		ajax(action,cat_id);
	return false;
	});
	$('.table-manage').delegate('.delete','click',function(){
	if(confirm("Chắc chắn xóa?")){
		cat_id=$(this).attr('cat_id');
		ajax("delete",cat_id);
		return false;
	}
	});
	/*-Xác nhận thông báo-*/
	$('.close').click(function(){
		$('#add_status').hide();	
		$('#fixed_notifi').hide();
	})
	
	function ajax(action,id)
	{
		if(action=="sub_add")
		{
			name=$('#name').val();
			pri=$('#priority').val();
			if(warning(name)==false)
			{
				return false;
			}
			data="cat_name="+name+"&pri="+pri+"&action=sub_add";
			data_type="json";
		}
		else if(action=="delete")
		{
			data="cat_id="+id+"&action=delete";
			data_type="json";
		}
	    else if(action=="edit")
		{
			data="cat_id="+id+"&action=edit";
			data_type="json";
			
		} 
		else if(action=="sub_edit")
		{
			name=$('#name').val();
			pri=$('#priority').val();
			if(warning(name)==false)
			{
				return false;
			}
			data="cat_name="+name+"&pri="+pri+"&cat_id="+id+"&action=sub_edit";
			data_type="json";
		}
		$.ajax({
		url:"solve_cat.php",
		async:true,
		type:"post",
		data:data,
		dataType:data_type,
		success: function(kq){
			
			if(action=="sub_add")
			{
				if(kq.status)
				{
					$('.table-manage').append('<tr><td>'+kq.cat_name+'</td><td>'+kq.priority+'</td><td>'+kq.user_name+'</td><td>'+kq.date+'</td><td><a href="#" class="edit" cat_id='+kq.cat_id+'><img src="../images/icon-edit.png" width="16px" height="16px" style="margin-right:5px;"/>Chỉnh sửa</a></td><td><a href="#" class="delete" cat_id='+kq.cat_id+'><img src="../images/remove-icon.png" width="16px" height="16px" style="margin-right:5px;"/>Xóa</a></td>');
				} 
				else
				{
					$('#fixed_notifi').show();
					$('#add_status').show();
					$('.mess').html("Chuyên mục đã tồn tại, vui lòng kiểm tra lại");
				} 
			} 
			else if(action=="delete")
			{
				$("a[cat_id='"+kq.cat_id+"']").closest("tr").hide();
			}
		    else if(action=="edit")
			{
				
				$('#name').attr('value',kq.cat_name);
				//$("option[value='"+kq.priority+"']").attr('selected','selected');
				$('.flag-taskbar').html('Sửa chuyên mục: '+kq.cat_name);
				reset_option(kq.priority);
			} 
			else if(action=="sub_edit")
			{
				if(kq.status==1)
				{
					$('#confirm img').click();
					$("a[cat_id='"+kq.cat_id+"']").closest("tr").find("td:eq(0)").html(kq.cat_name);
					$("a[cat_id='"+kq.cat_id+"']").closest("tr").find("td:eq(1)").html(kq.priority);	
				}
				else 
				{
					$('#fixed_notifi').show();
					$('#add_status').show();
					$('.mess').html("Chuyên mục đã tồn tại, vui lòng kiểm tra lại");

				} 
			}
		},
	});
	}
	
	function warning(name)
	{
		result= true;
		if(name=="")
		{
			 $('#name_error').html('<span class="warning">Thiếu tên</span>');
			 result=false;
		}
		else 
		{
			check=/[0-9\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/;
			//Không nhận kí tự đặc biệt- Nhận tiếng việt
			if(check.test(name))
			{
				$('#name_error').html('<span class="warning">Có ký tự đặc biệt</span>');
				result=false;
			}
			else
			{
				$('#name_error').html('');
			}
		}
		return result;
	}
	function reset_warning()
	{
		$('#name_empty').html('');	
	} 
	function priority(index)
	{
		if(index==0)
		{
			return "Không có";
		} else if(index==1)
		{
			return "Thấp";
		} else if(index==2)
		{
			return "Trung bình";
		} else if(index==3)
		{
			return "Cao";
		} else if(index==4)
		{
			return "Rất cao";
		} else return "Lỗi";
	}
	function reset_option(selected)
	{
		$('#priority').html('');
		$('#priority').append('<option value="'+selected+'">'+priority(selected)+'</option>');
		for(i=0;i<=4;i++)
		{
			if(i!=selected)
			{
				$('#priority').append('<option value="'+i+'">'+priority(i)+'</option>');
			}
		}
	}
});