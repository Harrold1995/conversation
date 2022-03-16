<?php
/**
 * Student Communication Settings Template
 *
 * @since 3.6.0
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div class="wrap">
    <h3><?php esc_html_e( 'Student Communication Settings', 'wdm_instructor_role' ); ?></h3>

    <form method="post" id="ir-student-communication-settings-form">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="ir_st_comm_editor_accent_color">
                            <?php esc_html_e( 'Accent Color', 'wdm_instructor_role' ); ?>
                        </label>
                    </th>
                    <td>
                    <input type="text" name="ir_st_comm_editor_accent_color" id="ir_st_comm_editor_accent_color" value="<?php echo esc_attr( $ir_st_comm_editor_accent_color ); ?>" data-default-color="#00ACD3" />
                    </td>
                </tr>
            </tbody>
        </table>
        <input id="ir_student_communication_settings_save" type="submit" class="button button-primary" name="ir_student_communication_settings_save" value="<?php esc_html_e( 'Save', 'wdm_instructor_role' ); ?>">
        <?php wp_nonce_field( 'ir_stu_com_settings_nonce', 'ir_nonce' ); ?>
    </form>
</div>