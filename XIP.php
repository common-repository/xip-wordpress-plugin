<?php
/*
Plugin Name: XIP WordPress Plugin
Plugin URI: http://xip.ir
Description:  <a href="http://xip.ir">XIP.ir</a> افزونه وردپرس زیپ کلیه لینکهای شما را تبدیل به لینک زیپی می‌کند و دیگر شما نیازی به تبدیل کردن دستی‌ لینکها ندارید
Version: 1.0
Author: XIP.ir
Author URI: http://xip.ir
License: GPL2
*/
/*
XIP.ir WordPress Plugin


Options:

- Enable XIP.ir
- Convert outgoing links only/all links to XIP.ir
- Ad type: Intestitial or banner
*/

add_action('wp_footer', 'wp_xip_get_script');

//get options
function wp_xip_get_options(){
    $explode = explode('/',get_option('home'));
    $options = array(
        'wp_xip_id' => get_option('wp_xip_id'),
        'wp_xip_type' => get_option('wp_xip_type'),
		'wp_xip_domains' => get_option('wp_xip_domains'),
        'wp_xip_convert' => (get_option('wp_xip_convert') == 'outgoing') ? $explode[2]:''
    );
    return $options;
}

function wp_xip_get_script(){
    if(!get_option('wp_xip_enable')){
        return false;
    }
    //get plugin options
    $options = wp_xip_get_options();
	
	if  (get_option('wp_xip_convert') == 'exclude') {
	 //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_xip_id'].";\n";
    $script .= "var adType = '".$options['wp_xip_type']."';\n";
    $script .= "var disallowDomains = [".$options['wp_xip_domains']."];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://xip.ir/js/fp.js\"></script>\n";
	} 
	else if  (get_option('wp_xip_convert') == 'include') {
	 //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_xip_id'].";\n";
    $script .= "var adType = '".$options['wp_xip_type']."';\n";
    $script .= "var allowDomains = [".$options['wp_xip_domains']."];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://xip.ir/js/fp.js\"></script>\n";
	} else {
    //populate script;
    $script = "<script>\n";
    $script .= "var accountID = ".$options['wp_xip_id'].";\n";
    $script .= "var adType = '".$options['wp_xip_type']."';\n";
    $script .= "var disallowDomains = ['".$options['wp_xip_convert']."'];\n";
    $script .= "</script>\n";
    $script .= "<script src=\"http://xip.ir/js/fp.js\"></script>\n";
	}
    echo $script;
		
}

//Let's create the options menu
// create custom plugin settings menu
add_action('admin_menu', 'wp_xip_create_menu');

function wp_xip_create_menu() {

	//create new top-level menu
	add_options_page('XIP.ir Plugin Settings', 'XIP.ir تنظیمات', 'administrator', __FILE__, 'wp_xip_settings_page',plugins_url('http://XIP.ir/images/Home-32.png', __FILE__));

	//call register settings function
	add_action( 'admin_init', 'wp_xip_register_mysettings' );
}


function wp_xip_register_mysettings() {
	//register our settings
	register_setting( 'wp-xip-settings-group', 'wp_xip_enable' );
	register_setting( 'wp-xip-settings-group', 'wp_xip_id' );
	register_setting( 'wp-xip-settings-group', 'wp_xip_convert' );
	register_setting( 'wp-xip-settings-group', 'wp_xip_domains' );
	register_setting( 'wp-xip-settings-group', 'wp_xip_type' );
}

function wp_xip_settings_page() {
?>
<div class="wrap" style="font-family: ubuntu,Sans-Serif">

<h2>XIP.ir WordPress Plugin</h2>
<div style = "background: #2b6b6a; background: -moz-linear-gradient(top, #2b6b6a, #2b6b6a);
	background: -ms-linear-gradient(#2b6b6a, #2b6b6a);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#2b6b6a), to(#2b6b6a));
	background: -webkit-linear-gradient(#2b6b6a, #2b6b6a);
	background: -o-linear-gradient(#2b6b6a, #2b6b6a);
	background: linear-gradient(#2b6b6a, #2b6b6a);
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;">
	<a href="http://XIP.ir">
		<center>
			<img src="http://xip.ir/images/logo1.png" title="XIP.ir | Earn Money From Your Website's Content"/>
		</center>
	</a>
</div>
<form method="post" action="options.php">
    <?php settings_fields( 'wp-xip-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><div>
          <p>فعال سازی افزونه</p></div></th>
        <td><p>&nbsp;
          </p>
          <p>
            <input type="checkbox" <?php if( get_option('wp_xip_enable' ) == 1){ echo 'checked'; }; ?> value="1" name="wp_xip_enable"/>
          </p></td>
        </tr>

        <tr valign="top">
        <th scope="row">XIP.ir ID
        <td><p>
          <input type="text" name="wp_xip_id" value="<?php echo get_option('wp_xip_id'); ?>" /> 
          </p>
          <div>
          <div>
            <p>برای یافتن آیدی خود به صفحه معرفی‌ به دوستان رفته و عددی که بعد از = می‌بینید آیدی شماست          </p>
          </div>
          <p><a href="http://xip.ir/referrals.php">http://xip.ir/referrals.php</a></p>
</div></td>
        </tr>

        <tr valign="top">
        <th scope="row"><div>
          <p>&nbsp;</p>
          <p>تبدیل کردن</p>
          </p>
        </div></th>
        <td>
            <p>
              <select name="wp_xip_convert" id="convert">
                <option value="outgoing" <?php if(get_option('wp_xip_convert') == 'outgoing') { echo 'selected="selected"';}?>>فقط لینکهای خروجی‌
                </options>
                <option value="all" <?php if(get_option('wp_xip_convert') == 'all') { echo 'selected="selected"';}?>>تمامی‌ لینک ها
                </options>
                <option value="include" <?php if(get_option('wp_xip_convert') == 'include') { echo 'selected="selected"';}?>>تمامی‌ لینک‌ها برای دامین های
                </options>
                <option value="exclude" <?php if(get_option('wp_xip_convert') == 'exclude') { echo 'selected="selected"';}?>>تمامی‌ لینک‌ها به غیر از دامین های
                </options>
              </select>
              <br/>
              <input type="text" name="wp_xip_domains" value="<?php echo get_option('wp_xip_domains'); ?>" /> 
              <br/>			
          </p>
          <div>
            <p>برای لیست دامین‌ها جهت افزودن یا منع کردن مطابق زیر عمل کنید            </p>
            <p>
              </p>
              'google.com' , 'facebook.com'</p>
          </div></td>
        </tr>

        <tr valign="top">
        <th scope="row">نوع تبلیغ</th>
        <td>
            <select name="wp_xip_type">
                <option value="int" <?php if(get_option('wp_xip_type') == 'int') { echo 'selected="selected"';}?>>تمام صفحه (بینابینی)</options>
                <option value="banner" <?php if(get_option('wp_xip_type') == 'banner') { echo 'selected="selected"';}?>>بنری</options>
            </select>
			
        </td>
        </tr>

    </table>

    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

    <p>

    </p>

</form>
</div>

<?php } ?>