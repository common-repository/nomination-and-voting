<?php
/*
Plugin Name: nomination and voting
Plugin URI: #
Description: nomination and voting
Author: macmonir
Version: 1.1
Author URI: http://macmonir.net
*/


function wpnv_install(){
	global $wpdb;

	   $table_name1 = $wpdb->prefix . "nv_nomination";
	   $table_name2 = $wpdb->prefix . "nv_nominee";
	   $table_name3 = $wpdb->prefix . "nv_visitor";
	   $table_name4 = $wpdb->prefix . "nv_vote";


	   $sql1 = "CREATE TABLE IF NOT EXISTS ".$table_name1." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitorid` int(11) NOT NULL,
  `nomineeid` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1";

	$sql2 = "CREATE TABLE IF NOT EXISTS ".$table_name2." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nominee_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1";

$sql3 = "CREATE TABLE IF NOT EXISTS ".$table_name3." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1";

$sql4 = "CREATE TABLE IF NOT EXISTS ".$table_name4." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `visitorid` int(11) NOT NULL,
  `voterid` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1";

	   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	   dbDelta($sql1);
	   dbDelta($sql2);
	   dbDelta($sql3);
	   dbDelta($sql4);

}

function wpnv_custom_init()
{
  $labels = array(
    'name' => _x('Vote', 'post type general name'),
    'singular_name' => _x('Vote', 'post type singular name'),
    'add_new' => _x('Add New', 'vote'),
    'add_new_item' => __('Add New Voting Poll'),
    'edit_item' => __('Edit Voting Poll'),
    'new_item' => __('New Voting Poll'),
    'all_items' => __('All Voting Polls'),
    'view_item' => __('View Voting Poll'),
    'search_items' => __('Search Voting Poll'),
    'not_found' =>  __('No Voting Poll found'),
    'not_found_in_trash' => __('No Voting Poll found in Trash'),
    'parent_item_colon' => '',
    'menu_name' => 'Vote'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor')
  );
  register_post_type('vote',$args);


  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'VotingCategories', 'taxonomy general name' ),
    'singular_name' => _x( 'VotingCategory', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search VotingCategories' ),
    'all_items' => __( 'All VotingCategories' ),
    'parent_item' => __( 'Parent VotingCategory' ),
    'parent_item_colon' => __( 'Parent VotingCategory:' ),
    'edit_item' => __( 'Edit VotingCategory' ),
    'update_item' => __( 'Update VotingCategory' ),
    'add_new_item' => __( 'Add New VotingCategory' ),
    'new_item_name' => __( 'New VotingCategory Name' ),
    'menu_name' => __( 'VotingCategory' ),
  );

  register_taxonomy('VotingCategory',array('vote'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'VotingCategory' ),
  ));
}


function nomination_menu(){
     //add_menu_page( "Voting", "Voting and Nomination", "administrator", "vote_nomination", "wp_nv_settings" );
     add_submenu_page('edit.php?post_type=vote', 'Voting and Nomination Settings', 'Settings', 'administrator', 'vote_nomination_settings', 'wp_nv_settings');
     add_submenu_page('edit.php?post_type=vote', 'All Nominees', 'All Nominees', 'administrator', 'all_nominee', 'wp_nv_nominee_list');
     add_submenu_page('edit.php?post_type=vote', 'All Voters', 'All Voters', 'administrator', 'all_voter', 'wp_nv_voter_list');



}

function wpnv_add_custom_box(){
	add_meta_box( 'select-nominees', __( 'Nominee Selection', 'wpnv' ), 'wpnv_nominee_select', 'vote', 'normal','core' );
	//remove metabox
	 remove_meta_box( 'VotingCategorydiv', 'vote', 'side' );
}

function wpnv_nominee_select(){
	global $wpdb;
$categories=$wpdb->get_results("SELECT t. * , tt. *
FROM ".$wpdb->prefix."terms AS t
INNER JOIN ".$wpdb->prefix."term_taxonomy AS tt ON t.term_id = tt.term_id
WHERE tt.taxonomy
IN (
'VotingCategory'
)

ORDER BY t.term_id ASC");
include_once("tpls/metabox_options.php");
}

function wp_nv_nominee_list(){
    global $wpdb;
    $where="";
    $data=$wpdb->get_results("select * from ".$wpdb->prefix."nv_nominee ". $where);
    include("tpls/nominee_list.php");
}
function wp_nv_voter_list(){

	global $wpdb;
    $where="";
    $data=$wpdb->get_results("select * from ".$wpdb->prefix."nv_visitor wvs inner join ".$wpdb->prefix."nv_vote wv on wvs.id=wv.visitorid inner join ".$wpdb->prefix."nv_nominee wn on wn.id=wv.voterid ". $where);
    include("tpls/voter_list.php");
}

function wp_nv_settings(){

      include("tpls/settings.php");
}

function wp_nv_plugin_mode(){
    update_option('_wp_nv_mode', $_POST['voting']);
    update_option('_wp_nv_fb_twiter_text', $_POST['fb_twiter_text']);
	update_option('_wp_nv_fb_twiter_text1', $_POST['fb_twiter_text1']);
	update_option('_wp_nv_fb_twiter_text2', $_POST['fb_twiter_text2']);
    update_option('_wp_nv_fb_twiter_text3', $_POST['fb_twiter_text3']);
	update_option('_wp_nv_fb_appid', $_POST['fb_appid']);
    die("saved");
}

function wp_nomination_frontend(){

    //$concat= get_option("permalink_structure")?"?":"&";
	$mode=get_option('_wp_nv_mode');
	if($mode=="nomination")
    	include("tpls/asking_nomination.php");
	/*else
		include("tpls/asking_voting.php");*/
}

function wp_nomination_submit(){
    if($_REQUEST['action']=="nomination_submit") {
        global $wpdb;
		$id=0;
		$data = $wpdb->get_results("select id from ".$wpdb->prefix."nv_visitor where email='".$_POST['c_email']."'");
		foreach($data as $val){
			$id=$val->id;
		}
		if($id==0){
			$wpdb->insert($wpdb->prefix.'nv_visitor',array('email' =>$_POST['c_email'] ,'name' => $_POST['c_name']),array('%s','%s'));
			$id=$wpdb->insert_id;
		}
		//$wpdb->last_query;
		$data1 = $wpdb->get_results("select id from ".$wpdb->prefix."nv_nominee where nominee_name='".$_POST['nominee']."'");
		foreach($data1 as $val){
			$nid=$val->id;
		}
		if($nid==0){
			//insert into nominee
		$wpdb->insert(
            $wpdb->prefix.'nv_nominee',
            array( 'nominee_name' => $_POST['nominee']), array('%s'));
			$nid=$wpdb->insert_id;
		}


		//insert into nomination
		$wpdb->insert(
            $wpdb->prefix.'nv_nomination',
            array(
                'visitorid' => $id,
                'nomineeid' => $nid ,
                'category' => $_POST['cat']

            ),
            array(
                '%d',
                '%d',
                '%d'
            )
        );
        die("submitted");
    }
}

function wpnv_cat_nominee(){
	global $wpdb;
	global $post;
	$nominees= get_post_meta($_POST['postid'], 'voter_nomineeid',true);
	//print_r($nominees); die();
	$data = $wpdb->get_results("select *,wn.id as id from ".$wpdb->prefix."nv_nominee wn inner join ".$wpdb->prefix."nv_nomination wnm on wn.id=wnm.nomineeid where category='".$_POST['catid']."' group by nomineeid");
	$html="";
	foreach($data as $val){
		$flag=0;
		for($j=0;$j<count($nominees);$j++){
			if($val->id==$nominees[$j]){
				$flag=1;break;
			}
		}
		if($flag==1)$select="checked='checked'";else $select="";
		$html.='<input type="hidden" name="voter_nomineename[]" value="'.$val->nominee_name.'" ><input type="checkbox" name="voter_nomineeid[]" value="'.$val->id.'" '.$select.'> '.$val->nominee_name;
	}
	//echo $html;
	die($html);
}
function wpnv_save_indvidual__voting_options($post_id ){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( !current_user_can( 'edit_post', $post_id ) ) return;

	update_post_meta($post_id,'vote_category',$_POST['cat']);
	update_post_meta($post_id,'voter_nomineeid',$_POST['voter_nomineeid']);
	update_post_meta($post_id,'voter_nomineename',$_POST['voter_nomineename']);
}

function wpnv_voting_panel($param){
    $mode=get_option('_wp_nv_mode');
    if($mode=="voting"){
        global $wpdb;
        global $post;
        $args=array('post_type' => 'vote','post_status' => 'publish');
        $posts_array=get_posts($args);
        $vcat=array();
        foreach($posts_array as $key => $value){
                $cat=0;
                $nominees  =array();
                $nominees_name =array();
                $cat = get_post_meta($value->ID, 'vote_category',true);
                $nominees= get_post_meta($value->ID, 'voter_nomineeid',true);
                $nominees_name= get_post_meta($value->ID, 'voter_nomineename',true);


                if($cat){

                     $catname=$data = $wpdb->get_results("select name from ".$wpdb->prefix."terms  where term_id='".$cat."' ");
                      $vcat[$cat]=$catname[0]->name;
                      $pid[$cat]=$value->ID;
                }

                //if($nominees)

        }
        include_once("tpls/asking_voting.php");
        //print_r($posts_array);
        return;
    }

}

function nominee_for_cat(){
    if($_POST['action']=="nominee_for_cat"){
        //get the nominees selected by admin for the category $_post['catid']
        $nominees= get_post_meta($_POST['postid'], 'voter_nomineeid',true);
        //$nominees_name= get_post_meta($_POST['postid'], 'voter_nomineename',true);
        $html="";
        global $wpdb;

        /*print_r($nominees);
        print_r($nominees_name);die();*/
        $data = $wpdb->get_results("select *,wn.id as id from ".$wpdb->prefix."nv_nominee wn inner join ".$wpdb->prefix."nv_nomination wnm on wn.id=wnm.nomineeid where category='".$_POST['catid']."' group by nomineeid");

        if(count($data))
        foreach($data as $val){
            //$data = $wpdb->get_results("select nominee_name  from ".$wpdb->prefix."nv_nominee  where id='".$nominees[$i]."' ");
            $html.= '<option value="'.$val->id.'">'.$val->nominee_name.'</option>';
        }
        die($html);
    }
}

function wp_vote_submit(){
    if($_REQUEST['action']=="voting_submit") {
        global $wpdb;
		$id=0;
		$data = $wpdb->get_results("select id from ".$wpdb->prefix."nv_visitor where email='".$_POST['c_email']."'");
		foreach($data as $val){
			$id=$val->id;
		}
		if($id==0){
			$wpdb->insert($wpdb->prefix.'nv_visitor',array('email' =>$_POST['c_email'] ,'name' => $_POST['c_name']),array('%s','%s'));
			$id=$wpdb->insert_id;
		}
		//$wpdb->last_query;


		//echo $id;
        $dataf = $wpdb->get_results("select id from ".$wpdb->prefix."nv_vote where visitorid='".$id."' and category='".$_POST['cat']."' and (social='facebook' or social='twitter')");
        $count=0;
        if($dataf){
            foreach($dataf as $val){
                $count++;
                $sc=$val->social;
            }
        }

		//echo $count;
		//insert into nomination
        if($count<2){
            if($count==0){
                if($_POST['fb']){
                    if($_COOKIE['email']){
                        $social=$_COOKIE['email'];
		                $wpdb->insert(
                            $wpdb->prefix.'nv_vote',
                            array(
                                'visitorid' => $id,
                                'voterid' => $_POST['nominee'],
                                'category' => $_POST['cat'],
                                'social' => "facebook"

                            ),
                            array(
                                '%d',
                                '%d',
                                '%d',
                                '%s'
                            )
                        );
                    }
                }
                if($_POST['tw']){
                    if($_COOKIE['twitter_anywhere_identity']){
                    $social=$_COOKIE['twitter_name'];
                    $wpdb->insert(
                        $wpdb->prefix.'nv_vote',
                        array(
                            'visitorid' => $id,
                            'voterid' => $_POST['nominee'] ,
                            'category' => $_POST['cat'],
                            'social' => "twitter"

                        ),
                        array(
                            '%d',
                            '%d',
                            '%d',
                            '%s'
                        )
                    );
                }
                }
                die(" vote submitted");
            }
            if($count==1){
                if($sc=="facebook"){
                    if($_POST['tw']){
                       if($_COOKIE['twitter_anywhere_identity']){
                           $wpdb->insert(
                                $wpdb->prefix.'nv_vote',
                                array(
                                    'visitorid' => $id,
                                    'voterid' => $_POST['nominee'] ,
                                    'category' => $_POST['cat'],
                                    'social' => "twitter"

                                ),
                                array(
                                    '%d',
                                    '%d',
                                    '%d',
                                    '%s'
                                )
                            );
                       }
                    }
                    if($_POST['fb'])die("you already voted once using facebook account");
                }else{
                    if($_POST['fb']){
                        if($_COOKIE['email']){
                    $wpdb->insert(
                        $wpdb->prefix.'nv_vote',
                        array(
                            'visitorid' => $id,
                            'voterid' => $_POST['nominee'],
                            'category' => $_POST['cat'],
                            'social' => "facebook"

                        ),
                        array(
                            '%d',
                            '%d',
                            '%d',
                            '%s'
                        )
                    );
                    }
                    }
                     if($_POST['tw'])die("you already voted once using twitter account");
                }
            }
        }else{
             die("you already voted once");
        }
    }
}

function wp_post_twitter(){
	if($_POST['action']=="post_twitter"){
		/* Start session and load library. */
session_start();
include_once('twitteroauth/twitteroauth.php');
//require_once('config.php');

	//postToTwitter(CONSUMER_KEY, CONSUMER_SECRET, oauth_token, oauth_token_secret); petalert application
	postToTwitter("47CcqrU8EyPgbcZD9pmfZg","VYhl4pCsHrokZ4fiGctUnDErXL9JK6wFaOrMTn4s","125561843-zdGAagUe0bEka178R1jVYXXhC6FLbeLPYM8jZlTf","l5SUvacxP6nXqvec1AA27Gg9bu9wTdLW9PDojKUMes");
	}
}
/* Build TwitterOAuth object with client credentials. */
function postToTwitter($CONSUMER_KEY, $CONSUMER_SECRET, $oauth_token, $oauth_token_secret){
    $msg=get_option('_wp_nv_fb_twiter_text1');

	$msg=str_replace("{nominee}",$_POST['name'],$msg);
    $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
    //$content = $connection->get('account/verify_credentials');
    //var_dump($content->response);
    //print_r($content);
    $connection->post('statuses/update', array('status' => $msg));
	}




wp_enqueue_script("jquery");
wp_enqueue_script('jquery-form');
wp_enqueue_script('jquery-cookie',plugins_url("/nomination-and-voting/js/jquery.cookie.js"));
if(is_admin()){
    add_action("admin_menu","nomination_menu");
	add_action("init","wpnv_custom_init");
	add_action("wp_ajax_mode","wp_nv_plugin_mode");
	add_action("wp_ajax_wpnv_cat_nominee","wpnv_cat_nominee");
	add_action( 'save_post', 'wpnv_save_indvidual__voting_options' );
	//metabox
    //add_action( 'add_meta_boxes', 'wpnv_add_custom_box' );

}
add_action("wp_head","wp_nomination_frontend");
add_action("init","wp_nomination_submit");
add_action("init","nominee_for_cat");
add_action("init","wp_vote_submit");
//add_action("init","wp_post_twitter");

//voting panel
add_shortcode("wp_voting", "wpnv_voting_panel");
register_activation_hook(__FILE__,'wpnv_install');

?>