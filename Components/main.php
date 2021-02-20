   
     <?php 
        include "./Config/connection.php";
        if (isset($_POST['cart'])){
                    $sql = 'INSERT INTO cart(p_id,quantity,price) VALUES ('.$_POST['p_id'].','.$_POST['quantity'].','.$_POST['price'].')';
                    save_data($sql);
         }
    ?>
    <div class="search_container">
        <form class="form-inline" action="<?=$_SERVER['PHP_SELF'];?>" method="post">
            <input class="form-control mr-sm-2 col-7" type="search" id="tosearch" name="search" required placeholder="Search..." aria-label="Search">
            <button class="btn btn-default my-2 my-sm-0" style="background-color:orange;color:white"type="submit"  name="submit">Search</button>
        </form>
        <div class="dropdown" style="margin-top:10px;">
            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
              Advanced Options
            </button>
            <div class="dropdown-menu">
            <a class="dropdown-item" href="index.php?option=paid">Paid Members</a>
             <a class="dropdown-item" href="index.php?option=unpaid">Unpaid Members</a>
             <a class="dropdown-item" href="index.php?option=all">All Members</a>
            </div>
          </div>
    </div>
    
        <?php
            if(isset($_POST['submit'])){
                                
                $sql = 'SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON c_id = category AND (name LIKE "%'.$_POST['search'].'%" OR c_name LIKE "%'.$_POST['search'].'%")';
                get_data($sql,"main",false);  
            
                    
            }else if(isset($_GET['category'])){
                    $cat =  $_GET['category'];
                    if($cat == "allitems"){
                        $sql = 'SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON c_id = category ORDER BY category;';               
                    }else{
                        $sql = 'SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON c_id = category AND c_name = "'.$cat.'";';
                    
                    }
                get_data($sql,"main",false);

            }else if(isset($_POST['delete'])){
                    $sql = 'DELETE FROM product WHERE p_id = "'.$_POST['p_id'].'";';
                    delete_data($sql);
                    $cat =  $_POST['category'];
                    $sql = 'SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON c_id = category AND c_name = "'.$cat.'";';
                    get_data($sql);     
            }else if(isset($_POST['asubmit'])){
                $sql = 'SELECT p_id,name,purchaseprice,saleprice,c_name FROM product INNER JOIN category ON c_id = category AND (name LIKE "%'.$_POST['search'].'%" OR c_name LIKE "%'.$_POST['search'].'%")';
                get_data($sql,"main",true);  
            }
   ?>