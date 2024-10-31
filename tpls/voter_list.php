<table width="100%" border="1">
   <tr>
      <td>
         Votee Name
      </td>
      <td>
         Category
      </td>
      <td>
         Voter Email
      </td>
      <td>
         Voter Name
      </td>
   </tr>
   <?php
   //print_r($data); 
   if($data)
   foreach($data as $key => $value){
   	//$nom=$wpdb->get_results("select * from wp_nv_nomination wn inner join wp_nv_visitor wv on wn.visitorid=wv.id  where nomineeid= ". $value->id ."  ");
	//print_r($nom);  
   ?>
   <tr>
      <td>
         <?php echo $value->nominee_name;?>
      </td>
      <td>
        <?php echo get_term_by("id",$value->category,"VotingCategory")->name;?>
      </td>
      <td>
         <?php echo $value->email;?>
      </td>
      <td>
         <?php echo $value->name;?>
      </td>
   </tr>
   <?php  
   }
   ?>
</table>