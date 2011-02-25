<?PHP



/*



Plugin Name: Kaitora Affiliate System



Plugin URI: http://www.kaitoradesigns.co.uk



Description: A powerful affiliate link system that will turn your keywords into the affiliate links of your choice. Choose how often you want the keywords replaced, which keywords get replaced, what links you want and much more. This plugin also features a tracking system, allowing you to see which keywords are converting, and how many clicks they have recieved.



Version: 1.1



Author: Kaitora



Author URI: http://www.kaitoradesigns.co.uk



*/

/*  Copyright 2010  Kaitora  (email : bowerman39@hotmail.com)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/



//from hereon in, plugin abbreviated to kdals (kaitora designs affiliate link system)

//in order to maintain simplicity



$kdals_version = '1.1';
$kdals_vars = Array('kdals_function','kdals_keyword');

function kdals_install(){

   global $wpdb;

   global $kdals_version;

   $kdalstblname = $wpdb->prefix . 'kdals_links';

 

   if($wpdb->get_var("show tables like '$kdalstblname'") != $kdalstblname) {

      

      $sql = "CREATE TABLE " . $kdalstblname . " (

      id MEDIUMINT NOT NULL AUTO_INCREMENT ,

      keyword VARCHAR( 70 ) NOT NULL ,

      url VARCHAR( 500 ) NOT NULL ,

      replace_every MEDIUMINT NOT NULL,

      clicks INT NOT NULL ,

      PRIMARY KEY ( `id` )

)



;";



      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      dbDelta($sql);

      

      add_option("kdals_db_version", $kdals_version);

    }


}



function kdals_uninstall(){

    global $wpdb;

   $kdalstblname = $wpdb->prefix . 'kdals_links';    

   

   $sql = "DROP TABLE " . $kdalstblname;



    $wpdb->query($sql);    

}

function kdals_queryvars($qvars){

    global $kdals_vars;

return array_merge($qvars,$kdals_vars);

}

function kdals_flush(){
    
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
    
}

function kdals_friendly_url(){
    
    global $wp_rewrite;
    
    $kdals_friendlier_rule = array('out/([^/\.]+)?$' => 'index.php?kdals_function=recommended_sites&kdals_keyword=$1');
    
    $wp_rewrite->non_wp_rules = $kdals_friendlier_rule + $wp_rewrite->non_wp_rules;
}

function kdals_rewrite_rules($kdals_rules)

{

        global $wp_rewrite;

        global $wpdb;

        global $kdalstblname;

        

	$rules_sql="select keyword from $kdalstblname";

	$kdals=$wpdb->get_results($rules_sql,ARRAY_A);

	$kdals_custom_rules=Array();

	if(is_array($kdals) && count($kdals)>0){

		foreach($kdals as $kdals_rewrite_print){

		        $kdals_custom_rules[$kdals_rewrite_print['keyword']]='index.php?kdals_function=recommended_sites&kdals_keyword='.urlencode($kdals_rewrite_print['keyword']);

		}

        	$kdals_rules=array_merge($kdals_custom_rules,$kdals_rules);

	}

        return $kdals_rules;

}

function kdals_add_rewrite_rules($wp_rewrite){
    
$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

}

function kdals_url_parse(){

    

    global $kdals_vars;

    global $wpdb;

    global $kdals_version;

    $kdalstblname = $wpdb->prefix . 'kdals_links';

    

    foreach($kdals_vars as $qvars){

        if(isset($req->query_vars[$qvars])){

            $_GET[$qv]=$req->query_vars[$qvars];

        }

    }

   if($_GET['kdals_function'] == "recommended_sites"){



        $get_kdals_link_sql = "SELECT * FROM ".$kdalstblname." WHERE keyword='".$_GET['kdals_keyword']."'";

        $kdals_out = $wpdb->get_results($get_kdals_link_sql,ARRAY_A) or die(mysql_error());

        if($kdals_out){

            

            $add_click_kdals_sql = "UPDATE ".$kdalstblname." SET clicks=clicks+1 WHERE keyword='".$_GET['kdals_keyword']."'";

            $wpdb->query($add_click_kdals_sql);

                        

            foreach($kdals_out as $kdals_out_print){

                if(preg_match('#^ *http://#s',$kdals_out_print['url'])){

				include(WP_PLUGIN_DIR.'/kaitora-affiliate-system/out/out_link.php');	

			}else{

				include(WP_PLUGIN_DIR.'/kaitora-affiliate-system//out/out_code.php');

			}

			exit(0);                       

            }

            

        } 

    }

    

}



function kdals_versioncheck(){



global $kdals_version;

    

$ch = curl_init();

$timeout = 5; // set to zero for no timeout

curl_setopt ($ch, CURLOPT_URL, 'http://www.kaitoradesigns.co.uk/versions/kdals.txt');

curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

$currentvers = curl_exec($ch);

curl_close($ch);

    



    if($kdals_version != $currentvers){

        echo '<div id="message" class="updated fade"><FONT color=#FF0000><B>You are currently using an older version of the Kaitora Designs Affiliate Link System. Please upgrade to the latest version, which is version<FONT color=#0000FF> ' . $currentvers . '</FONT></B></FONT></div>';

    }

}



function kdals_admin_call(){

    

    global $kdals_version;

    

    require(WP_PLUGIN_DIR.'/kaitora-affiliate-system/admin/adminfunc.php');

    add_menu_page('Kaitora Affiliate Manager', 'Affiliate Manager', '8', 'kdalsmenu', 'kdals_menu_top');

    add_submenu_page('kdalsmenu', 'Keywords Settings', 'Keywords', 'administrator', 'kdals_keywords', 'kdals_links_menu');

    add_submenu_page('kdalsmenu', 'Replacement Settings', 'Replacement Options', 'administrator', 'kdals_replacement', 'kdals_replace_menu');

    add_submenu_page('kdalsmenu', 'Reports', 'Reports', 'administrator', 'kdals_reports', 'kdals_reports');



}



function run_kdals($content){

    global $wpdb;

    $kdalstblname = $wpdb->prefix . 'kdals_links';    

    

    if(get_option('kdals_active') == "yes"){

        

    preg_match('#([)omit_kdals(])#e',$content,$kdals_active_count);



        if($kdals_active_count == 1){

            

        $content2 = str_replace('[omit-kdals]','',$content);

        $content = $content2;

        

        return $content;

        

            

        } ELSE {

        

        $get_kdals_keywords = "SELECT keyword,replace_every FROM ".$kdalstblname;

        $kdals_replace_array = $wpdb->get_results($get_kdals_keywords,ARRAY_A);

      

        if($kdals_replace_array){

            

            $kdals_limit = get_option('kdals_limit');

            $kdals_count = 0;



        foreach ($kdals_replace_array as $kdals_keyword_go){

            

            if($kdals_count < $kdals_limit){

            

            $kdals_replace_limit = $kdals_limit - $kdals_count;

            $kdals_keyword = $kdals_keyword_go['keyword'];
            
            if(get_option('kdals_seo') == "yes"){
            $kdals_go_url = get_option('home') . '/out/' . $kdals_keyword;    
            } ELSE {
            $kdals_go_url = get_option('home') . '?kdals_function=recommended_sites&kdals_keyword=' . $kdals_keyword;
            }
            
            $replacement = '<A href="'.$kdals_go_url.'" title="'.$kdals_keyword.'" rel="nofollow">'.$kdals_keyword.'</A>';
            
            if($kdals_keyword_go['replace_every'] == 1){

             $kdals_replace_code = '#(\b' . preg_quote($kdals_keyword) . '\b)#is';

             $content2 = preg_replace($kdals_replace_code,$replacement, $content,$kdals_replace_limit,$kdals_count_add);   

            } ELSE {

            $kdals_replace_code = '#('.str_repeat('\b' . preg_quote($kdals_keyword) . '\b.+?', $kdals_keyword_go['replace_every'] - 1).')\b' . preg_quote($kdals_keyword) . '\b#is';

            $content2 = preg_replace($kdals_replace_code, "$1{$replacement}", $content,$kdals_replace_limit,$kdals_count_add);

            }            

            $content = $content2;

            $kdals_count = $kdals_count + $kdals_count_add;

            

                      }

            

                 }

      

            }

        

       } 

    }  

    

    return $content;

    

}


add_filter('query_vars', 'kdals_queryvars' );

add_filter('rewrite_rules_array', 'kdals_rewrite_rules');

register_activation_hook(__FILE__,'kdals_install');

register_deactivation_hook(__FILE__,'kdals_uninstall');

add_filter('the_content', 'run_kdals');

add_action('parse_request','kdals_url_parse');

add_action('admin_menu', 'kdals_admin_call');

add_action('generate_rewrite_rules', 'kdals_friendly_url');

add_action('admin_init', 'kdals_flush');


?>