<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 09:07:38
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
 global $wpmemory_memory;
    //display form
    echo '<div class="wrap-wpmemory ">' . "\n";
    echo '<h2 class="title">PHP and WordPress Memory</h2>' . "\n";
    echo '<p class="description"> This plugin check For High Memory Usage and include the result in the Tools => Site Health Page.';
    echo 'This plugin also Check Memory status and allows you to increase the Php Memory Limit and WordPress Memory Limit without editing any file.</p>' . "\n";

    /////////////////


    echo '<center><h2>Memory Usage</h2>';
    $ds = 256;
    $du = 60;
        $ds = $wpmemory_memory['wp_limit'];
        $du = $wpmemory_memory['usage'];
        if ($ds > 0)
            $perc = number_format(100 * $du / $ds, 0);
        else
            $perc = 0;
        if ($perc > 100)
            $perc = 100;
        //die($perc);
        $color = '#e87d7d';
        $color = '#029E26';
        if ($perc > 50)
            $color = '#e8cf7d';
        if ($perc > 70)
            $color = '#ace97c';
        if ($perc > 50)
            $color = '#F7D301';
        if ($perc > 70)
            $color = '#ff0000';
        $initValue = $perc;



    require_once "circle_memory.php";



    /////////////////////


    $mb = 'MB';
    echo '<br />';
    echo '<hr>';
    echo '<b>';
    echo 'WordPress Memory Limit (*): ' . $wpmemory_memory['wp_limit'] . $mb .
        '&nbsp;&nbsp;&nbsp;  |&nbsp;&nbsp;&nbsp;';
    $perc = $wpmemory_memory['usage'] / $wpmemory_memory['wp_limit'];
    if ($perc > .7)
        echo '<span style="color:' . $wpmemory_memory['color'] . ';">';
    echo 'Your usage now: ' . $wpmemory_memory['usage'] .
        'MB &nbsp;&nbsp;&nbsp;';
    if ($perc > .7)
        echo '</span>';
    echo '|&nbsp;&nbsp;&nbsp;   Total Php Server Memory (**): ' . $wpmemory_memory['limit'] .
        'MB';
    echo '</b>';
    echo '</center>';
    echo '<hr>';
    echo '<br />';
    echo 'The PHP memory limit needs be bigger than WordPress Memory Limit.';
    echo '<br />';
    echo '<br />';
    echo '(*)Instructions to increase WordPress Memory Limit:';
    echo '<a href="http://wpmemory.com/fix-low-memory-limit/">Click Here to Tips</a>';
    echo '<br />';
    echo '<br />';
    echo '(**) The Total Php Server Memory is the PHP "Memory Limit" usually defined on your php.ini file.';
    echo '<a href="http://wpmemory.com/php-memory-limit/">Click Here to learn more</a>';
    echo '<div class="main-notice">';
    echo '</div>' . "\n";
 //   echo '</div>';
    ?>
    <br /><br />
<b>
How to Tell if Your Site Needs a Shot of more Memory:
</b>
<br /><br />
If you got <i>Fatal error: Allowed memory size of xxx bytes exhausted</i> or
<br />
if your site is behaving slowly, or pages fail to load, you get random white screens of death or 500 internal server error you may need more memory. Several things consume memory, such as WordPress itself, the plugins installed, the theme you're using and the site content.
<br />
Basically, the more content and features you add to your site, the bigger your memory limit has to be. if you're only running a small site with basic functions without a Page Builder and Theme Options (for example the native Twenty twenty). However, once you use a Premium WordPress theme and you start encountering unexpected issues, it may be time to adjust your memory limit to meet the standards for a modern WordPress installation.
<br />
Increase the WP Memory Limit is a standard practice in WordPress and you find instructions also in the official WordPress documentation (Increasing memory allocated to PHP).
</div>