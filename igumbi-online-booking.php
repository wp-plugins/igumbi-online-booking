<?php
/*
Plugin Name: igumbi Online Booking
Plugin URI: http://www.igumbi.com/en/wordpress?utm_source=wpadmin
Description: Generate commission free online bookings directly on your WordPress Site. igumbi.com is a simple and fast online booking tool / online booking engine (also an online hotelsoftware / PMS & a revenue / yield management system). igumbi gives you dynamic prices based on revenue management algorithms, which help you implement an upselling strategy. This will help you, as the property owner, to earn more and pay less OTA comissions. The plugin is free to use, but you do need an account with igumbi.com. A <a href='http://www.igumbi.com/trial?lang=en&utm_source=wpadmin'>free trial account</a> is available at <a href='http://www.igumbi.com/'>igumbi.com</a>.
Version: 1.7
Author: Roland Oth
Author URI: http://www.igumbi.com
License: GPLv3
*/
register_activation_hook(__FILE__,'igumbi_booking_install'); 
register_deactivation_hook( __FILE__, 'igumbi_booking_remove');

function igumbi_booking_install()  {
  add_option("igumbi_hotel_id", '6BEA7AW', '', 'yes'); // Default is the igumbi Demo Hotel
  add_option("igumbi_language", 'de', '', 'yes');
  add_option("igumbi_wide", '0', '', 'yes');     
  add_option("igumbi_custom_css", '#avform {color:#333;width:220px;} /*tweak the CSS to adjust to your template */', '', 'yes');     
}


function igumbi_booking_remove() {
   delete_option('igumbi_language');
   delete_option('igumbi_hotel_id');
   delete_option('igumbi_wide');
   delete_option('igumbi_custom_css');

}

add_shortcode("igumbi_dialog", "igumbi_dialog");
add_shortcode("igumbi_avform", "igumbi_avform");

function igumbi_dialog() { 
  return "<div id='free_rooms'></div>";
}

function igumbi_avform($atts) {
  extract(shortcode_atts(array(
     'lang' =>get_option('igumbi_language'),
     'wide' =>get_option('igumbi_wide'),
     'test' =>false
  ), $atts))
  ;
  $str  = "";
  $str .= "<div id='avform'></div>";
  $str .= "<script src='https://www.igumbi.net/seller/";
  $str .= get_option('igumbi_hotel_id');
  $str .= "/". $lang . "/start.js";
  if( $wide ==1 or $wide =='true') {
    $str .= "?layout=wide";
  } else {
    $str .= "?layout=tall";
  }
  if ($test =='true') {
    $str .= "&test=true";
  }
  $str .= "'></script>";
  $str .='<style>'. get_option('igumbi_custom_css').'</style>';
  return $str;
}
      

if (is_admin()) {
  add_action('admin_menu', 'igumbi_menu');
  function igumbi_menu() {
    add_options_page('igumbi Online Booking', ' igumbi Online Booking', 'administrator', 'igumbi-admin-menu', 'igumbi_admin_page');
  }
}

function igumbi_admin_page() {
  include 'includes/views/admin-page.php';
}

class igumbi_Widget extends WP_Widget {
  function igumbi_Widget() {
    $widget_ops = array('classname' => 'igumbi_Widget', 'description' => 'Display the igumbi online booking tool entry form: dates, rooms and persons'); 
    $this->WP_Widget('igumbi_Widget', 'igumbi online booking tool', $widget_ops);   
  }
  	
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    $widget_html = $instance['widget_html'];
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">
       Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
      </label>
    </p>
    <?php
  }
  
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }

  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    if (!empty($title))
    	echo $before_title . $title . $after_title;
    echo igumbi_avform($attr);
    echo $after_widget;
  }
}
add_action( 'widgets_init', create_function('', 'return register_widget("igumbi_Widget");') );
?>
