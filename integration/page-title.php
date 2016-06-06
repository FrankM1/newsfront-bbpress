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

add_filter( 'radium_header_page_title' , 'radium_bbpress_page_title' );
  /**
  *  Modify shop page title
   *
   * @since  1.0.0
   *
   * @return null
   */
function radium_bbpress_page_title( $header_title ) {

    if ( ! is_bbpress() )
        return $header_title;

    // Search page
    if ( bbp_is_search() ) {
        $header_title = bbp_get_search_title();

    // Forum archive
    } elseif ( bbp_is_forum_archive() ) {
        $header_title = bbp_get_forum_archive_title();

    // Topic archive
    } elseif ( bbp_is_topic_archive() ) {
        $header_title = bbp_get_topic_archive_title();

    // View
    } elseif ( bbp_is_single_view() ) {
        $header_title = bbp_get_view_title();

    // Single Forum
    } elseif ( bbp_is_single_forum() ) {
        $header_title = bbp_get_forum_title();

    // Single Topic
    } elseif ( bbp_is_single_topic() ) {
        $header_title = bbp_get_topic_title();

    // Single Topic
    } elseif ( bbp_is_single_reply() ) {
        $header_title = bbp_get_reply_title();

    // Topic Tag (or theme compat topic tag)
    } elseif ( bbp_is_topic_tag() || ( get_query_var( 'bbp_topic_tag' ) && !bbp_is_topic_tag_edit() ) ) {

        // Always include the tag name
        $tag_data[] = bbp_get_topic_tag_name();

        // If capable, include a link to edit the tag
        if ( current_user_can( 'manage_topic_tags' ) ) {
            $tag_data[] = '<a href="' . esc_url( bbp_get_topic_tag_edit_link() ) . '" class="bbp-edit-topic-tag-link">' . esc_html__( '(Edit)', 'newsfront-bbpress' ) . '</a>';
        }

        // Implode the results of the tag data
        $header_title = sprintf( __( 'Topic Tag: %s', 'newsfront-bbpress' ), implode( ' ', $tag_data ) );

    // Edit Topic Tag
    } elseif ( bbp_is_topic_tag_edit() ) {
        $header_title = __( 'Edit', 'newsfront-bbpress' );

    }

    return $header_title;

}
