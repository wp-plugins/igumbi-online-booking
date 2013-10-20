  <div class="wrap">
  <div id="icon-options-general" class="icon32"><br /></div>
    <h2>igumbi Online Booking Tool Embedding Page</h2>
    <form method="post" action="options.php">
    <?php wp_nonce_field('update-options'); ?>
      <table>
        <tr valign="top">
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi Hotel Code:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <input name="igumbi_hotel_id" type="text" id="igumbi_hotel_id" size=6 maxlength=100 style="width:100px; padding:6px; font-size:16px;"
            value="<?php echo get_option('igumbi_hotel_id'); ?>"/>
          <p class="description">You will find the 7 character <b>hotel code</b> on the igumbi.com <a href="https://www.igumbi.net/settings/mine?locale=en">settings page</a>.<br/> 
            To see it you need an account and need to be logged in at <a href="https://www.igumbi.net/login">igumbi</a>.<br/>
            Create a new <a href="http://www.igumbi.com/trial?locale=en">trial account</a>.</p>
          </td>
          
        </tr>
        <tr valign="top">
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi Language Code:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <select id="igumbi_language" name="igumbi_language">
              <?php
                $langs = array("de","en","gr","ru","es");
                foreach($langs as $lang) { 
                  echo "<option value = " . $lang . " ";
                  if(get_option("igumbi_language") == $lang) echo 'selected="selected"';
                  echo ">" . $lang . "</option>";  
                }   
              ?>
            </select>

            
            <p class="description">Language: ISO code, Supported languages are de, en, gr, ru, es</p>            
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
          <td style="width: 160px; padding: 5px 5px 7px 5px;">igumbi custom css:</td>
          <td style="padding: 5px 5px 7px 5px;">
            <textarea id="igumbi_custom_css" name="igumbi_custom_css" style="width:600px;height:300px;"><?php echo get_option('igumbi_custom_css');?></textarea>
           <p class="description">
             You can overwrite the CSS provided by the igumbi booking tool. Check the <a href="http://www.igumbi.com/stylesheets/seller.css" target="_blank">basic CSS</a> (mostly width and dimensions),<br/> the <a href="http://www.igumbi.com/stylesheets/sellerci.css" target="_blank">button and colors CSS</a> and the <a href="http://www.igumbi.com/stylesheets/date.css" target="_blank">date popup CSS</a> files.
           </p>            
          </td>
        </tr>

        <tr valign="top">
          <td colspan="2" style="padding: 5px 5px 7px 5px;">
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="igumbi_hotel_id,igumbi_language,igumbi_wide,igumbi_custom_css" />
            <input type="submit" value="<?php _e('Save Changes') ?>" class="button button-primary" />
          </td>
        </tr>
      </table>
    </form>
    
    <h2>Getting started:</h2>
    <ol>
      <li>Sign-up for a <a href="http://www.igumbi.com/trial?locale=en" class="button button-primary">igumbi trial account</a> and enter the settings above.</li>
      <li>Set up your property in igumbi: at least describe your productcategories and load a picture for each productcategory. Ensure your bookings have been entered so that the correct availability can be calculated before setting the booking tool live on the site.</li>
      <li>Create a testpage (or update your site template) and enter the the shortcode: [igumbi_avform] and [igumbi_dialog]. You can get all fancy with [igumbi_avform wide=true lang=es] to handle different layouts and languages. 

</li>
      <li>Add the [igumbi_avform] to the sidebar widgets. Menu Appearance >> Widgets and pull it to the top under/above search - move it up high, as it will be your primary conversion goal.</li>
      <li>Add the shortcode [igumbi_dialog] to your theme as the first item after <code>&lt;div id="content" role="main"&gt;</code> with <code>&lt;?php echo do_shortcode("[igumbi_dialog]");?&gt;</code>. <br/> This is where the response from the booking tool will land after an availability request has been made.</li>
    </ol>    
  </div>
