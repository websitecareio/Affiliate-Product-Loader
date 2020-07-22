<?php
define( 'SHORTINIT', TRUE );
include(dirname(__FILE__)."/../../../wp-config.php");
$cronPass = "zp%HYb%LtNM4FAJ@1%pI6";

if( $_GET['pass'] == $cronPass ) {

      echo "Welcome CRON user<br>";
      echo "Password correct<br><br>";

      $feedId = ""; // NULL value

      // If we want to update specific feed ID, then use feedId in get.
      if( $_GET['feedId'] ) {
            $feedId = (int)$_GET['feedId'];
      }

      // Load config and class
      $host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
      $pdo = new PDO($host, DB_USER, DB_PASSWORD);

      require_once("wcioApl.class.php");
      $wcio_apl = new wcio_affiliate_product_listing($pdo);
      echo $wcio_apl->wcio_apl_update_feed( $feedId, $table_prefix );

}
?>
