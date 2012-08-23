<?php if (!empty($layout['center_top_banner'])) { ?>
<div class="frame_center_banner"><?php require('banner_center_top.php')?></div>
<?php } // end if have banner center top ?>
<div class="frame_center_Co">
    <div class="center_home">
    <div class="center_home_T">
        <div class="center_home_T_L"></div>
        <div class="center_home_T_Ce"><?php //echo $this->mr['page_title']?></div>
        <div class="center_home_T_R"></div>
    </div>
    <div class="center_home_M">
        <div class="center_home_M_L"></div>
        <div class="center_home_M_Ce">
            <div class="center_home_M">
            <div class="center_home_M_T"></div>
            <div class="center_home_M_M"><?php echo $content?></div>
            <div class="center_home_M_B"></div>
            </div>
        </div>
        <div class="center_home_M_R"></div>
    </div>
    <div class="center_home_B">
        <div class="center_home_B_L"></div>
        <div class="center_home_B_Ce">&nbsp;</div>
        <div class="center_home_B_R"></div>
    </div>
    </div>
</div>
<?php if (!empty($layout['center_bottom_banner'])) { ?>
<div class="frame_center_banner"><?php require('banner_center_bottom.php')?></div>
<?php } // end if have banner center bottom ?>