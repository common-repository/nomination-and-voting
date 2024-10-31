<br><br>
<?php
    $mode=get_option('_wp_nv_mode');
    if(empty($mode))$mode="nomination";
    $text= get_option('_wp_nv_fb_twiter_text');;
?>
Select Mode 
<form method="post" action="" id="plugin_mode" name="plugin_mode">
    <input type="hidden" name="action" value="mode"> 
<input type="radio" name="voting" id="voting" value="voting" <?php if($mode=="voting")echo "checked='checked'"; ?>>
Voting 
<input type="radio" name="voting" id="nomination" value="nomination" <?php if($mode=="nomination")echo "checked='checked'"; ?>>
Nomination
<br>
Text for the FB and Twitter posts <input type="text" name="fb_twiter_text" id="fb_twiter_text" value="<?php echo $text;?>">
<br> <br>

<input type="submit" id="btn" class="button-primary" value="Save Settings"> 
 <span id="loading" style="display: none;"><img src="images/loading.gif" alt=""> saving...</span> 
</form>

<script language="JavaScript">
    <!--
      jQuery('#plugin_mode').submit(function(){
           jQuery(this).ajaxSubmit({
               'url': ajaxurl,
               'beforeSubmit':function(){
                   jQuery('#loading').fadeIn();
               },
               'success':function(res){
                   jQuery('#loading').fadeOut();
               }
           });
      return false;
      });
    //-->
    </script>