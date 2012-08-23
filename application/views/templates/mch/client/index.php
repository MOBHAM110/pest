<?php require('top.php'); ?>
<div class="container">
<div class="index">
<!-- Header ( Top menu, header ) -->
<div class="index_header" <?php if ($layout['header_type']==1) { ?>style="background-image:url(<?php echo url::base()?>uploads/header/<?php echo $layout['header_image']; ?>); background-repeat:no-repeat" <?php }  // end if?>> <?php require('header.php')?></div>	
<?php if (!empty($layout['top_T_banner'])) { ?>
	<div class="index_banner_top"><?php require('banner_top_T.php')?></div>
<?php } // end if top T banner ?>
<?php if (!empty($layout['list_center_menu'])) { ?>
	<div class="index_menu_center"><?php require('menu_center.php')?></div>
<?php } // end if center menu ?>
<?php if (!empty($layout['top_B_banner'])) { ?>
	<div class="index_banner_top"><?php require('banner_top_B.php')?></div>
<?php } // end if top banner ?>
	<div class="index_middle">
        <div class="frame"> 
            <!-- Left -->
            <?php if (!empty($layout['left_col'])) { ?>
            <div class="frame_left"> 
                <?php require('left.php')?>
            </div>
            <?php } // end if have left column ?>
            <!-- Center -->	
            <div class="frame_center">
            	<?php if (isset($error_msg) || isset($success_msg)) { ?><?php require('error.php')?><?php } // end if ?>
                <?php if (uri::segment(1) === 'home' || uri::segment(1) === false) { ?>
                    <?php require('center_home.php')?>
                <?php } else { ?>
                    <?php require('center.php')?>
                <?php } // end if ?>
                <?php if (!empty($layout['left_outside_banner']) || !empty($layout['right_outside_banner'])) { ?>
				<?php require('banner_outside.php')?>
				<?php } // end if left_outside_banner ?>
            </div>
            <!-- Right -->
            <?php if (!empty($layout['right_col'])) { ?>
            <div class="frame_right"> 
                <?php require('right.php')?>
            </div>
            <?php } // end if have right column ?>
        </div>
    </div>
<!-- Bottom ( Bottom menu, Banner, Footer ) -->
<!-- Bottom Banners -->
<?php if (!empty($layout['bottom_banner'])) { ?>
<div class="index_banner_bottom"><?php require('banner_bottom.php')?></div>
<?php } // end if bottom banner ?>
</div>
<div class="index_footer"><?php require('footer.php')?></div>
</div>
<?php require('bottom.php'); ?>