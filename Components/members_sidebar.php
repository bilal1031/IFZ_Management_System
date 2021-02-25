<?php
    include "./Config/connection.php";
    $active = array("active","","");
    if(isset($_GET['mode'])){
        $get = $_GET['mode'];
        if($get == 'registration'){
            $active[1] = "active";
            $active[2] = "";
            $active[0] = "";
        }else if($get == 'payment'){
            $active[1] = "";
            $active[2] = "active";
            $active[0] = "";
        }else{
            $active[1] = "";
            $active[2] = "";
            $active[0] = "active";
        }
    }
        
echo '
<div class="d-flex flex-row mt-5 justify-content-between">
        <div class="col-3">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Menu
            </a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark '.$active[0].'" href="'.$_SERVER['PHP_SELF'].'">
            <i class="fa fa-user" style="font-size:24px;"></i>
            Members</a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark '.$active[1].'" href="'.$_SERVER['PHP_SELF'].'?mode=registration">
            <i class="fa fa-check-square" style="font-size:24px;"></i>
            Registration</a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark '.$active[2].'" href="'.$_SERVER['PHP_SELF'].'?mode=payment">
            <i class="fa fa-usd" aria-hidden="true"></i>
            Payment Management</a>
            </div>
        </div>
        <div class="col-9 mb-5">           
        ';
        if(isset($_GET['mode'])){
            if($_GET['mode'] == 'registration'){
                if(isset($_POST['submit'])){
                    register_member($_POST['member_name'],$_POST['number']);
                }
                echo '
                <div class="container col-8 p-3" style="background-color:orange">
                <h2 style="color:white;">Registration Form</h2></div>       
                <div class="container col-8 p-4" style="background-color:white;" >
                  <form action="'.$_SERVER['PHP_SELF'].'?mode=registration" method="post">
                    <div class="form-group">
                      <label for="membertname">Member Name:</label>
                      <input type="member_name" class="form-control" id="member_name" required placeholder="Enter member name" name="member_name">
                    </div>
                    <div class="form-group">
                      <label for="number">Contact Number:</label>
                      <input type="text" class="form-control" id="number" required placeholder="Enter number" name="number" >
                    </div>
                    <button type="submit" name="submit" class="btn btn-success col-2">Save</button>
                  </form>
                </div>
                <div style="height:50px"></div>
                ';
            }else if($_GET['mode'] == 'payment'){
                if(isset($_POST['psubmit'])){
                    add_fee($_POST['month'],$_POST['year'],$_POST['Fee']);
                }
                echo '
                <div class="container col-8 p-3" style="background-color:orange">
                <h2 style="color:white;">Set Fee For the month</h2></div>       
                <div class="container col-8 p-4" style="background-color:white;" >
                  <form action="'.$_SERVER['PHP_SELF'].'?mode=payment" method="post">
                    <div class="form-group">
                      <label for="membertname">Fee:</label>
                      <input type="number" class="form-control" id="fee" required placeholder="Enter Fee" name="Fee">
                    </div>
                    <div class="form-group">
                      <label for="month">Month:</label>
                      <input type="text" class="form-control" id="month" required placeholder="Enter month" name="month" value="'.date("F").'">
                    </div>
                    <div class="form-group">
                      <label for="year">Year:</label>
                      <input type="text" class="form-control" id="year" required placeholder="Enter year" name="year" value="'.date("Y").'">
                    </div>
                    <button type="submit" name="psubmit" class="btn btn-success col-2">Save</button>
                  </form>
                </div>
                <div style="height:50px"></div>
                ';
               
            }
        }else if(isset($_GET['member'])){
            if(isset($_POST['payment'])){
                set_payment(1,$_GET['member']);
            }
            $data = get_member_info($_GET['member']);
            $pay = get_cur_pay_info($_GET['member']);
            echo '
                  <div class="container p-2" style="background-color:orange;border-top-right-radius:5px;border-top-left-radius:5px">
                    <h2 style="color:white;">Member Information</h2></div>
                  <div class="container pl-3 pt-3" style="background-color:white;height:140px">
                    <div class="d-flex col-12 flex-row justify-content-between">
                        <h5>Name: '.$data['name'].'</h5>
                        <h5>Contact Number: '.$data['contact_number'].'</h5>
                    </div>
                    <div class="d-flex flex-column col-12">
                    <h5>Joining Date: '.$data['reg_date'].'</h5>
                    <div class="d-flex flex-row">
                        <h5>Current month fee status:</h5>';
                    if($pay){
                            echo '<button class=" btn btn-success ml-2" name="payment" disabled >Paid</button>';
                    }else{
                        echo '<form action="'.$_SERVER['PHP_SELF'].'?member='.$_GET['member'].'" method="post">
                                <input name="pay" type="hidden" value="1"/>
                                <button class=" btn btn-danger ml-2" name="payment">Not Paid</button>
                              </form>';
                    }

             echo'   </div>
                    </div>
                </div>
                 '; 
        }else{
            echo '   
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Members
            </a>';
            get_members();
            echo ' 
            </div>';
        }
        echo '
        
         </div>
        
</div>';
if(isset($_GET['member'])){
    $member = $_GET['member'];
    if(isset($_POST['set_paid'])){
        update_payment($_POST['p_id'],$_POST['f_id']);
    }
    echo '
        <div class="col-12 mb-5 b-2">                  
            <div class="col-12 p-2" style="background-color:orange;border-top-right-radius:5px;border-top-left-radius:5px">
            <h2 class="ml-2"style="color:white;">Past Fee Information</h2></div>

            <div class="col-12 p-2" style="background-color:white;">
            <p class="ml-3"style="font-size:16">The table gives all the past fee payment of the memeber:</p>            
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Year/Month</th>
                    <th>January</th>
                    <th>Feburary</th>
                    <th>March</th>
                    <th>April</th>
                    <th>May</th>
                    <th>June</th>
                    <th>July</th>
                    <th>August</th>
                    <th>September</th>
                    <th>October</th>
                    <th>November</th>
                    <th>December</th>
                </tr>
                </thead>
                <tbody>';
                  get_past_info($_GET['member']);

           echo' 

                </tbody>
            </table>
            <div style="height:10px"></div>
            </div>
            </div>  
            <div style="height:50px"></div>
        </div>
        
        ';
}
?>