$(document).ready(function(e) {
    $('.filter-tag').attr('check',0);
	$('.filter-tag').click(function(){ 
		if( $(this).attr('check')==0)
		{
			$(this).css('background','#f87205');
			$(this).css('color','#fff');
			$(this).attr('check',1);
		} else
		{
			old_bg=$(this).attr('bg');
			old_color=$(this).attr('color');
			$(this).css('background',old_bg);
			$(this).css('color',old_color);
			$(this).attr('check',0);
		}
	});
	//------------
	function tool(action)
	{
		if(action=='search')
		{
			keyword=$('.search-input').val();
			send='find_result.php?keyword='+keyword+'&action=search';
			window.location.href=send;
		} 
		else if(action=='filter')
		{
			json="";
			index=1;
			for(i=0;i<12;i++)
			{
				if($('.filter-tag').eq(i).attr('check')==1)
				{
					tmp='&cat_'+index+'=';
					json=json+tmp+$('.filter-tag').eq(i).attr('value');
					index++;
				}
			}
			index--;
			data='action=filter'+json+'&num_cat='+index;
			send='find_result.php?'+data;
			window.location.href=send;
		}
	}
	//---Khi nhấn enter
	$('.search-input').keypress(function(event){
	  var keycode = (event.keyCode ? event.keyCode : event.which);
	  if (keycode == '13') {
		tool('search');
	  }
	});
	//---Khi click
	$('.search-icon').click(function()
	{
		tool('search');
	})
	$('.filter-icon').click(function()
	{
		tool('filter');
	})
});