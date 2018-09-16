$(document).ready(function(){
	/* Khởi tạo toàn cục */
	var http_arr = new Array();
	var truyen_id="";
	/*------------*/
	$('#thumb_curr').height(300);
	/*--Khởi tạo lự chọn thể loại*/
	$('.cat-item').attr('check','false');
	$('.cat-item').click(function()
	{
		if($(this).attr('check')=='false')
		{
			$(this).attr('check','true');
			$(this).css('background','#5151FF');
		} else 
		{
			$(this).attr('check','false');
			$(this).css('background','#666');
		}
	})
	/*-Xác nhận thông báo-*/
	$('.close').click(function(){
		$('#add_status').hide();
		$('#fixed_notifi').hide();	
		$('.name_thumb').html('Chưa chọn ảnh');
		$('#new')[0].reset();
		$('input[type="checkbox"]').attr('check','false');
	})
	/*--Click thêm truyen--*/
	$('#add').click(function(){
		$('.flag-taskbar').html('Thêm truyện mới');
		$('option').removeAttr('selected');
		$('#name').removeAttr('value');
		$('.submit').attr(
		{
			value:"Thêm truyện",
			id:"sub_add"
		});
		$('#fixed').show();
		$('#confirm').show();	
	})
	/*Click xóa truyen*/
	$('.table-manage').delegate('.delete','click',function(){
	if(confirm("Chắc chắn xóa?")){
		truyen_id=$(this).attr('truyen_id');
		ajax("delete",truyen_id);
		return false;
	}
	});
	/*--Edit truyen--*/
	$('.table-manage').delegate('.edit','click',function(){
			$('.submit').attr(
			{
				value:"Lưu thay đổi",
				id:"sub_edit"
			});
			$('#fixed_detail').show();
			$('#detail').show();
			truyen_id=$(this).attr('truyen_id');	
			ajax("edit",truyen_id);
			return false;
	})
	/*Thoát khung thao tác*/
	$('.exit:eq(0)').click(function(){
		$('#fixed').hide();
		$('#confirm').hide();
		$('#new')[0].reset();
		reset_warning();
		$('input[type="checkbox"]').attr('check','false');
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
		ajax(action,truyen_id);//submit bằng ajax
	return false;
	});
	/*Thêm truyen bằng ajax với jquery*/
	function ajax(action,id)
	{
		if(action=="sub_add")
		{
		    sum=0;//tổng số thể loại
			cat="";//json thể loại
			serial=$('#serial').val();
			season=$('#season').val();
			name=$('#name').val();
			status=$('#status').val();
			country=$('#country').val();
			intro=$('#introduce').val();
			num_cat=$('#num_cat').attr('value');
			author=$('#author').val();
			for(i=0;i<num_cat;i++)//lấy các thể loại, thêm json
			{
				if($('.cat-item').eq(i).attr('check')=="true")
				{
					val=$('.cat-item').eq(i).attr('value');
					cat=cat+"&cat"+sum+"="+val;
					sum++;
				}
			}
			if(warning(name,sum,author)==false) //kiểm tra thông tin cần thiết
			{
				return false;
			}
			else
			{
			data="name="+name+"&author="+author+"&serial="+serial+"&season="+season+"&status="+status+"&action=sub_add"+cat+"&sum="+sum+"&intro="+intro+"&country="+country;//json data gửi đi
			data_type="json";//data type nhận về
			}
		} 
		else if(action=="delete")
		{
			data="truyen_id="+id+"&action=delete";
			data_type="json";
		}
		else if(action=="edit")
		{
			data="truyen_id="+id+"&action=edit";
			data_type="json";
		}
		else if(action=='delete-some')
		{
			var numrows=$('#get-numrows').attr('numrows');
			var data='';
			var sum=0;
			for(i=0;i<numrows;i++)
			{
				if($('.select-a').eq(i).attr('check')=='1')
				{
					data=data+'&id_'+sum+'='+$('.select-a').eq(i).attr('value');
					sum++;
				}
			}
			$('#fixed_notifi').show();
			$('#add_status').show();
			$('.mess').html("Đang xóa, vui lòng đợi");
			data="action=delete-some"+data+"&sum="+sum;
			data_type='json';
		}
		else if(action=="sub_edit")
		{
			serial=$('#edit_serial').val();
			season=$('#edit_season').val();
			name=$('#edit_name').val();
			status=$('#edit_status').val();
			suggest=$('#edit_suggest').val();
			country=$('#edit_country').val();
			num_chap=$('#edit_numchap').val();
			intro=$('#edit_introduce').val();
			author=$('#edit_author').val();
			if(warning_edit(name,author)==false) //kiểm tra thông tin cần thiết
			{
				$('#fixed_notifi').show();
				$('#add_status').show();
				$('.mess').html("Thiếu thông tin bắt buộc, vui lòng kiểm tra lại");
				return false;
			}
			else
			{
			data="name="+name+"&author="+author+"&serial="+serial+"&season="+season+"&status="+status+"&action=sub_edit"+"&intro="+intro+"&num_chap="+num_chap+"&truyen_id="+id+"&suggest="+suggest+"&country="+country;//json data gửi đi
			data_type="json";//data type nhận về
			console.log(data);
			}
		} 
		$.ajax({
		url:"solve_truyen.php",
		async:true,
		type:"post",
		data:data,
		dataType:data_type,
		success: function(kq){
			if(action=="sub_add")
			{
				if(kq.status!="false")
				{
				    var numrows=parseInt($('#get-numrows').attr('numrows')) + 1;
					$('#get-numrows').attr('numrows',numrows);
					
					$('#first-tr').after('<tr><td>'+kq.name+'</td><td>0</td><td>'+kq.user+'</td><td>'+kq.date+'</td><td><a href="#" class="edit" truyen_id="'+kq.truyen_id+'"><i class="far fa-edit"></i>Chi tiết</a></td><td><a href="manage_content.php?truyen_id='+kq.truyen_id+'" truyen_id="'+kq.truyen_id+'"><i class="fas fa-book-open"></i>Nội dung</a></td><td><a href="#" class="delete" truyen_id="'+kq.truyen_id+'"><i class="fas fa-trash-alt"></i>Xóa</a></td><td><input type="checkbox" check="0" class="select-a" value="'+kq.truyen_id+'"></td></tr>');
					
					var files;
					files = document.getElementById('thumbnail').files;
					if(files.length==0) 
					{
						//window.location.reload();
					} 
					else
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.close').hide();
						$('.mess').html('Vui lòng đợi, đang xử lý...');
						upload(files[0],0,0,kq.truyen_id,"thumbnail");
					}
				} else 
				{
					alert('Truyện đã tồn tại, vui lòng kiểm tra lại');
				}
			} 
			else if(action=="delete")
			{
			    var numrows=parseInt($('#get-numrows').attr('numrows')) - 1;
				$('#get-numrows').attr('numrows',numrows);
					
				$("a[truyen_id='"+kq.truyen_id+"']").closest("tr").hide();
			}
			else if(action=='delete-some')
			{
			    var numrows=parseInt($('#get-numrows').attr('numrows')) - parseInt(kq.sum);
				$('#get-numrows').attr('numrows',numrows);
				
				for(i=0;i<kq.sum;i++)
				{
					$("a[truyen_id='"+kq[i]+"']").closest("tr").html('');
					$('#fixed_notifi').show();
					$('#add_status').show();
					$('.mess').html("Xóa hoàn tất");
				} 
			}
			else if(action=="edit")
			{
				$('.flag-taskbar').html(kq.name);
				$('#show_thumbnail').html('<img src="../'+kq.thumbnail+'" width="94%" style="float:right"/>');
				$('#edit_name').attr('value',kq.name);
				$('#edit_author').attr('value',kq.author);
				$('#edit_serial').attr('value',kq.serial);
				$('#edit_numchap').attr('value',kq.num_chap);
				reset_status(kq.status);
				reset_season(kq.season);
				reset_country(kq.country);
				reset_suggest(kq.suggest);
				$('#info_date').html(kq.date);
				$('#info_datechange').html(kq.date_change);
				kq.cat=kq.cat.substring(0,kq.cat.length-2);
				$('#info_cat').html(kq.cat);
				$('#info_user').html(kq.user_name);
				$('#edit_introduce').html(kq.intro);
				/*var sym='*', str=kq.cat;
					while(str.length>1)
					{
						index=str.indexOf(sym);
						result=str.substring(0,index);
						str=str.substring(index+1,str.length);
						
					}*/
			}
			else if(action=="sub_edit")
			{
				if(kq.status=="true")
				{
					var files;
					files = document.getElementById('edit_thumbnail').files;
					if(files.length==0) 
					{
						$('.exit').eq(1).click();
					} else
					{
						$('#fixed_notifi').show();
						$('#add_status').show();
						$('.close').hide();
						$('.mess').html('Vui lòng đợi, đang xử lý...');
						upload(files[0],0,0,kq.truyen_id,"e-thumbnail");
					}				
					$("a[truyen_id='"+kq.truyen_id+"']").closest("tr").find("td:eq(0)").html(kq.name);	
					$("a[truyen_id='"+kq.truyen_id+"']").closest("tr").find("td:eq(1)").html(kq.num_chap);
				}
				else
				{
					$('#fixed_notifi').show();
					$('#add_status').show();
					$('.mess').html("Truyện đã tồn tại, vui lòng kiểm tra lại");
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
			data.append('truyen_id',id);
			data.append('type',type);
			data.append('myfile', file);
			http.open('POST', 'solve_truyen.php', true);
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
							if(type=="thumbnail" || type=="e-thumbnail")
							{
								$('.close').show();
								$('#fixed_notifi').hide();
								$('#add_status').hide();
								
								if(confirm("Phát hiện bạn vừa thêm hình mới. Bạn có muốn crop thumbnail từ hình mới upload?"))
								{
									if(server.ratio!=0 && server.url!="")
									{
										var ratio = "";
										if(server.ratio>1) ratio='height-frame';
										else ratio='width-frame';
									} else return false;
									$('#type-frame').attr('class',ratio);
									if(ratio == 'height-frame') $('#limit').css('height','100%');
									$('#fixed-crop').show();
									$('#manage_thumb').show();
									$('#img-need-crop').html('<img src="'+server.url+'" />');
									$('#crop').attr('get_id', id);									
								}
							} 
							
						}
						else if(server.status==0)//That bai
						{
							alert(server.message);
						}
				}
			}
	} //upload ajax bằng javascript thuần
	
	function warning(name,sum_cat,author)
	{
		result=true;
		if(name=="") 
		{
			$('#name_error').html('<span class="warning">Tên đang trống</span>');
			result=false;
		}
		else
		{
			$('#name_error').html('');
		}
		if(sum_cat==0) 
		{
			$('#type_error').html('<span class="warning">Cần ít nhất một thể loại</span>');
			result=false;
		}
		else 
		{
			$('#type_error').html('');
		}
		if(author=="") 
		{
			$('#author_error').html('<span class="warning">Thiếu tác giả</span>');
			result=false;
		}
		else 
		{
			check_name=/[\,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\+\=]/;
			if(check_name.test(author))
			{
				$('#author_error').html('<span class="warning">Tên có ký tự đặc biệt</span>');
				result=false;
			}
			else
			{
				$('#author_error').html('');
			}
		}
		return result;
	}
	function reset_warning()
	{
		$('#name_error').html('');	
		$('#type_error').html('');
		$('#author_error').html('');
		$('#edit_name_error').html('');
		$('#edit_author_error').html('');
	} 
	function warning_edit(name,author)
	{
		result=true;
		if(name=="") 
		{
			$('#edit_name_error').html('<span class="warning">Tên đang trống</span>');
			result=false;
		}
		else 
		{
			$('#edit_name_error').html('');
		}
		if(author=="") 
		{
			$('#edit_author_error').html('<span class="warning">Thiếu tác giả</span>');
			result=false;
		}
		else 
		{
			check_name=/[\.,<>?\/:;"\'{}\[\]\|`~\!@#\$%\^\&\*\(\)\_\-\+\=]/;
			if(check_name.test(author))
			{
				$('#edit_author_error').html('<span class="warning">Tên có ký tự đặc biệt</span>');
				result=false;
			}
			else
			{
				$('#edit_author_error').html('');
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
	function find_code(type,index)
	{
		if(type=='status')
		{
			if(index==0)
			{
				return "Đang cập nhật";
			} else if(index==1)
			{
				return "Đã hoàn thành";
			} else if(index==2)
			{
				return "Đã dừng";
			} else return "Lỗi";
		} else if(type=='season')// dùng chung cho suggest
		{
			if(index==0) return "Không có";
			else return index;
		} else if(type=='country')
		{
			if(index==0)
			{
				return "Việt Nam";
			} else if(index==1)
			{
				return "Trung Quốc";
			} else if(index==2)
			{
				return "Nhật Bản";
			} else if(index==3)
			{
				return "Phương Tây";
			} else return "Lỗi";
		} else return 'Lỗi';
	}
	
	function reset_status(selected)
	{
		$('#edit_status').html('');
		$('#edit_status').append('<option value="'+selected+'">'+find_code('status',selected)+'</option>');
		for(i=0;i<=2;i++)
		{
			if(i!=selected)
			{
				$('#edit_status').append('<option value="'+i+'">'+find_code('status',i)+'</option>');
			}
		}
	}
	
	function reset_season(selected)
	{
		$('#edit_season').html('');
		$('#edit_season').append('<option value="'+selected+'">'+find_code('season',selected)+'</option>');
		for(i=0;i<=4;i++)
		{
			if(i!=selected)
			{
				$('#edit_season').append('<option value="'+i+'">'+find_code('season',i)+'</option>');
			}
		}
	}
	
	function reset_country(selected)
	{
		$('#edit_country').html('');
		$('#edit_country').append('<option value="'+selected+'">'+find_code('country',selected)+'</option>');
		for(i=0;i<=3;i++)
		{
			if(i!=selected)
			{
				$('#edit_country').append('<option value="'+i+'">'+find_code('country',i)+'</option>');
			}
		}
	}
	
	function reset_suggest(selected)
	{
		$('#edit_suggest').html('');
		$('#edit_suggest').append('<option value="'+selected+'">'+find_code('season',selected)+'</option>');
		for(i=0;i<=3;i++)
		{
			if(i!=selected)
			{
				$('#edit_suggest').append('<option value="'+i+'">'+find_code('season',i)+'</option>');
			}
		}
	}
	//--------------------
	$('.table-manage').delegate('.select-a','click',function(){		
		if($(this).attr('check')=='1')
		{
			$(this).attr('check','0');
			$(this).closest('.table-manage tr').removeAttr('style');
		}
		else
		{
			$(this).attr('check','1');
			$(this).closest('.table-manage tr').css('background','#CACACA');
		}
	})
	//-------------------
	$('.check-all').click(function(){
		var numrows=$('#get-numrows').attr('numrows');
		var action=$(this).attr('action');
		if(action=='all')
		{
			for(i=0;i<numrows;i++)
			{
				if($('.select-a').eq(i).attr('check')!='1')
				{
					$('.select-a').eq(i).click();
				}
			}
			$(this).attr('action','clear');
		}
		else if(action=='clear')
		{
			$('.select-a').click();
			$(this).attr('action','all');
		}
	})
	
	$('#delete-some').click(function(){
		if(confirm("Việc này sẽ xóa toàn bộ những truyện được chọn và dữ liệu liên quan. Chắc chắn xóa?")){
			ajax('delete-some','');
			return false;
		}
	})
}); 
