<?PHP

function kdals_menu_top(){

global $kdals_version;
kdals_versioncheck($kdals_version_url);

echo '

<div class="wrap">

<h2>Kaitora Affiliate Manager </h2>

<p>Thank you for choosing the Kaitora Designs Affiliate Links System. We hope you can gain maximum use of this plugin to mask your affiliate links for your website.</p>

<H2>Basic Help and Support</H2>

<p>
<H3>Known Issues:</H3>
Should you use a keyword that is in your URL (in my example, it was wordpress) if it is not the first keyword in the replacement sequence (this is the order you add keywords to the system), then it will corrupt all of the other links.<BR />
The workaround for this is to ensure that the first keyword you input is the one featured in the URL. This is especially important for users who have installed wordpress into a folder or subdomain (e.g. blog.example.com or example.com/blog). In these examples, if you have the keyword blog, and it is ran afterwards, the replacement code, picks up the keyword in all of the previously created links.
</p>

<p>
<i><b>How do I prevent KDALS running on specific pages/posts?</b></i><br /><br />
To prevent KDALS from replacing keywords in specific posts/pages, then insert the following code at the beginning of each post/page you wish to omit this plugin from:<BR /> <code>[omit-kdals]</code>
</p>

<p>
<i><b>How do I contact you to request further assistance?</b></i><br /><br />
Head on over to <A href="http://www.kaitoradesigns.co.uk">Kaitora Designs</A> and use the contact form to request assistance. Please be as specific as possible in all requests.
</p>
<i><b>I purchased your plugin, and there is an update available, do i have to pay for it?</b></i><br /><br />
With the purchase of this plugin, all updates are free. Should this update be a bigger release of the plugin, you may need to request a voucher to remove any fees. To do that, please head on over to <A href="http://www.kaitoradesigns.co.uk">Kaitora Designs</A> and use the contact form. If possible, use the email address you used to purchase this plugin with, or provide us with your Paypal transaction number, and we will provide you with a voucher ASAP.
<p>

</p>

</div>

';

}

function kdals_links_menu(){
    global $wpdb;
    $kdalstblname = $wpdb->prefix . 'kdals_links';
    
    echo '
    <div class="wrap">

    <h2>Kaitora Affiliate Manager - Keywords Settings</h2>
    ';

        if($_GET['kdals_action'] == 'del_kdals' && $_GET['keyword']){
        
        $kdals_del_grab_sql = "SELECT id,keyword FROM ".$kdalstblname." WHERE keyword='".$_GET['keyword']."'";
        $kdals_del_grab = $wpdb->get_row($kdals_del_grab_sql);
        
        if($kdals_del_grab){
            
            if($_POST['kdals_del_confirm']){

            $kdals_del_sql = "DELETE FROM ".$kdalstblname." WHERE keyword='".$_GET['keyword']."'";
            $kdals_del = $wpdb->query($kdals_del_sql);
            
            if($kdals_del){
                
                echo 'Successfully Deleted <B>' . $_GET['keyword'] . '</B><BR />';
                
            }
                
            } ELSE {
                
        echo '<p>
        
        <form method="post" name="kdals_del_confirm_form" action="">
        
        Are you sure you wish to delete the keyword <B>'.$_GET['keyword'].'</B>? Once deleted, it cannot be restored! <BR />

        <input type="submit" name="kdals_del_confirm" class="button-primary" value="Confirm Keyword Deletion!" />

        </form> </p><BR />';
                
            }
            
        }
        
    }
    
    if($_GET['kdals_action'] == 'edit_kdals' && $_GET['keyword']){

        $kdals_edit_grab_sql = "SELECT id,keyword,url,replace_every FROM ".$kdalstblname." WHERE keyword='".$_GET['keyword']."'";
        $kdals_edit_grab = $wpdb->get_row($kdals_edit_grab_sql);
        
        if($kdals_edit_grab){
            
            if($_POST['kdals_keyword_update']){
                
                $kdals_new_word = $_POST['kdals_update_link'];
                $kdals_new_link = $_POST['kdals_update_url'];
                $kdals_new_freq = $_POST['kdals_update_every'];
                
                if(!is_numeric($kdals_new_freq)){
                    $kdals_new_freq = "1";
                }
                
                if($kdals_new_word != "" && $kdals_new_link != ""){
                    
                    $kdals_update_keyword_sql = "UPDATE ".$kdalstblname." SET keyword='".$kdals_new_word."',url='".$kdals_new_link."',replace_every='".$kdals_new_freq."' WHERE id='".$kdals_edit_grab->id."'"; 
                    $kdals_update_keyword_go = $wpdb->query($kdals_update_keyword_sql);
                    
                    if($kdals_update_keyword_go){
                        echo '<B>Successfully Updated '.$kdals_new_word.'!</B><BR />';
                    }
                }
           }     
         ELSE {
        
        echo '
        <form method="post" name="add_kdals" action="">
        <table class="form-table">
        <legend><H3>Update details for '.$_GET['keyword'].'</H3></legend>
        <p>Anchor Text to Replace <BR />
        <input name="kdals_update_link" type="text" value="'.$_GET['keyword'].'"/>
        </p>
        <p>Affiliate Link URL<BR />
        <input name="kdals_update_url" length="25" type="text" value="'.$kdals_edit_grab->url.'"/>
        </p>
        <p>Replace every <input name="kdals_update_every" length="5" size="3" type="text" value="'.$kdals_edit_grab->replace_every.'"/> of this keyword (set to 1 for every instance to be replaced)</p>
        </table>
        <p class="submit">
        <input type="submit" name="kdals_keyword_update" class="button-primary" value="Update Keyword" />
        </p>
        </form>        
        ';          
        
            }  
        }
    }

   if($_POST['kdals_add']){
        
        $kdals_link_name = $_POST['kdals_new_link'];
        $kdals_link_url = $_POST['kdals_new_url'];
        $kdals_replace_every = $_POST['kdals_replace_every'];
        
        if(!is_numeric($kdals_replace_every)){
            $kdals_replace_every = "1";
        }
        
        $checkforkeyword = "SELECT keyword FROM ".$kdalstblname." WHERE keyword='".$kdals_link_name."'";
        if($wpdb->query($checkforkeyword)){
            echo '<B><FONT color=#FF0000>ERROR! The keyword '.$kdals_link_name.' is already being replaced!</FONT></B><BR />';
        } ELSE {

        $kdals_add_link_sql = "INSERT INTO ".$kdalstblname." (keyword,url,replace_every,clicks) VALUES ('".$kdals_link_name."','".$kdals_link_url."','".$kdals_replace_every."',0)";
        
        if($wpdb->query($kdals_add_link_sql)){
            
            echo "<B>Successfully added the keyword '".$kdals_link_name."' to the KDALS system</B><BR />";
            
        } ELSE {
            
            echo '<B><FONT color=#FF0000>Error adding new keyword to KDALS!</FONT></B><BR />';
            
        }
           
    }
        

    }
    
require_once('keywords.php');
    
}

function kdals_replace_menu(){

require_once('replace.php');    
 
}

function kdals_groups_menu(){
         
require_once('groups.php');
    
}

function kdals_reports(){
    global $wpdb;
    $kdalstblname = $wpdb->prefix . 'kdals_links';
require_once('reports.php'); 

}

?>