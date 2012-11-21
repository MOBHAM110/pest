<?php
session_start();
ini_set('display_errors', 0);
$flag_next = TRUE;
$errors = '';
if (!isset($_SESSION['sess_page']))
    $_SESSION['sess_page'] = 'require';
if ($config['has_install'] === 'complete')
    $_SESSION['sess_page'] = 'complete';
$db_con = isset($_SESSION['db_con']) ? $_SESSION['db_con'] : array();
$site_con = isset($_SESSION['site_con']) ? $_SESSION['site_con'] : array();
$account = isset($_SESSION['account']) ? $_SESSION['account'] : array();
$cur_page = $_SESSION['sess_page'];
if (isset($_POST['hd_process'])) {
    switch ($cur_page) {
        case 'require' : {
                $cur_page = ($_POST['hd_process'] == 'next') ? 'config' : 'require';
                break;
            }
        case 'config' : {
                //$_SESSION['sess_page'] = ($_POST['hd_process'] == 'next') ? 'complete' : 'require';
                if ($_POST['hd_process'] == 'next') { // submit from config
                    $db_con = array(
                        'type' => $_POST['sel_db_type'],
                        'host' => trim($_POST['txt_host']),
                        'port' => trim($_POST['txt_port']),
                        'socket' => trim($_POST['txt_socket']),
                        'username' => trim($_POST['txt_username']),
                        'password' => trim($_POST['txt_pass']),
                        'name' => trim($_POST['txt_db_name'])
                    );
                    $_SESSION['db_con'] = $db_con;

                    $site_con = array(
                        'site_domain' => str_replace('http://', '', trim($_POST['txt_site_domain'])),
                        'site_name' => trim($_POST['txt_site_name']),
                        'title' => trim($_POST['txt_title']),
                        'keyword' => trim($_POST['txt_keyword']),
                        'description' => trim($_POST['txt_description'])
                        //'lang' => $_POST['sel_lang']
                    );
                    $_SESSION['site_con'] = $site_con;

                    $config_db_file = file_get_contents(APPPATH . 'config/database.php');
                    $config_file = file_get_contents(APPPATH . 'config/config.php');
                    $js_menu = file_get_contents(DOCROOT . 'plugins/ddsmoothmenu/ddsmoothmenu.js');

                    if ($config_db_file === FALSE || $config_file === FALSE) {
                        $errors = "Could not open file 'config.php' or 'database.php'";
                        break;
                    } else {
                        $db_file = $con_file = FALSE;

                        $config_db_file = preg_replace("/'type'.*,/", "'type' => '" . $db_con['type'] . "',", $config_db_file);
                        $config_db_file = preg_replace("/'user'.*,/", "'user' => '" . $db_con['username'] . "',", $config_db_file);
                        $config_db_file = preg_replace("/'pass'.*,/", "'pass' => '" . $db_con['password'] . "',", $config_db_file);
                        if (empty($db_con['socket'])){
                            //$config_db_file = preg_replace("/'host'.*,/", "'host' => '" . $db_con['host'] . "',", $config_db_file);
                            $config_db_file = preg_replace("/'host'.*,/", "'host' => '',", $config_db_file);
                            $config_db_file = preg_replace("/'connection'.*,/", "'connection' => 'mysql://" . $db_con['username'] . ':' . $db_con['password'] . '@' . $db_con['host'] . '/' . $db_con['name'] . "',", $config_db_file);
                        } else {
                            $config_db_file = preg_replace("/'host'.*,/", "'host' => '',", $config_db_file);
                            $config_db_file = preg_replace("/'connection'.*,/", "'connection' => 'mysql://" . $db_con['username'] . ':' . $db_con['password'] . '@' . $db_con['host'] . ':' . $db_con['socket'] . '/' . $db_con['name'] . "',", $config_db_file);
                        }
                        if (!empty($db_con['port']))
                            $config_db_file = preg_replace("/'port'.*,/", "'port' => '" . $db_con['port'] . "',", $config_db_file);
                        $config_db_file = preg_replace("/'database'.*/", "'database' => '" . $db_con['name'] . "'", $config_db_file);

                        if (!empty($config_db_file))
                            $db_file = file_put_contents(APPPATH . 'config/database.php', $config_db_file);

                        $site_domain = $site_con['site_domain'];
                        $config_file = preg_replace("\$config\['site_domain'].*;$", "config['site_domain'] = '$site_domain';", $config_file);

                        if (!empty($config_file))
                            $con_file = file_put_contents(APPPATH . 'config/config.php', $config_file);

                        if ($db_file === FALSE || $con_file === FALSE) {
                            $errors = "Write in file 'config.php' or 'database.php' failure";
                            break;
                        }
                        /* $url_base = $config['site_protocol']."://".($_SERVER['SERVER_NAME']=='localhost'?'localhost':$_SERVER['SERVER_NAME'])."$site_domain/";
                          $js_file = str_replace('|kohana_url_base|', $url_base,$js_menu);
                          $js_file = file_put_contents(DOCROOT.'plugins/ddsmoothmenu/ddsmoothmenu.js', $js_file); */
                    }
                    // Check socket

                    if (!empty($db_con['socket']))
                        $db_con['host'] = $db_con['host'] . ':' . $db_con['socket'];
                    $con = mysql_connect($db_con['host'], $db_con['username'], $db_con['password']);                    
                    if (!$con) {
                        $errors = 'Could not connect: ' . mysql_error();
                        break;
                    }
                    @mysql_set_charset('utf8',$con);
                    @mysql_query("SET NAMES 'utf8'");
                    @mysql_query("SET CHARACTER_SET_CLIENT=utf8");
                    @mysql_query("SET CHARACTER_SET_RESULTS=utf8");
                    //Check Account
                    if ($_POST['txt_account_username'] == "" || $_POST['txt_account_password'] == "") {
                        $errors = 'Account information for administrator (username,password) is required';
                        break;
                    }
                    $arr_dir = scandir('database', 1);
                    $db_file_sql = $arr_dir[0];
                    if (!is_file("database/$db_file_sql") || strrpos($db_file_sql, '.sql') === FALSE) {
                        $errors = 'File SQL contain database is not exist';
                        break;
                    }

                    $dump_db = mysql_install_db($db_con['name'], "database/$db_file_sql", $con);

                    if ($dump_db !== TRUE) {
                        $errors = $dump_db;
                        break;
                    }

                    $name = $site_con['site_name'];
                    $title = $site_con['title'];
                    $key = $site_con['keyword'];
                    $des = $site_con['description'];
                    $sql_site = "UPDATE `site` SET `site_name` = '$name', `site_title` = '$title', `site_keyword` = '$key', `site_description` = '$des' WHERE `site`.`site_id` = 1 LIMIT 1;";
                    //$sql_conf_admin_lang = "UPDATE `configuration` SET `configuration_value` = '2' WHERE `configuration`.`configuration_id` =13 LIMIT 1 ;"
                    mysql_query($sql_site, $con);

                    // Infor Account
                    $account = array(
                        'user_email' => $_POST['txt_account_email'],
                        'user_username' => $_POST['txt_account_username'],
                        'user_pass' => $_POST['txt_account_password']
                    );
                    $_SESSION['account'] = $account;

                    $email = $account['user_email'];
                    $username = $account['user_username'];
                    $user_pass = md5($account['user_pass']);
                    $sql_account = "INSERT INTO `user`(user_name,user_pass,user_email,user_level,user_status) VALUES ('$username','$user_pass','$email','2','1');";
                    mysql_query($sql_account, $con);
                    $user_id = mysql_insert_id();
                    mysql_query("INSERT INTO `admin`(user_id)VALUES('$user_id')", $con);
                    //End Account

                    $config_file = preg_replace("\$config\['has_install'].*;$", "config['has_install'] = 'complete';", $config_file);
                    if (!empty($config_file))
                        $con_file = file_put_contents(APPPATH . 'config/config.php', $config_file);

                    $cur_page = 'complete';
                }
                elseif ($_POST['hd_process'] == 'back') {
                    $cur_page = 'require';
                }

                break;
            }
        case 'complete' : {

                if ($_POST['hd_process'] == 'client' || $_POST['hd_process'] == 'admin') { // submit from complete
                    $config_file = file_get_contents(APPPATH . 'config/config.php');
                    $config_file = preg_replace("\$config\['has_install'].*;$", "config['has_install'] = true;", $config_file);

                    if (!empty($config_file))
                        $con_file = file_put_contents(APPPATH . 'config/config.php', $config_file);

                    if ($con_file === FALSE)
                        $errors = "Write in file 'config.php' or 'database.php' failure";
                    else {
                        $redirect = 'http://' . ($_SERVER['SERVER_NAME'] == 'localhost' ? 'localhost' : '');
                        $redirect .= $site_con['site_domain'] . ($_POST['hd_process'] == 'admin' ? 'tkadmin' : '');
                        rename(DOCROOT . "install/install.php", DOCROOT . "install/un_install.php");
                        session_destroy();
                        header("Location: $redirect");
                        exit('Redirect failure');
                    }
                }

                break;
            }
    }
}
//echo var_dump($db_con);
?>
<?php
$_SESSION['sess_page'] = $cur_page;
?>
<?php

function _utf8_decode($string) {
    $tmp = $string;
    $count = 0;
    while (mb_detect_encoding($tmp) == "UTF-8") {
        $tmp = utf8_decode($tmp);
        $count++;
    }

    for ($i = 0; $i < $count - 1; $i++) {
        $string = utf8_decode($string);
    }
    return $string;
}
function mysql_import_file($filename, $link_con) {
    /* Read the file */
    $lines = file($filename); //print_r($lines); die();

    if (!$lines) {
        return "cannot open file $filename";
    }

    $scriptfile = false;

    /* Get rid of the comments and form one jumbo line */
    foreach ($lines as $line) {
        $line = trim($line);

        if (!ereg('^--', $line))
            $scriptfile.=" " . $line;
    }

    if (!$scriptfile) {
        return "No text found in $filename";
    }

    $prev = 0;
    $i = 1;
    while ($next = strpos($scriptfile, ";", $prev + 1)) {
        $i++;
        $query = substr($scriptfile, $prev + 1, $next - $prev);
        mysql_query($query, $link_con); //print_r($query); die();
        $prev = $next;
    }

    return TRUE;
}
function mysql_install_db($dbname, $dbsqlfile, $link_con) {

    $db_selected = mysql_select_db($dbname, $link_con);

    if ($db_selected) {
        //$result = mysql_query("SHOW TABLES FROM $dbname", $link_con);
        $result = mysql_list_tables($dbname, $link_con);
        if (mysql_num_rows($result) > 0)
            return "Database [$dbname] has been exist and not empty";
    }
    else {
        $result = mysql_query("CREATE DATABASE $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;", $link_con);

        if (!$result) {
            if (empty($dbname))
                return "Database name is required";
            return "Could not create [$dbname] database in mysql";
        }

        $db_selected = mysql_select_db($dbname, $link_con);
    }


    if (!$db_selected) {
        return "Could not select [$dbname] database in mysql";
    } else {
        return mysql_import_file($dbsqlfile, $link_con);
    }
}
?>