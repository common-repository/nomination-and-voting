<?php
global $post, $wpdb;
$cat = get_post_meta($post->ID, 'vote_category',true); 
$nominees= get_post_meta($post->ID, 'voter_nomineeid',true);
$nominees_name= get_post_meta($post->ID, 'voter_nomineename',true);
//print_r($nominees_name); 
?>
Select Category for Voting
<?php
echo "<select name='cat' id='cate'>";
echo "<option value=''>--Select--</option>";
foreach($categories as $key => $value){
	if($cat){if($cat==$value->term_id)$select="selected='selected'";else $select="";}
	echo "<option value='".$value->term_id."' ".$select.">".$value->name."</option>";
}
echo "</select>";
?>
<br />
<div style="height:auto; width:auto;padding-top:10px;" id="mydiv">
<?php
if($cat){
	/*$i=0;
	foreach($nominees as $nomin){
		echo '<input type="hidden" name="voter_nomineename[]" value="'.$nominees_name[$i].'" ><input type="checkbox" name="voter_nomineeid[]" value="'.$nominees[$i].'" > '.$nominees_name[$i];
	}*/
	$data = $wpdb->get_results("select *,wn.id as id from ".$wpdb->prefix."nv_nominee wn inner join ".$wpdb->prefix."nv_nomination wnm on wn.id=wnm.nomineeid where category='".$cat."' group by nomineeid");
	$html="";
	//print_r($data);
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
	echo $html;
}else{
?>
Select category to show the nominee for the category.
<?php } ?>
</div>
<script>
jQuery("#cate").live("change",function(){
	var catid=jQuery("#cate").val();
	if(catid){
		jQuery.post(ajaxurl,{
		action : "wpnv_cat_nominee",
		catid : catid,
		postid : <?php echo $post->ID;?>
		},
		function(res){
			if(res==0)
				jQuery("#mydiv").text("NO Nominee found in this category.");
			else
				jQuery("#mydiv").html(res);
		});
	}
});

</script>
