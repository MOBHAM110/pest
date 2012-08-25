<form name="frm" id="frm" action="<?php echo $this->site['base_url']?>contact/sm" method="post">
<table class="contact" align="center" cellpadding="5" cellspacing="0" width="100%">
<tr>
    <td class="contact_title"><strong><?php echo $this->site['site_name']?></strong></td>
</tr>
<tr>	
    <td align="center" class="contact_content">
        <table class="contact_info">
        <?php if ($contact->contact_address == 1&&!empty($this->site['site_address'])) { ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_address')?></b> :</td>
            <td align="left"><?php echo $this->site['site_address']?></td>
        </tr>
        <?php } // end if ?>
        <?php if ($contact->contact_city == 1 || $contact->contact_state == 1 || $contact->contact_zipcode == 1) { ?>
        <tr>
            <td align="right"><b>
                <?php echo $contact->contact_city==1?Kohana::lang('account_lang.lbl_city'):''?>
                <?php echo ($contact->contact_city==1)&&($contact->contact_state==1)?'/':''?>
                <?php echo ($contact->contact_city==1)&&($contact->contact_zipcode==1)&&($contact->contact_state==0)?'/':''?>
                <?php echo $contact->contact_state==1?Kohana::lang('account_lang.lbl_state'):''?>
                <?php echo ($contact->contact_state==1)&&($contact->contact_zipcode==1)?'/':''?>
                <?php echo $contact->contact_zipcode==1?Kohana::lang('account_lang.lbl_zipcode'):''?></b> :
            </td>
            <td align="left">
                <?php echo $contact->contact_city==1?$this->site['site_city']:''?>
                <?php echo ($contact->contact_city==1)&&($contact->contact_state==1)?',':''?>
                <?php echo ($contact->contact_city==1)&&($contact->contact_zipcode==1)&&($contact->contact_state==0)?' ':''?>
                <?php echo $contact->contact_state==1?$this->site['site_state']:''?>
                <?php echo ($contact->contact_state==1)&&($contact->contact_zipcode==1)?' ':''?>
                <?php echo $contact->contact_zipcode==1?$this->site['site_zipcode']:''?>		
            </td>
        </tr>
        <?php } // end if ?>
        <?php if ($contact->contact_phone == 1) { ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_phone')?></b> :</td>
            <td align="left"><?php echo $this->site['site_phone']?></td>
        </tr>
        <?php } // end if ?>
        <?php if ($contact->contact_fax == 1) { ?>
        <tr>
            <td align="right"><b><?php echo Kohana::lang('account_lang.lbl_fax')?></b> :</td>
            <td align="left"><?php echo $this->site['site_fax']?></td>
        </tr>
        <?php } // end if ?>
        <?php if ($contact->contact_email == 1) { ?>
        <tr>
            <td width="37%" align="right"><b><?php echo Kohana::lang('account_lang.lbl_email')?></b> :</td>
            <td align="left"><?php echo $this->site['site_email']?></td>
        </tr>
        <?php } // end if ?>
        <?php if(!empty($this->site['site_contact_name'])){?>
        <tr>
            <td align="right" nowrap="nowrap"><b><?php echo Kohana::lang('account_lang.lbl_contact_name')?></b> :</td>
            <td align="left"><?php echo $this->site['site_contact_name']?></td>
        </tr>
        <?php } // end if ?>
        </table>    
    </td>
</tr>
<tr>
    <td align="center" class="contact_title">
        <strong><?php echo Kohana::lang('customer_lang.lbl_customer')?></strong>
    </td>
</tr>
<tr>
    <td align="center"  class="contact_content">
        <table class="contact_form">
        <tr>
            <td width="35%" align="right"><?php echo Kohana::lang('account_lang.lbl_name') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" name="txt_name" id="txt_name" size="45" value="<?php echo isset($mr['txt_name'])?$mr['txt_name']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_email') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" name="txt_email" id="txt_email" size="45" value="<?php echo isset($mr['txt_email'])?$mr['txt_email']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('account_lang.lbl_phone') ?></td>
            <td align="left"><input type="text" name="txt_phone" id="txt_phone" size="45" value="<?php echo isset($mr['txt_phone'])?$mr['txt_phone']:''?>" /></td>
        </tr>
        <tr>
            <td align="right"><?php echo Kohana::lang('global_lang.lbl_subject') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left"><input type="text" name="txt_subject" id="txt_subject" size="45" value="<?php echo isset($mr['txt_subject'])?$mr['txt_subject']:''?>" /></td>
        </tr>
        <tr>
            <td align="right" valign="top"><?php echo Kohana::lang('global_lang.lbl_content') ?>&nbsp;<font color="#FF0000">*</font></td>
            <td align="left">
                <textarea id="txt_content" name="txt_content" cols="48" rows="7"><?php isset($mr['txt_content'])?$mr['txt_content']:''?></textarea>
            </td>
        </tr>       
        <tr>
            <td colspan="2" align="center"><button type="submit" name="submit"><?php echo Kohana::lang('global_lang.btn_send') ?></button>
            &nbsp;<button id="reset" name="reset" type="reset"><?php echo Kohana::lang('global_lang.btn_reset') ?></button>
            </td>
        </tr>
        </table>    
    </td>
</tr>
</table>
</form>
<?php require('frm_js.php')?>