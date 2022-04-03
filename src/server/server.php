<?php
    require __DIR__.'/config.php';

    //Receive Data From Form
    if(isset($_SERVER['REQUEST_METHOD']) && !empty($_POST['email']) && !empty($_POST['username'])) {
        $name = $_POST['username'];
        $email = $_POST['email'];
        $check_name = filter_var($name, FILTER_SANITIZE_STRING);
        $check_email = filter_var($email, FILTER_SANITIZE_EMAIL);

        if(!$check_email && !$check_name) {
            exit('Invalid Email or Name');
        }

        //Connect To The Database

        $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DB);

        if($conn) {
            $sql = "INSERT INTO `sublist` (`name`, `email`, `sub`) VALUES ('$name', '$email', '0');";
            $result = mysqli_query($conn, $sql);

            if($result) {
                echo '<script>alert("Subscription Succesfull. Check You Mail Box At '.$email.' For Confimation")</script>';

                $eemail = rawurlencode($email);

                $confimationLink = DOMAIN_URL.'activation.php?link='.$eemail;
                
                $to_email = "$email";
                $subject = "Confirmation For XKCD Comic Subscription.";
                $body = "Hi, Please Go To This URL To Confirm Your Subscription: '.$confimationLink.";
                $headers = "From: ".FROMEMAIL;

                mail($to_email, $subject, $body, $headers);

                
            } else {
                echo '<script>alert("Some Error Has Ocurred Please Try Later or With Different Email SQL Result error")</script>';
            } 
             
        } else {
            echo '<script>alert("Some Error Has Ocurred Try Later. DB Connection Error")</script>';
        }
        
        mysqli_close($conn);
        
    } else {
        echo '<script>alert("Some Error Has Ocurred Please Try Again. Method or username or email error")</script>';
    }
    
?>