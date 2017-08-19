<?php
    // Create connection
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if(mysqli_connect_errno()){
        // Connection failed
        echo '<tr>Failed to connect to MySql, error no: '. mysqli_connect_errno() . '</tr>';
    }


 ?>
