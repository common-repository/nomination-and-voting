
<style type="text/css">
.dark{
 -moz-border-bottom-colors: none;
    -moz-border-image: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background: none repeat scroll 0 0 #8DC469;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    border-color: #AFE57A #305010 #497A18 #AFE57A;
    border-style: solid;
    border-width: 0 1px 1px;
    box-shadow: 3px 1px 2px rgba(0, 0, 0, 0.15);
    color: white;
    font-size: 1.25em;
    padding: 5px 10px;
    position: absolute;
    right: 8px;
    text-shadow: 1px 1px 1px #407120;
    top: -1px;
    z-index: 9999;
}
.voting{
    width:auto;
    
}
</style>
<?php
    $fbappid= get_option('_wp_nv_fb_appid');
?>
<div id="fb-root"></div> 
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
	  <div class="dark" id="dark1">
        <fb:login-button scope="publish_actions,email" onlogin="checkLoginState();">
      </fb:login-button>
      
      
      
      
      

    
	  </div>






<div>
<form name="voting" id="voting" method="post" >
<input type="hidden" name="action" value="voting_submit">
I vote <select name="nominee" id="nominee">
<option value="">--Select</option>
</select> 
for 
<?php 
global $wpdb;
$categories=$wpdb->get_results("SELECT t. * , tt. *
FROM ".$wpdb->prefix."terms AS t
INNER JOIN ".$wpdb->prefix."term_taxonomy AS tt ON t.term_id = tt.term_id
WHERE tt.taxonomy
IN (
'VotingCategory'
)
AND tt.count >0
ORDER BY t.term_id ASC");
echo "<select id='votcat' name='cat'><option value=''>--Select--</option>";
foreach($vcat as $key => $value){
	echo "<option rel='".$pid[$key]."' value='".$key."'>".$value."</option>";
}
echo "</select>";
?> in the awards.<br>
<input type="checkbox" name="fb" id="fb" value="fb"> vote through facebook 
   <br> 
  <br>
<input type="submit" name="nominate" id="nominate" value="Vote">
<input name="c_email" id="c_email" type="hidden" value="" />
<input name="c_name" id="c_name" type="hidden" value="" />  

</form>
<div id="message"></div> 
<script language="JavaScript">
    <!--
    jQuery('#votcat').live("change",function(){
        
        //alert(jQuery('#votcat>option:selected').attr("rel"));
        jQuery.post('<?php echo home_url()?>/',{
           action : "nominee_for_cat",
           catid : jQuery("#votcat").val(),
           postid : jQuery('#votcat>option:selected').attr("rel") 
        },
         function(res){
           jQuery("#nominee").html(res);  
         }
        );
    });
      jQuery('#voting').submit(function(){
	  	  if(jQuery('#fb').is(':checked')){
            if(jQuery.cookie("email")== null || jQuery.cookie("email")== ""){
                document.getElementById("dark1").style.display="";
                alert("Please Sign in with your facebook account");
                return false;
            }
          }
           if(jQuery('#tw').is(':checked')){
            if(jQuery.cookie("twitter_anywhere_identity")==null || jQuery.cookie("twitter_anywhere_identity")==""){
                document.getElementById("dark1").style.display="";
                alert("Please Sign in with your twitter account");
                return false;
            }
          }
          
          if(!jQuery('#fb').is(':checked') && !jQuery('#tw').is(':checked')){ 
            document.getElementById("dark1").style.display="";
            alert("Please Sign in with your facebook/twitter account");
            return false;
        }
          /*if((jQuery.cookie("email")== null || jQuery.cookie("email")== "") && (jQuery.cookie("twitter_name")=="" && jQuery.cookie("twitter_anywhere_identity")=="")){
			
            document.getElementById("dark1").style.display="";
			alert("Please Sign in with your facebook/twitter account");
			return false;
		}*/
        
           jQuery(this).ajaxSubmit({
               'url': '<?php echo home_url()?>/',
               'beforeSubmit':function(){
                   jQuery('#loading').fadeIn();
               },
               'success':function(res){
                   jQuery('#loading').fadeOut();
                   jQuery('#message').text(res);
                   
                   var nomi=jQuery("#nominee").val();
                   var fbmsg="<?php echo get_option('_wp_nv_fb_twiter_text2');?>";
                   fbmsg=fbmsg.replace("{nominee}", nomi);
                   //alert(fbmsg);
                   if(jQuery.cookie("email")){
					   var params = {};
						params['message'] = fbmsg;
						/*params['name'] = 'asf';
						params['description'] = 'asf';
						params['link'] = 'http://apps.facebook.com/summer-mourning/';
						params['picture'] = 'http://summer-mourning.zoocha.com/uploads';
						params['caption'] = 'Caption';*/
						  
						FB.api('/me/feed', 'post', params, function(response) {
						  if (!response || response.error) {
							alert('Error occured');
						  } else {
							//alert('Published to stream - you might want to delete it now!');
						  }
						});
					}
					
					if(jQuery.cookie("twitter_anywhere_identity")){
						jQuery.post(
						'<?php echo home_url();?>',
						{
						action: "post_twitter",
						name:nomi
						},
						function(res){
						}
						);
						/*jQuery.ajax({
							type: "POST",
							url: "http://api.twitter.com/1/statuses/update.json",
							data: "status="+fbmsg,
							datatype: "json"
						});*/
					}
               }
           });
      return false;
      });
    //-->
    </script>
</div>