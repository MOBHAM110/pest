<table cellspacing="0" cellpadding="0" class="home" align="center">
<?php
/* */ for ($i=1;$i<=$this->site['config']['HOME_NUM_ROW'];$i++) { ?>
<tr>
	<?php $colspan = 1 // Variable count colspan?>	
	<?php for ($k=1;$k<=$this->site['config']['HOME_NUM_COL'];$k++) { if (!isset($mlist[$i][$k])) $colspan++; }?>
    
	<?php for ($j=1;$j<=$this->site['config']['HOME_NUM_COL'];$j++) { ?>
    	<?php if (isset($mlist[$i][$j])) { ?>			
            <?php
				$css = '';
				if ($this->site['config']['HOME_NUM_COL'] != 1)
				{
					if ($j == 1 && next($mlist[$i]) !== FALSE) $css = ' a';
					elseif ($j != 1)
					{
						if (next($mlist[$i]) === FALSE) $css = ' c';
						else $css = ' b';
					} 
				}
			?>    	
        	<td class="home_<?php echo $mlist[$i][$j]['page_type_name'].$css?>" <?php if ($colspan!==1) { echo 'colspan="'.$colspan.'"'; $colspan = 1; }?> width="<?php echo 100/($this->site['config']['HOME_NUM_COL']==0?1:$this->site['config']['HOME_NUM_COL'])?>%" height="100%" valign="top"> 
            	<?php $view = new View('templates/'.$this->site['config']['TEMPLATE'].'/home/'.$mlist[$i][$j]['page_type_name'])?>
                <?php $view->mlist = $mlist[$i][$j]?>
                <?php $view->render(TRUE)?>
				<?php //require($mlist[$i][$j]['page_type_name'].'.php')?>
            </td>
        <?php } // end if page empty ?>
    <?php } // end for j (col) ?>
</tr>
<?php } // end for i (row) ?>
</table>
<?php require('akcomp.php')?>