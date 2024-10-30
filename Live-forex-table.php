<?php  
/* 
Plugin Name: Live Forex Rate
Plugin URI: http://spikersolutions.com/wp_plugins_live_forex.php
Version: 1.1 
Description: Live Forex Table plugin displays unlimited foreign exchange rate in tabular format based on Google Finance. You can choose your currency and exchange value with the desired currency.  This plugin allows to create unlimited foreign exchange list like in news paper / magazine. 
Author: Spiker Solutions
Author URI: http://spikersolutions.com/ 
*/  
define( 'WP_DEBUG', false );

include_once(  dirname( __FILE__ )  . '/inc/widgets.php'  );

add_shortcode( 'live_forex_rate', 'lft_callinti' );

add_action( 'admin_menu', 'lft_live_forex_settings_menu' );

add_action( 'admin_init', 'lft_mysettings' ); 

add_action( 'widgets_init',  'register_forex_widget'  ); 




   function lft_callinti()
	{
		 require_once('display_rate.php');
	}
 	function lft_liveForex($your,$other){
		 $timeOut = 0; 
		 $curlrequest = curl_init(); 
		 curl_setopt ($curlrequest, CURLOPT_URL, "http://www.google.com/finance/converter?a=1&from=$your&to=$other"); 
		 curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1); 
		 curl_setopt ($curlrequest, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
		 curl_setopt ($curlrequest, CURLOPT_CONNECTTIMEOUT, $timeOut); 
		 $gresponse = curl_exec($curlrequest); 
		 curl_close($curlrequest); 
		$checkreg     = '#\<span class=bld\>(.+?)\<\/span\>#';
		preg_match($checkreg, $gresponse, $datas);
		return $datas[0];
	} 
	
	function lft_live_forex_settings_menu() {
    add_options_page(
        'Live Forex Settings',
        'Live Forex Settings',
        'manage_options',
        'live-forex-plugin',
        'lft_managesettings'
    );
	}
	
	function lft_forex_settings(){
		require_once('forex-settings.php');
		
	}

	function lft_mysettings() {
		register_setting( 'lft_option_group', 'lft_txttitle' );
		register_setting( 'lft_option_group','lft_your_currency');
		register_setting( 'lft_option_group', 'lft_other_currency');
 	} 

	function lft_managesettings() { 
	
		if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			
			echo '<div class="wrap">';
			echo '<h1> Settings</h1><br /><h2>Live Forex Settings</h2><br /><form action ="options.php" method="post">';
			settings_fields( 'lft_option_group' );
		
			echo 'Plugin Title: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<input type="text" name="lft_txttitle" value="Live Forex" /><br /> Base Currency:&nbsp;&nbsp;&nbsp;<input type="text" name="lft_your_currency" value="' . get_option( 'lft_your_currency' ) .  '" /><br />Other Currency: <input type="text" name="lft_other_currency" value="' . get_option( 'lft_other_currency' )  . '" /><br />- Use currency <a href="https://en.wikipedia.org/wiki/ISO_4217" target="_blank">code </a>like USD, EUR <br />- Use comma seperation in each currency, eg: USD, EUR, CHF<br /> ';
		submit_button(); 
			echo '</form><br /><b>If you need any help or want to customize it contact us at support@spikersolutions.com</b>';
	
	 } 
	 
	 /**
		 * Registers Plugin's widget.
		 */
		function register_forex_widget(){

			register_widget( 'LFT_Widget' );

		}
 
 ?>