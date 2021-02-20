<?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "al_com_dealer";
        
            $conn = new mysqli($servername, $username, $password, $dbname);       
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

?>