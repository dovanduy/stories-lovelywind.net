$(document).ready(function(){
	content_id="";
	truyen_id=$('#get_id').attr("value");
	$('#add').click(function(){
		$('.table-fill tr:eq(1)').show();
		$('#name').removeAttr('value');
		$('#content').html('');
		$('#new legend').html('Thêm');
		$('option').removeAttr('selected');
		$('.submit').attr(
		{
			value:"Thêm",
			id:"sub_add"
		});
		$('#fixed').show();
		$('#confirm').show();	
	})
	$('.table-manage').delegate('.edit','click',function(){
		
		$('option').removeAttr('selected');
			$('.submit').attr(
			{
				value:"Sửa",
				id:"sub_edit"
			});
			$('#fixed').show();
			$('#confirm').show();
			content_id=$(this).attr('content_id');	
			ajax("edit",content_id);
			$('.table-fill tr:eq(1)').hide();
			return false;	
	})
	$('.exit:eq(0)').click(function(){
		$('#fixed').hide();
		$('#confirm').hide();
		$('#new')[0].reset();
	})
	
	$('.submit').click(function(){
		action=$(this).attr('id');
		ajax(action,content_id);
	return false;
	});
	$('.table-manage').delegate('.delete','click',function(){
	  	if(confirm("Chắc chắn xóa?")){
			content_id=$(this).attr('content_id');
			ajax("delete",content_id);
			return false;
		}
	});
	
	function ajax(action,id)
	{
		if(action=="sub_add")
		{
			title=$('#name').val();
			content=$('#content').val();
			chap=$('#chap').val();
			part=$('#part').val();
			if(warning(content,chap)==false)
			{
				/*$('#add_status').show();
				$('.mess').html("Thiếu thông tin bắt buộc, vui lòng kiểm tra lại");*/
				return false;
			} else
			{
				data="content="+content+"&chap="+chap+"&part="+part+"&title="+title+"&truyen_id="+truyen_id+"&action=sub_add";
				data_type="json";
			}
		}
		else if(action=="delete")
		{
			data="content_id="+id+"&action=delete";
			data_type="json";
		} 
		else if(action=="edit")
		{
			data="content_id="+id+"&action=edit";
			data_type="json";
		}
	    else if(action=="sub_edit")
		{
			content=$('#content').val();
			title=$('#name').val();
			data="content="+content+"&content_id="+id+"&title="+title+"&action=sub_edit";
			data_type="json";
		}
		$.ajax({
		url:"solve_content.php",
		async:true,
		type:"post",
		data:data,
		dataType:data_type,
		success: function(kq){
			if(action=="sub_add")
			{
				if(kq.status!=0)
				{
					$('.table-manage').append('<tr><td> Chương: '+kq.chap+kq.part+'</td><td>'+kq.title+'</td><td><a href="#" class="edit" content_id="'+kq.content_id+'"><img src="../images/icon-edit.png" width="16px" height="16px" style="margin-right:5px;"/>Sửa</a></td><td><a href="#" class="delete" content_id="'+kq.content_id+'"><img src="../images/remove-icon.png" width="16px" height="16px" style="margin-right:5px;"/>Xóa</a></td></tr>');
				} else 
				{
					if (confirm(kq.mess))
					{
						ajax("sub_edit",kq.content_id);
					}
				}
			} 
			else if(action=="delete")
			{
				$("a[content_id='"+kq.content_id+"']").closest("tr").hide();
				/*$('#add_status').show();
				$('.mess').html("Xóa thành công");*/
			} 
			else if(action=="edit")
			{
				$('#name').attr('value',kq.title);
				$('#content').html(kq.content);
				$('#new legend').html('Sửa chương: '+kq.mess);
			} 
			else if(action=="sub_edit")
			{
				$('#confirm img').click();
				$("a[content_id='"+kq.content_id+"']").closest("tr").find("td:eq(1)").html(kq.title);	
			}
		},
	});
	}
	/* extra function */
	$('.close').click(function(){
		$('#add_status').hide();	
	})
	
	function warning(content,chap)
	{
		result = true;
		if(chap=="")
		{
			 $('#chap_error').html('<span class="warning">Số chương rỗng</span>');
			 result=false;
		}
		else 
		{
			check=/[0-9]/;
			if(!check.test(chap))
			{
				$('#chap_error').html('<span class="warning">Cần nhập số!</span>');
				result=false;
			}
			else
			{
				$('#chap_error').html('');
			}
		}
		if(content=="") 
		{
			$('#content_error').html('<span class="warning">Nội dung rỗng</span>');
			result=false;
		}
		else 
		{
			$('#content_error').html('');
		}

		return result;
	}
	function reset_warning()
	{	
		$('#chap_empty').hide();
		$('#content_empty').hide();
	}
	$('#clear_form').click(function(){
		$('#new')[0].reset();
	})
});