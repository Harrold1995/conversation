<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:38:04
 */
  Global $wpmemory_checkversion;
 if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
  } 
?>

<div id="wp-memory-logo" >
   
    <img src="<?php echo WPMEMORYIMAGES;?>/logo.png" width="200">
</div>
<?php
if( isset( $_GET[ 'tab' ] ) ) 
    $active_tab = sanitize_text_field($_GET[ 'tab' ]);
else
   $active_tab = 'dashboard';

?>
<h2 class="nav-tab-wrapper">
    <a href="tools.php?page=wp_memory_admin_page&tab=dashboard" class="nav-tab">Dashboard</a>
    <a href="tools.php?page=wp_memory_admin_page&tab=phpmemory" class="nav-tab">Update PHP Memory Limit</a>
    <a href="tools.php?page=wp_memory_admin_page&tab=wpmemory" class="nav-tab">Update WordPress Memory Limit</a>
    <a href="tools.php?page=wp_memory_admin_page&tab=hardware" class="nav-tab">Hardware Memory</a>
    <a href="tools.php?page=wp_memory_admin_page&tab=tools" class="nav-tab">More Tools</a>
<?php

  if(empty($wpmemory_checkversion))
    echo '<a href="tools.php?page=wp_memory_admin_page&tab=premium" class="nav-tab" style="color:red;">Premium</a>';
  else
    echo '<a href="tools.php?page=wp_memory_admin_page&tab=premium" class="nav-tab">Premium</a>'
?>


</h2>
<?php  

// Do not confuse with hardware physical memory).

if($active_tab == 'phpmemory') { 
  echo '<div id="wpmemory-dashboard-wrap">';
  echo '<div id="wpmemory-dashboard-left">';    
    require_once (WPMEMORYPATH . 'dashboard/memory-fix-php.php');
    ?>
    </div> <!-- "wpmemory-dashboard-left"> -->
    <div id="wpmemory-dashboard-right">
        <div id="wpmemory-containerright-dashboard">
            <?php require_once(WPMEMORYPATH . "dashboard/mybanners.php"); ?>
        </div>
    </div> <!-- "wpmemory-dashboard-right"> -->
</div> <!-- "car-dealer-dashboard-wrap"> -->
<?php
 } 
 elseif($active_tab == 'wpmemory') {  
  echo '<div id="wpmemory-dashboard-wrap">';
  echo '<div id="wpmemory-dashboard-left">';   
    require_once (WPMEMORYPATH . 'dashboard/memory-fix.php');
    ?>
    </div> <!-- "wpmemory-dashboard-left"> -->
    <div id="wpmemory-dashboard-right">
        <div id="wpmemory-containerright-dashboard">
            <?php require_once(WPMEMORYPATH . "dashboard/mybanners.php"); ?>
        </div>
    </div> <!-- "wpmemory-dashboard-right"> -->
</div> <!-- "car-dealer-dashboard-wrap"> -->
<?php
 } 
 elseif($active_tab == 'premium') {     
   require_once (WPMEMORYPATH . 'dashboard/premium.php');
 } 
 elseif($active_tab == 'tools') {     
   require_once (WPMEMORYPATH . 'dashboard/tools.php');
 } 
 elseif($active_tab == 'hardware') {  
   
  echo '<div id="wpmemory-dashboard-wrap">';
  echo '<div id="wpmemory-dashboard-left">';

  require_once (WPMEMORYPATH . 'dashboard/hardware.php');

  ?>
    </div> <!-- "wpmemory-dashboard-left"> -->
    <div id="wpmemory-dashboard-right">
        <div id="wpmemory-containerright-dashboard">
            <?php require_once(WPMEMORYPATH . "dashboard/mybanners.php"); ?>
        </div>
    </div> <!-- "wpmemory-dashboard-right"> -->
</div> <!-- "car-dealer-dashboard-wrap"> -->
<?php
} 
 else
 { 
  echo '<div id="wpmemory-dashboard-wrap">';
  echo '<div id="wpmemory-dashboard-left">';

    require_once (WPMEMORYPATH . 'dashboard/dashboard.php');
?>
    </div> <!-- "wpmemory-dashboard-left"> -->
    <div id="wpmemory-dashboard-right">
        <div id="wpmemory-containerright-dashboard">
            <?php require_once(WPMEMORYPATH . "dashboard/mybanners.php"); ?>
        </div>
    </div> <!-- "wpmemory-dashboard-right"> -->
</div> <!-- "car-dealer-dashboard-wrap"> -->
<?php

 }

 ////////////////////////////////////
?>