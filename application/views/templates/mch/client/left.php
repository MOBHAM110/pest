<div class="left">
<div style="clear:both">
    <div class="left_T_L"></div>
    <div class="left_T_Ce"></div>
    <div class="left_T_R"></div>
</div>
<div style="clear:both">
    <div class="left_M_L"></div>
    <div class="left_M_Ce">
        <div class="content_left">
        <?php if (!empty($layout['left_T_banner'])) { ?>
        <div class="content_left_banner"><?php require('banner_left_T.php')?></div>
        <?php } // end if ?>
        <?php if (isset($this->site['config']['LOGIN_FRM']) && $this->site['config']['LOGIN_FRM'] == 1) { ?>
        <div class="content_left_login">
            <?php require(Kohana::find_file('views','templates/'.$this->site['config']['TEMPLATE'].'/login/frm_login',TRUE))?>
		</div>
        <?php } // end if have login form ?>
        <div class="content_left_search"></div>
        <?php if (!empty($layout['list_left_menu'])) { ?>
        <div class="content_left_menu"><?php require('menu_left.php')?></div>
        <?php } // end if ?>
        <?php if (isset($this->site['config']['SUPPORT_FRM']) && $this->site['config']['SUPPORT_FRM'] == 1) { ?>
        <div class="content_left_support">
            <?php require(Kohana::find_file('views','templates/'.$this->site['config']['TEMPLATE'].'/support/frm',TRUE))?>
		</div>
        <?php } // end if have support form ?>
        <?php if (!empty($layout['left_B_banner'])) { ?>
        <div class="content_left_banner"><?php require('banner_left_B.php')?></div>
        <?php } // end if ?>
        </div>    
    </div>
    <div class="left_M_R"></div>
</div>
<div style="clear:both">
    <div class="left_B_L"></div>
    <div class="left_B_Ce">&nbsp;</div>
    <div class="left_B_R"></div>
</div>
</div>