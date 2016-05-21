<?php
/**
 * Plugin Name: Newsfront bbPress
 * Plugin URI:  http://radiumthemes.com/plugins/newsfront-bbpress/
 * Description: Provides basic compaitibility between bbPress and the <a href="http://themeforest.net/item/newsfront-blog-news-editorial-ecommerce-wordpress-theme/15182108?ref=RadiumThemes">Newsfront Theme</a>.
 * Version:     1.0.0
 * Author:      Franklin Gitonga
 * Author URI:  http://www.radiumthemes.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author     Franklin Gitonga
 * @version    1.0.0
 * @package    Newsfront BBpress
 * @copyright  Copyright (c) 2016, Franklin Gitonga
 * @link       http://radiumthemes.com
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * bbPress Radium Extend init class
 *
 * @since 1.0.0
 */
class bbpnewsfront_init {

    /**
     * We hook into bbp_after_setup_theme, this way if bbPress
     * isn't activated we won't load the plugin.
     *
     * @since 1.0.0
     */
    function __construct() {

        // Activation
        register_activation_hook( __FILE__,  array( $this, 'activation'    ) );

        add_action( 'radium_init', array( $this, 'radium_check' ) );

    }

    /**
     * Check to see if  a Radium child theme is in place.
     *
     * @since 1.0.0
     */
    function radium_check() {

        if ( ! function_exists( 'bbpress' ) ) return;

        // Load the text domain for translations
        add_action( 'init', array( $this, 'pre_init' ) );

        require_once( dirname( __FILE__ )  . '/integration/templates.php' );
        require_once( dirname( __FILE__ )  . '/integration/layout.php' );
        require_once( dirname( __FILE__ )  . '/integration/breadcrumbs.php' );
        require_once( dirname( __FILE__ )  . '/integration/page-title.php' );
        require_once( dirname( __FILE__ )  . '/integration/options.php' );
        require_once( dirname( __FILE__ )  . '/integration/widgetize.php' );

    }

    /**
     * Set plugin version number upon activation
     *
     * We use this to turn off features for new installs that have been deprecated.
     *
     * @since 1.0.0
     */
    function activation() {}

    /**
     * Load the textdomain so we can support other languages
     *
     * @since 1.0.0
     */
    function pre_init() {
        load_plugin_textdomain( 'newsfront-bbpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

}

new bbpnewsfront_init();
