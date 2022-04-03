<?php

    require __DIR__.'/config.php';

    if(isset($_SERVER['REQUEST_METHOD']) && !empty($_GET['link'])) {
        $elink = $_GET['link'];
        $dlink = rawurldecode($elink);
        $check_dlink = filter_var($dlink, FILTER_SANITIZE_EMAIL);

        if(!$check_dlink) {
            exit('Invalid Deactivation Link');
        }
        

        //Connect To The Database

        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DB);

        if($conn) {
            $sql = "UPDATE `sublist` SET `sub`='0' WHERE `email`='$dlink';";
            $result = mysqli_query($conn, $sql);

            if($result) {
                echo '<script>alert("Deactivation Succesfull")</script>';
            } else {
                echo '<script>alert("Deactivation Failed Try Again, O\r Try Contacting Website Support")</script>';             
            }
            
        } else {
            echo '<script>alert("Some Error Has Ocurred Try Later.")</script>';
        }

        mysqli_close($conn);
        
    }

?>