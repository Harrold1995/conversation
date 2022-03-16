<?php
/**
 * Message window topics list template
 *
 * @since 3.6.0
 *
 * @var array   $topic_list      Array of learndash topics.
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<option value="0">
    <?php echo sprintf( esc_html__( 'Select %s', 'wdm_instructor_role' ), \LearnDash_Custom_Label::get_label('topic') ); ?>
</option>
<?php foreach ( $topic_list as $topic ) : ?>
    <option value="<?php echo esc_attr( $topic->ID ); ?>">
        <?php echo wp_trim_words(
            sprintf(
                // translators: Topic label, Topic title.
                esc_html__(
                    "%s: %s",
                    'wdm_instructor_role'
                ),
                \LearnDash_Custom_Label::get_label('topic'),
                $topic->post_title
            )
        );
        ?>
    </option>
<?php endforeach; ?>
