$(document).ready(function(e) {

var top = 0 , left = 0 , now_id = 0 ,  stt = 0; 
//----init

var key = $('#get_info').attr('c_type'); 
var rat=1.5;
var wi=100;
if(key=='avatar') 
{
	rat=1;
	$('#frame_crop').height(98);
	$('.bottom').css('top',100);	
}
var he=wi*rat;

$('.exit').click(function(){
        top = 0 , left = 0
		$("#frame_crop").css({
			'left': 0,
			'top': 0,
			'width': wi-2,
			'height': he-2
    	});
		$('.left').css('width',0);
		$('.right').css('left',wi-1);	
		$('.top').css({'height':0, 'left':0, 'width':wi-1});
		$('.bottom').css({'top':he-1, 'left':0, 'width':wi-1});
		$('#fixed-crop').hide();
		$('#manage_thumb').hide();
})
//----------
    
$("#frame_crop").draggable({
  'containment': '#limit',
  'cursor': 'move',
  drag: function(event, ui) 
  	  {
		  	var new_size=$("#frame_crop" ).width();
			left = ui.position.left;
			top = ui.position.top;
			
			$('.left').css('width',left);
			$('.right').css('left',left+new_size+1);	
			$('.top').css({'height':top, 'left':left, 'width':new_size+1});
			$('.bottom').css({'top':top+new_size*rat+1, 'left':left, 'width':new_size+1});	  
      }
});

var $resizew =  null , $resizeh = null , $resizec = null;
	check_h=false;
	check_w=false;
	check_mi=false;
	$(document.body).on("mousemove", function(e) {
		//---var
		frame=$('#frame_crop');
		contain=$('#limit');
		
		rightF=parseInt(frame.offset().left+frame.width());
		botF=parseInt(frame.offset().top+frame.height());
		rightC=parseInt(contain.offset().left+contain.width());
		botC=parseInt(contain.offset().top+contain.height());
		
		x=parseInt(e.pageX);
		y=parseInt(e.pageY);
		
		//---solve
        if ($resizew)
		{
			if(x <= rightC && botF<=botC && frame.width()>=80)
			{
				val = x-frame.offset().left-1;
				
				if((check_h==true && val<=old) || (check_h==false && check_mi==false) || (check_mi==true && val>=old))
				{
					//console.log(check+" "+old+ " " +val);
					frame.width(val);
					frame.height(val*rat);
					old = val;
					if(check_h==true) check_h=false;
					if(check_mi==true) check_mi=false;
				}
			}
			else if(botF > botC)
			{
				val = botC-frame.offset().top-1;
				frame.width(val/rat);
				frame.height(val);
				old = val/rat;
				check_h = true;
			}
			else if(frame.width()<80)
			{
				val = 80;
				frame.width(val);
				frame.height(val*rat);
				old = val;
				check_mi=true;
			}
			else if(x > rightC)
			{
				if(check_h==false)
				{
					val = rightC-frame.offset().left-1;
					frame.width(val);
					frame.height(val*rat);
				}
			}
			var new_size=frame.width();
			$('.right').css('left',left+new_size+1);
			$('.top').css({'height':top, 'left':left, 'width':new_size+1});
			$('.bottom').css({'top':top+new_size*rat+1, 'left':left, 'width':new_size+1});
        }
		
		if ($resizeh)
		{
			if(y <= botC && rightF<=rightC && frame.width()>=80)
			{
				val = y-frame.offset().top-1;
				
				if((check_w==true && val<=old) || (check_w==false && check_mi==false) || (check_mi==true && val>=old))
				{
					//console.log(check+" "+old+ " " +val);
					frame.width(val/rat);
					frame.height(val);
					old = val;
					if(check_w==true) check_w=false;
					if(check_mi==true) check_mi=false;
				}
				
			}
			else if(rightF > rightC)
			{
				val = rightC-frame.offset().left-1;
				frame.width(val);
				frame.height(val*rat);
				old = val;
				check_w = true;
			}
			else if(frame.width()<80)
			{
				val = 80;
				frame.width(val);
				frame.height(val*rat);
				old = val;
				check_mi=true;
			}
			else if(y > botC)
			{
				if(check_w==false)
				{
					val = botC-frame.offset().top-1;
					frame.width(val/rat);
					frame.height(val);
				}
			}
			var new_size=frame.width();
			$('.right').css('left',left+new_size+1);
			$('.top').css({'height':top, 'left':left, 'width':new_size+1});
			$('.bottom').css({'top':top+new_size*rat+1, 'left':left, 'width':new_size+1});
        }
		
		if ($resizec)
		{
			if(y-frame.offset().top > x-frame.offset())
			{
				if(x <= rightC && botF<=botC && frame.width()>=80)
				{
				val = x-frame.offset().left-1;
				
				if((check_h==true && val<=old) || (check_h==false && check_mi==false) || (check_mi==true && val>=old))
				{
					//console.log(check+" "+old+ " " +val);
					frame.width(val);
					frame.height(val*rat);
					old = val;
					if(check_h==true) check_h=false;
					if(check_mi==true) check_mi=false;
				}
			}
				else if(botF > botC)
				{
					val = botC-frame.offset().top-1;
					frame.width(val/1.5);
					frame.height(val);
					old = val;
					check_h = true;
				}
				else if(frame.width()<80)
				{
				val = 80;
				frame.width(val);
				frame.height(val*rat);
				old = val;
				check_mi=true;
			}
				else if(x > rightC)
				{
					if(check_h==false)
					{
						val = rightC-frame.offset().left-1;
						frame.width(val);
						frame.height(val*rat);
					}
				}
			}
			else
			{
				if(y <= botC && rightF<=rightC && frame.width()>=80)
				{
				val = y-frame.offset().top-2;
				
				if((check_w==true && val<=old) || (check_w==false && check_mi==false) || (check_mi==true && val>=old))
				{
					//console.log(check+" "+old+ " " +val);
					frame.width(val/rat);
					frame.height(val);
					old = val;
					if(check_w==true) check_w=false;
					if(check_mi==true) check_mi=false;
				}
				
			}
				else if(rightF > rightC)
				{
					val = rightC-frame.offset().left-1;
					frame.width(val);
					frame.height(val*rat);
					old = val;
					check_w = true;
				}
				else if(frame.width()<80)
				{
				val = 80;
				frame.width(val);
				frame.height(val*rat);
				old = val;
				check_mi=true;
			}
				else if(y > botC)
				{
					if(check_w==false)
					{
						val = botC-frame.offset().top-1;
						frame.width(val/rat);
						frame.height(val);
					}
				}	
			}
		
		    var new_size=frame.width();
			$('.right').css('left',left+new_size+1);
			$('.top').css({'height':top, 'left':left, 'width':new_size+1});
			$('.bottom').css({'top':top+new_size*rat+1, 'left':left, 'width':new_size+1});
		}
    });
    
    $(document.body).on("mousedown", ".resizew", function (e) {
		if($resizew==null) $resizew = $(e.target);	
    });
    $(document.body).on("mousedown", ".resizeh", function (e) {
        if($resizeh==null) $resizeh = $(e.target);
    });
	$(document.body).on("mousedown", ".resizec", function (e) {
        if($resizec==null) $resizec = $(e.target);
    });
    $(document.body).on("mouseup", function (e) {
        $resizew = null;
		$resizeh = null;
		$resizec = null;
    });
function ajax(action, key, id)
	{
		if(action=="crop")
		{
			
			var size_img = {
				width : $('#img-need-crop').width(),
				height : $('#img-need-crop').height()
				}
			var size_crop = {
				width : $('#frame_crop').width(),
				height : $('#frame_crop').height()
				}
			var location = {
				x : left,
				y : top
				}
			//console.log(location);
			data={
					id : id,
					ratio_w : size_crop.width/size_img.width,
					ratio_h : size_crop.height/size_img.height,
					ratio_x : location.x/size_img.width,
					ratio_y : location.y/size_img.height,
					action : "crop",
					type : key
				}
			console.log(data);
			data_type="json";//data type nhận về
		} 
		else if(action=="get_thumb")
		{
			
			data={
					id : id,
					action : "get_thumb"
				}
			data_type="json";//data type nhận về
			
		}
		$.ajax({
			url:"include_admin/crop_thumb/crop.php",
			async:true,
			type:"post",
			data:data,
			dataType:data_type,
			success: function(kq){
				if(action=="crop")
				{
					if(kq.status==1)
					{
						$('#thumb_curr').html('<img src="../'+kq.url+'" />');
						//console.log(stt+' '+key);
						if(key=='album')
						{
							//console.log(stt);
							//$('.avai-thumb').eq(stt).css('background','#0F0');
						}
						else if(key=='thumbnail')
						{	
						//	window.location.reload();
						}
						else if(key=='avatar')
						{
							window.location.reload();
						}
						//console.log(kq.url);
					}
				} 
				else if(action=="get_thumb")
				{
					if(kq.status==1)
					{
						//console.log(kq.q);
						$('#thumb_curr').html('<img src="../'+kq.url+'" />');
					}
					else
					{
						$('#thumb_curr').html('Chưa có thumbnail');
					}
				}
			},
		});
	}
	
$('#crop').click(function(){
		ajax('crop',key, $(this).attr('get_id'));
})
});