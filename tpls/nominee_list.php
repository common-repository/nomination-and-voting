<table width="100%" border="1">
   <tr>
      <td>
         Nominee Name
      </td>
      <td>
         Category
      </td>
      <td>
         Visitor Email
      </td>
      <td>
         Visitor Name
      </td>
   </tr>
   <?php
   //print_r($data); 
   if($data)
   foreach($data as $key => $value){
   	$nom=$wpdb->get_results("select * from wp_nv_nomination wn inner join wp_nv_visitor wv on wn.visitorid=wv.id  where nomineeid= ". $value->id ."  ");
	//print_r($nom);  
   ?>
   <tr>
      <td>
         <?php echo $value->nominee_name;?>
      </td>
      <td>
        <?php foreach($nom as $val){echo get_term_by("id",$val->category,"VotingCategory")->name.",";}?>
      </td>
      <td>
         <?php foreach($nom as $val){echo $val->email.",";}?>
      </td>
      <td>
         <?php foreach($nom as $val){echo $val->name.",";}?>
      </td>
   </tr>
   <?php  
   }
   ?>
</table>