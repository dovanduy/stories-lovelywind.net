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
	$('.home').click(function(){
		window.location.href="";
	})
	//---------------
	$('.emergent-content').hide();
	$('.emergent-content:eq(0)').show();
	var index=1;
	function startSlide()
	{
		interval=setInterval(function(){
				$('.emergent-content').hide();
				$('.emergent-content').eq(index).show();
				index++;
				if(index==3) {index = 0;}
		},4000);
	}
	function pauseSlide()
	{
		 clearInterval(interval);
	}
	$('.emergent-image').on('mouseenter',pauseSlide).on('mouseleave',startSlide);
	$('.emergent-info').on('mouseenter',pauseSlide).on('mouseleave',startSlide);
	$('.emergent-item').on('mouseenter',pauseSlide).on('mouseleave',startSlide);
	$('.list-select li').on('mouseenter',pauseSlide).on('mouseleave',startSlide);
	
	$('.list-select li').click(function(){
		index = parseInt($(this).attr('stt'));	
		$('.emergent-content').hide();
		$('.emergent-content').eq(index).show();
		index++;
		if(index==3) {index = 0;}
	})
	startSlide();
	//----------------
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
	$('.like-now').click(function(){
		truyen_id=$(this).attr('truyen_id');
		value=$(this).attr('value');
		data="truyen_id="+truyen_id+"&value="+value;
		data_type="html";
		$.ajax({
			url:"homepage/rating.php",
			async:true,
			type:"post",
			data:data,
			dataType:data_type,
			success: function(kq){
				$('.like-now[truyen_id="'+truyen_id+'"]').html('<img src="images/heart-01.png" height="30"/><a style="cursor:pointer;">'+kq+'</a>')
				$('.like-now[truyen_id="'+truyen_id+'"]').attr('value',kq);
			}
		});
	})
	//----------------
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
			send='tim-kiem/?keyword='+keyword+'&action=search';
			window.location.href=send;
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
			send='loc-truyen/?'+data;
			window.location.href=send;
		}
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