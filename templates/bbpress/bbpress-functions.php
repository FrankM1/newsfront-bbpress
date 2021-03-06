<?php
/**
 * Functions of bbPress's NewsFront theme
 *
 * @package radium framework
 * @subpackage BBP_NewsFront
 * @since NewsFront 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/** Theme Setup ***************************************************************/

if ( ! class_exists( 'BBP_NewsFront' ) ) :

/**
 * Loads bbPress NewsFront Theme functionality
 *
 * Usually functions.php contains a few functions wrapped in function_exists()
 * checks. Since bbp-NewsFront is intended to be used both as a child theme and
 * for Theme Compatibility, we've moved everything into one convenient class
 * that can be copied or extended.
 *
 * See @link BBP_Theme_Compat() for more.
 *
 * @since bbPress (r3277)
 * @package radium framework
 * @subpackage BBP_NewsFront
 */
class BBP_NewsFront extends BBP_Theme_Compat {

    /** Functions *************************************************************/

    /**
     * The main bbPress (NewsFront) Loader
     *
     * @since bbPress (r3277)
     * @uses BBP_NewsFront::setup_globals()
     * @uses BBP_NewsFront::setup_actions()
     */
    public function __construct() {
        $this->setup_globals();
        $this->setup_actions();
    }

    /**
     * Component global variables
     *
     * @since bbPress (r2626)
     * @access private
     * @uses bbp_get_version() To get the bbPress version
     * @uses get_stylesheet_directory() To get the stylesheet path
     * @uses get_stylesheet_directory_uri() To get the stylesheet uri
     */
    private function setup_globals() {

        $framework = radium_framework();

        $this->id      = 'newsfront-bbpress';
        $this->name    = __( 'NewsFront (bbPress)', 'newsfront-bbpress' ) ;
        $this->version = bbp_get_version();
        $this->dir     = $framework->theme_dir;
        $this->url     = $framework->theme_url;
    }

    /**
     * Setup the theme hooks
     *
     * @since bbPress (r3277)
     * @access private
     * @uses add_filter() To add various filters
     * @uses add_action() To add various actions
     */
    private function setup_actions() {

        add_action( 'wp_enqueue_scripts',          array( $this, 'enqueue_styles'        ) ); // Enqueue theme CSS
        add_action( 'bbp_enqueue_scripts',          array( $this, 'enqueue_scripts'       ) ); // Enqueue theme JS
        add_filter( 'bbp_enqueue_scripts',          array( $this, 'localize_topic_script' ) ); // Enqueue theme script localization
        add_action( 'bbp_head',                     array( $this, 'head_scripts'          ) ); // Output some extra JS in the <head>

        add_action( 'bbp_ajax_favorite',            array( $this, 'ajax_favorite'           ) ); // Handles the topic ajax favorite/unfavorite
        add_action( 'bbp_ajax_subscription',        array( $this, 'ajax_subscription'       ) ); // Handles the topic ajax subscribe/unsubscribe
        add_action( 'bbp_ajax_forum_subscription',  array( $this, 'ajax_forum_subscription' ) ); // Handles the forum ajax subscribe/unsubscribe

        add_filter ('bbp_no_breadcrumb',  		    array( $this, 'bbp_no_breadcrumb') ); //disable breadcrumbs

        // Remove Radium profile fields from front end
        $this->remove_profile_fields();

        // Add Layout and SEO options to Forums
        add_post_type_support( bbp_get_forum_post_type(), 'radium-layouts' );

    }

    /**
     * Load the theme CSS
     *
     * @since bbPress (r2652)
     * @uses wp_enqueue_style() To enqueue the styles
     */
    public function enqueue_styles() {
        wp_enqueue_style( 'radium-bbpress-style', plugins_url('assets/css/bbpress.min.css', dirname( dirname(__FILE__) ) ), array(), $this->version, 'all' );
    }

    /**
     * Enqueue the required Javascript files
     *
     * @since bbPress (r2652)
     * @uses bbp_is_single_topic() To check if it's the topic page
     * @uses get_stylesheet_directory_uri() To get the stylesheet directory uri
     * @uses bbp_is_single_user_edit() To check if it's the profile edit page
     * @uses wp_enqueue_script() To enqueue the scripts
     */
    public function enqueue_scripts() {

        if ( bbp_is_single_topic() )
            wp_enqueue_script( 'bbp_topic', plugins_url('assets/js/topic.min.js', dirname( dirname(__FILE__) ) ), array( 'wp-lists' ), $this->version, true );

        // Always pull in jQuery for TinyMCE shortcode usage
        if ( bbp_use_wp_editor() ) {

            wp_enqueue_script( 'bbpress-editor', plugins_url('assets/js/editor.min.js', dirname( dirname(__FILE__) )), array( 'jquery' ), $this->version, true );

        }

        // Forum-specific scripts
        if ( bbp_is_single_forum() ) {
             wp_enqueue_script( 'bbpress-forum', plugins_url('assets/js/forum.min.js', dirname( dirname(__FILE__) ) ), array( 'jquery' ), $this->version, true );
        }

        // Topic-specific scripts
        if ( bbp_is_single_topic() ) {

            // Topic favorite/unsubscribe
              wp_enqueue_script( 'bbpress-topic', plugins_url('assets/js/topic.min.js', dirname( dirname(__FILE__) ) ), array( 'jquery' ), $this->version, true );

            // Hierarchical replies
            if ( bbp_thread_replies() ) {
                 wp_enqueue_script( 'bbpress-reply', plugins_url('assets/js/reply.min.js', dirname( dirname(__FILE__) )), array( 'jquery' ), $this->version, true );
            }
        }

        // User Profile edit
        if ( bbp_is_single_user_edit() ) {
             wp_enqueue_script( 'bbpress-user', plugins_url('assets/js/user.min.js', dirname( dirname(__FILE__) ) ), array( 'user-query' ), $this->version, true );
        }

        if ( bbp_is_single_user_edit() )
            wp_enqueue_script( 'user-profile' );
    }

    /**
     * Put some scripts in the header, like AJAX url for wp-lists
     *
     * @since bbPress (r2652)
     * @uses bbp_is_single_topic() To check if it's the topic page
     * @uses admin_url() To get the admin url
     * @uses bbp_is_single_user_edit() To check if it's the profile edit page
     */
    public function head_scripts() {
        if ( bbp_is_single_topic() ) : ?>

        <script type='text/javascript'>
            /* <![CDATA[ */
            var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            /* ]]> */
        </script>

        <?php elseif ( bbp_is_single_user_edit() ) : ?>

        <script type="text/javascript" charset="utf-8">
            if ( window.location.hash == '#password' ) {
                document.getElementById('pass1').focus();
            }
        </script>

        <?php
        endif;
    }

    /**
     * Load localizations for topic script
     *
     * These localizations require information that may not be loaded even by init.
     *
     * @since bbPress (r2652)
     * @uses bbp_is_single_topic() To check if it's the topic page
     * @uses is_user_logged_in() To check if user is logged in
     * @uses bbp_get_current_user_id() To get the current user id
     * @uses bbp_get_topic_id() To get the topic id
     * @uses bbp_get_favorites_permalink() To get the favorites permalink
     * @uses bbp_is_user_favorite() To check if the topic is in user's favorites
     * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
     * @uses bbp_is_user_subscribed() To check if the user is subscribed to topic
     * @uses bbp_get_topic_permalink() To get the topic permalink
     * @uses wp_localize_script() To localize the script
     */
    public function localize_topic_script() {

        // Single forum
        if ( bbp_is_single_forum() ) {
            wp_localize_script( 'bbpress-forum', 'bbpForumJS', array(
                'bbp_ajaxurl'        => bbp_get_ajax_url(),
                'generic_ajax_error' => __( 'Something went wrong. Refresh your browser and try again.', 'newsfront-bbpress' ),
                'is_user_logged_in'  => is_user_logged_in(),
                'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
            ) );

        // Single topic
        } elseif ( bbp_is_single_topic() ) {
            wp_localize_script( 'bbpress-topic', 'bbpTopicJS', array(
                'bbp_ajaxurl'        => bbp_get_ajax_url(),
                'generic_ajax_error' => __( 'Something went wrong. Refresh your browser and try again.', 'newsfront-bbpress' ),
                'is_user_logged_in'  => is_user_logged_in(),
                'fav_nonce'          => wp_create_nonce( 'toggle-favorite_' .     get_the_ID() ),
                'subs_nonce'         => wp_create_nonce( 'toggle-subscription_' . get_the_ID() )
            ) );
        }
    }

    /**
     * AJAX handler to Subscribe/Unsubscribe a user from a forum
     *
     * @since bbPress (r5155)
     *
     * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
     * @uses bbp_is_user_logged_in() To check if user is logged in
     * @uses bbp_get_current_user_id() To get the current user id
     * @uses current_user_can() To check if the current user can edit the user
     * @uses bbp_get_forum() To get the forum
     * @uses wp_verify_nonce() To verify the nonce
     * @uses bbp_is_user_subscribed() To check if the forum is in user's subscriptions
     * @uses bbp_remove_user_subscriptions() To remove the forum from user's subscriptions
     * @uses bbp_add_user_subscriptions() To add the forum from user's subscriptions
     * @uses bbp_ajax_response() To return JSON
     */
    public function ajax_forum_subscription() {

        // Bail if subscriptions are not active
        if ( ! bbp_is_subscriptions_active() ) {
            bbp_ajax_response( false, __( 'Subscriptions are no longer active.', 'newsfront-bbpress' ), 300 );
        }

        // Bail if user is not logged in
        if ( ! is_user_logged_in() ) {
            bbp_ajax_response( false, __( 'Please login to subscribe to this forum.', 'newsfront-bbpress' ), 301 );
        }

        // Get user and forum data
        $user_id = bbp_get_current_user_id();
        $id      = intval( $_POST['id'] );

        // Bail if user cannot add favorites for this user
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            bbp_ajax_response( false, __( 'You do not have permission to do this.', 'newsfront-bbpress' ), 302 );
        }

        // Get the forum
        $forum = bbp_get_forum( $id );

        // Bail if forum cannot be found
        if ( empty( $forum ) ) {
            bbp_ajax_response( false, __( 'The forum could not be found.', 'newsfront-bbpress' ), 303 );
        }

        // Bail if user did not take this action
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $forum->ID ) ) {
            bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'newsfront-bbpress' ), 304 );
        }

        // Take action
        $status = bbp_is_user_subscribed( $user_id, $forum->ID ) ? bbp_remove_user_subscription( $user_id, $forum->ID ) : bbp_add_user_subscription( $user_id, $forum->ID );

        // Bail if action failed
        if ( empty( $status ) ) {
            bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'newsfront-bbpress' ), 305 );
        }

        // Put subscription attributes in convenient array
        $attrs = array(
            'forum_id' => $forum->ID,
            'user_id'  => $user_id
        );

        // Action succeeded
        bbp_ajax_response( true, bbp_get_forum_subscription_link( $attrs, $user_id, false ), 200 );
    }

    /**
     * AJAX handler to add or remove a topic from a user's favorites
     *
     * @since bbPress (r3732)
     *
     * @uses bbp_is_favorites_active() To check if favorites are active
     * @uses bbp_is_user_logged_in() To check if user is logged in
     * @uses bbp_get_current_user_id() To get the current user id
     * @uses current_user_can() To check if the current user can edit the user
     * @uses bbp_get_topic() To get the topic
     * @uses wp_verify_nonce() To verify the nonce & check the referer
     * @uses bbp_is_user_favorite() To check if the topic is user's favorite
     * @uses bbp_remove_user_favorite() To remove the topic from user's favorites
     * @uses bbp_add_user_favorite() To add the topic from user's favorites
     * @uses bbp_ajax_response() To return JSON
     */
    public function ajax_favorite() {

        // Bail if favorites are not active
        if ( ! bbp_is_favorites_active() ) {
            bbp_ajax_response( false, __( 'Favorites are no longer active.', 'newsfront-bbpress' ), 300 );
        }

        // Bail if user is not logged in
        if ( ! is_user_logged_in() ) {
            bbp_ajax_response( false, __( 'Please login to make this topic a favorite.', 'newsfront-bbpress' ), 301 );
        }

        // Get user and topic data
        $user_id = bbp_get_current_user_id();
        $id      = !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;

        // Bail if user cannot add favorites for this user
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            bbp_ajax_response( false, __( 'You do not have permission to do this.', 'newsfront-bbpress' ), 302 );
        }

        // Get the topic
        $topic = bbp_get_topic( $id );

        // Bail if topic cannot be found
        if ( empty( $topic ) ) {
            bbp_ajax_response( false, __( 'The topic could not be found.', 'newsfront-bbpress' ), 303 );
        }

        // Bail if user did not take this action
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-favorite_' . $topic->ID ) ) {
            bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'newsfront-bbpress' ), 304 );
        }

        // Take action
        $status = bbp_is_user_favorite( $user_id, $topic->ID ) ? bbp_remove_user_favorite( $user_id, $topic->ID ) : bbp_add_user_favorite( $user_id, $topic->ID );

        // Bail if action failed
        if ( empty( $status ) ) {
            bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'newsfront-bbpress' ), 305 );
        }

        // Put subscription attributes in convenient array
        $attrs = array(
            'topic_id' => $topic->ID,
            'user_id'  => $user_id
        );

        // Action succeeded
        bbp_ajax_response( true, bbp_get_user_favorites_link( $attrs, $user_id, false ), 200 );
    }

    /**
     * AJAX handler to Subscribe/Unsubscribe a user from a topic
     *
     * @since bbPress (r3732)
     *
     * @uses bbp_is_subscriptions_active() To check if the subscriptions are active
     * @uses bbp_is_user_logged_in() To check if user is logged in
     * @uses bbp_get_current_user_id() To get the current user id
     * @uses current_user_can() To check if the current user can edit the user
     * @uses bbp_get_topic() To get the topic
     * @uses wp_verify_nonce() To verify the nonce
     * @uses bbp_is_user_subscribed() To check if the topic is in user's subscriptions
     * @uses bbp_remove_user_subscriptions() To remove the topic from user's subscriptions
     * @uses bbp_add_user_subscriptions() To add the topic from user's subscriptions
     * @uses bbp_ajax_response() To return JSON
     */
    public function ajax_subscription() {

        // Bail if subscriptions are not active
        if ( ! bbp_is_subscriptions_active() ) {
            bbp_ajax_response( false, __( 'Subscriptions are no longer active.', 'newsfront-bbpress' ), 300 );
        }

        // Bail if user is not logged in
        if ( ! is_user_logged_in() ) {
            bbp_ajax_response( false, __( 'Please login to subscribe to this topic.', 'newsfront-bbpress' ), 301 );
        }

        // Get user and topic data
        $user_id = bbp_get_current_user_id();
        $id      = intval( $_POST['id'] );

        // Bail if user cannot add favorites for this user
        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            bbp_ajax_response( false, __( 'You do not have permission to do this.', 'newsfront-bbpress' ), 302 );
        }

        // Get the topic
        $topic = bbp_get_topic( $id );

        // Bail if topic cannot be found
        if ( empty( $topic ) ) {
            bbp_ajax_response( false, __( 'The topic could not be found.', 'newsfront-bbpress' ), 303 );
        }

        // Bail if user did not take this action
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'toggle-subscription_' . $topic->ID ) ) {
            bbp_ajax_response( false, __( 'Are you sure you meant to do that?', 'newsfront-bbpress' ), 304 );
        }

        // Take action
        $status = bbp_is_user_subscribed( $user_id, $topic->ID ) ? bbp_remove_user_subscription( $user_id, $topic->ID ) : bbp_add_user_subscription( $user_id, $topic->ID );

        // Bail if action failed
        if ( empty( $status ) ) {
            bbp_ajax_response( false, __( 'The request was unsuccessful. Please try again.', 'newsfront-bbpress' ), 305 );
        }

        // Put subscription attributes in convenient array
        $attrs = array(
            'topic_id' => $topic->ID,
            'user_id'  => $user_id
        );

        // Action succeeded
        bbp_ajax_response( true, bbp_get_user_subscribe_link( $attrs, $user_id, false ), 200 );
    }

    /**
     * Disable default bbpress breadcrumb
     *
     */
    public function bbp_no_breadcrumb ($param) { return true; }

    /**
     * Remove Genesis profile fields
     *
     * In some use cases the Genesis fields were showing (incorrectly)
     * on the bbPress profile edit pages, so we remove them entirely.
     *
     * @since 1.1.1
     */
    public function remove_profile_fields() {
        if ( !is_admin() ) {
            remove_action( 'show_user_profile', 'radium_user_options_fields' );
            remove_action( 'edit_user_profile', 'radium_user_options_fields' );
            remove_action( 'show_user_profile', 'radium_user_archive_fields' );
            remove_action( 'edit_user_profile', 'radium_user_archive_fields' );
            remove_action( 'show_user_profile', 'radium_user_layout_fields'  );
            remove_action( 'edit_user_profile', 'radium_user_layout_fields'  );
        }
    }

}
new BBP_NewsFront();
endif;
