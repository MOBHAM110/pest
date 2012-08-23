<table align="center" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php if (isset($error_msg))  echo '<div class="error message"><img src="'.url::base().'themes/ui/pics/icon_error.png">'.$error_msg.'</div>'; ?>
      <?php if (isset($warning_msg))  echo '<div class="warning message"><img src="'.url::base().'themes/ui/pics/icon_warning.png">'.$warning_msg.'</div>'; ?>
      <?php if (isset($info_msg))  echo '<div class="info message"><img src="'.url::base().'themes/ui/pics/icon_info.png">'.$info_msg.'</div>'; ?>
      <?php if (isset($success_msg))  echo '<div class="success message"><img src="'.url::base().'themes/ui/pics/icon_success.png">'.$success_msg.'</div>'; ?></td>
  </tr>
</table>