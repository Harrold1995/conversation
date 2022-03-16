<?php
namespace bill_banners;
/**
 * @author William Sergio Minossi
 * @copyright 26/11/2021
 */

$termina = get_transient('termina');
$wpmemory_checkversion = trim($wpmemory_checkversion);

// Debug
// $termina = false;

  
if (!$termina) {

     ob_start();

    // Debug
    // $wpmemory_checkversion = '123';


    if(!empty($wpmemory_checkversion)){
        $myarray = array(
            'checkversion' => $wpmemory_checkversion
        );
    }
    else
    {
        $myarray = array();  
    }

    $url = "https://billminozzi.com/API/bill-api.php";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()
    ));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        // echo "Something went wrong: $error_message";
        set_transient('termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
        ob_end_clean();
        return;
    }
    $r = trim($response['body']);
    ob_end_clean();
    $r = json_decode($r, true);

    if($r == NULL or count($r) < 5){
        set_transient('termina', time(), DAY_IN_SECONDS);
        $title = '';
        $image = '';
        return;
    }
    else {
        $type = $r['type'];
        if ($type == 'news')
            $message = $r['message'];
        else
            $code = $r['code'];
        $title = $r['title'];
        $termina = $r['termina'];

        set_transient('termina', $termina, DAY_IN_SECONDS);
        $image = $r['image'];
        set_transient('title', $title, DAY_IN_SECONDS);
        $x = set_transient('type', $type, DAY_IN_SECONDS);
        set_transient('image', $image, DAY_IN_SECONDS);
        if ($type == 'news')
            set_transient('message', $message, DAY_IN_SECONDS);
        else
            set_transient('code', $code, DAY_IN_SECONDS);

    }
} else {
    // termina existe
    $type = get_transient('type');
    if ($type == 'news')
        $message = get_transient('message');
    else
        $code = get_transient('code');
    $title = get_transient('title');
    $termina = get_transient('termina');
    $image = get_transient('image');
}

// Debug
//$termina = time() + DAY_IN_SECONDS;

if (empty($wpmemory_checkversion) or trim($type) == 'news' ) {

        // free always or news


        if ((strtotime($termina) > time()) and !empty($title) and  !empty($image)) {

            // show block...
            echo '<ul>';
            echo '<h2>' . $title . '</h2>';
            echo '<img src="' . WPMEMORYIMAGES . '/' . $image . '" width="250" />';
            if ($type == 'news'){
                echo "<br>";
                echo '<BIG>' . $message . '</BIG>';
            }

            else
                echo '<center><BIG>CODE: ' . $code . '</BIG></center>';
            echo '</ul>';
        } // if termina..

}


if (empty($wpmemory_checkversion)) {

        // Only Free

        echo '<ul>';
            $x = rand(1, 3);
            if($x == 1)
             $url = WPMEMORYURL."assets/videos/memory4.mp4";
            if($x == 2)
             $url = WPMEMORYURL."assets/videos/memory2.mp4";
            if($x == 3)
             $url = WPMEMORYURL."assets/videos/memory3.mp4";
        ?>

        <video id="bill-banner-2" style="margin:-20px 0px -15px -12px; padding:0px;" width="400" height="230" muted>
            <source src="<?php echo $url;?>" type="video/mp4">
        </video>

        <li>Go Premium and remove all plugin restrictions</li>
        <li>Take Your Site to the Next Level</li>   
        <li>Dedicated Premium Support</li>
        <li>More...</li>
        <br />
        <a href="https://wpmemory.com/premium/" class="button button-medium button-primary"><?php _e('Learn More', 'wpmemory'); ?></a>




        <?php
        echo '</ul>';
}

// Always...
echo '<ul>';
$x = rand(1, 3);
if ($x < 2) {
    echo '<h2>Like This Plugin?</h2>';
    _e('If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.<br><b>Thank you!</b>', 'wpmemory');
?>
    <br /><br />
    <a href="http://wpmemory.com/share/" class="button button-medium button-primary"><?php _e('Rate or Share', 'wpmemory'); ?></a>
<?php
} else {
    echo '<h2>Please help us keep the plugin live & up-to-date</h2>';
    _e('If you use & enjoy WP Memory Plugin, please rate it on WordPress.org. It only takes a second and helps us keep the plugin live and maintained. Thank you!', 'wpmemory');
?>
    <br /><br />

    <a href="https://wordpress.org/support/plugin/wp-memory/reviews/#new-post" class="button button-medium button-primary"><?php _e('Rate', 'wpmemory'); ?></a>
<?php
}

echo '</ul>';
?>