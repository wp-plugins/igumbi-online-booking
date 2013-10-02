<?php
/*
Plugin Name: igumbi Online Booking
Plugin URI: http://www.igumbi.com/en/wordpress?utm_source=wpadmin
Description: Generate commission free online bookings directly on your Wordpress Site. igumbi.com is a simple and fast online booking tool / online booking engine (also a online hotelsoftware / PMS & a revenue / yield management system). igumbi gives you dynamic prices based on revenue management algorithms, which help you implement an upselling strategy. This will help you, as the property owner, to earn more and pay less OTA comissions. The plugin is free to use, but you do need an account with igumbi.com. A free trial account is available at http://www.igumbi.com/trial.
Version: 1.0
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
}


function igumbi_booking_remove() {
   delete_option('igumbi_language');
   delete_option('igumbi_hotel_id');
   delete_option('igumbi_wide');
}

add_shortcode("igumbi_dialog", "igumbi_dialog");
add_shortcode("igumbi_avform", "igumbi_avform");

function igumbi_dialog() { 
  return "<div id='free_rooms'></div>";
}

function igumbi_avform() { 
  $str  = "";
  $str .= "<div id='avform' style='height:240px;width:200px;'></div>";
  $str .= "<script src='https://www.igumbi.net/seller/";
  $str .= get_option('igumbi_hotel_id');
  $str .= "/de/start.js";
  if(get_option('igumbi_wide')==1)
    $str .= "?layout=wide";
  $str .= "'></script>";
  return $str;
}
      

if (is_admin()) {
  add_action('admin_menu', 'igumbi_menu');
  function igumbi_menu() {
    add_options_page('igumbi Online Booking', ' igumbi Online Booking', 'administrator', 'igumbi-admin-menu', 'igumbi_admin_page');
  }
}

function igumbi_admin_page() {
?>
  <div class="wrap">
  <div id="icon-options-general" class="icon32"><br /></div>
    <h2>igumbi Online Booking Tool Embedding Page</h2>
    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
      <table>
        <tr valign="top">
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi Hotel Code:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <input name="igumbi_hotel_id" type="text" id="igumbi_hotel_id" size=6 maxlength=100 
            value="<?php echo get_option('igumbi_hotel_id'); ?>"/>
          <p class="description">The 6 character <b>hotel code</b> you will find on the igumbi.com <a href="https://www.igumbi.net/settings/mine?locale=en">settings page</a>.<br/> 
            To see it you need an account and need to be logged in over at <a href="https://www.igumbi.net/login">igumbi</a>.<br/>
            You can create a new <a href="http://www.igumbi.com/trial?locale=en">trial account</a>.</p>
          </td>
          
        </tr>
        <tr valign="top">
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi Language Code:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <input name="igumbi_language" type="text" id="igumbi_language" size=6 maxlength=100 
            value="<?php echo get_option('igumbi_language'); ?>"/>
            <p class="description">ISO language code: Supported languages are DE, EN, GR, RU, ES</p>            
          </td>
        </tr>
        <tr valign="top">
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi wide mode:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <select id="igumbi_wide" name="igumbi_wide">
              <option value="0" <?php if(get_option('igumbi_wide') == 0) echo "selected='selected'";?>>tall</option>
              <option value="1" <?php if(get_option('igumbi_wide') == 1) echo "selected='selected'";?>>wide</option>
           </select>
           <p class="description">
             Tall or wide mode: The tall mode should be used in the sidebar as a widget. <br/>
             The wide mode you integrate into the header or the top of the body via the shortcode [igumbi_avform].
           </p>            
          </td>
        </tr>

        <tr valign="top">
          <td colspan="2" style="padding: 5px 5px 7px 5px;">
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="igumbi_hotel_id,igumbi_language,igumbi_wide" />
            <input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary" />
          </td>
        </tr>
      </table>
    </form>
    
    <h2>Getting started:</h2>
    <ol>
      <li>Sign-up for a <a href="http://www.igumbi.com/trial?locale=en">igumbi trial account</a> and enter the settings above.</li>
      <li>Set up your property in igumbi: at least describe your productcategories and load a picture for each productcategory. Ensure your bookings have been entered so that the correct availability can be calculated before setting the booking tool live on the site.</li>
      <li>Create a testpage (or update your site template) and enter the the shortcode: [igumbi_avform] and [igumbi_dialog].</li>
      <li>Add the [igumbi_avform] to the sidebar widgets. Menu Appearance >> Widgets and pull it to the top under/above search - move it up high, as it will be your primary conversion goal.</li>
      <li>Add the shortcode to your theme as the first item after <code>&lt;div id="content" role="main"&gt;</code> with <code>&lt;?php echo do_shortcode("[igumbi_dialog]");?&gt;</code></li>
    </ol>    
  </div>
<?php
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