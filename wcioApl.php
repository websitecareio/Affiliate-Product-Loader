<?php
/*
Plugin Name: Affiliate Product Listing - Websitecare.io
Plugin URI: https://websitecare.io/
Description: [PREMIUM] Load Affiliate Products from PartnerAds feeds.
Version: 1.0.0
Author: Kim Vinberg
Author URI: https://websitecare.io
*/

class wcioApl {

      public function __construct() {

            // Shortcodes
            add_shortcode( 'wcioApl', array($this, 'wcio_apl_shortcode_function') );

            // Create database tables
            register_activation_hook( __FILE__, array($this, 'wcio_apl_create_database_tables') );

            // Load JS
            add_action('wp_enqueue_scripts',array( $this, 'wcio_apl_frontend_javascript' ) );

            // Admin menu
            if( is_admin() ){

                  add_action( 'admin_menu', array( $this, 'wcio_apl_add_admin_plugin_page' ) );
                  add_action( 'admin_init', array( $this, 'wcio_apl_page_init' ) );

                  // Add new feed
                  if(isset($_POST['wcio_apl_post_add_new_feed'])){

                        // Variables for adding new feed
                  	$wcio_apl_post_add_new_feed = $_POST['wcio_apl_post_add_new_feed'];
                        $feedUrl = $_POST['feedUrl'];
                        $feedType = $_POST['feedType'];
                        $saveFeed = $_POST['wcioAplSaveButton'];

                        if( !empty($feedUrl) && !empty($feedType) && !empty($saveFeed) ) {

                              global $wpdb;

                              // Save the feed
                              $host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
                              $pdo = new PDO($host, DB_USER, DB_PASSWORD);
                              require_once(dirname(__FILE__)."/wcioApl.class.php");
                              $wcio_apl = new wcio_affiliate_product_listing($pdo);
                              $prefix = $wpdb->prefix;
                              $output = $wcio_apl->wcio_apl_add_feed( $feedUrl, $feedType, $prefix );

                        }


                  }


                  if(isset($_GET['wcio_apl_post_delete_feed'])){

                        // Variables for adding new feed
                        $feedId = (int)$_GET['wcio_apl_post_delete_feed'];

                  	// Delete feed by id
                        if( !empty($feedId) && is_int($feedId) ) {

                              global $wpdb;

                              // Save the feed
                              $host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
                              $pdo = new PDO($host, DB_USER, DB_PASSWORD);
                              require_once(dirname(__FILE__)."/wcioApl.class.php");
                              $wcio_apl = new wcio_affiliate_product_listing($pdo);
                              $prefix = $wpdb->prefix;
                              $output = $wcio_apl->wcio_apl_delete_feed( $feedId, $prefix );

                        }

                  }

            }

      }

      public function wcio_apl_frontend_javascript() {
            wp_enqueue_script( 'wcop_apl_js', plugins_url( '/js/scripts.js', __FILE__ ));
      }

      public function wcio_apl_add_admin_plugin_page() {
          // This page will be under "Settings"
          add_menu_page(
              __( 'Affiliate Product List Settings', 'wcioApl' ),
              'Affiliate Product Listing',
              'manage_options',
              'wcioAplAdminSettings',
              array( $this, 'wcio_apl_admin_page_content' )
          );
      }

      public function wcio_apl_admin_page_content() {
            global $wpdb;
          ?>
          <style>
          .wcioSettingLabel {
                font-size:12px;
                color: #a0a0a0;
                display:block;
          }
          </style>
          <div class="wrap">
              <h1 style="font-weight: 700;"><?php echo __( 'Affiliate Product Listing Settings', 'wcioApl' ); ?></h1>
              <h4>Websitecare.io | support@websitecare.io</h4>
              <br/>

              <form action="" method="POST">
              <h3>Settings:</h3>

              <table class="wp-list-table widefat fixed striped posts">
                    <thead>
               <tr>
                          <th scope="col" id="settings" class="manage-column column-id" style="width:20%;">Setting</th>
                          <th scope="col" id="title" class="manage-column column-title column-url" style="width:80%;">Value</th>
                    </tr>
               </thead>

     <tr>
           <!-- API key -->
            <th scope="col" id="title" class="manage-column column-title column-button" style="width:20%;">API key
            <span class="wcioSettingLabel">For premium freatures. Find your API keys on invoice</span></th>
            <th scope="col" id="title" class="manage-column column-title column-url" style="width:80%;">
                  <input type="text" class="create_feed_input" name="feedUrl" style="width:100%;height:32px;padding: 3px 8px;" placeholder="wcioApl-0000-0000-0000-0000" />
            </th>
      </tr>

      <tr>
           <!-- Cron password -->
            <th scope="col" id="title" class="manage-column column-title column-button" style="width:20%;">Cronjob password
            <span class="wcioSettingLabel">For cronojb feature</span></th>
            <th scope="col" id="title" class="manage-column column-title column-url" style="width:80%;">
                  <input type="text" class="create_feed_input" name="feedUrl" style="width:100%;height:32px;padding: 3px 8px;" placeholder="" />
            </th>
     </tr>


     <tr>
           <td colspan="2" style="text-align:right;"><button type="submit" name="" class="button button-primary" value="Save" style="line-height: 20px; font-size: 12px;">Save settings</button></td>
     </tr>
   </table>
              </form>

              <h3>Create a new feed:</h3>
                 <form action="" method="POST">
                       <input type="hidden" name="wcio_apl_post_add_new_feed" value="1">
                       <table class="wp-list-table widefat fixed striped posts">
                             <thead>
                       	<tr>
                                   <th scope="col" id="settings" class="manage-column column-id" style="width:60%;">Feed Url</th>
                                   <th scope="col" id="title" class="manage-column column-title column-url" style="width:30%;">Feed type</th>
                                   <th scope="col" id="title" class="manage-column column-title column-type" style="width:10%;"></th>
                             </tr>
                       	</thead>

                              <tr>
                          <th scope="col" id="title" class="manage-column column-title column-url" style="width:50%;"> <input type="text" class="create_feed_input" name="feedUrl" style="width:100%;height:32px;padding: 3px 8px;" placeholder="https://" /></th>
                          <th scope="col" id="title" class="manage-column column-title column-type">
                              <select name="feedType" style="width:100%;height:32px;padding: 3px 8px;" >
                                    <option value="partnerads">PartnerAds</option>
                                    <option value="wcioshop">WCIOShop</option>
                                    <option value="shoporama">[Premium] Shoporama</option>
                                    <option value="shopify">[Premium] Shopify</option>
                                    <option value="dandomain">[Premium] Dandomain Shop Feed</option>
                                    <option value="google">[Premium] Google Feed</option>
                                    <option value="adtraction">[Premium] Adtraction Feed</option>
                              </select>

                          </th>
                          <th scope="col" id="title" class="manage-column column-title column-button" style="text-align:right;">
                                 <button type="submit" name="wcioAplSaveButton" class="button button-primary" value="Save" style="line-height: 20px; font-size: 12px;">Create New Feed</button>
                           </th>
                        </tr>
                        </table>
                 </form>


              <h3>Feeds:</h3>
              <?php
              $table = $wpdb->prefix.'wcio_apl_feeds';
              $feeds = $wpdb->get_results("SELECT * FROM $table");
              ?>
              <table class="wp-list-table widefat fixed striped posts">
	<thead>
	<tr>
            <th scope="col" id="settings" class="manage-column column-id" style="width:5%;">Feed Id</th>
            <th scope="col" id="title" class="manage-column column-title column-url" style="width:60%;"><span>Feed Url</span></th>
            <th scope="col" id="title" class="manage-column column-title column-type"><span>Feed type</span></th>
            <th scope="col" id="title" class="manage-column column-title column-type" style="width:5%;"></th>
      </tr>
	</thead>

	<tbody id="the-list">



<?php
              foreach ($feeds as $feed) {
              ?>
                  <tr>
                        <td><?php echo $feed->id ?></td>
                        <td><?php echo $feed->feedUrl ?></td>
                        <td><?php echo $feed->feedType ?></td>
                        <td><a href="admin.php?page=wcioAplAdminSettings&wcio_apl_post_delete_feed=<?php echo $feed->id ?>" onclick="return confirm('<?php echo __( 'Removing a feed will delete products in the database from this feed, are you sure?', 'wcioApl' ); ?>')"><?php echo __( 'Remove', 'wcioApl' ); ?></a></td>
                  </td>

                </tr>
              <?php
              }

               ?>

               <tfoot>
                     <tr>
                           <th scope="col" id="settings" class="manage-column column-id">Feed Id</th>
                           <th scope="col" id="title" class="manage-column column-title column-url" style="width:50%;"><span>Feed Url</span></th>
                           <th scope="col" id="title" class="manage-column column-title column-type"><span>Feed type</span></th>
                           <th scope="col" id="title" class="manage-column column-title column-type"></th>
                     </tr>
               </tfoot>
</tbody>
         </table>
          </div>
          <?php
      }

      public function wcio_apl_page_init() {

          register_setting(
             'my_option_group', // Option group
             'my_option_name', // Option name
             array( $this, 'sanitize' ) // Sanitize
          );

          add_settings_section(
             'setting_section_id', // ID
             'Settings For Ad Feed Links', // Title
             array( $this, 'print_section_info' ), // Callback
             'wcioAplAdminSettings' // Page
          );

          add_settings_field(
             'counter' , //amount of fields created
             '', //title
             array($this, 'num_fields_callback'), //callback
             'wcioAplAdminSettings', //Page
             'setting_section_id' //Section
             );
      }

      public function wcio_apl_create_database_tables() {
            global $wpdb;
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            $charset_collate = $wpdb->get_charset_collate();

            // Products table
            $wcioDatabaseTableName = $wpdb->prefix . 'wcio_apl_products';  // table name
            $sql = "CREATE TABLE $wcioDatabaseTableName (
                  id int(20)  NOT NULL AUTO_INCREMENT,
                  feedId int(10) NOT NULL,
                  productDealer varchar(255) NULL,
                  category varchar(255) NULL,
                  brand varchar(255) NULL,
                  stockLevel varchar(255) NULL,
                  productId varchar(255) NULL,
                  productEan varchar(255) NULL,
                  productName varchar(255) NULL,
                  productDescription varchar(10000) NULL,
                  productPrice int(100) NULL,
                  productPriceOld varchar(255) NULL,
                  productPriceDiscount int(10) NULL,
                  productImage varchar(255) NULL,
                  productUrl varchar(255) NULL,
                  created varchar(255) NULL,
                  PRIMARY KEY (id)
            ) $charset_collate;";
            dbDelta( $sql );

            // Feeds table
            $wcioDatabaseTableName = $wpdb->prefix . 'wcio_apl_feeds';  // table name
            $sql = "CREATE TABLE $wcioDatabaseTableName (
                    id int(20) NOT NULL AUTO_INCREMENT,
                    feedUrl varchar(255) NOT NULL,
                    feedType varchar(255) NOT NULL,
                    updated varchar(255) NOT NULL,
                    PRIMARY KEY (id)
                  ) $charset_collate;";
            dbDelta( $sql );

      }


      public function wcio_apl_shortcode_function( $atts ) {
            global $wpdb;

            $host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
            $pdo = new PDO($host, DB_USER, DB_PASSWORD);

          $atts = shortcode_atts( array(
              'searchcolumn' => 'productName',
              'searchcolumntype' => 'LIKE',
              'searchcolumnvalue' => '',
              'orderby' => 'productPrice',
              'orderbytype' => 'DESC',
              'secondaryoperator' => ' AND ',
              'secondarysearch' => '',
              'limitto' => '100',
              'startfrom' => '0',
              'template' => '1',
        ), $atts, 'wcioApl' );

          // Load class
          require_once(dirname(__FILE__)."/wcioApl.class.php");
          $output = "";
          $wcio_apl = new wcio_affiliate_product_listing($pdo);
          $prefix = $wpdb->prefix;
          $products = $wcio_apl->wcio_apl_get_products(
                $atts['searchcolumn'],
                $atts['searchcolumntype'],
                $atts['searchcolumnvalue'],
                $atts['orderby'],
                $atts['orderbytype'],
                $atts['secondaryoperator'],
                $atts['secondarysearch'],
                $atts['limitto'],
                $atts['startfrom'],
                $prefix);

          $output .= "<div class=\"wcioApl-wrapper template-".$atts['template']."\">";
          $i = "1";
          foreach( $products AS $product) {

                if( $atts['template'] == '1') {
                     include(dirname(__FILE__)."/templates/template-1.php");
                  $i++;
           }
                if( $atts['template'] == '2') {
                     include(dirname(__FILE__)."/templates/template-2.php");
                  $i++;
           }

      }

          $output .= "</div>";

          return $output;
      }



}


$wcioApl = new wcioApl();
