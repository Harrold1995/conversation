<?php namespace wp_bill_fix_config {
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:46:59
 */
    define('Error_config', 'Error to Update Config. ');
    $to = '';
    if (isset($_POST['email']))
        $to = $_POST['email'];
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        die("Error. Invalid email format. Aborted.");
    }


if(!isset($_COOKIE['fixconfig'])) 
  die('Unable to create cookie on your device. Please, enable cookies (-931)');



    
/*
    if (isset($_POST['verticalmenu_urlkey']))
        $verticalmenu_urlkey = $_POST['verticalmenu_urlkey'];
    else
        die("Error. Fail to get key (-931). Aborted.");
*/

        

//die($verticalmenu_urlkey);




    if (isset($_POST['url_config']))
        $url_config =  $_POST['url_config'];
    else
        die("Error. Fail to get url_config (-932). Aborted.");
        $pos = strpos($url_config, "wp-content/plugins/");
        if ($pos === false)
            die(Error_config . 'Fail to Create new config file. Aborted.');
        $siteroot = substr($url_config, 0, $pos);
        $pos = strpos($url_config, "fixconfig/fixconfig.php");
        $themeroot = substr($url_config, 0, $pos);
       $themepublicurl = $themeroot. 'public/';
      if (isset($_POST['qmem']))
        $qmem =  $_POST['qmem'];
    else
        die("Error. Fail to get New Memory Value (-940). Aborted.");  
    try {
        $mypath = getcwd();
    }
    catch (exception $e) {
        die('Error (1): ' . $e->getMessage());
    }
    $pos = strpos($mypath, '/wp-content/plugins/');
    if ($pos === false)
        die(Error_config . 'Unable to find theme path. Aborted.');
    $themepublic = substr($mypath, 0, $pos + 1);
    $configFilePath = $themepublic . 'wp-config.php';
    try {
        if (!is_readable($configFilePath))
            die(Error_config . 'Couldn\'t read wp-config.php file. Aborted.');
    }
    catch (exception $e) {
        die('Aborted. Error (2): ' . $e->getMessage());
    }
    try {
        $configContentOri = file_get_contents($configFilePath);
        $file = fopen($configFilePath, "r");
        $lines = array();
        while (!feof($file)) {
           $lines[] = fgets($file);
        }
        fclose($file);
    }
    catch (exception $e) {
        die('Aborted. Error (3): ' . $e->getMessage());
    }
    try {
        $asize = count($lines);
        if($asize < 10)
            die('Aborted. Error (3a): ' . $e->getMessage());
    }
    catch (exception $e) {
        die('Aborted. Error (3b): ' . $e->getMessage());
    }
    $configContent = '';
    for($i=0;$i<$asize;$i++){
       $line = trim($lines[$i]);
       $lookfor = 'WP_MEMORY_LIMIT'; 
       $pos = strpos($line, $lookfor);
       if ($pos === false)
       {
         $configContent .= $line.PHP_EOL;
       }
    }
    try {
        $bkpsizeori = filesize($configFilePath);
    }
    catch (exception $e) {
        die('Aborted. Error (3): ' . $e->getMessage());
    }
    $lookfor = "WP_MEMORY_LIMIT";
    $pos = strpos($configContent, $lookfor);
    if ($pos !== false){
         die(Error_config . 'You have the WP_MEMORY_LIMIT defined yet (1). Aborted.');
    }
    $mystring = "/* That's all, stop editing! Happy blogging. */";
    $look_for = array(
    "define('WP_DEBUG'",
    "define( 'WP_DEBUG'",
    'define ( "WP_DEBUG"', 
    'define ("WP_DEBUG"');
    FOR($i= 0; $i < 5; $i++)
    {
      $pos = strpos($configContent, $look_for[$i]);
      if($pos !== false)
        break;
    }
    if ($pos === false)
        die(Error_config . 'Fail to Edit the WP-CONFIG.PHP. Aborted.');
    $TMPconfigbegin = substr($configContent, 0, $pos);
    $TMPconfigend = substr($configContent, $pos);
    $newline = "define('WP_MEMORY_LIMIT', '".$qmem."M');" . PHP_EOL;
    $newconfig = $TMPconfigbegin . $newline . $TMPconfigend;
    # Check config file permissions
    $configok = true;
    try {
        if (!is_readable($configFilePath) || !is_writable($configFilePath)) {
            $configok = false;
        }
    }
    catch (exception $e) {
        die('Aborted. Error (5): ' . $e->getMessage());
    }
    if (!$configok) {
        try {
            if (!chmod($configFilePath, 0755)) {
                die(Error_config . 'Config file is not writable: ' . $configFilePath);
            }
        }
        catch (exception $e) {
            die('Aborted. Error: (18) ' . $e->getMessage());
        }
    }
        $pos = strpos($mypath, "dashboard");
        $themepathroot = substr($mypath, 0, $pos);
    $configBackup = $themepathroot . 'public/wp-config.php';
    $falhou = false;
    try {
        if (!file_put_contents($configContent, $configContentOri)) {
            $falhou = true;
        }
    }
    catch (exception $e) {
        die('Aborted. Error: (6) ' . $e->getMessage());
    }
    if ($falhou) {
        $dirfail = false;
        try {
            if (!is_writable($themepublic)) {
                $dirfail = true;
            }
        }
        catch (exception $e) {
            die('Aborted. Error: (7) ' . $e->getMessage());
        }
        if ($dirfail) {
            try {
                if (!chmod($themepublic, 0755)) {
                    die(Error_config . 'Could not Change Dir Permissions: ' . $themepublic .
                        ' Aborted.');
                }
            }
            catch (exception $e) {
                die('Aborted. Error: (8) ' . $e->getMessage());
            }
        }
        $filefail = false;
        try {
            if (!is_writable($configBackup)) {
                $filefail = true;
            }
        }
        catch (exception $e) {
            die('Aborted. Error: (9) ' . $e->getMessage());
        }
        if ($filefail) {
            try {
                if (!chmod($configBackup, 0755)) {
                    die(Error_config . 'Could not Change File Permissions: ' . $configBackup .
                        ' Aborted.');
                }
            }
            catch (exception $e) {
                die('Aborted. Error: (10) ' . $e->getMessage());
            }
        }
        // again
        try {
            if (!file_put_contents($configBackup, $configContentOri)) {
                $falhou = true;
                die(Error_config . 'Could not write Backup file: ' . $configBackup . ' Aborted.');
            }
        }
        catch (exception $e) {
            die('Aborted. Error: (11) ' . $e->getMessage());
        }
    }
    try {
        if (!is_readable($configBackup)) {
            die(Error_config . 'Invalid Backup File: ' . $configBackup . '  Aborted.');
        }
    }
    catch (exception $e) {
        die('Aborted. Error (12): ' . $e->getMessage());
    }
    try {
            $bkpsize = filesize($configBackup);
    }
    catch (exception $e) {
        die('Aborted. Error (13): ' . $e->getMessage());
    }
    if ($bkpsize != $bkpsizeori){
        die(Error_config . 'Backup file size error! Aborted');
}
    try {
        // die('File WP_CONFIG.PHP Updated !!! ');
        if (file_put_contents($configFilePath, $newconfig)) {
           $filefail = false;
        }
    }
    catch (exception $e) {
        die('Aborted. Error (14): ' . $e->getMessage());
    }
    if ($filefail) {
        try {
            if (!chmod($configFilePath, 0755)) {
                die(Error_config . 'Could not Change File Permissions: ' . $configFilePath .
                    ' Aborted.');
            }
        }
        catch (exception $e) {
            die('Aborted. Error: (16) ' . $e->getMessage());
        }
    }
    try {
        // die('File WP_CONFIG.PHP Updated !!! ');
        if (!file_put_contents($configFilePath, $newconfig))
            die(Error_config . 'Could not write config file: ' . $configFilePath);
    }
    catch (exception $e) {
        die('Aborted. Error (17): ' . $e->getMessage());
    }
    $restoreLink = create_restore_link($themepublicurl);
    $return = send($restoreLink, $to);
    if ($return == 1)
        die('WP-CONFIG.PHP File updated!');
    else
        die('Aborted. Error Code: ' . $return . ' to send email.');
    //////////////////////////////////////////////////////////////////////
    function create_restore_link($themepublic)
    {
        global $configContent;
        //global $verticalmenu_urlkey;
        $pos = strpos($themepublic, "wp-content/plugins/");
        if ($pos === false)
            die(Error_config . 'Fail to Create new config file. Aborted.');
      $pos = strpos($themepublic, "dashboard");
      $themepublic = substr($themepublic, 0, $pos);
       // $url = $themepublic . 'public/restore-config.php?key=' . $verticalmenu_urlkey;
       $url = $themepublic . 'public/restore-config.php';
        return $url;
    }
    function get_key()
    {
        global $configContent;
        $pos = strpos($configContent, "define('NONCE_KEY',");
        if ($pos === false)
         $pos = strpos($configContent, "define( 'NONCE_KEY',");  
        if ($pos === false)
            die(Error_config . 'Fail to find key constant. Aborted.');
        $key = trim(substr($configContent, $pos + 20, 25));
        $key = substr($key, 1, 10);
        return $key;
    }
    function get_domain()
    {
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        return $matches[0];
    }
    function send($restoreLink, $to)
    {
        $blogdomain = get_domain();
        $blogemail = 'WordPress@' . $blogdomain;
        $subject = 'WP Config File - Emergency Restore Link';
        $headers[] = 'Content-Type: text/plain';
        $headers[] = 'From: ' . $blogemail;
        $message[] = 'Here is a link to Restore your original  wp-config.php file.';
        $message[] = 'Use it only if you\'ve problem accessing your site after update the file today.';
        $message[] = $restoreLink;
        $headers = join("\r\n", $headers);
        $message = join("\n", $message);
        $status = mail($to, $subject, $message, $headers);
        return $status;
    }
}