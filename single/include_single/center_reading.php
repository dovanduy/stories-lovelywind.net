<div class="clear"></div>
		<div id="suggest">
        	<div class="sitemap">
            	<a href="<?php echo BASE_URL; ?>">Trang chủ</a> / 
                <a href="<?php echo $url_truyen; ?>"><?php echo quote_content($now['name'],15); ?></a> /
                <a href="<?php echo $url_content; ?>"><span><?php echo 'Chương '.$now['chap'].season($now['part']); ?></span></a> 
            </div>
            <div class="tool">
            	<div class="contain-search">
                     <input type="text" class="search-input" placeholder="Tìm kiếm ..." stt="2"/>
                     <button class="search-icon" stt="2"><img src="<?php echo BASE_URL; ?>images/search_02.png" width="60%" height="60%" /></button>
                      <div class="warning-search"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <div class="center reading">
        	<div class="title-center">
            	<div class="title">
					<h1><?php echo $now['name']; ?></h1>
					<h3>
					    <?php 
					        if($now['title']!="") $t=": ".$now['title'];
					        else $t="";
					        echo'Chương '.$now['chap'].season($now['part']).$t; 
					    ?>
					</h3>
               </div>
            </div>
            <div class="control-chap">
            	<a href="<?php if($pre!=NULL) echo $url_pre; else echo ""; ?>" <?php if($pre==NULL) echo 'class="dis"'; ?>>
                	<div class="control-pre" <?php if($pre==NULL) echo 'style="background:#ccc;"'; ?>>Chương trước</div>
                </a>
                <a href="<?php if($next!=NULL) echo $url_next; else echo ""; ?>" <?php if($next==NULL) echo 'class="dis"'; ?>>
                	<div class="control-next" <?php if($next==NULL) echo 'style="background:#ccc;"'; ?>>Chương sau</div>
                </a>
            </div>
            <div class="control-go">
            	<div class="contain-search">
                     <input type="text" truyen_id="<?php echo $now['truyen_id']; ?>" class="go-input" placeholder="<?php echo $now_location.'/'.$num_content; ?>" max="<?php echo $num_content; ?>"/>
                     <div class="go-icon" stt="0">
                     	 &rarr;
                     </div>
                </div>
            </div>
        	<?php 
				echo format_content($now['content']);
			?>
            <div class="control-chap">
            	<a href="<?php if($pre!=NULL) echo $url_pre; else echo ""; ?>" <?php if($pre==NULL) echo 'class="dis"'; ?>>
                	<div class="control-pre" <?php if($pre==NULL) echo 'style="background:#ccc;"'; ?>>Chương trước</div>
                </a>
                <a href="<?php if($next!=NULL) echo $url_next; else echo ""; ?>" <?php if($next==NULL) echo 'class="dis"'; ?>>
                	<div class="control-next" <?php if($next==NULL) echo 'style="background:#ccc;"'; ?>>Chương sau</div>
                </a>
            </div>
            <div class="control-go">
            	<div class="contain-search">
                     <input type="text" truyen_id="<?php echo $now['truyen_id']; ?>" class="go-input" placeholder="<?php echo $now_location.'/'.$num_content; ?>" max="<?php echo $num_content; ?>"/>
                     <div class="go-icon" stt="1">
                        &rarr;
                     </div>
                </div>
            </div>
        </div>