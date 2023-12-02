<?php 

/*
 * Plugin Name:       Topbar News
 * Plugin URI:        #
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Adbrains
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */


 if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
//enqueue css for frontend
function load_plugin_styles() {
    wp_enqueue_style('topbar-news', plugins_url('/css/style.css', __FILE__), array(), '1.0');
}
add_action('wp_enqueue_scripts', 'load_plugin_styles');
//enqueue css for backend
function admin_load_plugin_styles() {
    wp_enqueue_style('topbar-news', plugins_url('/css/style.css', __FILE__), array(), '1.0');
}
add_action('admin_enqueue_scripts', 'admin_load_plugin_styles');

// Add admin menu page
add_action('admin_menu', 'add_topbar_admin_menu');

function add_topbar_admin_menu()
{
    add_menu_page(
        'Topbar News',
        'Topbar News',
        'manage_options',
        'topbar-news',
        'topbar_news_page',
        'dashicons-format-status',
        30                      // Menu position
    );
}

// Callback function for the admin menu page
function topbar_news_page()
{
    // Check if the form is submitted
    if (isset($_POST['topbar_news'])) {
        // Allow HTML tags and save the submitted content
        $saved_news = wp_kses_post($_POST['topbar_news']);
        update_option('topbar_news_option', $saved_news);
        echo '<div class="updated"><p>News saved!</p></div>';
    }
    // Retrieve the saved news
    $saved_news = get_option('topbar_news_option', '');

?>
    <div class="container">
        <h2 class="font-bold text-lg">Topbar News</h2>
        <!-- Form for saving the news -->
        <form method="post">
            <?php
            // Use the WordPress editor
            wp_editor(
                $saved_news,
                'topbar_news', // Editor ID and Name
                array(
                    'textarea_name' => 'topbar_news',
                    'textarea_rows' => 10,
                )
            );
            ?>
            <button class="button button-primary" type="submit">Save</button>
        </form>
        <p>This is your Topbar news page. You can add your news here.</p>
    </div>
<?php
}

// Register the shortcode
function topbar_news_shortcode() {
    ob_start(); // Start output buffering

    ?>
    <div class="container topBarNews">
        <?php
        // Retrieve the saved message
        $saved_message = get_option('topbar_message_option', '');

        // Output the saved message with HTML tags
        echo wpautop(do_shortcode($saved_message));
        ?>
    </div>
    <?php

    return ob_get_clean(); // Get the output and clean the buffer
}

add_shortcode('topbar_news', 'topbar_news_shortcode');
//append in header.php file
function append_topbar_news_shortcode() {
    echo do_shortcode('[topbar_news]');
}

add_action('wp_head', 'append_topbar_news_shortcode', 10);

