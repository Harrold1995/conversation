<?php

/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 12:33:13
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}

if (!wpmemory_isShellEnabled()) {
    echo 'Please, talk with your hosting company and request to then enable shell_exec function.';
    return;
}


$wpmemory_total_ram = shell_exec("grep -w 'MemTotal' /proc/meminfo | grep -o -E '[0-9]+'");

if (gettype($wpmemory_total_ram) != 'numeric')
    $wpmemory_total_ram = (int)$wpmemory_total_ram;

if ($wpmemory_total_ram > 0)
    $wpmemory_total_ram =    wpmemory_format_filesize_kB($wpmemory_total_ram);
else {
    echo 'Unable to find your total RAM memory. Please, ask to your hosting company.';
    return;
}

// Do not confuse with hardware physical memory).


echo '<div class="wrap-wpmemory ">' . "\n";
echo '<h2 class="title">Total of Server RAM</h2>' . "\n";
echo '<p class="description">The Server RAM (Random Access Memory) is a 
physical memory, which usually takes the form of cards (DIMMs) attached onto the motherboard.
Talk with your hosting company if you need to increase that.';
echo '<hr><strong>';
echo 'Total Current RAM Memory: ' . $wpmemory_total_ram;

echo '</strong><hr>';
echo '<br />';

echo '</div>';

function wpmemory_isShellEnabled()
{
    if (function_exists('shell_exec') && !in_array('shell_exec', array_map('trim', explode(', ', ini_get('disable_functions'))))) {
        $returnVal = shell_exec('cat /proc/cpuinfo');
        if (!empty($returnVal)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function wpmemory_format_filesize_kB($kiloBytes)
{
    if (($kiloBytes / pow(1024, 4)) > 1) {
        return number_format_i18n(($kiloBytes / pow(1024, 4)), 0) . ' ' . __('PB', 'wpmemory');
    } elseif (($kiloBytes / pow(1024, 3)) > 1) {
        return number_format_i18n(($kiloBytes / pow(1024, 3)), 0) . ' ' . __('TB', 'wpmemory');
    } elseif (($kiloBytes / pow(1024, 2)) > 1) {
        return number_format_i18n(($kiloBytes / pow(1024, 2)), 0) . ' ' . __('GB', 'wpmemory');
    } elseif (($kiloBytes / 1024) > 1) {
        return number_format_i18n($kiloBytes / 1024, 0) . ' ' . __('MB', 'wpmemory');
    } elseif ($kiloBytes >= 0) {
        return number_format_i18n($kiloBytes / 1, 0) . ' ' . __('KB', 'wpmemory');
    } else {
        return __('Unknown', 'wpmemory');
    }
}
