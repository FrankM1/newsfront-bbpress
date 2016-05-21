<?php
/**
 * This file is a part of the Radium Framework core.
 * Please be cautious editing this file,
 *
 * @category Radium Framework
 * @package  NewsFront
 * @author   Franklin M Gitonga
 * @link     http://radiumthemes.com/
 */

add_filter( 'radium_options_sections', 'radium_options_bbpress_sections', 99 );
/**
 * [radium_options_bbpress_sections description].
 *
 * @since 1.0.0
 *
 * @return [type] [description]
 */
function radium_options_bbpress_sections( $sections ) {

    $sections[] = array(
        'title' => esc_html__( 'bbPress', 'newsfront' ),
        'fields' => array(

            array(
                'id'        => 'bbp_forum_layout',
                'type'      => 'image_select',
                'title'     => esc_html__( 'Forum Layout', 'newsfront' ),
                'sub_desc'  => esc_html__( 'Select a layout for the forum.', 'newsfront' ),
                'options'   => radium_get_layouts_for_options(),
                'default'   => 'content-sidebar',
            ),

        ),

    );

    return $sections;
}
