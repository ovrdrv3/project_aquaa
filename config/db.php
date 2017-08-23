<?php
    // Create connection
    $conn = new mysqli($server, $username, $password, $db);

    // Check connection
    if(mysqli_connect_errno()){
        // Connection failed
        echo '<tr>Failed to connect to MySql, error no: '. mysqli_connect_errno() . '</tr>';
    }


 ?>
