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

    radium_register_widget_area( array(
        'id'          => 'sidebar-bbpress',
        'name'        => __( 'Forum Sidebar', 'newsfront-bbpress' ),
        'description' => __( 'This is the primary sidebar used on the forums.', 'newsfront-bbpress' )
        )
    );
}

 /**
  * Loads the forum specific sidebar
  *
  * @since 1.0.0
  */
function radium_bbpress_widget_area_content() {

     echo '<div class="widget widget_text"><div class="widget-wrap">';

         echo '<div class="section-title"><h4 class="widget-title">';
             __( 'Forum Sidebar Widget Area', 'newsfront-bbpress' );
         echo '</h4></div>';

         echo '<div class="textwidget"><p>';
             printf( __( 'This is the Forum Sidebar Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'newsfront-bbpress' ), admin_url( 'widgets.php' ) );
         echo '</p></div>';
     echo '</div></div>';

 }

 /**
  * Echo primary sidebar default content.
  *
  * Only shows if sidebar is empty, and current user has the ability to edit theme options (manage widgets).
  *
  * @since 1.0.0
  *
  * @uses radium_default_widget_area_content() Template for default widget are content.
  */
 function radium_do_bbpress_sidebar() {

     if ( ! dynamic_sidebar( 'sidebar-bbpress' ) && current_user_can( 'edit_theme_options' ) ) {
         radium_bbpress_widget_area_content( esc_html__( 'Forum Sidebar Widget Area', 'newsfront-bbpress' ) );
     }

 }
