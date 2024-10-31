<br><br>
<?php
    $mode=get_option('_wp_nv_mode');
    if(empty($mode))$mode="nomination";
    $text= get_option('_wp_nv_fb_twiter_text');
	$text1= get_option('_wp_nv_fb_twiter_text1');
	
	$text2= get_option('_wp_nv_fb_twiter_text2');
    $text3= get_option('_wp_nv_fb_twiter_text3');
	$fbappid= get_option('_wp_nv_fb_appid');
?>
<strong>Select Mode</strong> 
<form method="post" action="" id="plugin_mode" name="plugin_mode">
    <input type="hidden" name="action" value="mode"> 
<input type="radio" name="voting" id="voting" value="voting" <?php if($mode=="voting")echo "checked='checked'"; ?>>
Voting 
<input type="radio" name="voting" id="nomination" value="nomination" <?php if($mode=="nomination")echo "checked='checked'"; ?>>
Nomination
<br><br>
{nominee} for the nominee name. {category} for the selected category name <br><br>
Text for the FB  posts for nomination <input type="text" name="fb_twiter_text" id="fb_twiter_text" value="<?php echo $text;?>" size="80">(e.g. I nominate {nominee} for this category)
<br />
<!--Text for the Twitter posts for nomination <input type="text" name="fb_twiter_text1" id="fb_twiter_text1" value="<?php echo $text1;?>" size="80">(e.g. I nominate {nominee} for this category-->

<br>
Text for the FB  posts for voting <input type="text" name="fb_twiter_text2" id="fb_twiter_text2" value="<?php echo $text;?>" size="80">(e.g. I vote {nominee} for this category)
<br />
<!--Text for the Twitter posts for voting <input type="text" name="fb_twiter_text3" id="fb_twiter_text3" value="<?php echo $text1;?>" size="80">(e.g. I vote {nominee} for this category-->

Facebook Application ID: <input type="text" name="fb_appid" id="fb_appid" value="<?php echo $fbappid;?>" size="80">
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