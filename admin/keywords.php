<form method="post" name="add_kdals" action="">

<table class="form-table">

<legend><H3>Add New Keyword to Replace</H3></legend>
<p>Anchor Text to Replace <BR />
<input name="kdals_new_link" type="text" />
</p>
<p>Affiliate Link URL<BR />
<input name="kdals_new_url" length="25" type="text" />
</p>
<p>Replace every <input name="kdals_replace_every" length="5" size="3" type="text" /> of this keyword (set to 1 for every instance to be replaced)</p>

</table>

<p class="submit">

<input type="submit" name="kdals_add" class="button-primary" value="Add New Link" />

</p>



</form>

<h3>Current Keywords Being Replaced</h3>

<?PHP

$kdals_currect_sql = "SELECT id,keyword FROM ".$kdalstblname."";
$current_links = $wpdb->get_results($kdals_currect_sql);

echo '<table width="45%">';

foreach($current_links as $kdals_cl){
    echo '<tr><td>' . $kdals_cl->keyword . '</td><td><A href="admin.php?page=kdals_keywords&kdals_action=edit_kdals&keyword='.$kdals_cl->keyword.'">Edit</A></td><td><A href="admin.php?page=kdals_keywords&kdals_action=del_kdals&keyword='.$kdals_cl->keyword.'">Delete</A></td></tr>';
}    
    
echo '</table>';


?>

</div>
