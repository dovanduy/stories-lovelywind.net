<body>
	<div id="fixed_notifi">
			<div id="add_status">
				<div class="mess"></div>
				<div class="close">Xác nhận</div>  
			</div>
	</div>
	<div id="top-bar">
    	<div style="width:90%; max-width:1277px; margin:auto;">
        <div id="slogan">
        	TRANG QUẢN TRỊ
        </div>
        <div id="user">
        	<?php 
				if(isset($_SESSION['user_name']))
				{
					echo "Chào mừng: ".$_SESSION['user_name'];
				}
			?>
        	<a href="../login/logout.php" class="logout">Đăng xuất</a>
        </div>
        </div>
    </div>
    <div id="contain">
				<div id="main-menu">
					<ul>
						<a href="../admin"><li>Trang chủ</li></a>
						<a href="manage_cat.php"><li>Quản lý chuyên mục</li></a>
						<a href="manage_truyen.php"><li>Quản lý truyện</li></a>
				<?php
                    if(isset($_SESSION['level']))
                    {
                        if($_SESSION['level']==3)
                        {
                            echo'<a href="manage_user.php"><li>Quản lý thành viên</li></a>';
                        }
                    }
                ?>
						<a href="#"><li>Quản lý bình luận</li></a>
					</ul>
                	<div class="contain-search">
                         <input type="text" class="search-input" placeholder="Tìm kiếm ..."/>
                         <button class="search-icon" type="submit">
                            <img src="../images/search_02.png" width="80%" height="80%" />
                         </button>
                    </div>
                    <div class="contain-filter">
                            <div class="filter">
                            <?php 
								$q='select * from categories limit 12';
								$db->query($q);
								while($cat=$db->get())
								{
									echo'
											<div class="filter-tag" value="'.$cat['cat_id'].
											'" bg="rgba(241,241,241,0.5)" color="#4aaaba">'.$cat['cat_name'].'</div>
										';
								}
							?>
                            </div>

                            <div class="filter-active">
                            	<div class="filter-icon">
                                	Lọc truyện
                                </div>
                            </div>
                    </div>
				</div>