<?php
/*
Plugin Name: 豆瓣读书观影记录
Plugin URI: https://noooooe.cn
Description: 用的是牧风的SDK，原项目地址https://mufeng.me/post/have-seen-the-film。我拿来适配了wordpress，三无产品，不接受技术支持。
Version: 2.0
Author: Bearye
Author URI: https://noooooe.cn
*/

//register css and js
function my_bmdb_css_js(){
    wp_enqueue_script("jquery");
    wp_enqueue_style( 'bbmdbb', plugins_url( 'includes/Bmdb.min.css',__FILE__),null,'1.6.0');
    wp_enqueue_script( 'bbmdbb', plugins_url( 'includes/Bmdb.min.js',__FILE__),null,'1.6.0');
}
add_action('wp_enqueue_scripts', 'my_bmdb_css_js');

//add head meta
function bmdb_head(){
    echo '<meta name="referrer" content="never">';
}
add_action('wp_head','bmdb_head');

//add shortcode
function add_bmdb($atts=null, $content=null, $code=""){
    return "<div class='BMDB'></div><script>jQuery(document).ready(function ($) {new Bmdb({type: '".$content."', selector: '.BMDB', secret: '".get_option('bmdb_secret')."', noMoreText: '".(get_option('bmdb_endtext')?get_option('bmdb_endtext'):"加载完毕")."', limit: ".(get_option('bmdb_limit')?get_option('bmdb_limit'):30)."})})</script>";
}
add_shortcode('bmdb', 'add_bmdb');

//add bmdb settings page
function bmdb_admin(){
    if( !empty($_POST) && check_admin_referer('bmdb_update') ) {
        update_option('bmdb_secret', $_POST['bmdb_secret']);
        update_option('bmdb_limit', $_POST['bmdb_limit']);

        update_option('bmdb_endtext', $_POST['bmdb_endtext']);
        ?>
        <div id="message" class="updated">
            <p><strong>更改成功</strong></p>
        </div>
        <?php
    }
    ?>
    <div class="wrap">
        <h1>豆瓣读书观影设置</h1>
        <form method="post" action="" novalidate="novalidate">
            <input type="hidden" name="option_page" value="general"><input type="hidden" name="action" value="update"><input type="hidden" id="_wpnonce" name="_wpnonce" value="adde27b40a"><input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php">
            <table class="form-table" role="presentation">

                <tbody><tr>
                    <th scope="row"><label for="bmdb_secret">Secret</label></th>
                    <td>
                        <input name="bmdb_secret" type="text" id="bmdb_secret" value="<?php echo esc_attr(get_option('bmdb_secret')) ?>" class="regular-text">
                        <p class="description" id="new-admin-email-description">在页面填入 <code>[bmdb]movies[/bmdb]</code> 显示观影记录，填入 <code>[bmdb]books[/bmdb]</code> 显示读书记录。</p>
                        <p class="description" id="new-admin-email-description">secret请到https://bm.weajs.com/申请</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="bmdb_secret">每页显示的数量</label></th>
                    <td>
                        <input name="bmdb_limit" type="text" id="bmdb_limit" value="<?php echo esc_attr(get_option('bmdb_limit')) ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="bmdb_secret">加载完成显示文字</label></th>
                    <td>
                        <input name="bmdb_endtext" type="text" id="bmdb_endtext" value="<?php echo esc_attr(get_option('bmdb_endtext')) ?>" class="regular-text">
                    </td>
                </tr>
                </tbody></table>


            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="保存更改"></p>
            <?php wp_nonce_field('bmdb_update'); ?>
        </form>
    </div>
<?php
}

//add bmdb settings menu
function bmdb_menu() {
    add_options_page('豆瓣读书观影', '豆瓣读书观影', 'manage_options', 'bmdb','bmdb_admin' );
}
add_action( 'admin_menu', 'bmdb_menu' );