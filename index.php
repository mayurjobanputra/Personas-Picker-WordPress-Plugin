<?php
/*
Plugin Name: Persona Picker Plugin
Author: Mayur Jobanputra
Description: A plugin to create and manage a 'Persona' custom post type, with a unique URL structure and shortcode functionality.
Version: 1.1.2
*/

// Activation Hook
function persona_picker_activate() {
    if (post_type_exists('persona')) {
        die('Persona post type already exists. Plugin activation stopped.');
    }

    $slug = 'you-are';
    $slugExists = persona_picker_slug_exists($slug);
    
    if ($slugExists) {
        $slug = 'you-are-user';
        $slugExists = persona_picker_slug_exists($slug);
    }

    if ($slugExists) {
        $slug = 'you-are-user-role';
        $slugExists = persona_picker_slug_exists($slug);
    }

    if ($slugExists) {
        die('All potential slugs for Persona post type are already in use. Plugin activation stopped.');
    }

    create_persona_post_type($slug);
    flush_rewrite_rules();
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

// Add menu items
function add_persona_menu_items() {
    add_submenu_page('edit.php?post_type=persona', 'Persona Shortcodes', 'Persona Shortcodes', 'manage_options', 'persona_shortcodes', 'persona_shortcodes_page');
}

add_action('admin_menu', 'add_persona_menu_items');

function persona_shortcodes_page() {
    echo '<div class="wrap"><h1>Persona Shortcodes</h1><p>Use the shortcode [persona_picker] to display Persona titles.</p></div>';
}
