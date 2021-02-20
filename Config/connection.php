<?php
         function print_table($p_id,$name,$purchaseprice,$saleprice,$category,$admin){

                    echo '<tr>
                    <td>'.$name.'</td>
                    ';
                    if($admin){
                        echo'                                
                               <td>'.$purchaseprice.'</td>
                               <td>'.$saleprice.'</td>
                               <td>'.$category.'</td> ';
                    
                    }else{
                    echo '
                    <td>'.$category.'</td>
                    <td> 
                        <form class="form " action="'.$_SERVER["PHP_SELF"].'" method="post">
                        <input type="number" class="form-control" id="price" required " name="price" value="'.$saleprice.'">                        
                    </td>';
                    echo '
                         <td>                   
                            <form class="form-inline" action="'.$_SERVER["PHP_SELF"].'" method="post">
                                <input type="number" class="form-control" id="quantity" required placeholder="Enter Quantity" name="quantity">
                                <input type="hidden" name="p_id" value="'.$p_id.'"/>
                        </td>
                        <td>
                                <button class="btn btn-warning my-2 my-sm-0 col-15 ml-3 from-control" type="submit"  name="cart">Add to cart</button>                     
                                </form>
                        </td>
                        ';
                    }
                   
                   if($admin){
                   echo '
                    <td>
                    <div class="d-flex flex-row">
                        <form class="form-inline" action="product_form.php?editproduct='.$name.'" method="post">
                            <button class="btn btn-success my-2 my-sm-0 col-15 " type="submit"  name="add">Edit</button>
                        </form>
                        <form class="form-inline" action="'.$_SERVER["PHP_SELF"].'" method="post">
                             <input type="hidden" name="p_id" value="'.$p_id.'"/>
                             <input type="hidden" name="category" value="'.$category.'"/>
                             <button class="btn btn-danger my-2 my-sm-0 col-15 ml-3" type="submit"  name="delete">Delete</button>              
                        </form>
 
                    </div>
                    </td>';
                   }
                    echo '</tr>';

        }
        function print_invoice_table($c_id,$p_id,$name,$saleprice,$quantity,$button){
  
            echo '<tr>
            <td>'.$name.'</td>
            <td>'.$saleprice.'</td>
            <td>'.$quantity.'</td>
            <td>'.$saleprice*$quantity.'</td>';
            if($button == null){
                echo '<td>
                        <div class="d-flex flex-row">
                            <form class="form-inline" action="'.$_SERVER["PHP_SELF"].'" method="post">
                                <input type="hidden" name="p_id" value="'.$p_id.'"/>
                                <input type="hidden" name="c_id" value="'.$c_id.'"/>
                                <button class="btn btn-danger my-2 my-sm-0 col-15 ml-3" type="submit"  name="delete">Delete from cart</button>                
                            </form>
            
                        </div>
                        </td>';
            }
            echo '</tr>';
            return $saleprice*$quantity;
        }

        function delete_data($sql){
            include "serverconfig.php";
            if(!mysqli_query($conn, $sql)){
                echo "Error Occured";
            }else{
                echo '
                    <div class="d-flex flex-row justify-content-around pl-5" id="delete-alert">
                        <div class="alert alert-info w-50 mt-3 ml-5" role="alert">
                        Data Delete! 
                        </div>
                    </div>'; 
            }
        }
        function get_edit_data($name){
               
            include "serverconfig.php";

                $sql = "SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON name = '$name' AND c_id = category;";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = $result->fetch_assoc();
                    return $row;
                }else{
                    echo "Result 0";
                }
            
            

        }
        function data_dupclication_check($sql){

            include "serverconfig.php";
            //echo "Connected successfully";
            //echo $sql;
            $c_id = "";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                return 1;   
            }else{
                return 0;
            }
            
        }

        function save_data($sql){
            include "serverconfig.php";
            //echo "Connected successfully";
            //echo $sql;
             if(mysqli_query($conn, $sql)){
                echo '<div class="alert alert-success" role="alert">
                        Data Saved
                    </div>';   
             }else{
                echo '<div class="alert alert-danger" role="alert">
                        Failed to Save!
                        '."Error: " . $sql . "<br>" . mysqli_error($conn).'
                    </div>';   
             }

        }
         
        function get_cat_id($sql){
            include "serverconfig.php";
            //echo "Connected successfully";
            //echo $sql;
            $c_id = "";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                   while($row = $result->fetch_assoc()){
                       $c_id = $row['c_id'];
                   }
            }else {
                echo "0 results";
            }
            return $c_id;
               
        }
        function get_data($sql,$mode,$admin){
            include "serverconfig.php";
            //echo "Connected successfully";
            //echo $sql;
             $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
            if($mode == "main"){
                echo '
                <div class="container mt-5 mb-5">
                        <h2>Product Table</h2>     
                        <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                            <th>Name</th>';
                if($admin){
                    echo '<th>Purchase Price</th>
                          <th>Sale Price</th>
                          <th>Category</th>
                          <th></th>';
                }else{
                    echo '
                    <th>Category</th>
                    <th>Price</th>                
                    <th>Quantity</th>
                    <th></th>';
                }
                            
                echo '       
                        </tr>
                        </thead>
                        <tbody>';
                        while($row = $result->fetch_assoc()) {
                            print_table($row['p_id'],$row['name'],$row['purchaseprice'],$row['saleprice'],$row['c_name'],$admin)  ; 
                        }                             
                echo '   </tbody>
                        </table>
                    </div>
                    <div style="height:100px"></div>
                    ';
            }else if($mode == "cart"){
                $isrecipt = null;

                echo '<div class="container mt-5 mb-5">';
                if(isset($_GET['recipt'])){
                        $isrecipt = $_GET['recipt'];
                }
                if($isrecipt){
                    echo '<div class="container mt-5 mb-5">
                          <div class="d-flex flex-row justify-content-between">
                              <div class="d-flex flex-row">
                                <h1>AL.COM</h1>
                                <p style="font-size:12px;margin-left:20px">Whole<br>Sale<br>Dealer</p>
                              </div> 
                              <div class="d-flex justify-content-right">
                                <b>
                                    <span class="ml-5">Faisal Attari</span><br>
                                    <span >Contact: 0304-8688644</span><br>
                                    <span >Contact: 0313-8688644</span><br>
                                    <span>faisalhdd3@gmail.com</span>
                                </b>
                              </div> 

                          </div>     
                          <div class="containter mt-3">                 
                            <span class="mt-2"><b>Date: </b>'.date("Y/m/d").'</span><br>
                            <span class="mt-3"><b>M/s: </b>'.$_GET['client'].'</span>
                          </div> 
                          </div>';
                    echo '<h2>Recpit:</h2>';
                }else{
                    echo '<h2>Cart</h2>';
                }
                    echo'   
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                          
                            </tr>
                        </thead>
                        <tbody>';
                        $total = 0;  
                        while($row = $result->fetch_assoc()) {
                            $total += print_invoice_table($row['c_id'],$row['p_id'],$row['name'],$row['price'],$row['quantity'],isset($_GET['recipt']));
                        }                             
                echo '   </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-row justify-content-center mt-5">
                    <div class="alert alert-info" role="alert" style="width:28%;">
                    <form class="form-inline justify-content-center" action="'.$_SERVER["PHP_SELF"].'" method="post">
                             <b><span class="">Grand total = '.$total.'</span></b>
                    ';
                    if(!$isrecipt){
                         echo '<input type="text" name="client" placeholder="Enter buyer name" required class="form-control ml-5 mr-4 mt-3"/>
                               <button class="btn btn-success my-2 col-15 ml-2 mt-4" type="submit"  name="generate">Generate Recipt</button>     
                               <button class="btn btn-danger my-2 col-15 ml-3 mt-4" type="submit"  name="deleteinvoice">Delete cart</button>                           
                              ';
                    }
                 echo  '</form>
                            
                        </div>      
                    </div>  .
                    <div style="height:200px"></div>  
                    ';
                    if($isrecipt){
                        echo ' <div class="text-center p-3 fixed-bottom mb-5" >
                                <span style="color:black"><b>Address: </b>Shop# 3-4, 1st Floor Zaitoon Plaza Hall Road,Lahore.</span>
                            </div>  ';
                        $sql = 'DELETE FROM cart'; 
                        delete_data($sql);
                    }
            }
            
        } else {
            echo '
                <div class="d-flex flex-row justify-content-center mt-5">
                  <div class="alert alert-info" role="alert" style="width:40%;">
                        No Record Found!
                  </div>
                </div>';  
        }
        $conn->close();
    }

    function get_category($ismain,$selected){
        include "serverconfig.php";
        $sql = "SELECT c_name FROM category";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {
                if($ismain == true){
                    echo ' <a class="dropdown-item" href="index.php?category='.strtolower($row['c_name']).'">'.$row['c_name'].'</a>' ;            
                }
                if($ismain == false){
                    if($selected == $row['c_name']){
                        echo '<option selected value="'.$row['c_name'].'">'.$row['c_name'].'</option>';
                    }else {
                        echo '<option value="'.$row['c_name'].'">'.$row['c_name'].'</option>';
                    }
                    
                    
                }
             }   
        }

    }
?>