<!DOCTYPE html>
<html lang="en">
<?php require 'config.php';?>
<head>
  <title>Enrol a Student | Learning Management System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="logo-icon.png" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="select2.min.css" />
  
  <style>
      
      body{
          font-size: 15px;
      }
  </style>
</head>
<body> 
 
<div class="container">
  <div class="card mt-3">
    <div class="card-body"><i class="mdi mdi-apple-keyboard-command"></i> 
      <h4>Enrol a student</h4>
    </div>
  </div>

  <div class="row mt-4">
    <div class="col-md-10 offset-1">
        <div class="card p-4">
          <div class="card-title">
            <h6 class="ml-4">ENROLMENT FORM</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3">User <span class="star">*</span></label>
                  <div class="col-sm-7">
                    <select class="form-control" id="user" name="user">
                      <option disabled="" selected="">Select a user</option>
                      <!-- <input class="form-control" type="text" placeholder="Search.." id="myInput"> -->
                      <?php
                      $getUser = mysqli_query($conn, "SELECT * FROM users WHERE is_instructor<>1");
                      $numUsers = mysqli_num_rows($getUser); 
                  
                      if($numUsers > 0) {
                          while($userData = mysqli_fetch_assoc($getUser)) {
                              echo '<option value ="'.$userData["id"].'">' . $userData["email"]. ' </option> ';
                          }
                      }
                      ?>
                    </select>
                  </div>   
                </div>

                <div class="form-group row">
                  <label class="col-sm-3">Course to enrol <span class="star">*</span></label>
                  <div class="col-sm-7">
                    <select class="form-control" id="course" name="course" required="">
                      <option disabled="" selected="">Select a course</option>
                      <?php
                      $getCourse = mysqli_query($conn, "SELECT * FROM course");
                      $numCourse = mysqli_num_rows($getCourse); 
                  
                      if($numCourse > 0) {
                          while($courseData = mysqli_fetch_assoc($getCourse)) {
                              echo '<option value ="'.$courseData["id"].'">' . $courseData["title"]. ' </option> ';
                          }
                      }
                      ?>
                    </select>
                  </div>

                  <div class="col-sm-2"> 
                    <button class="btn btn-primary btn-sm circle" onclick="addRow()">+</button>
                  </div> 
                </div>





              </div>
            </div>
            <!-- table section start  -->
            <div class="row">
              <?php if (isset($_GET['view_id'])): ?>
                <br>
              <div id="here" style="width: 100%;">
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped" style="width:100%">

                    <?php 
                    $view_id = $_GET['view_id'];
                    $userDetail = mysqli_query($conn, "SELECT * FROM users WHERE id='$view_id'");
                    $viewUser = mysqli_fetch_assoc($userDetail);

                    $first_name = $viewUser['first_name'];
                    $last_name  = $viewUser['last_name'];
                    $email      = $viewUser['email'];
                    ?>

                      <thead>
                        <tr>
                          <td colspan="7" class="text-center" style="background-color: #59A2AE;"><b><?php echo $first_name. ' ' . $last_name . ' ['.$email.']';?> enrolled for</b></td>
                        </tr>
                        <tr>
                          <td>#</td>
                          <td>Course</td>
                          <td>Enrollment Date</td>
                          <td>Language</td>
                          <td>Level</td>
                          <td>Fee</td>
                          <td>DELETE</td>  
                        </tr>
                      </thead>
                      <tbody>
                        <?php

                            $sql_real=mysqli_query($conn,"SELECT * FROM enrol WHERE user_id='$view_id'");

                            $numRows_real = mysqli_num_rows($sql_real);  
                      
                            if($numRows_real>0) {
                              
                              $i = 1;
                    
                              while($rowReal = mysqli_fetch_assoc($sql_real)) {

                                $id        = $rowReal['id'];
                                $course_id = $rowReal['course_id'];

                                $fetchCourse=mysqli_query($conn,"SELECT E.date_added AS date_added, C.title AS title, C.language AS language, C.level AS level, C.price AS price FROM course C INNER JOIN enrol E ON C.id=E.course_id WHERE C.id=$course_id");
                                $count = mysqli_num_rows($fetchCourse);

                                if($count>0){
                                  $dataRow = mysqli_fetch_assoc($fetchCourse);

                                  $title       = $dataRow['title'];
                                  $date_added  = date('D, d-M-Y', $dataRow['date_added']);
                                  $language    = $dataRow['language'];
                                  $level       = $dataRow['level'];
                                  $fee         = $dataRow['price'];


                                  echo ' <tr>';
                                  echo ' <td>'.$i.' </td>';
                                  echo ' <td>'.$title.' </td>';
                                  echo ' <td>'.$date_added.' </td>';
                                  echo ' <td>'.$language.' </td>';
                                  echo ' <td>'.$level.' </td>';
                                  echo ' <td class="text-right">'.number_format($fee,2,'.',',').' </td>';
                                  echo '<td class="td-center"><button class="btn btn-danger btn-sm" id="DeleteButton" onclick="removeForm('.$id.')">Delete</button></td>';
                                  echo ' </tr>';
                                  $i++;

                                }
                              }
                                
                            }else{
                            echo '<tr>
                              <td colspan="7" class="text-center" style="color: red;"> No available enrollments</td>
                            </tr>';

                            }
                          ?>
                        </tbody>

                  </table>
                </div> 
                <input type="hidden" class="form-control" id="user_id" name="user_id" value="<?php if(isset($_GET['view_id'])){ echo $view_id;} ?>" />          
                </div> <br><br>          
                <!-- end -->

              <?php else: ?>
              <?php endif ?>
            </div>

            <div class="row">
              <button class="btn btn-primary ml-4 btn-sm" onclick="RefreshPage()">Refresh</button>
            </div>

          </div>
        </div>
    </div>
  </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>
<script src="select2.min.js"></script>
<script>
   
    $("#user").select2( {
      placeholder: "Select a user",
      allowClear: true
    } );

    $("#course").select2( {
      placeholder: "Select a course",
      allowClear: true
    } );

    $('#user').on('change',function(){
        var id = $('#user').val();
        window.location.href = "student_enrol.php?view_id=" + id;
    });

    // add data to the temp table                      
    function addRow(){

      var addrow  ="addrow";

      var user_id   = $('#user_id').val();
      var course_id = $('#course').val();

      if(user_id!='' || course!=''){

       $.ajax({
            type: 'post',
            url: 'enrol_controller.php',
            data: {addrow:addrow,user_id:user_id,course_id:course_id},
            success: function (data) {

                if(data==0){

                  alert('This user already enrolled for this course');
                  $('#course').val("");

                }else{

                    $('#course').val("");
                    $( "#here" ).load(window.location.href + " #here" );
                    $("#course").focus();
                }            
              } 
        });     
      }else{
        alert('Invalid');
      }
    }

    /////////// Remove the Row 
    function removeForm(id){

        var removeRow  ="removeRow";

         $.ajax({
            type: 'post',
            url: 'enrol_controller.php',
            data: {removeRow:removeRow,id:id},
            success: function (data) {
                $( "#here" ).load(window.location.href + " #here" );
              } 
        });
    }

    ////// page refresh //////
    function RefreshPage(){
      window.location.href = "student_enrol.php";
    }
</script>


</body>
</html>

