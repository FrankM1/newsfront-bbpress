<?php
/*
 * This file is a part of the Radium Framework core.
 * Please be cautious editing this file,
 *
 * @package  Radium\Extensions\Video-Central-Addon
 * @package  NewsFront
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 */

 // Register forum sidebar if needed
add_action( 'radium_register_after_default_widget_areas', 'radium_bbpress_init_sidebars' );
/**
 * [radium_bbpress_init_sidebars description]
 * @return [type] [description]
 */
function radium_bbpress_init_sidebars() {

    $site_layout = radium_site_layout();

    // Don't load sidebar on pages that don't need it
    if ( 'full-width-content' === $site_layout ) {
        return;
    }

    radium_register_widget_area( array(
        'id'          => 'sidebar-bbpress',
        'name'        => __( 'Forum Sidebar', 'bbpress-radium-extend' ),
        'description' => __( 'This is the primary sidebar used on the forums.', 'bbpress-radium-extend' )
        )
    );
}

 /**
  * Loads the forum specific sidebar
  *
  * @since 1.0.0
  */
function radium_bbpress_load_forum_sidebar() {

    $site_layout = radium_site_layout();

    // Don't load sidebar on pages that don't need it
    if ( 'full-width-content' === $site_layout ) {
        return;
    }

     echo '<div class="widget widget_text"><div class="widget-wrap">';

         echo '<div class="section-title"><h4 class="widget-title">';
             __( 'Forum Sidebar Widget Area', 'bbpress-radium-extend' );
         echo '</h4></div>';

         echo '<div class="textwidget"><p>';
             printf( __( 'This is the Forum Sidebar Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'bbpress-radium-extend' ), admin_url( 'widgets.php' ) );
         echo '</p></div>';
     echo '</div></div>';

 }
