<link href="<?php echo plugins_url("nomination-and-voting/css/");?>vote.css" rel="stylesheet"  />
<?php
    $fbappid= get_option('_wp_nv_fb_appid');
?>
<style type="text/css">

</style>

<!--<div id="fb-root"></div> -->
      <script>
function SetCookie(cookieName,cookieValue) {
 var today = new Date();
 var expire = new Date();
 var nDays=1;
 expire.setTime(today.getTime() + 3600000*24*nDays);
 document.cookie = cookieName+"="+escape(cookieValue)
                 + ";expires=0";
}
function ReadCookie(cookieName) {
 var theCookie=" "+document.cookie;
 var ind=theCookie.indexOf(" "+cookieName+"=");
 if (ind==-1) ind=theCookie.indexOf(";"+cookieName+"=");
 if (ind==-1 || cookieName=="") return "";
 var ind1=theCookie.indexOf(";",ind+1);
 if (ind1==-1) ind1=theCookie.length; 
 return unescape(theCookie.substring(ind+cookieName.length+2,ind1));
}
	  
	  function  fblogin(){
	  
	  FB.login(function(response) {
   
 
 
	  	if (response.status === 'connected') {
                  FB.api('/me', function(response) {
				  
                  for (var key in response) {
                      if(key=="email" ||  key=="name"){
                      	SetCookie(key,response[key]);
						document.getElementById("c_"+key).value=response[key];
                      }
                    }
					//alert(ReadCookie("email"));
					if(ReadCookie("email")) {
					//document.getElementById("dark1").style.display="none";
					}
					else{ document.getElementById("dark1").style.display="";}
                  });
				  
				  
                }else{
					SetCookie("email","");
					SetCookie("name","");
				}
				}, {scope: 'email,publish_actions'}); 
	  }
	 
	 
	 
	 
	  

      function checkLoginState() {
        FB.getLoginStatus(function(response) {
          showMe(response);
        });
      }
        window.fbAsyncInit = function() {
          FB.init({
            appId      : '<?php echo $fbappid;?>',
            status     : true, 
            cookie     : true,
            xfbml      : true,
            version    : 'v2.5'
            //oauth      : true,
          });
          showMe = function(response){
          
              if (response.status === 'connected') {
                  FB.api('/me',{fields: 'id,email,name'}, function(anoresponse) {
				  //alert(JSON.stringify(anoresponse));
                  for (var key in anoresponse) {//alert(key+anoresponse[key]);
                      if(key=="email" ||  key=="name"){
                          
                      	SetCookie(key,response[key]);
						document.getElementById("c_"+key).value=anoresponse[key];
                      }
                    }
					//alert(ReadCookie("email"));
					if(ReadCookie("email")) document.getElementById("dark1").style.display="none";else{ document.getElementById("dark1").style.display="";}
                  });
				  
				  
                }else{
					SetCookie("email","");
					SetCookie("name","");
				} 
            };
          FB.getLoginStatus(function(response) {
              showMe(response);
              FB.Event.subscribe('auth.statusChange', showMe);
              FB.Event.subscribe('auth.logout', function(response){
  	            SetCookie("email","");
	            SetCookie("name","");
              });
          });
          
          
          
};
         
        (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
      </script>
	  
<div class="outer_div">

<div class="dark" id="dark1">
        
      <fb:login-button scope="publish_actions,email" onlogin="checkLoginState();">
      </fb:login-button>
</div>
	  
	  
<form name="nomination" id="nomination" method="post" >
<input type="hidden" name="action" value="nomination_submit">
<div class="inner_top_div">
I nominate <input type="text" name="nominee" id="nominee"> for 
<?php 
global $wpdb;
$categories=$wpdb->get_results("SELECT t. * , tt. *
FROM ".$wpdb->prefix."terms AS t
INNER JOIN ".$wpdb->prefix."term_taxonomy AS tt ON t.term_id = tt.term_id
WHERE tt.taxonomy
IN (
'VotingCategory'
)
ORDER BY t.term_id ASC");
echo "<select name='cat' id='cat'>";
foreach($categories as $key => $value){
	echo "<option value='".$value->term_id."'>".$value->name."</option>";
}
echo "</select>";
?> in the awards.
</div>
<div class="bottom_div">

<div class="bottom_inner_div">
<div class="award_logo"><img src="<?php echo plugins_url("nomination-and-voting/tpls/");?>award.png" height="112" /></div>
<div class="award_details">
<div class="award_title">The 5th annual awards</div>
<div class="award_message">
<textarea rows="2"  name="textarea">I just nominated monir for game of the year</textarea>
</div>
</div>
<div class="share_n">
<span><font style="color:#FFFFFF; font-weight:bold; padding-left:5px;font-size:15px;">Share my nomination to : </font></span>
<span><div class="social_media">
    <!--<img src="<?php echo plugins_url("nomination-and-voting/tpls/");?>twitter.png" style="vertical-align:text-bottom" /> Twitter <input type="checkbox" name="tw" id="tw" value="tw" />-->
    <img src="<?php echo plugins_url("nomination-and-voting/tpls/");?>facebook.png" style="vertical-align:text-bottom" /> Facebook <input type="checkbox" name="fb" id="fb" value="fb" />
    </div></span>
<span class="btnspan">
<input type="submit" name="nominate" id="nominate" value="Nominate" class="nom_btn">
<input name="c_email" id="c_email" type="hidden" value="" />
<input name="c_name" id="c_name" type="hidden" value="" /></span>    
</div>

</div>

<br>
</form>
</div>
<div id="message"></div> 
<script language="JavaScript">
    <!--
      jQuery('#nomination').submit(function(){
	  	if(jQuery('#fb').is(':checked')){
            if(jQuery.cookie("email")== null || jQuery.cookie("email")== ""){
                document.getElementById("dark1").style.display="";
                alert("Please Sign in with your facebook account");
				fblogin();
                return false;
            }
          }
           /*if(jQuery('#tw').is(':checked')){
            if(jQuery.cookie("twitter_anywhere_identity")==null || jQuery.cookie("twitter_anywhere_identity")==""){
                document.getElementById("dark1").style.display="";
                alert("Please Sign in with your twitter account");
				  twttr.anywhere(function (T) {
				  T.signIn();
					T.bind("authComplete", function (e, user) {
					  // triggered when auth completed successfull
					  //alert('<?php //echo $_GET['access_token'];?>');
					});
					
					
				  });
                return false;
            }
          }
          
          if(!jQuery('#fb').is(':checked') && !jQuery('#tw').is(':checked')){ 
            document.getElementById("dark1").style.display="";
            alert("Please tick the checkbox either you nominate facebook/twitter account");
            return false;
        }
          if((jQuery.cookie("email")== null || jQuery.cookie("email")== "") && (jQuery.cookie("twitter_name")=="" && jQuery.cookie("twitter_anywhere_identity")=="")){
            
            document.getElementById("dark1").style.display="";
            alert("Please Sign in with your facebook/twitter account");
            return false;
        }*/
        
           if(jQuery('#fb').is(':checked')){
                       if(jQuery.cookie("email")!=""){
                            var nomi=jQuery("#nominee").val();
                            if(nomi==""){
                                alert("Please enter the nominee name");
								//Fb.login(function(res){});
                                return false;
                            }
                           var fbmsg="<?php echo get_option('_wp_nv_fb_twiter_text');?>";
						   var cate=jQuery('#cat option:selected').text();
                           fbmsg=fbmsg.replace("{nominee}", nomi);
                           fbmsg=fbmsg.replace("{category}", cate);
                           var params = {};
                            params['message'] = fbmsg;
                            /*params['name'] = 'asf';
                            params['description'] = 'asf';
                            params['link'] = 'http://apps.facebook.com/summer-mourning/';
                            params['picture'] = 'http://summer-mourning.zoocha.com/uploads';
                            params['caption'] = 'Caption';*/
                              
                            FB.api('/me/feed', 'post', params, function(response) {
                              if (!response || response.error) {
                                alert('You are not logged in to your facebook account.Please login to continue');
                                 fblogin();
								 return false;
                              } else {
                                //alert('Published to stream - you might want to delete it now!');
                                jQuery('#nomination').ajaxSubmit({
                                   'url': '<?php echo home_url()?>/',
                                   'beforeSubmit':function(){
                                       jQuery('#loading').fadeIn();
                                   },
                                   'success':function(res){
                                       jQuery('#loading').fadeOut();
                                       jQuery('#message').text(res);
                                       
                                      
                                       //alert(fbmsg);
                                   }
                               });
                              }
                            }); 
                        }
                   }
                   
                   
                   if(jQuery('#tw').is(':checked')){
                        if(jQuery.cookie("twitter_anywhere_identity")){
                            /*jQuery.post(
                            '<?php //echo home_url();?>/',
                            {
                            action: "post_twitter",
                            name:nomi
                            },
                            function(res){
                            }
                            );*/
							var nomi=jQuery("#nominee").val();
							if(nomi==""){
                                alert("Please enter the nominee name");
                                return false;
                            }
                            jQuery('#nomination').ajaxSubmit({
                               'url': '<?php echo home_url()?>/',
                               'beforeSubmit':function(){
                                   jQuery('#loading').fadeIn();
                               },
                               'success':function(res){
                                   jQuery('#loading').fadeOut();
                                   jQuery('#message').text(res);
                                   
                                   
                                   //alert(fbmsg);
                               }
                           });
                         var cate=jQuery('#cat option:selected').text();   
                        var twmsg='<?php echo get_option('_wp_nv_fb_twiter_text1');?>';
                       twmsg=twmsg.replace("{nominee}", nomi);
                        twmsg=twmsg.replace("{category}", cate);    
                            
                              twttr.anywhere(function (T) { 
                                T("#dark1").tweetBox({
                                  height: 100,
                                  width: 400,
                                  defaultContent: twmsg
                                }); 
                              });
                        }
               }
                   
                   

      return false;
      });
    //-->
    </script>
</div>
