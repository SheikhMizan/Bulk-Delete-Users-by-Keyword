<?php
/*
Plugin Name: Bulk Delete Users by Keyword
Description: Advanced bulk user deletion with AJAX processing and progress tracking.
Version: 2.0
Author: Sheikh MiZan
Author URI: https://sheikhmizan.com
Requires at least: 5.5
Tested up to: 6.8
Requires PHP: 7.4
License: GPL v2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') || exit;

class BDUBK_Bulk_Delete_Users {

    public function __construct() {
        add_action('admin_menu', [$this, 'bdubk_custom_admin_page']);
        add_action('admin_init', [$this, 'bdubk_process_bulk_delete']);
        add_action('admin_enqueue_scripts', [$this, 'bdubk_enqueue_admin_styles']);
        add_action('admin_init', [$this, 'bdubk_register_settings']);
        add_action('wp_ajax_bdubk_get_user_count', [$this, 'ajax_get_user_count']);
        add_action('wp_ajax_bdubk_process_batch', [$this, 'ajax_process_batch']);
    }

    public function bdubk_register_settings() {
        register_setting('bdubk_settings', 'bdubk_batch_size', [
            'type' => 'integer',
            'default' => 100,
            'description' => 'Number of users to process in each batch',
            'sanitize_callback' => 'absint'
        ]);
    }

    public function bdubk_enqueue_admin_styles($hook_suffix) {
        if ($hook_suffix === 'toplevel_page_bdubk_bulk_delete_users') {
            wp_enqueue_style('bdubk-admin-styles', plugin_dir_url(__FILE__) . 'css/bdubk-admin-styles.css', [], '2.0');
            wp_enqueue_script('bdubk-admin-script', plugin_dir_url(__FILE__) . 'js/bdubk-admin-script.js', ['jquery'], '2.0', true);
            
            wp_localize_script('bdubk-admin-script', 'bdubk_ajax', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('bdubk_ajax_nonce'),
                'processing_text' => __('Processing...', 'bdubk'),
                'complete_text' => __('Process completed!', 'bdubk'),
                'error_text' => __('An error occurred:', 'bdubk')
            ]);
        }
    }

    public function bdubk_custom_admin_page() {
        add_menu_page(
            'Bulk Delete Users by Keyword',
            'Bulk Delete Users',
            'manage_options',
            'bdubk_bulk_delete_users',
            [$this, 'bdubk_render_keyword_settings_page'],
            'dashicons-trash'
        );
    }

    public function bdubk_render_keyword_settings_page() {
        $batch_size = get_option('bdubk_batch_size', 100);
        ?>
        <div class="wrap">
            <h2><?php esc_html_e('Bulk Delete Users by Keyword', 'bdubk'); ?></h2>

            <?php settings_errors('bdubk_bulk_delete_users'); ?>

            <form method="post" action="" id="bdubk-bulk-delete-form">
                <?php wp_nonce_field('bdubk_bulk_delete_users_action', 'bdubk_bulk_delete_users_nonce'); ?>
                
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Keyword', 'bdubk'); ?></th>
                        <td><input type="text" name="bdubk_keyword" value="" class="regular-text" required /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Batch Size', 'bdubk'); ?></th>
                        <td>
                            <input type="number" name="bdubk_batch_size" value="<?php echo esc_attr($batch_size); ?>" min="1" max="1000" />
                            <p class="description"><?php esc_html_e('Number of users to process at a time (recommended: 100-500 for large sites)', 'bdubk'); ?></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Search In', 'bdubk'); ?></th>
                        <td>
                            <label><input type="checkbox" name="bdubk_search_fields[]" value="user_login" checked> <?php esc_html_e('Username', 'bdubk'); ?></label><br>
                            <label><input type="checkbox" name="bdubk_search_fields[]" value="user_nicename" checked> <?php esc_html_e('Nickname', 'bdubk'); ?></label><br>
                            <label><input type="checkbox" name="bdubk_search_fields[]" value="display_name" checked> <?php esc_html_e('Display Name', 'bdubk'); ?></label><br>
                            <label><input type="checkbox" name="bdubk_search_fields[]" value="user_email" checked> <?php esc_html_e('Email', 'bdubk'); ?></label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Bulk Delete Users', 'bdubk'), 'primary', 'bdubk_submit'); ?>
                
                <div id="bdubk-progress-container" style="display: none;">
                    <div id="bdubk-progress-bar">
                        <div id="bdubk-progress-bar-fill"></div>
                    </div>
                    <p id="bdubk-progress-text"><?php esc_html_e('Preparing to process...', 'bdubk'); ?></p>
                    <p id="bdubk-processed-count"><?php esc_html_e('Users processed:', 'bdubk'); ?> <span>0</span></p>
                    <p id="bdubk-deleted-count"><?php esc_html_e('Users deleted:', 'bdubk'); ?> <span>0</span></p>
                </div>
            </form>

            <div class="warning">
                <h2><?php esc_html_e('Warning!', 'bdubk'); ?></h2>
                <p><?php esc_html_e('Deleting users will permanently remove them from the database. This action cannot be undone. It\'s strongly recommended to backup your database before performing bulk deletion.', 'bdubk'); ?></p>
                <p><?php esc_html_e('For large sites, the process may take several minutes. Do not close the browser window until the process completes.', 'bdubk'); ?></p>
            </div>
        </div>
        <?php
    }

    public function bdubk_process_bulk_delete() {
        if (isset($_POST['bdubk_bulk_delete_users_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bdubk_bulk_delete_users_nonce'])), 'bdubk_bulk_delete_users_action')) {
            if (isset($_POST['bdubk_batch_size'])) {
                $new_batch_size = absint($_POST['bdubk_batch_size']);
                if ($new_batch_size > 0) {
                    update_option('bdubk_batch_size', $new_batch_size);
                }
            }
        }
    }

    public function ajax_get_user_count() {
        try {
            check_ajax_referer('bdubk_ajax_nonce', 'nonce');

            if (!current_user_can('manage_options')) {
                throw new Exception(__('Permission denied', 'bdubk'), 403);
            }

            $keyword = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
            $search_fields = isset($_POST['search_fields']) ? array_map('sanitize_text_field', wp_unslash($_POST['search_fields'])) : 
                              ['user_login', 'user_nicename', 'display_name', 'user_email'];

            if (empty($keyword)) {
                throw new Exception(__('Keyword is required', 'bdubk'), 400);
            }

            $args = [
                'search'         => '*' . $keyword . '*',
                'search_columns' => $search_fields,
                'fields'         => 'ID',
                'count_total'    => true
            ];

            $user_query = new WP_User_Query($args);
            $total_users = $user_query->get_total();

            /* translators: %d: number of users found */
            $message = sprintf(_n('%d user found', '%d users found', $total_users, 'bdubk'), $total_users);

            wp_send_json_success([
                'total_users' => $total_users,
                'message' => $message
            ]);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), $e->getCode());
        }
    }

    public function ajax_process_batch() {
        try {
            check_ajax_referer('bdubk_ajax_nonce', 'nonce');

            if (!current_user_can('manage_options')) {
                throw new Exception(__('Permission denied', 'bdubk'), 403);
            }

            $keyword = isset($_POST['keyword']) ? sanitize_text_field(wp_unslash($_POST['keyword'])) : '';
            $search_fields = isset($_POST['search_fields']) ? array_map('sanitize_text_field', wp_unslash($_POST['search_fields'])) : 
                             ['user_login', 'user_nicename', 'display_name', 'user_email'];
            $offset = isset($_POST['offset']) ? absint($_POST['offset']) : 0;
            $batch_size = isset($_POST['batch_size']) ? absint($_POST['batch_size']) : 100;

            if (empty($keyword)) {
                throw new Exception(__('Keyword is required', 'bdubk'), 400);
            }

            $args = [
                'search'         => '*' . $keyword . '*',
                'search_columns' => $search_fields,
                'number'         => $batch_size,
                'offset'         => $offset,
                'fields'         => 'ID',
                'orderby'        => 'ID',
                'order'          => 'ASC'
            ];

            $users = get_users($args);
            $deleted = 0;
            $errors = [];

            foreach ($users as $user_id) {
                if (!wp_delete_user($user_id)) {
                    /* translators: %d: user ID that failed to delete */
                    $errors[] = sprintf(__('Failed to delete user ID %d', 'bdubk'), $user_id);
                } else {
                    $deleted++;
                }
            }

            $result = [
                'processed' => count($users),
                'deleted' => $deleted,
                'offset' => $offset,
                'batch_size' => $batch_size
            ];

            if (!empty($errors)) {
                $result['errors'] = $errors;
            }

            wp_send_json_success($result);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage(), $e->getCode());
        }
    }
}

new BDUBK_Bulk_Delete_Users();