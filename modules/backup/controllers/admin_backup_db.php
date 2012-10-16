<?php
class Admin_backup_db_Controller extends Template_Controller {
	
	public $template = 'admin/index';
	public $auto_render = true;
	public $mr;
	public $site;
	public $mlist;
	public $error ;
	public $file ;
	public $upload_dir;
    public $db_debug = FALSE;
	public $database;
    public $data_cache = array();
    
	function __construct()
	{
		parent::__construct();
		$this->upload_dir = DOCROOT.'/uploads/dbbackup/';
		$conn = Kohana::config('database.default.connection');
		
        if(!is_array($conn)){
            $arr = explode('/',$conn);            
            $this->database = $arr[count($arr) - 1];
        } else
            $this->database = Kohana::config('database.default.connection.database');
		
	}
    
	function _get_submit()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
		if($this->session->get('frm'))			
			$this->template->content->mr = $this->session->get('frm');
	}	
	
	function index()
	{		
		
		$this->showlist();
		if(Kohana::config('database.default.connection.database'))
		//$con  = Kohana::config('database.default.connection.database');
		//echo $con ;
		$this->_get_submit();
	}
    
	function showlist()
	{		
		$this->template->content = new View('admin_backup_db/list');	
		$error = false;
		$file  = false;
		if (!$error)
		{ 
		  $upload_max_filesize = ini_get("upload_max_filesize");
		  if (preg_match("/([0-9]+)K/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024;
		  if (preg_match("/([0-9]+)M/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024;
		  if (preg_match("/([0-9]+)G/i",$upload_max_filesize,$tempregs)) $upload_max_filesize=$tempregs[1]*1024*1024*1024;
		  $this->mr['upload_max_filesize'] = $upload_max_filesize; 
		  $this->mr['upload_dir'] = $this->upload_dir; 
		}
		////////////
		if (!$error && $this->input->post('uploadbutton')== true){ 
			if (is_uploaded_file($_FILES["dumpfile"]["tmp_name"]) && ($_FILES["dumpfile"]["error"])==0)
			{ 
		
				$uploaded_filename= str_replace(" ","_",$_FILES["dumpfile"]["name"]);
				$uploaded_filename= preg_replace("/[^_A-Za-z0-9-\.]/i",'',$uploaded_filename);
				$uploaded_filepath= str_replace("\\","/",$this->upload_dir."/".$uploaded_filename);
				
				if (file_exists($uploaded_filename))
				{ 
					$this->session->set_flash('error_msg',Kohana::lang('backup_restore_lang.lbl_file').' '.$uploaded_filename.' '.Kohana::lang('backup_restore_lang.error_file_exist'));
				}
				else if (!preg_match("/(\.(sql|zip|csv|gz))$/i",$uploaded_filename))
				{ 
					$this->session->set_flash('error_msg',Kohana::lang('backup_restore_lang.valid_file_type'));
				}
				else if (!@move_uploaded_file($_FILES["dumpfile"]["tmp_name"],$uploaded_filepath))
				{
				 	$this->session->set_flash('error_msg',"Error moving uploaded file ".$_FILES["dumpfile"]["tmp_name"]." to the $uploaded_filepath<br>Check the directory permissions for '".$this->upload_dir."' (must be 777)");
				}
				else
				{ 
					$this->session->set_flash('success_msg','Uploaded file saved as '.$uploaded_filename);
				}
		    }
		  else{
		  		 $this->session->set_flash('error_msg',"Error uploading file ".$_FILES["dumpfile"]["name"]."");
		    }
		}		
		$this->template->content->mr =  $this->mr;  
		$this->_get_submit();
	
	}
    
	function zip_extract($file, $extractPath)
    {
        $zip = new ZipArchive;
        $res = $zip->open($file);
        if ($res === TRUE) {
        	$zip->extractTo($extractPath);
        	$zip->close();
        	return TRUE;
        } else {
        	return FALSE;
        }
    }
    
	function import($fn)
	{		 
		 if(preg_match("/(\.(zip))$/i",$fn))
		 {
			 $this->zip_extract($this->upload_dir.$fn,$this->upload_dir);
			 $fn = substr($fn,0,-3);
			 $fn = $fn.'sql';			 
		 }
		
		 $curfilename=$this->upload_dir.$fn;
		 
		 if($this->mysql_install_db($this->database,$curfilename))
		 	$this->session->set_flash('success_msg',"Import database Success");
		 else
		  	$this->session->set_flash('success_msg',"Import database Success");
		//delete file extrac
		
		@unlink($this->upload_dir.basename($fn));
		url::redirect('admin_backup_db');
		die();
	}

	function mysql_import_file($filename)
	{
		/* Read the file */
		$lines = file($filename);
		
		if(!$lines)
		{
            $this->session->set_flash('error_msg',"cannot open file $filename");
            return false;
		}
		
		$scriptfile = false;
	
        /* Get rid of the comments and form one jumbo line */
		foreach($lines as $line)
		{
            $line = trim($line);
            
            if(!preg_match('/^--/', $line))
                $scriptfile.=" ".$line;
		}
		
		if(!$scriptfile)
		{
            $this->session->set_flash('error_msg',"no text found in $filename");
            return false;
		}
		$prev = 0;
		$i=1;
		while ($next = strpos($scriptfile,";",$prev+1))
		{
            $i++;
            $query = substr($scriptfile,$prev+1,$next-$prev);
            $this->db->query($query);
            $prev = $next;
		}
	}
	
	function mysql_install_db($dbname, $dbsqlfile)
	{
        $result = true;
        if(!mysql_select_db($dbname))
        {
            $result = mysql_query("CREATE DATABASE $dbname");
            if(!$result)
            {
                $this->session->set_flash('error_msg',"could not create [$dbname] db in mysql");
                return false;
            }
                $result = mysql_select_db($dbname);
        }
		
        if(!$result)
        {
            $errmsg = "Could not select [$dbname] database in mysql";
            return false;
        } else {		
            $result = $this->mysql_import_file($dbsqlfile);
            return $result;
        }
	}

	function delete($id)
	{
		if ($id)
		{
            if (preg_match("/(\.(sql|zip|csv|gz))$/i",$id) && @unlink($this->upload_dir.basename($upload_dir.$id)))
                $this->session->set_flash('success_msg',''.$id.' was removed successfully');
            else
                $this->session->set_flash('error_msg','Can not remove "'.$id.'"');
            url::redirect('admin_backup_db');
            die();
		}	
	}
	function backup()
    {		
		 $this->template->content = new View('admin_backup_db/frm');
	}
    
    
	function backupsm()
    {	
        $this->template->content = new View('admin_backup_db/frm');
        //$db = 'yesnotebook';
        //$this->backup_tables();
        if($this->backup_Database())
            $this->template->success_msg = Kohana::lang('backup_restore_lang.msg_backup_successfully');
        else 
            $this->template->error_msg = Kohana::lang('backup_restore_lang.msg_backup_error');
        $this->template->content->mr =  $this->mr;
        $this->_get_submit();
	}
    
	function backup_tables($tables = '*') 
	{
		$return ='';
		if($tables == '*') 
		{		      
            $tables = array();
			$dbname = 'Tables_in_'.$this->database;
            $result = $this->db->query('SHOW TABLES WHERE Tables_in_'.$this->database.' != "sessions"');
            $list = $result->as_array();
            for($i=0; $i<count($list); $i++)
            {
				$tables[] =  $list[$i]->{$dbname};
				
            }
			
		} else {
			$tables = is_array($tables) ? $tables : 
			explode(',',$tables);
		
		}
		foreach($tables as $table) 
        {
			$result = mysql_query('SELECT * FROM '.$table);
            $num_fields = mysql_num_fields($result);
            $return.= 'DROP TABLE IF EXISTS '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$row2[1] = str_replace(';',',',$row2[1]);
            $return.= "\n\n".$row2[1].";\n\n";
            for ($i = 0; $i < $num_fields; $i++) 
            {
                while($row = mysql_fetch_row($result)) 
                {
                	$return.= 'INSERT INTO '.$table.' VALUES(';
                	for($j=0; $j<$num_fields; $j++) 
                	{
                		$row[$j] = mysql_escape_string($row[$j]);
						$row[$j] = addslashes($row[$j]);
                		$row[$j] =str_replace("",'',$row[$j]);
						$row[$j] = str_replace(';',',',$row[$j]);
						$row[$j] = preg_replace("/\n/","\\n",$row[$j]);
				        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '" ""'; } 
                		if ($j<($num_fields-1)) { $return.= ','; } 
                	}
                    $return.= ');';
                }
			 }
			 $return.="\n\n\n";
		}
		$this->mr['file'] = $dbname.'-'.date("m-d-Y").'.gz'; 
		//echo $return;die();	 
		$handle = fopen($this->upload_dir.$dbname.'-'.date("m-d-Y").'.sql','w+');
		fwrite($handle,$return);
		fclose($handle);
		//File to be added inside zip archive.
		$file= $this->upload_dir.$dbname.'-'.date('m-d-Y').'.sql';
		//Open zip archive.
		$zip = new ZipArchive();
		if(($zip->open($this->upload_dir.$dbname.'-'.date("m-d-Y").'.zip',ZipArchive::CREATE))!==true)
		 $this->session->set_flash('error_msg',Kohana::lang('backup_restore_lang.error_create_zip'));
		//Add file by its real name.
		$zip->addFile($file,$dbname.'-'.date('m-d-Y').'.sql');
		$zip->close();
		@unlink($this->upload_dir.basename($file));		
	}
	
    
    // CLEAN QUERIES
    function clean($str) {
        if(@isset($str)){
            $str = @trim($str);
            if(get_magic_quotes_gpc()) {
                $str = stripslashes($str);
            }
            return mysql_real_escape_string($str);
        }
        else{
            return 'NULL';
        }
    }

    // DATABASE BACKUP CREATING FUNCTION
    function backup_Database($tables = '*')
    {
        if($tables == '*')
        {
            $tables = array();
            $DbName = 'Tables_in_'.$this->database;
            $result = $this->db->query('SHOW TABLES WHERE Tables_in_'.$this->database.' != "sessions"');
            //$result = $this->db->query('SHOW TABLES');
            $list = $result->as_array();
            for($i=0; $i<count($list); $i++)
            {
				$tables[] =  $list[$i]->{$DbName};
				
            }
        }
        else
        {
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        
//        $return = 'SET FOREIGN_KEY_CHECKS=0;' . "\r\n";
//        $return.= 'SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";' . "\r\n";
//        $return.= 'SET AUTOCOMMIT=0;' . "\r\n";
//        $return.= 'START TRANSACTION;' . "\r\n";

        $data = '';
        foreach($tables as $table)
        {
            $result = mysql_query('SELECT * FROM '.$table) or die(mysql_error());
            $num_fields = mysql_num_fields($result) or die(mysql_error());

            $data.= 'DROP TABLE IF EXISTS '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $data.= "\n\n".$row2[1].";\n\n";

            for ($i = 0; $i<$num_fields; $i++) 
            {
                while($row = mysql_fetch_row($result))
                {
                    $data.= "INSERT INTO `".$table."` VALUES(";
                    for($x=0; $x<$num_fields; $x++) 
                    {
                        $row[$x] = addslashes($row[$x]);
                        //$row[$x] = $this->clean($row[$x]);// CLEAN QUERIES
                        if (isset($row[$x])) { 
                        $data.= "'".$row[$x]."'" ; 
                        } else { 
                        $data.= "''"; 
                        }

                        if ($x<($num_fields-1)) { 
                        $data.= ","; 
                        }
                    }  
                    $data.= ");\n";
                } 
            } 

        $data.="\n\n\n";
        }  // end of the foreach*/
        $this->mr['file'] = $this->database.'_'.date('m-d-Y_H-i').'.sql.gz'; 
        
//        $return .= 'SET FOREIGN_KEY_CHECKS=1;' . "\r\n";
//        $return .= 'COMMIT;';

        /*/SAVE THE BACKUP AS SQL FILE
        $handle = fopen($this->upload_dir.$DbName.'-DB-'.date('m-d-Y-m').'.sql','w+');
        fwrite($handle,$data);
        fclose($handle);
        //*/
        //*/
        $gzdata = gzencode($data, 9);
        $handle = fopen($this->upload_dir.$this->database.'_'.date('m-d-Y_H-i').'.sql.gz','w+');
        fwrite($handle, $gzdata);
        fclose($handle);

        if($data)
            echo 'ok';
        else
            echo 'error';
    }  // end of the function
    
    
    function restore($db)
	{		 
        error_reporting(E_ALL ^ E_NOTICE);

        @include_once('phpMyImporterClass.php');

        $con = Kohana::config('database.default.connection');
        $dbhost = $con['host'];
        $dbuser = $con['user'];
        $dbpass = $con['pass'];
        $dbname = $con['database'];

        $filename  = $this->upload_dir.$db;
        $type = explode('.',$filename);
        $type = $type[count($type)-1]; 
        $compress  = ($type=='sql')?false:true;; 
        
        $connection = @mysql_connect($dbhost,$dbuser,$dbpass);
        
        $dump = new phpMyImporter($dbname,$connection,$filename,$compress);

        $dump->utf8 = true; 

    	if(!$dump->doImport())
            $this->session->set_flash('error_msg',"Import database Error");
		else
		  	$this->session->set_flash('success_msg',"Import database Success");
		
		@unlink($this->upload_dir.basename($fn));
		url::redirect('admin_backup_db');
        
		die();
	}

}
?>