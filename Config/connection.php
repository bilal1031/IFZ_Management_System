<?php
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
        $sql = 'INSERT INTO payment(m_id,f_id,is_paid) VALUES('.$mid.','.$row['f_id'].','.$value.')';
        mysqli_query($conn, $sql);

    }
    function add_fee($month,$year,$fee){
        include "serverconfig.php";
        
        $sql = "SELECT max(f_id) as f_id from fee";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();

        $sql = 'select m_id from payment where f_id < '.$row['f_id'].' group by m_id';
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = $result->fetch_assoc()){
     
                $sql = "insert into payment(m_id,f_id,is_paid) values (".$row['m_id'].",(select max(f_id)-1 from fee),0)";
                if(mysqli_query($conn, $sql)){
                
                }   
            }
        }else{
            echo "not";
        }
        $sql = 'INSERT INTO fee(month,year,fee) VALUES("'.$month.'","'.$year.'",'.$fee.')';
        if(mysqli_query($conn, $sql)){
            echo '<div class="alert alert-success container col-8 p-3" role="alert" style="bacground-color:orange">
                        Data Saved
                  </div>';  
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
    function get_past_info($mid){
        include "serverconfig.php";
        $sql = "select MONTH(reg_date) as month from member where m_id = ".$mid.""; 
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $diff = $row['month'];
        $sql = "select fee.fee from fee inner join payment on fee.f_id = payment.f_id and m_id = ".$mid."";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<tr style="background-color:grey">
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
            <tr>';

            $sql = "select fee.year from fee inner join payment on fee.f_id = payment.f_id and m_id = ".$mid." group by year;";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

                while($row = $result->fetch_assoc()) {
                        echo '
                               <tr>
                                <td><b>'.$row['year'].'</b></td>';
                        {
                            $sql = "select name,payment.is_paid,p_id from fee inner join payment on fee.f_id = payment.f_id inner join member on payment.m_id = member.m_id and member.m_id = ".$mid.";";
                            $result = mysqli_query($conn, $sql); 
                            if (mysqli_num_rows($result) > 0) {
                                
                                for($i = 1;$i<$diff;$i++){
                                    echo '<td><button class=" btn btn-info ml-2" disabled>Null</button></td>';
                                }
                                while($row = $result->fetch_assoc()) {
                                        if($row['is_paid'] != null){
                                        if($row['is_paid']){
                                            echo '<td><button class=" btn btn-success ml-2" disabled>Fee Paid</button></td>';
                                        }else{
                                            echo '<td>
                                                    <form action="'.$_SERVER['PHP_SELF'].'?member='.$row['name'].'" method="post">
                                                    <input type="hidden" value="etc"/>
                                                    <button class=" btn btn-danger ml-2">Not Paid</button>
                                                    </form>
                                                </td>';
                                        }
                                    }else{
                                        echo 'td><button class=" btn btn-info ml-2" disabled>Null</button></td>';
                                    }
                                        
                
                                } 
                                
                            }
                        }
                        echo '</tr>';
                } 
                
            }
        }
    }
?>
        