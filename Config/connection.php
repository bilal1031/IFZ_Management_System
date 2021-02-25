<?php
    function get_member($search){
        include "serverconfig.php";
        $sql = "SELECT member.*,is_paid from member inner join payment on member.m_id = payment.m_id and name like '%".$search."%' and payment.f_id = (select max(f_id) from fee)";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '  
            <div class="container justify-content-center p-3 col-8">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Member';
            while($row = $result->fetch_assoc()) {
                echo '<a type="button" class="list-group-item list-group-item-action p-2" href="memberspage.php?member='.$row['m_id'].'">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div class="col-3 justify-content-between"><span>'.$row['name'].'</span></div>  
                    <div class="col-6 justify-content-between">
                
                        <form class="form-inline" action="'.$_SERVER['PHP_SELF'].'" method="post">
                            <span><b>Attendence of '.date('y-m-d').':</b></span> 
                            <input type="hidden" name="name"   value="'.$row['name'].'"/>
                            <input type="hidden" name="m_id"   value="'.$row['m_id'].'"/>';
                        if(true){
                            echo ' <button class=" btn btn-success ml-1" name="attendence">Save</button> ';
                        }else{
                            echo ' <button class=" btn btn-success ml-1" disbled name="attendence">Save</button>';
                        }
                           
                    echo'   
                          </form>
                     </div>';
                    echo '               
                    <div class="col-3 justify-content-between">
                    <span><b>Fee:</b></span> ';
                    if($row['is_paid']){
                        echo '<button class=" btn btn-success ml-1" disabled>Paid</button> ';
                    }else{
                        echo '<button class=" btn btn-danger ml-1" disabled>Not Paid</button> ';
                    }             
                                                
                echo '       </div>
                            </div>
                    </a>';
            }   
            echo '
            </div>
           
   </div>
   </div>
   <div style="height:50px"></div> ';
        }
    }
    function register_member($name,$number){
        include "serverconfig.php";
        $sql = 'INSERT INTO member(name,contact_number,reg_date) VALUES("'.$name.'",'.$number.',now())';
        if(mysqli_query($conn, $sql)){
            $sql = 'SELECT m_id from member WHERE name = "'.$name.'"';
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            set_payment(1,$row['m_id']);
            echo '<div class="alert alert-success container col-8 p-3" role="alert" style="bacground-color:orange">
                    Data Saved
                 </div>';   
         }else{
            echo '<div class="alert alert-danger" role="alert">
                    Failed to Save!
                    '."Error: " . $sql . "<br>" . mysqli_error($conn).'
                </div>';   
         }

    }

    function get_members(){
        include "serverconfig.php";
        $sql = "SELECT member.*,is_paid from member left JOIN payment on member.m_id = payment.m_id and f_id = (select max(f_id) from fee)";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<a type="button" class="list-group-item list-group-item-action p-2" href="'.$_SERVER['PHP_SELF'].'?member='.$row['m_id'].'">
                            <div class="d-flex flex-row justify-content-between align-items-center">
                                <div class="col-3 justify-content-between"><span>'.$row['name'].'</span></div>                 
                                <div class="col-3 justify-content-between">
                                <span><b>Fee:</b></span> ';
                                if($row['is_paid']){
                                    echo '<button class=" btn btn-success ml-1" disabled>Paid</button> ';
                                }else{
                                    echo '<button class=" btn btn-danger ml-1" disabled>Not Paid</button> ';
                                }             
                                                
                echo '       </div>
                            </div>
                      </a>';
            }   
        }
    }

    function set_payment($value,$mid){
        include "serverconfig.php";
        $sql = "SELECT max(f_id) as f_id from fee";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $fid = $row['f_id'];

    
        $sql = 'INSERT INTO payment(m_id,f_id,is_paid) VALUES('.$mid.','.$fid.','.$value.')';
        mysqli_query($conn, $sql);
            
               
        

    }
    function add_fee($month,$year,$fee){
        include "serverconfig.php";
        $payment = true;
        $sql = "SELECT max(f_id) as f_id from fee";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $count = $row['f_id'];
        $sql = 'select m_id from member where m_id != all (select payment.m_id from member inner join payment on f_id = '.$row['f_id'].' and is_paid = 1 group by m_id);';
        $result = mysqli_query($conn, $sql);
        $ids = array();
        if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()){
                array_push($ids,$row['m_id']);
            }              
            for($x = 0;$x < sizeof($ids);$x++){
                $sql = "insert into payment(m_id,f_id,is_paid) values (".$ids[$x].",(select max(f_id) from fee),0)";
                if(mysqli_query($conn, $sql)){
                    $payment = true;
                }  else{
                    $payment = false;
                }
            }
        }else{
            
        }
        if($payment){
            $sql = 'INSERT INTO fee(month,year,fee) VALUES("'.$month.'","'.$year.'",'.$fee.')';
            if(mysqli_query($conn, $sql)){
                echo '<div class="alert alert-success container col-8 p-3" role="alert" style="bacground-color:orange">
                            Data Saved
                    </div>';  
            }
        }

    }
    function get_member_info($id){
        include "serverconfig.php";
        $sql = "SELECT * from member where m_id = ".$id.";";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();       
        }
        return $row;
    }
    function get_cur_pay_info($id){
        include "serverconfig.php";
        $sql = "SELECT is_paid from payment where f_id = (select max(f_id) from fee) and m_id=".$id.";";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();       
        }else {
            return false;
        }
    
            return true;
        
        
    }
    function get_specific_members($type){
        include "serverconfig.php";
        $check;
        if($type == 'paid'){
            $check = 1;
        }else{
            $check = 0;
        }

        $sql = "SELECT member.*,is_paid from member left JOIN payment on member.m_id = payment.m_id and f_id = (select max(f_id) from fee) and is_paid = ".$check."";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '   
            <div class="container col-8 mt-5 mb-5">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Members
            </a>';
            while($row = $result->fetch_assoc()) {
                echo '<a type="button" class="list-group-item list-group-item-action p-2" href="memberspage.php?member='.$row['m_id'].'">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div class="col-3 justify-content-between"><span>'.$row['name'].'</span></div>                 
                    <div class="col-3 justify-content-between">
                    <span><b>Fee:</b></span> ';
                    if($row['is_paid']){
                        echo '<button class=" btn btn-success ml-1" disabled>Paid</button> ';
                    }else{
                        echo '<button class=" btn btn-danger ml-1" disabled>Not Paid</button> ';
                    }             
                                    
    echo '       </div>
                </div>
          </a>';
            }
            echo ' 
            </div>
            </div>
            <div style="height:50px"></div>
            ';
        }
    }
    function update_payment($pid,$fid){
        include "serverconfig.php";
        echo $sql = "UPDATE payment
                SET is_paid = true
                WHERE f_id = ".$fid." AND p_id = ".$pid."; "; 
        if(mysqli_query($conn, $sql)){
          
        }
    }
    function get_past_info($mid){
        include "serverconfig.php";
        $sql = "select MONTH(reg_date) as month from member where m_id = ".$mid.""; 
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $diff = $row['month'];
        $sql = "select fee.fee,month from fee inner join payment on fee.f_id = payment.f_id and m_id = ".$mid."";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {

            /*echo '<tr style="background-color:grey">
                        <th>Fee</th>';
                        for($i = 1;$i<$diff;$i++){
                            echo ' <td></td>';
                        }
            while($row = $result->fetch_assoc()) {
               echo '
                        <th>'.$row['fee'].'</th>
                   ';
            }   
     
            echo '</tr>
            <tr>';*/

            $sql = "select fee.year from fee inner join payment on fee.f_id = payment.f_id and m_id = ".$mid." group by year;";
            $result = mysqli_query($conn, $sql);
            $count = 0;
            $year = array();
            if (mysqli_num_rows($result) > 0) {

                while($row = $result->fetch_assoc()) {
                        array_push($year,$row['year']);
                } 

                for ($i=0; $i < sizeof($year); $i++) { 
                    echo '
                    <tr>
                     <td><b>'.$year[$count].'</b></td>';
                    
                 $sql = "select month,name,payment.is_paid,p_id,payment.m_id,fee.f_id,fee.fee from fee inner join payment on fee.f_id = payment.f_id inner join member on payment.m_id = member.m_id and member.m_id = ".$mid.";";
                 $result = mysqli_query($conn, $sql); 
                 if (mysqli_num_rows($result) > 0) {
                     
                     for($i = 1;$i<$diff;$i++){
                         echo '<td><button class=" btn btn-info ml-2" disabled></br>Null</br></br> </button></td>';
                     }
                     while($row = $result->fetch_assoc()) {

                             if($row['is_paid'] != null){
                                 if($row['is_paid']){
                                     echo '<td>
                                            <button class=" btn btn-success ml-2" disabled><b>'.$row['fee'].'</b> <br/><br/>Paid</button></td>';
                                 }else{

                                     echo '<td>
                                          
                                             <form action="'.$_SERVER['PHP_SELF'].'?member='.$row['m_id'].'" method="post">
                                             <input type="hidden" name= "f_id" value="'.$row['f_id'].'"/>
                                             <input type="hidden" name= "p_id" value="'.$row['p_id'].'"/>
                                             <button class=" btn btn-danger ml-2 " name="set_paid">'.$row['fee'].'
                                             <br/> Not<br/> Paid</button>
                                             </form>
                                         </td>';
                                 }
                             }else{
                                 echo '<td><button class=" btn btn-info ml-2" disabled>Null</button></td>';
                             }
                             if($row['month'] == '12'){
                                 $count++;
                                 echo '</tr><tr>
                                 <td><b>'.$year[$count].'</b></td>';
                                 
                             }
                             
     
                     } 
                    
                     
                 }
                
                // echo '</tr>';
             
                }
                
            }
        }
    }
?>
        