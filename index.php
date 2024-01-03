<?php
/*
Plugin Name: Persona Picker Plugin
Author: Mayur Jobanputra
Description: A plugin to create and manage a 'Persona' custom post type, with a unique URL structure and shortcode functionality.
Version: 1.4.1
*/

// Activation Hook
function persona_picker_activate() {
    if (get_option('persona_picker_post_type_created')) {
        // The post type was already created by this plugin, so it's safe to continue.
        create_persona_post_type();
        flush_rewrite_rules();
    } else if (!post_type_exists('persona')) {
        create_persona_post_type();
        update_option('persona_picker_post_type_created', true);
        flush_rewrite_rules();
    } else {
        die('Persona post type already exists. Plugin activation stopped.');
    }
}

register_activation_hook(__FILE__, 'persona_picker_activate');

// Deactivation Hook
function persona_picker_deactivate() {
    //unregister_post_type('persona');
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'persona_picker_deactivate');



// Register Custom Post Type with dynamic slug
function create_persona_post_type($slug = 'you-are') {
    register_post_type('persona', array(
        'labels' => array('name' => __('Personas'), 'singular_name' => __('Persona')),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'categories', 'tags'),
        'rewrite' => array('slug' => $slug),
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-groups'
    ));
}

// Check if a given slug exists
function persona_picker_slug_exists($slug) {
    global $wpdb;
    return $wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = '" . $slug . "'", 'ARRAY_A') != null;
}

add_action('init', 'create_persona_post_type');

// Shortcode to display Persona titles
function persona_picker_shortcode() {
    $query = new WP_Query(array('post_type' => 'persona'));
    $output = '';

    while ($query->have_posts()) {
        $query->the_post();
        $post_title = get_the_title();
        $slug_title = sanitize_title($post_title);
        $output .= "<span class='persona-choice'><a href='#tab-" . $slug_title . "'>" . $post_title . "</a></span>";
    }

    wp_reset_postdata();
    return $output;
}

add_shortcode('persona_picker', 'persona_picker_shortcode');

// Function to create a shortcode for each Persona. Output should be like <div class="tab-pane" id="persona-title">...</div>
function persona_tabs_shortcode($atts) {
    $atts = shortcode_atts(array('title' => ''), $atts);
    $slug_title = sanitize_title($atts['title']);
    $query = new WP_Query(array('post_type' => 'persona', 'name' => $slug_title));
    $output = '';

    while ($query->have_posts()) {
        $query->the_post();
        $post_title = get_the_title();
        //sanitize the title to be used as an id for the tab-pane
        $slug_title = sanitize_title($post_title);
        $post_content = get_the_content();
        //replace all <p> tags with <br><br> tags so that the persona content is displayed in a single line
        $post_content = str_replace("<p>", "<br><br>", $post_content);
        //remove </p> tags
        $post_content = str_replace("</p>", "", $post_content);
    
        // Add the post content as a data-text attribute
        $output .= "<div class='tab-pane' id='tab-" . $slug_title . "'><p class='typeout' data-text='" . esc_attr($post_content) . "'></p></div>";
    }
    
    
    //jquery to hide all tabs initially and then when someone clicks on a persona title generated by the persona_picker_shortcode, it will show the content of that persona by using display: flex for that specific persona generated by this shortcode.
    
    $output .= "<style>.tab-pane{display:none;}</style>";
    $output .= "<style>.tab-pane{white-space: pre-wrap;}</style>";
    wp_reset_postdata();
    return $output;
}
add_shortcode('persona_content', 'persona_tabs_shortcode');




// Add menu items under the Settings menu
function add_persona_settings_menu() {
    add_submenu_page('options-general.php', 'Persona Settings', 'Persona Settings', 'manage_options', 'persona_settings', 'persona_settings_page');
}

// Hook into admin_menu to add the submenu under Settings
add_action('admin_menu', 'add_persona_settings_menu');


function persona_settings_page() {
    ?>
        <div class="wrap"><h1>Persona Settings</h1>
            <h2>How to use this plugin</h2>
            <p>First, create a new Persona by going to the Persona menu in wp-admin. Give it a title and content. The content will be displayed using a typewriter effect.</p>
            <p>Next, create a new page and add the following shortcode to it: [persona_picker]. This will display the Persona titles.</p>
            <p>On the SAME page below the above shortcode use [persona_tabs] to display the actualy persona content. It will be displayed using a typewriter effect.</p>
        </div>
            
    <?php
    
}

function enqueue_tabswitch_script() {
    wp_enqueue_script('tabswitch', plugin_dir_url(__FILE__) . 'tabswitch.js', array('jquery'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_tabswitch_script');
