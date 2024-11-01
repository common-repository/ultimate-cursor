<?php

/**
 * Plugin Name:                 Ultimate Cursor Addons for Elementor
 * Plugin URI:                  https://wpxero.com/plugins/ultimate-cursor/
 * Description:                 The Ultimate Cursor Plugin is ideal for Elementor users seeking to enhance their website with fun and sparkle, and it is a great way to add a touch of personality to your website.
 * Version:                     1.2.1
 * Author:                      WPXERO
 * Author URI:                  https://wpxero.com
 * Requires at least:           6.0
 * Requires PHP:                7.4
 * License:                     GPL v2 or later
 * License URI:                 https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:                 ultimate-cursor
 * Elementor requires at least: 3.0.0
 * Elementor tested up to:      3.24.7
 */

namespace UltimateCursor;


if (!defined('ABSPATH')) {
    die(__('Direct Access is not allowed', 'ultimate-cursor'));
}


// Some pre define value for easy use
define('UCA_VERSION', '1.2.1');
define('UCA_FILE_', __FILE__);
define('UCA_URL', plugins_url('/', UCA_FILE_));
define('UCA_ASSETS_URL', UCA_URL . 'assets/');

final class UltimateCursorLoader {
    const VERSION                   = UCA_VERSION;
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';
    const MINIMUM_PHP_VERSION       = '7.0';

    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'ultimate-cursor'),
            '<strong>' . esc_html__('Ultimate Cursor', 'ultimate-cursor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'ultimate-cursor') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'ultimate-cursor'),
            '<strong>' . esc_html__('Ultimate Cursor', 'ultimate-cursor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'ultimate-cursor') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'ultimate-cursor'),
            '<strong>' . esc_html__('Ultimate Cursor', 'ultimate-cursor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'ultimate-cursor') . '</strong>'
        );
        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }
    public function init() {
        load_plugin_textdomain('ultimate-cursor', false, plugin_dir_path(__FILE__) . '/languages');
        // load  extension
        require(dirname(__FILE__) . '/extension/cursor.php');
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
            return;
        }
    }
}
UltimateCursorLoader::instance();
