<div class="right">
<div style="clear:both">
    <div class="right_T_L"></div>
    <div class="right_T_Ce"></div>
    <div class="right_T_R"></div>
</div>
<div style="clear:both">
    <div class="right_M_L"></div>
    <div class="right_M_Ce">
        <div class="content_right">
        <?php if (!empty($layout['right_T_banner'])) { ?>
        <div class="content_right_banner"><?php require('banner_right_T.php')?></div>
        <?php } // end if ?>
        <?php if (isset($this->site['config']['LOGIN_FRM']) && $this->site['config']['LOGIN_FRM'] == 2) { ?>	
        <div class="content_right_login">
            <?php require(Kohana::find_file('views','templates/'.$this->site['config']['TEMPLATE'].'/login/frm_login',TRUE))?>
		</div>
        <?php } // end if have login form ?>
         <?php if (isset($this->site['config']['SUPPORT_FRM']) && $this->site['config']['SUPPORT_FRM'] == 2) { ?>
        <div class="content_right_login">
            <?php require(Kohana::find_file('views','templates/'.$this->site['config']['TEMPLATE'].'/support/frm',TRUE))?>
		</div>
        <?php } // end if have login form ?>
        <?php if (count($layout['list_right_menu']) > 0) { ?>
        <div class="content_right_menu"><?php require('menu_right.php')?></div>
        <?php } // end if ?>
        <div class="content_right_search"></div>
        <?php if (!empty($layout['right_banner']) && $layout['right_banner_order'] == 1) { ?>
        <div class="content_right_banner"><?php require('banner_right.php')?></div>
        <?php } // end if ?>
        <?php if (!empty($layout['right_B_banner'])) { ?>
        <div class="content_right_banner"><?php require('banner_right_B.php')?></div>
        <?php } // end if ?>
        </div>    
    </div>
    <div class="right_M_R"></div>
  </div>
<div style="clear:both">
    <div class="right_B_L"></div>
    <div class="right_B_Ce">&nbsp;</div>
    <div class="right_B_R"></div>
</div>
</div>