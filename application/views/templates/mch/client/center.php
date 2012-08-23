<?php if (!empty($layout['center_top_banner'])) { ?>
<div class="frame_center_banner"><?php require('banner_center_top.php')?></div>
<?php } // end if have banner center top ?>
<div style="clear:both">
    <div class="frame_center_Co">
        <div class="center">
        <div class="center_M_T">
            <div class="center_T_L"></div>
            <div class="center_T_Ce"><?php require('center_title.php')?></div>
            <div class="center_T_R"></div>
        </div>
        <div class="center_M_Ce">
        	<div class="center_M">
            <div class="center_M_T"></div>
            <div class="center_M_M"><?php echo $content?></div>
            <div class="center_M_B"></div>
        	</div>
        </div>
        <div class="center_M_B">
            <div class="center_B_L"></div>
            <div class="center_B_Ce">&nbsp;</div>
            <div class="center_B_R"></div>
        </div>
        </div>
    </div>
</div>
<?php if (!empty($layout['center_bottom_banner'])) { ?>
<div class="frame_center_banner"><?php require('banner_center_bottom.php')?></div>
<?php } // end if have banner center bottom ?>