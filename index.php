<?php
/*
Plugin Name: Persona Picker Plugin
Author: Mayur Jobanputra
Description: A plugin to create and manage a 'Persona' custom post type, with a unique URL structure and shortcode functionality.
Version: 1.1.8
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

        $output .= "<div class='tab-pane' id='tab-" . $slug_title . "'><p class='typeout'>" . $post_content . "</p></div>";
        

    }
    
    //jquery to hide all tabs initially and then when someone clicks on a persona title generated by the persona_picker_shortcode, it will show the content of that persona by using display: flex for that specific persona generated by this shortcode.
    
    $output .= "<style>.tab-pane{display:none;}</style>";
    $output .= "<style>.tab-pane{white-space: pre-wrap;}</style>";
    //jquery that is document safe in wordpress to show a persona content when someone clicks on a persona title generated by the persona_picker shortcode
    $output .= "<script src='typeout.js'></script>";
    $output .= "<script>
        jQuery(document).ready(function() {
            jQuery('.tab-pane').hide();
            jQuery('.persona-choice a').click(function() {
                jQuery('.tab-pane').hide();
                jQuery(jQuery(this).attr('href')).show().find('.typeout').typeOut();
            });
        });
    </script>";

    wp_reset_postdata();
    return $output;
}
add_shortcode('persona_content', 'persona_tabs_shortcode');




// Add menu items
function add_persona_menu_items() {
    add_submenu_page('edit.php?post_type=persona', 'Persona Shortcodes', 'Persona Shortcodes', 'manage_options', 'persona_shortcodes', 'persona_shortcodes_page');
}
// Hook into admin_menu with a lower priority (higher number) to ensure it runs later
add_action('admin_menu', 'modify_persona_menu_items', 100);


function modify_persona_menu_items() {
    global $submenu;
    $post_type = 'persona';

    // Check if the submenu is set
    if (isset($submenu['edit.php?post_type=' . $post_type])) {
        
        // Remove and store the submenu items
        $add_new_item = null;
        $all_items = null;
        $shortcodes_item = null;
        foreach ($submenu['edit.php?post_type=' . $post_type] as $key => $submenu_item) {
            if ($submenu_item[2] === 'post-new.php?post_type=' . $post_type) {
                $add_new_item = $submenu['edit.php?post_type=' . $post_type][$key];
                unset($submenu['edit.php?post_type=' . $post_type][$key]);
            } elseif ($submenu_item[2] === 'edit.php?post_type=' . $post_type) {
                $all_items = $submenu['edit.php?post_type=' . $post_type][$key];
                unset($submenu['edit.php?post_type=' . $post_type][$key]);
            } elseif ($submenu_item[2] === 'persona_shortcodes') {
                $shortcodes_item = $submenu['edit.php?post_type=' . $post_type][$key];
                unset($submenu['edit.php?post_type=' . $post_type][$key]);
            }
        }

        // Re-add in the desired order
        if ($all_items) {
            $all_items[0] = 'All Personas';
            $submenu['edit.php?post_type=' . $post_type][] = $all_items;
        }
        if ($add_new_item) {
            $add_new_item[0] = 'Add New Persona';
            $submenu['edit.php?post_type=' . $post_type][] = $add_new_item;
        }
        if ($shortcodes_item) {
            $submenu['edit.php?post_type=' . $post_type][] = $shortcodes_item;
        }
    }
}
add_action('admin_menu', 'modify_persona_menu_items', 100);


function persona_shortcodes_page() {
    ?>
        <div class="wrap"><h1>Persona Shortcodes</h1>
            <p>You need to use two shortcodes. Both are required to make it work. <p>
            <p>Use the shortcode [persona_picker] to display Persona titles. They will be displayed inline according to the order in wp-admin.</p>
            <p>On the SAME page below the above shortcode use [persona_tabs] to display the actualy persona content. It will be displayed using a typewriter effect.</p>
        </div>
    <?php
    
}

