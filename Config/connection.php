<?php

    include "serverconfig.php";

    function get_attendence($mid){
        include "serverconfig.php";
        $sql = "select 1 as verified from attendence where m_id= ".$mid." and date = '".date('y-m-d')."'";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        if(isset($row)){
            if($row['verified']){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    function save_att($mid){
        include "serverconfig.php";
        $sql = "insert into attendence(m_id,date,has_attented) values(".$mid.",'".date("y-m-d")."',1)";
        if(mysqli_query($conn, $sql)){
        
        }
    }
    function get_member($search){
        include "serverconfig.php";
        $sql = "select member.* from member where name like '%".$search."%' or m_id = '".$search."';";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '  
            <div class="container justify-content-center p-3 col-8">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Member';
            while($row = $result->fetch_assoc()) {
               $attendence = get_attendence($row['m_id']);
               
                echo '<a type="button" class="list-group-item list-group-item-action p-2" href="memberspage.php?member='.$row['m_id'].'">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div class="col-3 justify-content-between"><span>'.$row['name'].'</span></div>  
                    <div class="col-6 justify-content-between">
                
                        <form class="form-inline" action="'.$_SERVER['PHP_SELF'].'" method="post">
                            <span><b>Attendence of '.date('y-m-d').':</b></span> 
                            <input type="hidden" name="name"   value="'.$row['name'].'"/>
                            <input type="hidden" name="m_id"   value="'.$row['m_id'].'"/>';
                        if($attendence){
                            echo ' <button class=" btn btn-success ml-1" disabled name="attendence">Saved</button>';
                        }else{
                            echo ' <button class=" btn btn-success ml-1" name="attendence">Save</button> ';
                           
                        }
                           
                    echo'   
                          </form>
                     </div>';
                    echo '               
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
            $mid = $row['m_id'];

            $sql = 'SELECT max(f_id) as f_id from fee';
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();
            $fid = $row['f_id'];
            $sql = 'INSERT INTO payment(m_id,f_id,is_paid,date) VALUES('.$mid.','.$fid.',1,"'.date('y-m-d').'")';
            if(mysqli_query($conn, $sql)){
                echo "payment saved";
            }
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
                                <div class="col-3 justify-content-between"><span><b>'.$row['m_id'].':</b></span>
                                <span class="m-2">'.$row['name'].'</span></div>                 
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

        $sql = "SELECT 1 as verified from payment where m_id = ".$mid." and f_id = ".$fid."";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        if(!isset($row)){
            $sql = 'select date as date from payment where m_id = '.$mid.' and f_id = '.($fid-1).';';
            $result = mysqli_query($conn, $sql);   
            $row = $result->fetch_assoc();      
            if(isset($row)){   
               $date = $row['date'];
               $timestamp = strtotime($date);
               $incre = date("d", $timestamp);
               $nextdate = date('Y-m-d', strtotime($date."+".$incre." days"));
               $sql = 'INSERT INTO payment(m_id,f_id,is_paid,date) VALUES('.$mid.','.$fid.','.$value.',"'.$nextdate.'")';
               if(mysqli_query($conn, $sql)){
                echo "payment done";
               }
           
            }
        }       

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
                $sql = 'select date as date from payment where m_id = '.$ids[$x].' and f_id = '.($count-1).';';
                $result = mysqli_query($conn, $sql);   
                $row = $result->fetch_assoc();      
                if(isset($row)){   
                   $date = $row['date'];
                   $timestamp = strtotime($date);
                   $incre = date("d", $timestamp);
                   $nextdate = date('Y-m-d', strtotime($date."+".$incre." days"));
                   $sql = 'INSERT INTO payment(m_id,f_id,is_paid,date) VALUES('.$ids[$x].','.$count.',0,"'.$nextdate.'")';               
                   if(mysqli_query($conn, $sql)){
                    $payment = true;
                    }  else{
                        $payment = false;
                    }
            
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
        $sql = 'select date as date from payment where m_id = '.$id.' and f_id =  (select max(f_id)-1 from fee);';
        $result = mysqli_query($conn, $sql);   
        $row = $result->fetch_assoc();     
        $nextdate = array() ;
        if(isset($row)){   
                   $date = $row['date'];
                   $timestamp = strtotime($date);
                   $incre = date("d", $timestamp);
                   array_push($nextdate,date('Y-m-d', strtotime($date."+30 days")));          
        }else{
                   array_push($nextdate,date('Y-m-d', strtotime(date('Y-m-d')."+30 days")));     
        }
        $sql = "SELECT is_paid from payment where f_id = (select max(f_id) from fee) and m_id=".$id.";";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = $result->fetch_assoc();    
            array_push($nextdate,1);    
                             
        }else {   
            array_push($nextdate,0); 
                      
        }
        return $nextdate; 
        
        
    }
    function get_specific_members($type){
        include "serverconfig.php";
        $ispaid;
        $sql = "";
        if($type == 'paid'){
            $sql = 'select member.*,is_paid from member inner join payment on member.m_id = payment.m_id and payment.is_paid = 1
                    and payment.f_id = (select max(f_id) from fee);';
                   
        }else{
            $sql = "select member.name,m_id from member where m_id != all (select member.m_id from member inner join payment on member.m_id = payment.m_id and payment.is_paid = 1 and payment.f_id = (select max(f_id) from fee))";    
        }

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '   
            <div class="container col-8 mt-5 mb-5">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Members
            </a>';
            while($row = $result->fetch_assoc()) {
                $sqldate = 'select date as date from payment where m_id = '.$row['m_id'].' and f_id =  (select max(f_id)-1 from fee);';
                $resultdate = mysqli_query($conn, $sqldate);   
                $rowdate = $resultdate->fetch_assoc();     
                $nextdate ;
                if(isset($rowdate)){   
                           $date = $rowdate['date'];
                           $timestamp = strtotime($date);
                           $incre = date("d", $timestamp);
                           $nextdate = date('Y-m-d', strtotime($date."+30 days"));             
                 }else{
                           $nextdate = date('Y-m-d', strtotime(date('Y-m-d')."+30 days"));    
                 }
                echo '<a type="button" class="list-group-item list-group-item-action p-2" href="memberspage.php?member='.$row['m_id'].'">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <div class="col-3 justify-content-between"><span>'.$row['name'].'</span></div>                 
                    <div class="col-5 justify-content-between">
                    <span><b>Fee of <span>'.$nextdate.'</span>:</b></span> ';
                    if($type == 'paid'){                       
                       $ispaid = $row['is_paid'];
                    }else{
                       $ispaid = 0;
                    }
                    
                    if($ispaid){
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
                                 if(isset($year[$count])){
                                    echo '</tr><tr>
                                    <td><b>'.$year[$count].'</b></td>';
                                 }
                                
                                 
                             }
                             
     
                     } 
                    
                     
                 }
                
                // echo '</tr>';
             
                }
                
            }
        }
    }
    function  delete_member($mid){
        include "serverconfig.php";
        $sql = "delete from member where m_id = ".$mid.""; 
        if(mysqli_query($conn, $sql)){
            header("Location:memberspage.php");
            exit();
        }
    }
    function tsave_att(){
        include "serverconfig.php";
        $sql = "select m_id from member where m_id != all (select m_id from attendence where has_attented = 1 and date = '".date("y-m-d")."');"; 
        $result = mysqli_query($conn, $sql);
        $mid = array("");
        $i = 0;
        while($row = $result->fetch_assoc()){
            {
                $sql = "select 1 as verified from attendence where m_id = ".$row['m_id']." and date = '".date("y-m-d")."';"; 
                $result1 = mysqli_query($conn, $sql);
                $row1 = $result1->fetch_assoc();
            }
            if(!isset($row1)){     
                $mid[$i] = $row['m_id'];     
                $sql = "insert into attendence(m_id,date,has_attented) values (".$row['m_id'].",'".date("y-m-d")."',0)"; 
                if(mysqli_query($conn, $sql)){
                
                }
            }else{
              
            }
            
           
        }
        

    }
    function get_att($mid){
        include "serverconfig.php";
        $sql = "select count(has_attented) as attented from attendence where date between '".date("y-m-1")."' and '".date("y-m-30")."' and m_id = ".$mid." and has_attented = 1;"; 
        $result = mysqli_query($conn, $sql);
        $data = array();
        $row = $result->fetch_assoc();
        if(isset($row)){
            $acount = $row['attented'];
            if($acount == 0){
               array_push($data,100);
               array_push($data,0);
               array_push($data,0);
               return $data;
            }
            $sql = "select count(has_attented) as notattented from attendence where date between '".date("y-m-1")."' and '".date("y-m-30")."' and m_id = ".$mid." and has_attented = 0;"; 
            $result = mysqli_query($conn, $sql);
            $row = $result->fetch_assoc();        
            if(isset($row)){
               $ascount = $row['notattented']+$acount;
               if($ascount == 0){
                array_push($data,100);
                array_push($data,0);
                array_push($data,0);
                return $data;
               }
               array_push($data,number_format((float) ($acount/$ascount)*100));
               array_push($data, $row['notattented']);
               array_push($data,$acount);
            }
        }else{
               array_push($data,100);
               array_push($data,0);
               array_push($data,0);
        }
        
        return $data;    
    }
?>
        