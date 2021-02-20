<?php
echo '
<div class="d-flex flex-row mt-5 justify-content-between">
        <div class="col-3">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Menu
            </a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark p-3 active">
            <i class="fa fa-user" style="font-size:24px;"></i>
            Members
            </a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark">Registration</a>
            <a type="button" class="list-group-item list-group-item-action list-group-item-dark">Payment Management</a>
            </div>
        </div>

        <div class="col-9">
            <div class="list-group">
            <a type="button" class="list-group-item list-group-item-action disabled active" style="background-color:orange; border:none;font-weight:bold">
            Members
            </a>
            <a type="button" class="list-group-item list-group-item-action p-2">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <span>Ali Asad</span>
                    <div class="col-3 justify-content-between">
                    <span><b>Fee:</b></span>                 
                    <button class=" btn btn-danger ml-1" disabled>Not Paid</button>                 
                    </div>
                </div>
            </a>
            <a type="button" class="list-group-item list-group-item-action p-2">
                <div class="d-flex flex-row justify-content-between align-items-center">
                    <span>Muhammad Bilal</span>
                    <div class="col-3 justify-content-between">
                    <span><b>Fee:</b></span>                 
                    <button class=" btn btn-success ml-1" disabled>Paid</button>                 
                    </div>
                </div>
            </a>
            </div>
         </div>

</div>

';
?>