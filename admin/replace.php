<div class="wrap">

<h2>Kaitora Affiliate Manager - Replacement Options</h2>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>



<table class="form-table">

<legend>
<H3>Replacement Settings </H3>
</legend>
<p>Enable Kaitora Affiliate Manager? YES
  <input type="radio" name="kdals_active" id="kdals_active" value="yes" <?PHP if(get_option('kdals_active') == "yes") { echo 'checked="checked"}'; } ?> >
NO
<input type="radio" name="kdals_active" id="kdals_active" value="no" <?PHP if(get_option('kdals_active') == "no") { echo 'checked="checked"}'; } ?> >
</p>
<p>Enable Friendly Linking (e.g. Mysite.com/[friendlier word]/[keyword] ) YES
  <input type="radio" name="kdals_seo" id="kdals_seo" value="yes" <?PHP if(get_option('kdals_seo') == "yes") { echo 'checked="checked"}'; } ?> >
NO
<input type="radio" name="kdals_seo" id="kdals_seo" value="no" <?PHP if(get_option('kdals_seo') == "no") { echo 'checked="checked"}'; } ?> >
<BR />Custom word for redirection URL :
<input type="text" name="kdals_friendly_value" value="<?PHP echo get_option("kdals_friendly_value"); ?> ">

</p>
<p>Place no more than 
  <input name="kdals_limit" value="<?PHP echo get_option('kdals_limit');?>" type="text" size="3" maxlength="3" /> 
  affiliate links of each keyword in a post. </p>


</table>



<input type="hidden" name="action" value="update" />

<input type="hidden" name="page_options" value="kdals_active,kdals_seo,kdals_frequency,kdals_limit,kdals_friendly_value" />



<p class="submit">

<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

</p>



</form>

</div>
