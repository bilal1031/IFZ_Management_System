<?php
            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $conn = new mysqli($servername, $username, $password);      
            $sql = "CREATE DATABASE IF NOT EXISTS gymdb";
            if ($conn->query($sql)) {
              $dbname = "gymdb";        
              $conn = new mysqli($servername, $username, $password, "gymdb");      
              $sql = "
                create table member (
                m_id int primary key auto_increment,
                  name varchar(200),
                  reg_date date,
                  contact_number varchar(200)
              );";
              $conn->query($sql);
              $sql = "
                      create table fee (
                        f_id int primary key auto_increment,
                          month varchar(200),
                          year varchar(200),
                          fee int
                      );";
                      $conn->query($sql);
              $sql ="
                      create table attendence(
                      a_id int primary key auto_increment,
                      m_id int,
                      date date,
                      has_attented tinyint,
                      foreign key (m_id) references member(m_id) on delete cascade
                      );";
                      $conn->query($sql);

              $sql ="        
                    create table payment(
                    p_id int primary key auto_increment,
                    m_id int,
                    f_id int,
                    is_paid tinyint,
                    date date,
                    foreign key (m_id) references member(m_id) on delete cascade,
                    foreign key (f_id) references fee(f_id) on delete cascade
                    );";
                    $conn->query($sql);
            } else {
              echo '<div class="alert alert-danger container col-8 p-3" role="alert" style="bacground-color:orange">
                              Database creation failed!
                    </div>';
            }

            
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "gymdb";        
            $conn = new mysqli($servername, $username, $password, $dbname);       
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);         
              $conn->close();

            }

?>