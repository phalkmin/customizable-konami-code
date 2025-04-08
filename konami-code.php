<?php
/**
 * Plugin Name: Customizable Konami Code
 * Plugin URI: http://www.guravehaato.info/blog/tenha-o-konami-code-no-seu-blog
 * Description: Adds Konami Code easter egg functionality to your WordPress site
 * Author: GraveHeart (Original) / Updated by You
 * Version: 2.0
 * Text Domain: konami-code
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Define plugin constants
 */
define('KONAMI_CODE_VERSION', '2.0');
define('KONAMI_CODE_PATH', plugin_dir_path(__FILE__));
define('KONAMI_CODE_URL', plugin_dir_url(__FILE__));

/**
 * Activation hook
 */
function konami_code_activate(): void {
    // Set default options if they don't exist
    if (!get_option('konami_code_string')) {
        add_option('konami_code_string', 'Konami Code Activated! You found the Easter egg!');
    }
    
    if (!get_option('konami_code_type')) {
        add_option('konami_code_type', 'text');
    }
    
    if (!get_option('konami_code_code')) {
        add_option('konami_code_code', '');
    }
}

/**
 * Register the settings menu
 */
function konami_code_register_menu(): void {
    add_options_page(
        __('Konami Code', 'konami-code'),
        __('Konami Code', 'konami-code'),
        'manage_options',
        'konami-code-settings',
        'konami_code_settings_page'
    );
}

/**
 * Register plugin settings
 */
function konami_code_register_settings(): void {
    register_setting('konami_code_options', 'konami_code_string', 'sanitize_text_field');
    register_setting('konami_code_options', 'konami_code_type', 'sanitize_text_field');
    register_setting('konami_code_options', 'konami_code_code', 'sanitize_text_field');
    
    add_settings_section(
        'konami_code_main_section',
        __('Konami Code Settings', 'konami-code'),
        'konami_code_section_callback',
        'konami-code-settings'
    );
    
    add_settings_field(
        'konami_code_type',
        __('Effect Type', 'konami-code'),
        'konami_code_type_callback',
        'konami-code-settings',
        'konami_code_main_section'
    );
    
    add_settings_field(
        'konami_code_string',
        __('Message / URL', 'konami-code'),
        'konami_code_string_callback',
        'konami-code-settings',
        'konami_code_main_section'
    );
    
    add_settings_field(
        'konami_code_code',
        __('Custom Code', 'konami-code'),
        'konami_code_code_callback',
        'konami-code-settings',
        'konami_code_main_section'
    );
}

/**
 * Settings section callback
 */
function konami_code_section_callback(): void {
    // No content needed here as we're using the postbox header
}

/**
 * Type field callback
 */
function konami_code_type_callback(): void {
    $type = get_option('konami_code_type');
    ?>
    <fieldset>
        <label class="screen-reader-text" for="konami_code_type_text"><?php _e('Show Alert Text', 'konami-code'); ?></label>
        <label>
            <input type="radio" id="konami_code_type_text" name="konami_code_type" value="text" <?php checked($type, 'text'); ?>>
            <span><?php _e('Show Alert Text', 'konami-code'); ?></span>
        </label><br>
        
        <label class="screen-reader-text" for="konami_code_type_load"><?php _e('Load Another Page', 'konami-code'); ?></label>
        <label>
            <input type="radio" id="konami_code_type_load" name="konami_code_type" value="load" <?php checked($type, 'load'); ?>>
            <span><?php _e('Load Another Page', 'konami-code'); ?></span>
        </label>
    </fieldset>
    <?php
}

/**
 * String field callback
 */
function konami_code_string_callback(): void {
    $string = get_option('konami_code_string');
    $type = get_option('konami_code_type');
    ?>
    <input type="text" id="konami_code_string" name="konami_code_string" value="<?php echo esc_attr($string); ?>" class="regular-text">
    <p class="description">
        <?php if ($type === 'text'): ?>
            <?php _e('Enter the message to display in an alert box.', 'konami-code'); ?>
        <?php else: ?>
            <?php _e('Enter the URL to redirect to (include https://).', 'konami-code'); ?>
        <?php endif; ?>
    </p>
    <?php
}

/**
 * Code field callback
 */
function konami_code_code_callback(): void {
    $code = get_option('konami_code_code');
    ?>
    <input type="text" id="konami_code_code" name="konami_code_code" value="<?php echo esc_attr($code); ?>" class="regular-text">
    <p class="description">
        <?php _e('Leave blank for default Konami Code (up, up, down, down, left, right, left, right, B, A).', 'konami-code'); ?>
    </p>
    <p class="description">
        <?php echo sprintf(
            __('To customize, use JavaScript key codes. See %sthis KeyCode reference%s.', 'konami-code'),
            '<a href="https://keycode.info/" target="_blank">',
            '</a>'
        ); ?>
    </p>
    <p class="description">
        <code>38,38,40,40,37,39,37,39,66,65</code> <?php _e('is the default Konami Code sequence.', 'konami-code'); ?>
    </p>
    <?php
}

/**
 * Render the settings page
 */
function konami_code_settings_page(): void {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="card">
            <h2><?php _e('About Konami Code', 'konami-code'); ?></h2>
            <p>
                <?php _e('The Konami Code is a cheat code that appears in many Konami video games: ↑ ↑ ↓ ↓ ← → ← → B A', 'konami-code'); ?>
            </p>
            <p>
                <?php _e('This plugin adds this classic easter egg to your WordPress site with customizable behavior.', 'konami-code'); ?>
            </p>
        </div>
        
        <form action="options.php" method="post">
            <div class="postbox">
                <div class="postbox-header">
                    <h2 class="hndle"><?php _e('Settings', 'konami-code'); ?></h2>
                </div>
                <div class="inside">
                    <?php
                    settings_fields('konami_code_options');
                    do_settings_sections('konami-code-settings');
                    submit_button(__('Save Settings', 'konami-code'));
                    ?>
                </div>
            </div>
        </form>
        
        <div class="card">
            <h3><?php _e('How to Test', 'konami-code'); ?></h3>
            <p>
                <?php _e('Visit your website and type: ↑ ↑ ↓ ↓ ← → ← → B A', 'konami-code'); ?>
            </p>
            <p><strong><?php _e('Note:', 'konami-code'); ?></strong> <?php _e('On a keyboard, use arrow keys, then letters B and A.', 'konami-code'); ?></p>
        </div>
    </div>
    <?php
}

/**
 * Enqueue scripts and add konami code to the front-end
 */
function konami_code_enqueue_scripts(): void {
    wp_enqueue_script(
        'konami-code-js',
        KONAMI_CODE_URL . 'assets/js/konami.min.js',
        [],
        KONAMI_CODE_VERSION,
        true
    );
    
    $konami_string = get_option('konami_code_string');
    $konami_type = get_option('konami_code_type');
    $konami_code = get_option('konami_code_code');
    $konami_url = get_site_url();
    
    $script = "
        document.addEventListener('DOMContentLoaded', function() {
            var konami = new Konami();
            " . ($konami_code ? "konami.pattern = \"$konami_code\";" : "") . "
            
            " . ($konami_type === 'text' ? "
                konami.code = function() {
                    alert(\"" . esc_js($konami_string) . "\");
                };
                konami.load();
            " : "") . "
            
            " . ($konami_type === 'load' ? "
                konami.load(\"" . esc_js($konami_string) . "\");
            " : "") . "
        });
    ";
    
    wp_add_inline_script('konami-code-js', $script);
}

/**
 * Initialize plugin
 */
function konami_code_init(): void {
    load_plugin_textdomain('konami-code', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

/**
 * Add admin styles
 */
function konami_code_admin_styles(): void {
    $screen = get_current_screen();
    
    // Only add styles on our settings page
    if ($screen && $screen->id === 'settings_page_konami-code-settings') {
        ?>
        <style>
            .konami-code-row th {
                padding-top: 15px;
            }
            
            .konami-code-row .description code {
                background: #f0f0f1;
                padding: 2px 5px;
                border-radius: 3px;
            }
            
            .konami-code-row .description {
                margin-top: 5px;
            }
            
            .wrap .card {
                max-width: 800px;
                margin-bottom: 20px;
            }
            
            .wrap .postbox {
                max-width: 800px;
            }
        </style>
        <?php
    }
}

// Add hooks
register_activation_hook(__FILE__, 'konami_code_activate');
add_action('init', 'konami_code_init');
add_action('admin_menu', 'konami_code_register_menu');
add_action('admin_init', 'konami_code_register_settings');
add_action('admin_head', 'konami_code_admin_styles');
add_action('wp_enqueue_scripts', 'konami_code_enqueue_scripts');