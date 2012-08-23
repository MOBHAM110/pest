<?php
	for($i = 0; $i < count($arr[$list['permissions_id']]); $i++) {
?>

		
<label>
<input <?php echo ($arr[$list['permissions_id']][$i] == 2) ? 'checked="checked"' : ''?> type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-2" />
</label>

<label>
<input <?php echo ($arr[$list['permissions_id']][$i] == 3) ? 'checked="checked"' : ''?> type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-2" />
</label>

<label>
<input <?php echo ($arr[$list['permissions_id']][$i] == 4) ? 'checked="checked"' : ''?> type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-4" />
</label>


<label>
<input <?php echo ($arr[$list['permissions_id']][$i] == 5) ? 'checked="checked"' : ''?> type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-5" />
</label>


<label><input type="button" name="check<?php echo $list['permissions_id'];?>" value="Check All"/></label>
        
<?php
	}
?>


<?php
	if(isset($mRole_Permis) && isset($mlist)) 
	{
		foreach($mlist as $list) { 
			$arr = array();
			foreach($mRole_Permis as $r_p) {
				if($list['permissions_id'] == $r_p['permission_id']) {
					$arr[$list['permissions_id']] = explode('|',$r_p['role_perms_value']);
				
?>
	<tr>
		<td>
		<?php echo $list['permissions_name']; ?>
		</td>
		<td class="check-group<?php echo $list['permissions_id'];?>">
			
<!--<label><input type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-2" /></label>
<label><input type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-3" /></label>
<label><input type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-4" /></label>
<label><input type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-5" /></label>
<label><input type="button" name="check<?php echo $list['permissions_id'];?>" value="Check All"/></label>-->

		</td>
	</tr>

<?php
				} //if
			} //for 2
			
			/*echo "<pre>";
			print_r($arr);
			echo "</pre>";*/
		}// for1
	} else {
		foreach($mlist as $list) {
?>