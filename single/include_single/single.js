// JavaScript Document
$(document).ready(function(e) {	
	$('.icon-menu-center').click(function(){
		$('.fixed-menu').animate({'right':'0px'},400);
		$('.close').fadeIn(400);
		$('.home').fadeIn(400);
		$('.mini-filter').fadeIn(400);
		$('.list-cat').fadeIn(600);
		$('.switch-control').hide();
		$("html, body").scrollTop(0);
		setTimeout( function(){
			$('#global').css('min-height','0px');
			$('#global').css('height','0px');
		}, 400 );
	})
	$('.close').click(function(){
		$('.close').fadeOut(400);
		$('.list-cat').fadeOut(400);
		$('.home').fadeOut(400);
		$('.mini-filter').fadeOut(400);
		$('.fixed-menu').animate({'right':'100%'},500);
		$('#global').css('height','auto');
		$('#global').css('min-height','100%');
	})
	//---------------
	$('#intro-show').show();
	intro_show=1;
	$('#read-more').click(function(){
		if(intro_show==1)
		{
			$('#intro-hide').show(200);
			$(this).html('Rút gọn')
			intro_show=0;
		} else
		{
			$('#intro-hide').hide(200);
			$(this).html('Đọc thêm')
			intro_show=1;
		}
	});
	//---------------
	$('.filter-tag').attr('check',0);
	$('.filter-tag').click(function(){ 
		if( $(this).attr('check')==0)
		{
			$(this).css('background','linear-gradient(to right, #00c6ff, #0072ff)');
			$(this).css('color','#fff');
			$(this).attr('check',1);
		} else
		{
			old_bg=$(this).attr('bg');
			//alert(old_bg);
			old_color=$(this).attr('color');
			$(this).css('background',old_bg);
			$(this).css('color',old_color);
			$(this).attr('check',0);
		}
	});
	//---------------
	enter_filter=0; enter_search=0;
	$('.filter-icon').on('mouseleave',function(){
		enter_filter=0;
	}).on('mouseenter',function(){
		enter_filter=1;
	})
	$('.search-icon').on('mouseleave',function(){
		enter_search=0;
	}).on('mouseenter',function(){
		enter_search=1;
	})
	$('body').click(function(){
		if(enter_filter==0 && enter_search==0)
		{
			$('.warning-filter').hide();
			$('.warning-search').hide();
		}
		else if(enter_filter==0)
		{
			$('.warning-filter').hide();
		} 
		else if(enter_search==0)
		{
			$('.warning-search').hide();
		}
	})
	function tool(action,value,id)
	{
		if(action=='search')
		{
			keyword=value;
			if(keyword=='')
			{
				$('.warning-search').eq(id).html('Bạn chưa nhập gì cả!');
				$('.warning-search').eq(id).show();
				return false;
			}
			else
			{	
				if($('#type').attr('val')=='single')
				{
					window.location.assign('../tim-kiem/?keyword='+keyword+'&action=search');
				}
				else if($('#type').attr('val')=='reading')
				{
					window.location.assign('../../tim-kiem/?keyword='+keyword+'&action=search');
				}
			}
		}
		else if(action=='filter')
		{
			json="";
			if(id==0) start=0;
			else if(id==1) start=12;
			else if(id==2) start=23;
			index=1;
			finish=start+12;
			for(i=start;i<finish;i++)
			{
				if($('.filter-tag').eq(i).attr('check')==1)
				{
					tmp='&cat_'+index+'=';
					json=json+tmp+$('.filter-tag').eq(i).attr('value');
					index++;
				}
			}
			index--;
			
			if(index==0) 
			{
				
				$('.warning-filter').eq(id).html('Hãy chọn ít nhất một mục!');
				$('.warning-filter').eq(id).show()
				return false;
			}
			data='action=filter'+json+'&num_cat='+index;	
			if($('#type').attr('val')=='single')
			{
				send='../loc-truyen/?'+data;
			}
			else if($('#type').attr('val')=='reading')
			{
				send='../../loc-truyen/?'+data;
			}
			window.location.href=send;	
		}
	}
	function go_chap(chap,id)
	{
		$.ajax({
		url:"../../single/include_single/get_link.php",
		async:true,
		type:"post",
		data:"chap="+chap+"&id="+id,
		dataType:"html",
		success: function(kq){
			kq="../../"+kq;
			window.location.assign(kq);	
		}
		})
	}
	//---Khi nhấn enter
	$('.search-input').keypress(function(event){
	  var keycode = (event.keyCode ? event.keyCode : event.which);
	  if (keycode == '13') {
		keyword=$(this).val();
		id=$(this).attr('stt');
		tool('search',keyword,id);
	  }
	});
	
	$('.go-input').keypress(function(event){
	  var keycode = (event.keyCode ? event.keyCode : event.which);
	  if (keycode == '13') {
		chap=$(this).val();
		id=$(this).attr('truyen_id');
		maxx=parseInt($(this).attr('max'));
		check_num = /^[0-9]{1,10}$/;
		if(check_num.test(chap))
		{
			if(chap>maxx)
			{
				chap=maxx;
			}
			go_chap(chap,id);
		}
	  }
	});
	//---Khi click
	$('.search-icon').click(function()
	{
		id=$(this).attr('stt');
		keyword=$('.search-input').eq(id).val();
		tool('search',keyword,id);
	})
	$('.filter-icon').click(function()
	{
		id=$(this).attr('stt');
		tool('filter','',id);
	})
	
	$('.go-icon').click(function(){
		stt=$(this).attr('stt');
		chap=$('.go-input').eq(stt).val();
		id=$('.go-input').eq(stt).attr('truyen_id');
		maxx=parseInt($('.go-input').eq(stt).attr('max'));
		check_num = /^[0-9]{1,10}$/;
		if(check_num.test(chap))
		{
			if(chap>maxx)
			{
				chap=maxx;
			}
			go_chap(chap,id);
		}
	})
	//-----
	if($('#chap-list').attr('value')=='true')
	{
		top_scroll=$('#chap-list').offset().top;
		window.scroll(0,top_scroll);
	} else
	{}
	//-----
	$('.like').click(function(){
		truyen_id=$(this).attr('truyen_id');
		value=$(this).attr('value');
		data="truyen_id="+truyen_id+"&value="+value;
		data_type="html";
		$.ajax({
			url:"../homepage/rating.php",
			async:true,
			type:"post",
			data:data,
			dataType:data_type,
			success: function(kq){
				$('#num-like').html(kq);
				$('.like').attr('value',kq);
			}
		});
	})
	//------------
	$(window).resize(function(){
		if(screen.width>760)
		{
			$('.close').hide();
			$('.list-cat').hide();
			$('.home').hide();
			$('.mini-filter').hide();
			$('.fixed-menu').css('right','100%');
			$('#global').css('height','auto');
			$('#global').css('min-height','100%');
		}
	})
	//------------
	if(window.scrollX>500)
	{
		$('.switch-control').show();
	} else
	{
		$('.switch-control').hide();
	}
	old_scroll=0;
	$(window).scroll(function(){
		new_scroll=$(this).scrollTop();
		if($(this).scrollTop()>40)
		{
			$('#toggle-menu').hide();
			if($(this).scrollTop()>500)
			{
				$('.switch-control').show();
			} else
			{
				$('.switch-control').hide();
			}
		}
		if(new_scroll<old_scroll)
		{
			$('#toggle-menu').show();
		}
		old_scroll=new_scroll;
		
	})
	
	$('.switch-control').click(function(){
		$("html, body").animate({
	            	scrollTop: 0
	        }, 600);
	        return false;	
	})
});
