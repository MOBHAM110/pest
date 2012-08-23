<?php
	$per = '';
	if(uri::segment(2) == 'edit')
		$per = 'edit';
	
	if(uri::segment(2) == 'create')
		$per = 'add';
	
	if(!$this->permisController($per)) {
?>
<form name="frmlist" id="frmlist" action="<?php echo url::base() ?>admin_role/search" method="post" >
<table cellspacing="0" cellpadding="0" class="title">
<tbody><tr>
    <td class="title_label"><?php echo Kohana::lang('role_lang.tt_page')?></td>
    <td class="title_button">
    	<?php if($this->permisController('add')) { ?>
        <button type="button" class="button new" onclick="javascript:location.href='<?php echo url::base() ?>admin_role/create'">
        <span><?php echo Kohana::lang('role_lang.btn_new') ?></span>
        </button>
        <?php } ?>
    </td> 
</tr>
</tbody></table>
<table cellspacing="1" cellpadding="5" class="list">
  <tbody>
      <tr class="list_header">
      	<td style="color:#900;font-weight:bold;"><?php echo Kohana::lang('global_lang.authorized') ?></td>
      </tr>
	</tbody>
</table>
</form>
<?php   		
	} else {
?>
<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>admin_role/save" method="post" enctype="multipart/form-data" >
<table class="title" cellspacing="0" cellpadding="0">
  <tr>
    <td class="title_label"><?php echo Kohana::lang('role_lang.tt_page')?></td>
    <td class="title_button">
   <button type="button" name="btn_back" class="button back" onclick="javascript:location.href='<?php echo $this->site['base_url']?>admin_role'">
    <span><?php echo Kohana::lang('global_lang.btn_back')?></span>
    </button>
    &nbsp;
   <?php if($this->permisController('add')) { ?>
    <button type="submit" name="btn_save" class="button save">
    <span><?php echo Kohana::lang('global_lang.btn_save')?></span>
    </button>
    <?php } ?>
    </td>
  </tr>
</table>

<table class="form" align="center" cellpadding="5" cellspacing="0" border="0">
	<tr>
    	<!-- start col 1 -->
    	<td class="col1">
        	<fieldset>
            	<legend></legend>
                <table cellpadding="0" cellspacing="5">
                	<tr>
                    	<td>
                        	<label><?php echo Kohana::lang('role_lang.lbl_name')?>:<font color="#FF0000">*</font></label>
                            <input type="text" value="<?php echo (isset($mr['role_name']) ? $mr['role_name'] : ''); ?>" name="txt_name"/>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<label><?php echo Kohana::lang('global_lang.lbl_status')?>:<font color="#FF0000">*</font></label>
                            <select name="sel_status" id="sel_status">
                                <option value="1"><?php echo Kohana::lang('global_lang.lbl_active') ?></option>
                                <option value="0" <?php if(isset($mr) && $mr['role_status'] == 0) echo 'selected' ?>><?php echo Kohana::lang('global_lang.lbl_inactive') ?></option>      
                            </select>
                        </td>
                    </tr>
                    <tr>
                    	<td>
                        	<input type="hidden" value="<?php echo (isset($mr['role_id']) ? $mr['role_id'] : ''); ?>" name="hd_id" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
        <!-- end col 1 -->
        <!-- start col 2-->
        <td class="col2">
        	<fieldset>
            	<legend></legend>
                <table cellpadding="0" cellspacing="5">
                    <tr>
                        <td style="font-weight:bold;width:40%"><?php echo Kohana::lang('role_lang.lbl_page_name')?></td>
                        <td>
                        <label style="font-weight:bold;width:25%"><?php echo Kohana::lang('role_lang.lbl_view')?></label>
                        <label style="font-weight:bold;width:25%""><?php echo Kohana::lang('role_lang.lbl_add')?></label>
                        <label style="font-weight:bold;width:25%""><?php echo Kohana::lang('role_lang.lbl_edit')?></label>
                        <label style="font-weight:bold;width:25%""><?php echo Kohana::lang('role_lang.lbl_delete')?></label>
                       <!-- <label style="font-weight:bold">&nbsp;</label>-->
                        </td>
                    </tr>
                    <?php
						if(isset($mRole_Permis) && isset($mlist)) 
						{
							foreach($mlist as $list) { 
								$view = ''; $add = ''; $edit = ''; $delete = '';$all= Kohana::lang('role_lang.btn_check_all');
								foreach($mRole_Permis as $r_p) {
									if($list['permissions_id'] == $r_p['permission_id']) {
										echo '<input type="hidden" value="'. $r_p['permission_id'] .'" name="hd_group[]" />';
										$values = explode('|',$r_p['role_perms_value']);
										//Set view
										foreach($values as $vl) {
											if($vl == 2) {
												$view = 'checked="checked"';
												break;
											} else 
												$view = '';
										}
										
										//Set add
										foreach($values as $vl) {
											if($vl == 3) {
												$add = 'checked="checked"';
												break;
											} else 
												$add = '';
										}
										
										//Set edit
										foreach($values as $vl) {
											if($vl == 4) {
												$edit = 'checked="checked"';
												break;
											} else 
												$edit = '';
										}
										
										//Set delete
										foreach($values as $vl) {
											if($vl == 5) {
												$delete = 'checked="checked"';
												break;
											} else 
												$delete = '';
										}
									} //if
									if($view != '' && $add != '' && $edit != '' && $delete != '')
										$all = Kohana::lang('role_lang.btn_check_un');
								} //for2
									
					?>
                        <tr>
                            <td>
                            <?php echo $list['permissions_name']; ?>
                            </td>
                            <td class="check-group<?php echo $list['permissions_id'];?>">
                            <label style="font-weight:bold;width:25%">
                            	<input <?php echo ($add != '' || $edit != '' || $delete != '') ? 'disabled="disabled"' : '' ?> <?php echo $view; ?> id="view<?php echo $list['permissions_id'];?>" type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-2" />
                            </label>
                            <label style="font-weight:bold;width:25%">
                            	<input <?php echo ($edit != '' || $delete != '') ? 'disabled="disabled"' : '' ?> <?php echo $add; ?> onclick="addCheck(this.value);" id="add<?php echo $list['permissions_id'];?>" type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-3" />
                            </label>
                            <label style="font-weight:bold;width:25%">
                            	<input <?php echo $delete != '' ? 'disabled="disabled"' : '' ?> <?php echo $edit; ?> onclick="editCheck(this.value);" id="edit<?php echo $list['permissions_id'];?>" type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-4" />
                            </label>
                            <label style="font-weight:bold;width:25%">
                            	<input <?php echo $delete; ?> onclick="deleteCheck(this.value);" id="delete<?php echo $list['permissions_id'];?>" type="checkbox" name="group[]" value="<?php echo $list['permissions_id'];?>-5" />
                            </label>
                            <!--<label><input type="button" name="check<?php echo $list['permissions_id'];?>" value="<?php echo $all; ?>"/></label>-->
                            </td>
                        </tr>
                   <?php
							}// for1
						} else {
							foreach($mlist as $list) {
					?>
                    <tr>
                        <td>
                        <?php echo $list['permissions_name']; ?>
                        </td>
                        <td class="check-group<?php echo $list['permissions_id'];?>">
                        <input type="hidden" value="" name="hd_group[]" />
                        <label style="font-weight:bold;width:25%">
                        	<input type="checkbox" id="view<?php echo $list['permissions_id'];?>" name="group[]" value="<?php echo $list['permissions_id'];?>-2" />
                        </label>
                        <label style="font-weight:bold;width:25%">
                        	<input type="checkbox" onclick="addCheck(this.value);" id="add<?php echo $list['permissions_id'];?>" name="group[]" value="<?php echo $list['permissions_id'];?>-3" />
                        </label>
                        <label style="font-weight:bold;width:25%">
                        	<input type="checkbox" onclick="editCheck(this.value);" id="edit<?php echo $list['permissions_id'];?>" name="group[]" value="<?php echo $list['permissions_id'];?>-4" />
                        </label>
                        <label style="font-weight:bold;width:25%">
                        	<input type="checkbox" onclick="deleteCheck(this.value);" id="delete<?php echo $list['permissions_id'];?>" name="group[]" value="<?php echo $list['permissions_id'];?>-5" />
                        </label>
                       <!-- <label><input type="button" name="check<?php echo $list['permissions_id'];?>" value="<?php echo Kohana::lang('role_lang.btn_check_all')?>" /></label>-->
                        </td>
                    </tr>
                    <?php
							}
						}
					?>
                    
                </table>
            </fieldset>
        </td>
        <!-- end col 2-->
    </tr>
</table>
</form>

<script type="text/javascript">
	/*$(document).ready(function(){
		<?php foreach($mlist as $list) { ?>		
		$(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']").click(
			function()
			{
				if($(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']").val() == '<?php echo Kohana::lang('role_lang.btn_check_all')?>'){
					$(".check-group<?php echo $list['permissions_id'];?> input[name='group[]'][type='checkbox']").attr('checked',true);
					$(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']").val("<?php echo Kohana::lang('role_lang.btn_check_un')?>").hide().fadeIn(1000, function() {
					  $(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']");
					});
				}
				else {
					$(".check-group<?php echo $list['permissions_id'];?> input[name='group[]'][type='checkbox']").removeAttr('checked');
					
					$(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']").val("Check All").hide().fadeIn(1000, function() {
					  $(".check-group<?php echo $list['permissions_id'];?> input[name='check<?php echo $list['permissions_id'];?>'][type='button']");
					});
				}
			}
		);
		<?php }?>
	});*/

var addCheck = function(id) {
	var kind = id.split('-');
	chk = document.getElementById('add'+kind[0]);
	var option = new Array('view');
	optionCheck(chk,option,kind[0]);
}
	
var editCheck = function(id) {
	//alert(id);
	var kind = id.split('-');
	chk = document.getElementById('edit'+kind[0]);
	var option = new Array('view','add');
	optionCheck(chk,option,kind[0]);
}

var deleteCheck = function(id) {
	//alert(id);
	var kind = id.split('-');
	chk = document.getElementById('delete'+kind[0]);
	var option = new Array('view','add','edit');
	optionCheck(chk,option,kind[0]);
}

var optionCheck = function(check,option,id) {
	if(chk.checked == true) {
		for(var i = 0; i < option.length; i++) {
			$(".check-group"+id+" input[id='"+ option[i] + id+"'][type='checkbox']").attr('checked',true);
			$(".check-group"+id+" input[id='"+ option[i] + id+"'][type='checkbox']").attr('disabled',true);
		}
		
		if(option.length >= 3) {
			$(".check-group"+id+" input[name='check"+id+"'][type='button']").val("<?php echo Kohana::lang('role_lang.btn_check_un')?>").hide().fadeIn(1000, function() {
					  $(".check-group"+id+" input[name='check"+id+"'][type='button']");
					});
		}
	} else {
		for(var i = 0; i < option.length; i++) {
			$(".check-group"+id+" input[id='"+ option[i] + id+"'][type='checkbox']").removeAttr('checked');
			$(".check-group"+id+" input[id='"+ option[i] + id+"'][type='checkbox']").removeAttr('disabled');
		}
		if(option.length >= 3) {
			$(".check-group"+id+" input[name='check"+id+"'][type='button']").val("<?php echo Kohana::lang('role_lang.btn_check_all')?>").hide().fadeIn(1000, function() {
					  $(".check-group"+id+" input[name='check"+id+"'][type='button']");
					});
		}
	}
}
</script>
<?php } ?>
