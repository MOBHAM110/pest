<?php require('inc.php')?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Installation Wizard</title>
    <link href="install/asset/install.css" rel="stylesheet" type="text/css">
    <link href="install/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="install/asset/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="install/bootstrap/js/bootstrap.min.js"></script>
    <script src="install/asset/jquery.validate.js" type="text/javascript"></script>
    <?php require('js.php');?>
    </head>
    <body>
        <table align="center" class="install" border="0"  cellpadding="0" cellspacing="0" bordercolor="#eee"><tr><td align="center"> 
                    <table name="tbl_process" class="tbl_process" cellpadding="0" cellspacing="0" border="0" align="center">
                        <?php if ($cur_page == 'require') { ?>
                            <tr><td><img src="install/asset/1.png" /></td></tr><?php } ?>
                        <?php if ($cur_page == 'config') { ?>
                            <tr><td><img src="install/asset/2.png" /></td></tr><?php } ?>
                        <?php if ($cur_page == 'complete') { ?>
                            <tr><td><img src="install/asset/3.png" /></td></tr><?php } ?>
                    </table>
                    <p>
                        <?php if (!empty($errors)) { ?>
                        <div class="alert alert-error"><?php echo $errors ?></div><?php } // end if  ?>
                        <form name="frm_install" id="frm_install" class="form-horizontal" action="" method="post">
                            <table name="tbl_install" class="tbl_install" cellpadding="0" cellspacing="0" border="0" align="center">
                                <?php if ($cur_page == 'require') { ?>
                                    <?php
                                    $arr_path_files = array(
                                        'install',
                                        'application/config',
                                        'backups',
                                        'uploads'
                                    );
                                    ?>
                                    <!-- Requirements -->
                                    <tr><td><div class="page-header"><h3>Files Permissions</h3></div>        
                                    <div class="alert alert-info">The following files have to be writable</div></td></tr>
                                    <tr>
                                        <td>
                                            <table name="tbl_require" class="table table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0"  width="100%">
                                                <tr>
                                                    <th>File</th>
                                                    <th>Writable</th>
                                                </tr>
                                                <?php foreach ($arr_path_files as $path_file) { ?>
                                                    <tr>
                                                        <td><?php echo $path_file ?></td>
                                                        <td align="center"><?php echo is_writable($path_file) ? '<span class="label label-success">Writable</span>' : '<span class="label label-important">Not writable</span>' ?></td>
                                                    </tr>
                                                <?php } // end foreach  ?>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                    foreach ($arr_path_files as $path_file) {
                                        @chmod($path_file, 0777);
                                        if (!is_writable($path_file)) {
                                            $flag_next = FALSE;
                                            break;
                                        }
                                    }
                                    ?>
                                <?php } elseif ($cur_page == 'config') {?>
                                    <!-- Configurations -->
                                    <tr><td><div class="page-header"><h3>Database Connection</h3></div></td></tr>
                                    <tr>
                                        <td align="left">
                                            <table name="tbl_dbcon" cellpadding="7" cellspacing="0" align="left">
                                                <tr>
                                                    <td style="width:100px;">Database Type</td>
                                                    <td>
                                                        <select name="sel_db_type">
                                                            <option value="mysql">MySQL</option>
                                                        </select>            </td>
                                                </tr>
                                                <tr>
                                                    <td>Host <font color="red">*</font></td>
                                                    <td><input type="text" id="txt_host" name="txt_host" value="<?php echo !empty($db_con['host']) ? $db_con['host'] : 'localhost' ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Port</td>
                                                    <td>
                                                        <input type="text" name="txt_port" value="<?php echo !empty($db_con['port']) ? $db_con['port'] : '' ?>" />
                                                        (empty = Default Port 3306)            </td>
                                                </tr>
                                                <tr>
                                                    <td>Socket</td>
                                                    <td>
                                                        <input type="text" type="txt_socket" name="txt_socket" value="<?php echo !empty($db_con['socket']) ? $db_con['socket'] : '' ?>" />          
                                                        </select> (empty = No Socket)</td>
                                                </tr>
                                                <tr>
                                                    <td>Username <font color="red">*</font></td>
                                                    <td><input type="text" id="txt_username" name="txt_username" value="<?php echo !empty($db_con['username']) ? $db_con['username'] : '' ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Password</td>
                                                    <td><input type="password" id="txt_pass" name="txt_pass" value="<?php echo !empty($db_con['password']) ? $db_con['password'] : '' ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <td>DB Name <font color="red">*</font></td>
                                                    <td><input type="text" id="txt_db_name" name="txt_db_name" value="<?php echo !empty($db_con['name']) ? $db_con['name'] : '' ?>" /></td>
                                                </tr>
                                                <!--<tr>
                                                        <td>&nbsp;</td>
                                                    <td><font color="#FF0000"><strong>IMPORTANT NOTICE</strong> : The database has to be created already</font></td>
                                                </tr>-->
                                            </table>
                                        </td>
                                    </tr>
                                    <tr><td><div class="page-header"><h3>Website Settings</h3></div></td></tr>
                                    <tr>
                                        <td align="left">
                                            <table name="tbl_web_settings" cellpadding="7" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="20%">Site Domain <font color="red">*</font></td>
                                                    <td>
                                                        <?php $def_sd = ($_SERVER['SERVER_NAME'] == 'localhost' ? '' : $_SERVER['SERVER_NAME']) . '/' ?>
                                                        <input type="text" id="txt_site_domain" name="txt_site_domain" value="<?php echo !empty($site_con['site_domain']) ? $site_con['site_domain'] : $def_sd . substr($_SERVER['REQUEST_URI'], 1, strpos($_SERVER['REQUEST_URI'], '/', 1)) ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Site name <font color="red">*</font></td>
                                                    <td><input type="text" name="txt_site_name" id="txt_site_name" value="<?php echo !empty($site_con['site_name']) ? $site_con['site_name'] : '' ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Title</td>
                                                    <td><input type="text" name="txt_title" value="<?php echo !empty($site_con['title']) ? $site_con['title'] : '' ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Keyword</td>
                                                    <td><input type="text" name="txt_keyword" value="<?php echo !empty($site_con['keyword']) ? $site_con['keyword'] : '' ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Description</td>
                                                    <td><textarea name="txt_description" cols="50" rows="5" style="width:300px;"><?php echo !empty($site_con['description']) ? $site_con['description'] : '' ?></textarea></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr><td><div class="page-header"><h3>Administrator Account</h3></div></td></tr>
                                    <tr>
                                        <td>
                                            <table name="tbl_web_settings" cellpadding="7" cellspacing="0" width="100%">
                                                <tr>
                                                    <td width="20%">Email <font color="red">*</font></span></td>
                                                    <td><input type="text" id="txt_account_email" name="txt_account_email" value="<?php echo !empty($account['user_email']) ? $account['user_email'] : '' ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Username <font color="red">*</font></td>
                                                    <td><input type="text" id="txt_account_username" name="txt_account_username" value="<?php echo !empty($account['user_username']) ? $account['user_username'] : '' ?>" style="width:300px;"/></td>
                                                </tr>
                                                <tr>
                                                    <td>Password <font color="red">*</font></td>
                                                    <td><input type="password" id="txt_account_password" name="txt_account_password" value="" style="width:300px;"/></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php } elseif ($cur_page == 'complete') { ?>
                                    <!-- Complete -->
                                    <tr><td><div class="page-header"><h3>Administrator Complete</h3></div></td></tr>
                                    <tr>
                                        <td><pre>You have installed <?php echo $site_con['site_name'] ?> website successfully!
<br>The default account information for administrator is :
<br />- Username : <?php echo $account['user_username'] ?>
<br>- Password : <?php echo $account['user_pass'] ?>
<br />Url to Administrator page is <strong><?php $url_admin = 'http://'.($_SERVER['SERVER_NAME'] == 'localhost' ? 'localhost' : '') . $site_con['site_domain'] . 'tkadmin';?><?php echo $url_admin;?></strong>.
After log in, don't forget to change your password.
<br>You HAVE TO press one of two buttons below to the complete installing.
                                            </pre>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center"><br>
                                            <input type="button" class="btn" name="btn_client" value="Go to website" onclick="process_install('client');"/>
                                            <input type="button" class="btn" name="btn_admin" value="Go to Administrator page" onclick="process_install('admin');"/>
                                        </td>
                                    </tr>
                                <?php } // end if ?>
                                <?php if ($cur_page != 'complete') { ?>
                                    <tr>
                                        <td align="center"><br>
                                            <?php if ($cur_page != 'require') { ?>
                                                <input type="button"  class="btn"  name="btn_back" value="Back" onclick="process_install('back');"/>&nbsp;
                                            <?php } ?>
                                            <input type="submit" class="btn btn-primary" name="btn_next" value="Next" <?php if (!$flag_next) { ?>disabled="disabled"<?php } ?>/>
                                        </td>
                                    </tr>
                                <?php } // end if  ?>
                                	<tr><td align="center"><br /><font style="font-size:smaller">Copyright © 1982-<?php echo date('Y')?> <a href="http://www.akcomp.com" target="_blank">A&K Computer Inc</a>. All rights reserved.</font></td></tr>
                            </table>
                            <input type="hidden" name="hd_process" id="hd_process" value="" />
                        </form>
                        <script type="text/javascript">
                            function process_install(p){	
                                document.getElementById('hd_process').value = p;
                                document.getElementById('frm_install').submit();
                            }
                        </script>
                </td></tr></table>
    </body>
</html>