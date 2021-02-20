<?php
    function navbar($active){
        $isactive = array("active","","");
        if($active == "index"){
            $isactive[0] = "active";
            $isactive[1] = "";
            $isactive[2] = "";
        }else if($active == "member"){
            $isactive[0] = "";
            $isactive[1] = "active";
            $isactive[2] = "";
        }else if($active == "about"){
            $isactive[0] = "";
            $isactive[1] = "";
            $isactive[2] = "active";
        }
        echo '  <nav class="navbar navbar-expand-sm bg-dark navbar-dark" >
                        <div class="container-fluid">
                            <div class="navbar-header flex-row">
                            <img style="margin-bottom:15px" src="./photos/dumbbell.png" width="40" height="40">                         
                                <a href="index.php" class="navbar-brand" >            
                                <h3 style="font-weight:bold;color:orange">IFZ GYM</h3>
                                </a>    
                                 </div>
                            <ul class="navbar-nav">
                                <li class="nav-item '.$isactive[0].'" >                                
                                 <a href="index.php"  class="nav-link"> 
                                    <button class="button">
                                    <i class="fa fa-home" style="font-size:24px;"></i>
                                      Home
                                    </button>
                                  </a>    
                                </li>
                                <li class="nav-item '.$isactive[1].'"><a href="memberspage.php"  class="nav-link" >
                                    <button class="button ">
                                        <i class="fa fa-user" style="font-size:24px;"></i>
                                        Membership
                                    </button>
                                </a></li>
                                <li class="nav-item '.$isactive[2].'"><a  href="about.php" class="nav-link"  >
                                    <button class="button ">
                                    <i class="fa fa-info-circle" style="font-size:24px;"></i>
                                        About Us
                                    </button></a></li>
                            </ul>
                        </div>
                </nav>';
                    }




?>