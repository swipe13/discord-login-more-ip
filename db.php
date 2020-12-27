<?php

    $con = mysqli_connect("ip","user","password","Database");

    //Check connection

    if (mysqli_connect_errno()){

        echo "Failed to connect to MySQL: " . mysqli_connect_error();

    }

?>