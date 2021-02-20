<?php     
            function add_product($mode,$p_id){
              $cat_id =  get_cat_id('SELECT c_id FROM category WHERE c_name = "'.$_POST['category'].'"');
              $dup_sql = 'SELECT 1 AS verified FROM product WHERE name = "'.$_POST['product_name'].'" AND category = "'.$cat_id.'"';
              if($mode == 0){ 
                  if(data_dupclication_check($dup_sql)){                           
                      echo '<div class="alert alert-warning" role="alert">
                                Same Data is already stored in the Database.
                                Add new Data!
                            </div>';    
                    }else{
                    
                        $sql = 'INSERT INTO product(name,purchaseprice,saleprice,category) VALUES("'.ucwords($_POST['product_name']).'","'.$_POST['purchaseprice'].'","'.$_POST['saleprice'].'","'.$cat_id.'");';
                        save_data($sql);
                          
                    }
              }else{
                    $sql = 'UPDATE product SET name = "'.ucwords($_POST['product_name']).'" ,purchaseprice = "'.$_POST['purchaseprice'].'" ,saleprice = "'.$_POST['saleprice'].'" ,category = "'.$cat_id.'" WHERE p_id = '.$p_id.'';
                    save_data($sql);
              }
            }
            include "./Config/connection.php";
            $data;
            $data["name"] = "";
            $data["c_name"] = "";
            $data["purchaseprice"] = "";
            $data["saleprice"] = "";
            $link = $_SERVER["PHP_SELF"];
            if(isset($_POST['submit'])){
              if(isset($_GET['editproduct'])){
                add_product(1,$_GET['p_id']);


              }else{
                add_product(0,null);
                
              }
              
            }else if(isset($_GET['editproduct'])){
                $data = get_edit_data($_GET['editproduct']);
                $link = "product_form.php?editproduct=".$_GET['editproduct']."&p_id=".$data['p_id']."";
                if($data == null){
                  $data["name"] = "";
                  $data["purchaseprice"] = "";
                  $data["saleprice"] = "";
                  $data["c_name"] = "";
                  $link = $_SERVER["PHP_SELF"];
                }
            }
?>

<div style="height:30px"></div>
<div class="container col-5">
  <h2>Add Product</h2>
  <form action='<?php echo $link?>' method="post">
    <div class="form-group">
      <label for="productname">Product Name:</label>
      <input type="product_name" class="form-control" id="product_name" required placeholder="Enter product name" name="product_name" value = "<?php echo ucwords($data['name'])?>">
    </div>
    <div class="form-group">
      <label for="price">Purchase price:</label>
      <input type="text" class="form-control" id="purchaseprice" required placeholder="Enter price" name="purchaseprice" value= "<?php echo $data['purchaseprice']?>">
    </div>
    <div class="form-group">
      <label for="price">Sale price:</label>
      <input type="text" class="form-control" id="saleprice" required placeholder="Enter price" name="saleprice" value= "<?php echo $data['saleprice']?>">
    </div>
    <div class="form-group">
      <label for="category">Category:</label><br>
      <select name="category" class="form-control form-select form-select-lg mb-3" aria-label=".form-select-lg example">
        <option selected>Open this select category</option>
        <?php  
              get_category(false,$data["c_name"]); 
               
        ?>  
      </select>

    </div>
    <button type="submit" name="submit" class="btn btn-success col-2">Save</button>
  </form>
  <div style="height:100px"></div>
</div>
