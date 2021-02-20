<?php

      include "./Config/connection.php";

      if(isset($_POST['delete'])){
        $sql = 'DELETE FROM cart WHERE p_id = "'.$_POST['p_id'].'" and c_id = "'.$_POST['c_id'].'"';                                 
        delete_data($sql);
      }else if (isset($_POST['deleteinvoice'])) {
        $sql = 'DELETE FROM cart';                                 
        delete_data($sql);
      }else if(isset($_POST['generate'])){
        header("Location:cart.php?recipt=true&client=".$_POST['client']."");
        exit();
      }
      $sql = "SELECT cart.c_id,product.p_id,name,cart.price,quantity from product INNER JOIN cart on product.p_id = cart.p_id;" ;                               
      get_data($sql,"cart",false); 
   

?>