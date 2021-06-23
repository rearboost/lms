<?php
    // Database Connection
    require 'config.php';

    // Add Row Function 
    if(isset($_POST['addrow'])){

        $user_id    = $_POST['user_id'];
        $course_id  = $_POST['course_id'];
        
        $date_added = strtotime(date('D, d-M-Y'));

        $check = mysqli_query($conn,"SELECT * FROM enrol WHERE user_id='$user_id' AND course_id='$course_id' ");

        $count = mysqli_num_rows($check);
        if($count==0){
            $insert = mysqli_query($conn, "INSERT INTO enrol(user_id,course_id,date_added)VALUES($user_id,$course_id,$date_added) ");
            if($insert){
                echo 1;
            }
        }else{
            echo 0;
        }
    }

    // Remove  records 
    if(isset($_POST['removeRow'])){
        
        $id = $_POST['id'];
        $remove = "DELETE FROM enrol WHERE id='$id'";
        mysqli_query($conn,$remove);

        echo 1;

    }

   
?>