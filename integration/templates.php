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
add_filter( 'bbp_get_theme_compat_dir', 'radium_bbpress_add_template_locations' );
/**
 * Add new template location to bbpress
 *
 * @param [type] $path [description]
 */
function radium_bbpress_add_template_locations( $path ) {

    $path = dirname( dirname(__FILE__) ) . '/templates/bbpress/';

    return $path;
}

/**
 * Standard loop, meant to be executed without modification in most circumstances where content needs to be displayed.
 *
 * It outputs basic wrapping HTML, but uses hooks to do most of its content output like title, content, post information
 * and comments.
 *
 * The action hooks called are:
 *
 *  - `radium_before_entry`
 *  - `radium_entry_header`
 *  - `radium_before_entry_content`
 *  - `radium_entry_content`
 *  - `radium_after_entry_content`
 *  - `radium_entry_footer`
 *  - `radium_after_endwhile`
 *  - `radium_loop_else` (only if no posts were found)
 *
 * @since 1.0.0
 *
 * @uses radium_html5()       Check for HTML5 support.
 * @uses radium_attr()        Contextual attributes.
 */
function radium_do_bbpress_loop() {

    radium_markup( array(
        'html5'   => '<div %s>',
        'context' => 'bbpress-content',
    ) );

    if ( have_posts() ) :

        $args = radium_parse_args( $args, array(), 'standard_loop' );

        do_action( 'radium_before_while', $args );

        while ( have_posts() ) : the_post();

            printf( '<%s %s>', esc_attr( 'div' ), radium_attr( 'entry' ) );

                printf( '<div %s>', radium_attr( 'entry-content' ) );
                    do_action( 'radium_entry_content', $args );
                echo '</div>';

            echo '</'. esc_attr( 'div' ) .'>';

        endwhile; // end of one post

        do_action( 'radium_after_endwhile', $args );

    else : // if no posts exist

        do_action( 'radium_loop_else', $args );

    endif; // end loop */

    radium_markup( array(
        'html5' => '</div>', // end .content
    ) );
}
