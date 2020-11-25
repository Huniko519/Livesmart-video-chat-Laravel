<?php
/**
 * Plugin Name: LatePoint
 * Description: Appointment Scheduling Software for WordPress
 * Version: 3.1.2
 * Author: LatePoint
 * Author URI: http://latepoint.com
 * Text Domain: latepoint
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}


if ( ! class_exists( 'LatePoint' ) ) :

/**
 * Main LatePoint Class.
 *
 */

final class LatePoint {

  /**
   * LatePoint version.
   *
   */
  public $version = '3.1.2';
  public $db_version = '1.3.1';




  /**
   * LatePoint Constructor.
   */
  public function __construct() {
    if( !session_id() ) session_start();
    
    $this->define_constants();
    $this->includes();
    $this->init_hooks();
    OsDatabaseHelper::check_db_version();
    OsDatabaseHelper::check_db_version_for_addons();


    $GLOBALS['latepoint_settings'] = new OsSettingsHelper();

  }


  /**
   * Define constant if not already set.
   *
   */
  public function define( $name, $value ) {
    if ( ! defined( $name ) ) {
      define( $name, $value );
    }
  }



  /**
   * Get the plugin url. *has trailing slash
   * @return string
   */
  public static function plugin_url() {
    return plugin_dir_url( __FILE__ ) ;
  }

  public static function public_javascripts() {
    return plugin_dir_url( __FILE__ ) . 'public/javascripts/';
  }

  public static function public_vendor_javascripts() {
    return plugin_dir_url( __FILE__ ) . 'public/javascripts/vendor/';
  }

  public static function public_stylesheets() {
    return plugin_dir_url( __FILE__ ) . 'public/stylesheets/';
  }

  public static function node_modules_url() {
    return plugin_dir_url( __FILE__ ) . 'node_modules/';
  }

  public static function vendor_assets_url() {
    return plugin_dir_url( __FILE__ ) . 'vendor/';
  }

  public static function images_url() {
    return plugin_dir_url( __FILE__ ) . 'public/images/';
  }

  /**
   * Get the plugin path.
   * @return string
   */
  public static function plugin_path() {
    return plugin_dir_path( __FILE__ ) ;
  }


  /**
   * Define LatePoint Constants.
   */
  public function define_constants() {
    $upload_dir = wp_upload_dir();

    // ENVIRONMENTS TYPES
    $this->define( 'LATEPOINT_ENV_LIVE', 'live' );
    $this->define( 'LATEPOINT_ENV_DEMO', 'demo' );
    $this->define( 'LATEPOINT_ENV_DEV', 'dev' );


    $this->define( 'LATEPOINT_ENV', LATEPOINT_ENV_LIVE );
    $this->define( 'LATEPOINT_ENV_PAYMENTS', LATEPOINT_ENV_LIVE );

    $this->define( 'LATEPOINT_ALLOW_SMS', true );
    $this->define( 'LATEPOINT_ALLOW_EMAILS', true );

    $this->define( 'LATEPOINT_PLUGIN_FILE', __FILE__ );
    $this->define( 'LATEPOINT_STYLESHEETS_URL', $this->public_stylesheets() );
    $this->define( 'LATEPOINT_ABSPATH', dirname( __FILE__ ) . '/' );
    $this->define( 'LATEPOINT_LIB_ABSPATH', LATEPOINT_ABSPATH . 'lib/' );
    $this->define( 'LATEPOINT_BOWER_ABSPATH', LATEPOINT_ABSPATH . 'vendor/bower_components/' );
    $this->define( 'LATEPOINT_VIEWS_ABSPATH', LATEPOINT_LIB_ABSPATH . 'views/' );
    $this->define( 'LATEPOINT_VIEWS_ABSPATH_SHARED', LATEPOINT_LIB_ABSPATH . 'views/shared/' );
    $this->define( 'LATEPOINT_VIEWS_MAILERS_ABSPATH', LATEPOINT_VIEWS_ABSPATH . 'mailers/' );
    $this->define( 'LATEPOINT_VIEWS_LAYOUTS_ABSPATH', LATEPOINT_VIEWS_ABSPATH . 'layouts/' );
    $this->define( 'LATEPOINT_VIEWS_PARTIALS_ABSPATH', LATEPOINT_VIEWS_ABSPATH . 'partials/' );
    $this->define( 'LATEPOINT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

    $this->define( 'LATEPOINT_PLUGIN_URL', $this->plugin_url() );
    $this->define( 'LATEPOINT_LIB_URL', LATEPOINT_PLUGIN_URL . 'lib/' );
    $this->define( 'LATEPOINT_PUBLIC_URL', LATEPOINT_PLUGIN_URL. 'public/' );
    $this->define( 'LATEPOINT_IMAGES_URL', LATEPOINT_PUBLIC_URL. 'images/' );
    $this->define( 'LATEPOINT_DEFAULT_AVATAR_URL', LATEPOINT_IMAGES_URL . 'default-avatar.jpg');
    $this->define( 'LATEPOINT_MARKETPLACE', 'codecanyon');
    $this->define( 'LATEPOINT_SERVER', 'https://latepoint.com');

    $this->define( 'LATEPOINT_WP_AGENT_ROLE', 'latepoint_agent');
    $this->define( 'LATEPOINT_WP_CUSTOMER_ROLE', 'latepoint_customer');
    $this->define( 'LATEPOINT_WP_ADMIN_ROLE', 'latepoint_admin');

    $this->define( 'LATEPOINT_VERSION', $this->version );
    $this->define( 'LATEPOINT_ENCRYPTION_KEY', 'oiaf(*Ufdsoh2ie7QEy,R@6(I9H/VoX^r4}SHC_7W-<$S!,/kd)OSw?.Y9lcd105cu$' );

    $this->define( 'LATEPOINT_AGENT_POST_TYPE', 'latepoint_agent' );
    $this->define( 'LATEPOINT_SERVICE_POST_TYPE', 'latepoint_service' );
    $this->define( 'LATEPOINT_CUSTOMER_POST_TYPE', 'latepoint_customer' );

    $this->define( 'LATEPOINT_DB_VERSION', $this->db_version );

    global $wpdb;
    $this->define( 'LATEPOINT_TABLE_BOOKINGS', $wpdb->prefix . 'latepoint_bookings');
    $this->define( 'LATEPOINT_TABLE_SERVICES', $wpdb->prefix . 'latepoint_services');
    $this->define( 'LATEPOINT_TABLE_SETTINGS', $wpdb->prefix . 'latepoint_settings');
    $this->define( 'LATEPOINT_TABLE_SERVICE_CATEGORIES', $wpdb->prefix . 'latepoint_service_categories');
    $this->define( 'LATEPOINT_TABLE_WORK_PERIODS', $wpdb->prefix . 'latepoint_work_periods');
    $this->define( 'LATEPOINT_TABLE_CUSTOM_PRICES', $wpdb->prefix . 'latepoint_custom_prices');
    $this->define( 'LATEPOINT_TABLE_AGENTS_SERVICES', $wpdb->prefix . 'latepoint_agents_services');
    $this->define( 'LATEPOINT_TABLE_ACTIVITIES', $wpdb->prefix . 'latepoint_activities');
    $this->define( 'LATEPOINT_TABLE_TRANSACTIONS', $wpdb->prefix . 'latepoint_transactions');
    $this->define( 'LATEPOINT_TABLE_AGENTS', $wpdb->prefix . 'latepoint_agents');
    $this->define( 'LATEPOINT_TABLE_CUSTOMERS', $wpdb->prefix . 'latepoint_customers');
    $this->define( 'LATEPOINT_TABLE_CUSTOMER_META', $wpdb->prefix . 'latepoint_customer_meta');
    $this->define( 'LATEPOINT_TABLE_SERVICE_META', $wpdb->prefix . 'latepoint_service_meta');
    $this->define( 'LATEPOINT_TABLE_BOOKING_META', $wpdb->prefix . 'latepoint_booking_meta');
    $this->define( 'LATEPOINT_TABLE_AGENT_META', $wpdb->prefix . 'latepoint_agent_meta');
    $this->define( 'LATEPOINT_TABLE_STEP_SETTINGS', $wpdb->prefix . 'latepoint_step_settings');
    $this->define( 'LATEPOINT_TABLE_LOCATIONS', $wpdb->prefix . 'latepoint_locations');
    $this->define( 'LATEPOINT_TABLE_SENT_REMINDERS', $wpdb->prefix . 'latepoint_sent_reminders');

    $this->define( 'LATEPOINT_BOOKING_STATUS_APPROVED', 'approved' );
    $this->define( 'LATEPOINT_BOOKING_STATUS_PENDING', 'pending' );
    $this->define( 'LATEPOINT_BOOKING_STATUS_PAYMENT_PENDING', 'payment_pending' );
    $this->define( 'LATEPOINT_BOOKING_STATUS_CANCELLED', 'cancelled' );
    
    $this->define( 'LATEPOINT_DEFAULT_TIME_SYSTEM', '12' );
    $this->define( 'LATEPOINT_DEFAULT_DATE_FORMAT', 'm/d/Y' );

    $this->define( 'LATEPOINT_STATUS_ERROR', 'error' );
    $this->define( 'LATEPOINT_STATUS_SUCCESS', 'success' );

    $this->define( 'LATEPOINT_SERVICE_STATUS_ACTIVE', 'active' );
    $this->define( 'LATEPOINT_SERVICE_STATUS_DISABLED', 'disabled' );

    $this->define( 'LATEPOINT_SERVICE_VISIBILITY_VISIBLE', 'visible' );
    $this->define( 'LATEPOINT_SERVICE_VISIBILITY_HIDDEN', 'hidden' );

    $this->define( 'LATEPOINT_LOCATION_STATUS_ACTIVE', 'active' );
    $this->define( 'LATEPOINT_LOCATION_STATUS_DISABLED', 'disabled' );

    $this->define( 'LATEPOINT_AGENT_STATUS_ACTIVE', 'active' );
    $this->define( 'LATEPOINT_AGENT_STATUS_DISABLED', 'disabled' );


    $this->define( 'LATEPOINT_DEFAULT_TIMEBLOCK_INTERVAL', 15 );
    $this->define( 'LATEPOINT_DEFAULT_PHONE_CODE', '+1' );
    $this->define( 'LATEPOINT_DEFAULT_PHONE_FORMAT', '(999) 999-9999' );

    $this->define( 'LATEPOINT_TRANSACTION_STATUS_APPROVED', 'approved' );
    $this->define( 'LATEPOINT_TRANSACTION_STATUS_DECLINED', 'declined' );

    // Payments
    $this->define( 'LATEPOINT_DEFAULT_BRAINTREE_CURRENCY_ISO_CODE', 'USD' );
    $this->define( 'LATEPOINT_DEFAULT_STRIPE_CURRENCY_ISO_CODE', 'usd' );
    $this->define( 'LATEPOINT_DEFAULT_PAYPAL_CURRENCY_ISO_CODE', 'USD' );
    
    $this->define( 'LATEPOINT_PAYMENT_PROCESSOR_STRIPE', 'stripe' );
    $this->define( 'LATEPOINT_PAYMENT_PROCESSOR_BRAINTREE', 'braintree' );
    $this->define( 'LATEPOINT_PAYMENT_PROCESSOR_PAYPAL', 'paypal' );


    $this->define( 'LATEPOINT_PAYMENT_METHOD_LOCAL', 'local' );
    $this->define( 'LATEPOINT_PAYMENT_METHOD_PAYPAL', 'paypal' );
    $this->define( 'LATEPOINT_PAYMENT_METHOD_CARD', 'card' );

    $this->define( 'LATEPOINT_PAYMENT_PORTION_FULL', 'full' );
    $this->define( 'LATEPOINT_PAYMENT_PORTION_DEPOSIT', 'deposit' );

    $this->define( 'LATEPOINT_ANY_AGENT', 'any' );

    $this->define( 'LATEPOINT_ANY_AGENT_ORDER_RANDOM', 'random' );
    $this->define( 'LATEPOINT_ANY_AGENT_ORDER_PRICE_HIGH', 'price_high' );
    $this->define( 'LATEPOINT_ANY_AGENT_ORDER_PRICE_LOW', 'price_low' );
    $this->define( 'LATEPOINT_ANY_AGENT_ORDER_BUSY_HIGH', 'busy_high' );
    $this->define( 'LATEPOINT_ANY_AGENT_ORDER_BUSY_LOW', 'busy_low' );

    $this->define( 'LATEPOINT_ALL', 'all' );
  }


  /**
   * Include required core files used in admin and on the frontend.
   */
  public function includes() {

    // COMPOSER AUTOLOAD
    require LATEPOINT_ABSPATH . 'vendor/autoload.php';

    // TODO - replace with __autoload https://stackoverflow.com/questions/599670/how-to-include-all-php-files-from-a-directory

    // CONTROLLERS
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/activities_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/search_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/agents_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/customers_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/services_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/transactions_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/auth_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/service_categories_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/settings_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/bookings_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/dashboard_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/wizard_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/updates_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/addons_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/debug_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/notifications_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/locations_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/reminders_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/steps_controller.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/controllers/calendars_controller.php' );


    // MODELS
    include_once( LATEPOINT_ABSPATH . 'lib/models/model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/activity_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/work_period_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/agent_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/service_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/connector_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/service_category_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/customer_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/settings_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/booking_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/sent_reminder_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/step_settings_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/step_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/transaction_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/meta_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/booking_meta_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/customer_meta_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/agent_meta_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/service_meta_model.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/models/location_model.php' );


    // HELPERS
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/wp_date_time.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/router_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/reminders_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/auth_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/encrypt_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/social_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/updates_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/addons_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/license_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/form_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/util_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/debug_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/wp_user_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/menu_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/image_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/icalendar_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/booking_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/activities_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/settings_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/customer_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/agent_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/service_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/database_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/money_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/time_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/notifications_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/work_periods_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/updates_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/replacer_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/payments_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/payments_stripe_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/payments_braintree_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/payments_paypal_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/meta_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/shortcodes_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/connector_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/location_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/csv_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/steps_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/params_helper.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/helpers/livesmart_helper.php' );


    // MAILERS
    include_once( LATEPOINT_ABSPATH . 'lib/mailers/mailer.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/mailers/agent_mailer.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/mailers/customer_mailer.php' );

    // SMSERS
    include_once( LATEPOINT_ABSPATH . 'lib/smsers/smser.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/smsers/agent_smser.php' );
    include_once( LATEPOINT_ABSPATH . 'lib/smsers/customer_smser.php' );


    do_action('latepoint_includes');
  }


  /**
   * Hook into actions and filters.
   */
  public function init_hooks() {

    OsSettingsHelper::run_autoload();
    
    // Activation hook
    register_activation_hook( __FILE__, array($this, 'create_required_tables' ));
    register_activation_hook(__FILE__, array( $this, 'on_activate' ));
    register_deactivation_hook(__FILE__, [$this, 'on_deactivate']);

    add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );
    add_action( 'init', array( $this, 'init' ), 0 );
    add_action( 'admin_menu', array( $this, 'init_menus' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'load_front_scripts_and_styles' ));
    add_action( 'admin_enqueue_scripts',  array( $this, 'load_admin_scripts_and_styles' ));
    add_filter( 'admin_body_class', array( $this, 'add_admin_body_class' ));
    add_filter( 'body_class', array( $this, 'add_body_class' ) );


    // Add Link to latepoint to admin bar
    add_action( 'admin_bar_menu', array($this, 'add_latepoint_link_to_admin_bar'), 999 );


    if(OsSettingsHelper::is_using_google_login()) add_action( 'wp_head', array( $this, 'add_google_signin_meta_tags' ));
    
    // fix for output buffering error in WP
    remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );

    add_action ('wp_loaded', array( $this, 'pre_route_call'));


    // Create router action
    // ajax
    add_action( 'wp_ajax_latepoint_route_call', array( $this, 'route_call') );
    add_action( 'wp_ajax_nopriv_latepoint_route_call', array( $this, 'route_call') );
    // admin custom post/get
    add_action( 'admin_post_latepoint_route_call', array( $this, 'route_call') );
    add_action( 'admin_post_nopriv_latepoint_route_call', array( $this, 'route_call') );

    // crons
    add_action('latepoint_send_reminders', [$this, 'send_reminders']);


    // Register a URL that will set this variable to true
    add_action( 'init', array( $this, 'front_route_init' ));

    // Auth
    add_filter( 'login_redirect', [$this, 'agent_user_redirect'], 10, 3 );
    

    // But WordPress has a whitelist of variables it allows, so we must put it on that list
    add_action( 'query_vars', array( $this, 'front_route_query_vars' ));

    // If this is done, we can access it later
    // This example checks very early in the process:
    // if the variable is set, we include our page and stop execution after it
    add_action( 'parse_request', array( $this, 'front_route_parse_request' ));



    add_action('admin_init', array( $this, 'redirect_after_activation'));

    OsStepsHelper::init_step_actions();
  }


  public function agent_user_redirect($redirect_to, $request, $user) {
    global $user;
    if ( isset( $user->roles ) && is_array( $user->roles ) && in_array(LATEPOINT_WP_AGENT_ROLE, $user->roles ) && OsAuthHelper::is_logged_in_user_connected_to_agent($user->ID)) {
      return OsRouterHelper::build_link(['dashboard', 'for_agent']);
    }
    return $redirect_to;
  }

  public function send_reminders(){
    OsRemindersHelper::process_reminders();
  }

  public function on_deactivate(){
    wp_clear_scheduled_hook('latepoint_send_reminders');
  }

  function on_activate() {
    add_role(LATEPOINT_WP_AGENT_ROLE, __('LatePoint Agent', 'latepoint'));
    $agent_role = get_role( LATEPOINT_WP_AGENT_ROLE );

    // $agent_role->add_cap( 'delete_posts' );
    // $agent_role->add_cap( 'delete_published_posts' );
    // $agent_role->add_cap( 'edit_posts' );
    // $agent_role->add_cap( 'edit_published_posts' );
    // $agent_role->add_cap( 'publish_posts' );
    $agent_role->add_cap( 'read' );
    $agent_role->add_cap( 'upload_files' );
    $agent_role->add_cap( 'edit_bookings' );


    if (! wp_next_scheduled ( 'latepoint_send_reminders' )) {
      wp_schedule_event(time(), 'hourly', 'latepoint_send_reminders');
    }

    add_role(LATEPOINT_WP_CUSTOMER_ROLE, __('LatePoint Customer', 'latepoint'));
    // if wizard has not been visited yet - redirect to it
    if(!get_option('latepoint_wizard_visited', false)) add_option('latepoint_redirect_to_wizard', true);
  }

  function redirect_after_activation() {
    if (get_option('latepoint_redirect_to_wizard', false)) {
      delete_option('latepoint_redirect_to_wizard');
      if(!isset($_GET['activate-multi'])){
        wp_redirect(OsRouterHelper::build_link(OsRouterHelper::build_route_name('wizard', 'setup')));
      }
    }
  }

  public function front_route_parse_request( $wp ){
    if ( isset( $wp->query_vars['latepoint_is_custom_route'] ) ) {
      if(isset($wp->query_vars['route_name'])){
        $this->route_call();
      }
    }
  }

  public function front_route_query_vars( $query_vars )
  {
      $query_vars[] = 'latepoint_booking_id';
      $query_vars[] = 'latepoint_is_custom_route';
      $query_vars[] = 'route_name';
      return $query_vars;
  }

  public function front_route_init() {
    add_rewrite_rule( '^ical/([0-9]+)/?', OsRouterHelper::build_front_link(OsRouterHelper::build_route_name('bookings', 'ical_download')).'latepoint_booking_id=$matches[1]', 'top' );
    add_rewrite_rule( '^agent-login/?', OsRouterHelper::build_front_link(OsRouterHelper::build_route_name('agents', 'login_form')), 'top' );
  }

  public function route_call(){
    $route_name = OsRouterHelper::get_request_param('route_name', OsRouterHelper::build_route_name('dashboard', 'index'));
    OsRouterHelper::call_by_route_name($route_name, OsRouterHelper::get_request_param('return_format', 'html'));
  }

  public function agent_route_call(){
    $route_name = OsRouterHelper::get_request_param('route_name', OsRouterHelper::build_route_name('dashboard', 'for_agent'));
    OsRouterHelper::call_by_route_name($route_name, OsRouterHelper::get_request_param('return_format', 'html'));
  }

  public function pre_route_call(){
    if(OsRouterHelper::get_request_param('pre_route')){
      $this->route_call();
    }
  }



  public function customer_logout() {
    if ( isset( $_GET['os-action'] ) ) {
      OsAuthHelper::logout_customer();
      wp_redirect(OsSettingsHelper::get_customer_login_url());
      exit;
    }
  }



  /**
   * Init LatePoint when WordPress Initialises.
   */
  public function init() {
    $this->register_post_types();
    $this->register_shortcodes();
    // Set up localisation.
    $this->load_plugin_textdomain();
    if(OsSettingsHelper::is_using_stripe_payments()){
      OsPaymentsStripeHelper::set_api_key();
    }elseif(OsSettingsHelper::is_using_braintree_payments()){
      OsPaymentsBraintreeHelper::set_api_key();
    }

    add_filter( 'http_request_host_is_external', '__return_true' );

  }


  public function load_plugin_textdomain() {
    load_plugin_textdomain('latepoint', false, dirname(plugin_basename(__FILE__)) . '/languages');
  }


  /**
   * Register a custom menu page.
   */
  function init_menus() {
    // if wp user with agent role is logged in - check if it is assigned to latepoint agent
    if(OsAuthHelper::is_agent_logged_in() && !OsAuthHelper::is_logged_in_user_connected_to_agent()){
      return false;
    }
    if(current_user_can('edit_bookings') && !current_user_can('manage_sites')){
      $route_call_func = array( $this, 'agent_route_call');
      $capabilities = 'edit_bookings';
    }else{
      $route_call_func = array( $this, 'route_call');
      $capabilities = 'manage_options';
    }
    // link for admins
    add_menu_page(
        __( 'LatePoint', 'latepoint' ),
        __( 'LatePoint', 'latepoint' ),
        $capabilities,
        'latepoint',
        $route_call_func,
        'none'
    );


  }


  function add_latepoint_link_to_admin_bar( $wp_admin_bar ) {
    // if wp user with agent role is logged in - check if it is assigned to latepoint agent
    if(OsAuthHelper::is_agent_logged_in() && !OsAuthHelper::is_logged_in_user_connected_to_agent()){
      return false;
    }
    $link = (current_user_can('edit_bookings') && !current_user_can('manage_sites')) ? OsRouterHelper::build_link(OsRouterHelper::build_route_name('dashboard', 'for_agent')) : OsRouterHelper::build_link(OsRouterHelper::build_route_name('dashboard', 'index'));
    $args = array(
      'id'    => 'latepoint_top_link',
      'title' => '<span class="latepoint-icon latepoint-icon-lp-logo" style="margin-right: 7px; color:#2258ff;"></span><span style="">'.__('LatePoint', 'latepoint').'</span>',
      'href'  => $link,
      'meta'  => array( 'class' => '' )
    );
    if(OsAuthHelper::is_agent_logged_in() || OsAuthHelper::is_admin_logged_in()) $wp_admin_bar->add_node( $args );
  }


  /**
   * Register shortcodes
   */
  public function register_shortcodes() {
    add_shortcode( 'latepoint_book_button', array('OsShortcodesHelper', 'shortcode_latepoint_book_button' ));
    add_shortcode( 'latepoint_book_form', array('OsShortcodesHelper', 'shortcode_latepoint_book_form' ));
    add_shortcode( 'latepoint_customer_dashboard', array('OsShortcodesHelper', 'shortcode_latepoint_customer_dashboard' ));
    add_shortcode( 'latepoint_customer_login', array('OsShortcodesHelper', 'shortcode_latepoint_customer_login' ));
  }

  /*

   SHORTCODES 

  */




  public function setup_environment() {
    if ( ! current_theme_supports( 'post-thumbnails' ) ) {
      add_theme_support( 'post-thumbnails' );
    }
    add_post_type_support( LATEPOINT_AGENT_POST_TYPE, 'thumbnail' );
    add_post_type_support( LATEPOINT_SERVICE_POST_TYPE, 'thumbnail' );
    add_post_type_support( LATEPOINT_CUSTOMER_POST_TYPE, 'thumbnail' );
  }







  public function create_required_tables() {
    OsDatabaseHelper::run_setup();
  }



  /**
   * Register core post types.
   */
  public function register_post_types() {
  }



  public function add_facebook_sdk_js_code(){
    $facebook_app_id = OsSettingsHelper::get_settings_value('facebook_app_id');
    if(empty($facebook_app_id)) return '';
    return "window.fbAsyncInit = function() {
              FB.init({
                appId      : '{$facebook_app_id}',
                cookie     : true,
                xfbml      : true,
                version    : 'v3.1'
              });
                
              FB.AppEvents.logPageView();
                
            };

            (function(d, s, id){
               var js, fjs = d.getElementsByTagName(s)[0];
               if (d.getElementById(id)) {return;}
               js = d.createElement(s); js.id = id;
               js.src = 'https://connect.facebook.net/en_US/sdk.js';
               fjs.parentNode.insertBefore(js, fjs);
             }(document, 'script', 'facebook-jssdk'));";

  }


  public function add_google_signin_meta_tags(){
    echo '<meta name="google-signin-client_id" content="'.OsSettingsHelper::get_settings_value('google_client_id').'">';
  }

  /**
  * Register scripts and styles - FRONT 
  */
  public function load_front_scripts_and_styles() {
    $localized_vars = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 
      'string_today' => __('Today', 'latepoint'),
      'time_system' => OsTimeHelper::get_time_system(), 
      'msg_not_available' => __('Not Available', 'latepoint'), 
      'phone_format' => OsSettingsHelper::get_phone_format(),
      'enable_phone_masking' => OsUtilHelper::is_phone_formatting_disabled() ? 'no' : 'yes',
      'booking_button_route' => OsRouterHelper::build_route_name('steps', 'start'),
      'show_booking_end_time' => (OsSettingsHelper::get_settings_value('show_booking_end_time') == 'on') ? 'yes' : 'no',
      'customer_dashboard_url' => OsSettingsHelper::get_customer_dashboard_url(),
      'stripe_key' => '',
      'braintree_key' => '',
      'braintree_tokenization_key' => '',
      'is_braintree_active' => false,
      'is_stripe_active' => false,
      'demo_mode' => OsSettingsHelper::is_env_demo(),
      'is_braintree_paypal_active' => false,
      'is_paypal_native_active' => false,
      'manual_next_step' => OsSettingsHelper::get_settings_value('manual_next_step', 'off'),
      'cancel_booking_prompt' => __('Are you sure you want to cancel this appointment?', 'latepoint'),
      'body_font_family' => '-apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
      'currency_symbol_before' => OsSettingsHelper::get_settings_value('currency_symbol_before', ''),
      'currency_symbol_after' => OsSettingsHelper::get_settings_value('currency_symbol_after', ''),
      'is_timezone_selected' => OsTimeHelper::is_timezone_saved_in_session()
    );

    // Stylesheets
    wp_enqueue_style( 'latepoint-main-front',   $this->public_stylesheets() . 'main_front.css', false, $this->version );

    // Javscripts
    wp_enqueue_script( 'sprintf',                 $this->public_vendor_javascripts() . 'sprintf.min.js', [], $this->version );
    if(false == OsUtilHelper::is_phone_formatting_disabled()) wp_enqueue_script( 'jquery-mask',             $this->public_vendor_javascripts() . 'jquery.inputmask.bundle.min.js', ['jquery'], $this->version );

    // Addon scripts and styles
    do_action('latepoint_wp_enqueue_scripts');

    // PAYMENTS
    if(!OsSettingsHelper::is_env_demo()){
      // -- Stripe
      if(OsSettingsHelper::is_using_stripe_payments()){
        wp_enqueue_script( 'stripe', 'https://js.stripe.com/v3/', false, null );
        $localized_vars['stripe_key'] = OsPaymentsStripeHelper::get_publishable_key();
        $localized_vars['is_stripe_active'] = true;
      }

      // -- Braintree
      if(OsSettingsHelper::is_using_braintree_payments()){
        wp_enqueue_script( 'braintree-client', 'https://js.braintreegateway.com/web/3.44.2/js/client.min.js', false, null );
        wp_enqueue_script( 'braintree-hosted-fields', 'https://js.braintreegateway.com/web/3.44.2/js/hosted-fields.min.js', false, null );
        $localized_vars['braintree_key'] = OsPaymentsBraintreeHelper::get_publishable_key();
        $localized_vars['braintree_tokenization_key'] = OsPaymentsBraintreeHelper::get_tokenization_key();
        $localized_vars['is_braintree_active'] = true;

        // PayPal Braintree
        if(OsSettingsHelper::is_using_paypal_braintree_payments()){
          wp_enqueue_script( 'braintree-checkout', 'https://www.paypalobjects.com/api/checkout.js', false, null );
          wp_enqueue_script( 'braintree-paypal-checkout', 'https://js.braintreegateway.com/web/3.44.2/js/paypal-checkout.min.js', false, null );
          $localized_vars['is_braintree_paypal_active'] = true;
          $localized_vars['paypal_payment_currency'] = OsSettingsHelper::get_braintree_currency_iso_code();
          $localized_vars['braintree_paypal_environment_name'] = OsPaymentsBraintreeHelper::get_environment_name();
          $localized_vars['braintree_paypal_client_auth'] = OsPaymentsBraintreeHelper::generate_client_token();
        }
      }

      // PayPal Native
      if(OsSettingsHelper::is_using_paypal_native_payments()){
          $localized_vars['is_paypal_native_active'] = true;
          $localized_vars['paypal_payment_currency'] = OsSettingsHelper::get_paypal_native_currency_iso_code();
          wp_enqueue_script( 'paypal-sdk', 'https://www.paypal.com/sdk/js?currency='.$localized_vars['paypal_payment_currency'].'&intent=authorize&commit=false&client-id='.OsSettingsHelper::get_settings_value('paypal_client_id'), false, null );
      }

    }

    // Google Login
    if(OsSettingsHelper::is_using_google_login()) wp_enqueue_script( 'google-platform', 'https://apis.google.com/js/platform.js', false, null );

    wp_enqueue_script( 'latepoint-main-front',  $this->public_javascripts() . 'main_front.js', array('jquery', 'sprintf'), $this->version );

    if(OsSettingsHelper::is_using_facebook_login()) wp_add_inline_script( 'latepoint-main-front', $this->add_facebook_sdk_js_code());
    
    wp_localize_script( 'latepoint-main-front', 'latepoint_helper', $localized_vars );
  }

  public function add_admin_body_class( $classes ) {
    if((is_admin() || current_user_can('edit_bookings')) && isset($_GET['page']) && $_GET['page'] == 'latepoint'){
      $classes = $classes.' latepoint-admin latepoint';
    }
    return $classes;
  }

  public function add_body_class( $classes ) {
    $classes[] = 'latepoint';
    return $classes;
  }


  /**
  * Register admin scripts and styles - ADMIN
  */
  public function load_admin_scripts_and_styles() {
    // Stylesheets
    wp_enqueue_style( 'latepoint-google-fonts', 'https://fonts.googleapis.com/css?family=Barlow:400,500&display=swap&subset=latin-ext', false );
    wp_enqueue_style( 'latepoint-main-back', $this->public_stylesheets() . 'main_back.css', false, $this->version );

    // Javscripts
    wp_enqueue_media();


    wp_enqueue_script( 'sprintf',                 $this->public_vendor_javascripts() . 'sprintf.min.js', [], $this->version );
    wp_enqueue_script( 'dragula-js',              $this->public_vendor_javascripts() . 'dragula.min.js', [], $this->version );
    wp_enqueue_script( 'chart-js',                $this->public_vendor_javascripts() . 'Chart.min.js', [], $this->version );
    wp_enqueue_script( 'moment-js',               $this->public_vendor_javascripts() . 'moment-with-locales.min.js', [], $this->version );
    wp_enqueue_script( 'jquery-mask',             $this->public_vendor_javascripts() . 'jquery.inputmask.bundle.min.js', ['jquery'], $this->version );
    wp_enqueue_script( 'daterangepicker',         $this->public_vendor_javascripts() . 'daterangepicker.min.js', ['moment-js'], $this->version );
    wp_enqueue_script( 'pickr-widget',            $this->public_vendor_javascripts() . 'pickr.min.js', [], $this->version );
    wp_enqueue_script( 'circles-js',              $this->public_javascripts() . 'circles.js', $this->version );
    wp_enqueue_script( 'latepoint-main-back',     $this->public_javascripts() . 'main_back.js', ['jquery', 'sprintf', 'dragula-js', 'chart-js', 'moment-js', 'jquery-mask', 'daterangepicker', 'pickr-widget', 'circles-js'], $this->version );

    do_action('latepoint_admin_enqueue_scripts');

    $localized_vars = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 
      'font_family' => '"Barlow", -apple-system, system-ui, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, sans-serif',
      'wp_locale' => get_locale(),
      'string_today' => __('Today', 'latepoint'),
      'click_to_copy_done' => __('Copied', 'latepoint'),
      'click_to_copy_prompt' => __('Click to copy', 'latepoint'),
      'approve_confirm' => __('Are you sure you want to approve this booking?', 'latepoint'),
      'reject_confirm' => __('Are you sure you want to reject this booking?', 'latepoint'),
      'time_system' => OsTimeHelper::get_time_system(), 
      'msg_not_available' => __('Not Available', 'latepoint'), 
      'msg_addon_installed' => __('Installed', 'latepoint'), 
      'string_minutes' => __('minutes', 'latepoint'), 
      'phone_format' => OsSettingsHelper::get_phone_format(),
      'currency_symbol_before' => OsSettingsHelper::get_settings_value('currency_symbol_before', ''),
      'currency_symbol_after' => OsSettingsHelper::get_settings_value('currency_symbol_after', ''),
      'enable_phone_masking' => OsUtilHelper::is_phone_formatting_disabled() ? 'no' : 'yes'  );


    $localized_vars = apply_filters('latepoint_localized_vars_admin', $localized_vars);

    wp_localize_script( 'latepoint-main-back', 'latepoint_helper', $localized_vars );

  }

}
endif;


$LATEPOINT = new LatePoint();
