<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="include_login/style.css" />
<script src="include_login/jquery.js"></script>;
<script src="login_ajax.js"></script>;
</head>

<body>

	<div id="wrapper">
        <form id="login_form">
         
            <div class="header">
            <h1>Chào mừng đến với Lovelywind</h1>
            <span></span>
            </div>
         
            <div class="content">
            <input type="text" class="input email" placeholder="Email" id="email" />
            <div class="user_icon"></div>
            <div class="email_error"></div>
            <input type="password" class="input password" placeholder="Mật khẩu" id="password" />
            <div class="pass_icon"></div> 
            <div class="password_error"></div>      
            </div>
     
            <div class="footer">
            <input type="submit"  value="Tiếp tục" class="button" id="login"/>
            <input type="button"  value="Đăng ký" class="register" id="choose_register"/>
            </div>
         
        </form>
		
        <form id="register_form">
         
            <div class="header">
            <h1>Chào mừng đến với Lovelywind</h1>
            <span></span>
            </div>
         
            <div class="content">
            <input type="text" class="input name" placeholder="Tên của bạn" id="reg_name" />
            <div class="reg_name_error"></div>
            <!---------->
            <input type="text" class="input email" placeholder="Email" id="reg_email" />
            <div class="reg_email_error"></div>
            <!---------->
            <input type="password" class="input password" placeholder="Mật khẩu" id="reg_password" />  
            <div class="reg_password_error"></div>
            <!---------->
            <input type="password" class="input confpassword" placeholder="Xác nhận mật khẩu" id="reg_confpassword" />  
            <div class="reg_confpassword_error"></div>       
            </div>
     
            <div class="footer">
            <input type="submit"  value="Tiếp tục" class="button" id="register"/>
            <input type="button"  value="Đăng nhập" class="register" id="choose_login"/>
            </div>
         
        </form>
	</div>
</body>
</html>