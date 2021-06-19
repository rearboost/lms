<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'jwt/BeforeValidException.php';
require_once 'jwt/ExpiredException.php';
require_once 'jwt/SignatureInvalidException.php';
require_once 'jwt/JWT.php';

use \Firebase\JWT\JWT;
class Zoom_Api {
		private $zoom_api_key = 'mgx-9wXQRXCwPaMgvlEu9w';
		private $zoom_api_secret = 'YV99RXmECNAZJeOvrcOEd5uUHGhYrfP0r0Ui';
		
		
		protected function sendRequest($data) {
            $request_url = 'https://api.zoom.us/v2/users/me/meetings';
            $headers = array(
			"authorization: Bearer ".$this->generateJWTKey(),
			'content-type: application/json'
			);
           $postFields = json_encode($data);
		   $ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_URL, $request_url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
			if(!$response){
				return $err;
			}
			return json_decode($response);
		}

		//function to generate JWT
        private function generateJWTKey() {
            $key = $this->zoom_api_key;
            $secret = $this->zoom_api_secret;
            $token = array(
                "iss" => $key,
                "exp" => time() + 3600 //60 seconds as suggested
            );
		//	$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IlI1OWZNMEViUXFPcWNrU0c4dzR2MmciLCJleHAiOjE1OTA1MTM4NDUsImlhdCI6MTU5MDUwODQ0N30.4ch2OZoFM_vZFdqhoMzJX8r8GPYjKlOkV_vUa7LprFc";
            return JWT::encode( $token, $secret );
        }
		
		public function createAMeeting( $data = array() ) {
            $post_time  = $data['start_date'];
			
			$start_time = gmdate( "Y-m-d\TH:i:s", strtotime( $post_time->format('Y-m-d H:i:s') ) );
			
            $createAMeetingArray = array();
            if ( ! empty( $data['alternative_host_ids'] ) ) {
                if ( count( $data['alternative_host_ids'] ) > 1 ) {
                    $alternative_host_ids = implode( ",", $data['alternative_host_ids'] );
                } else {
                    $alternative_host_ids = $data['alternative_host_ids'][0];
                }
            }
            $createAMeetingArray['topic']      = $data['meetingTopic'];
            $createAMeetingArray['agenda']     = ! empty( $data['agenda'] ) ? $data['agenda'] : "";
            $createAMeetingArray['type']       = ! empty( $data['type'] ) ? $data['type'] : 2; //Scheduled
            $createAMeetingArray['start_time'] = $start_time;
            $createAMeetingArray['timezone']   = $data['timezone'];
            $createAMeetingArray['password']   = ! empty( $data['password'] ) ? $data['password'] : "";
            $createAMeetingArray['duration']   = ! empty( $data['duration'] ) ? $data['duration'] : 60;
            $createAMeetingArray['settings']   = array(
                'join_before_host'  => ! empty( $data['join_before_host'] ) ? true : false,
                'host_video'        => ! empty( $data['option_host_video'] ) ? true : false,
                'participant_video' => ! empty( $data['option_participants_video'] ) ? true : false,
                'mute_upon_entry'   => ! empty( $data['option_mute_participants'] ) ? true : false,
                'enforce_login'     => ! empty( $data['option_enforce_login'] ) ? true : false,
                'auto_recording'    => ! empty( $data['option_auto_recording'] ) ? $data['option_auto_recording'] : "none",
                'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
            );
            return $this->sendRequest($createAMeetingArray);
        }

	}

class addmeetings extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('courses','',TRUE);   
      $this->load->model('dmlcourse','',TRUE);
   $this->load->model('User','',TRUE);
   $this->load->helper('url');
   $session_data = $this->session->userdata('logged_in');
   if (!isset($session_data['username']))
   {
	   redirect('login', 'refresh');
   }
   $content;
 }
 function GetCourses($Subject){
	$session_data = $this->session->userdata('logged_in');
	$username = $session_data['username'];
	$courses = $this ->courses->GetCourses_Subject($Subject,$username);	
	//$course_list = "";
	echo '<option value="">Nil</option>';
	foreach($courses as $course) 
    {
		echo '<option value="'.$course['ID'].'">'.$course['Title'].'</option>';
	} 
 }
 function GetChapters($Course){
	$Lists = $this ->courses->GetChapter_Course($Course);	
	//$course_list = "";
	echo '<option value="">Nil</option>';
	foreach($Lists as $List) 
    {
		echo '<option value="'.$List['ChapterID'].'">'.$List['ChapterTitle'].'</option>';
	} 
 }
 function GetTopics($Chapter){
	$Lists = $this ->courses->GetTopic_Chapter($Chapter);	
	//$course_list = "";
	echo '<option value="">Nil</option>';
	foreach($Lists as $List) 
    {
		echo '<option value="'.$List['TopicID'].'">'.$List['TopicTitle'].'</option>';
	} 
 }
 function CreateZoomMeeting()
 {
    date_default_timezone_set("Asia/Karachi");
	$Title = $this->input->post('MeetingTitle');	
	$scheduledate = $this->input->post('MeetingDate');	
	$date=date_create($scheduledate);
	$js_Date= date_format($date,"Y/m/d H:i:s");	
	$dteStart = new DateTime($js_Date);
	
	$zoom_meeting = new Zoom_Api();
	try{
	$z = $zoom_meeting->createAMeeting(
		array(
			'start_date'=>$dteStart,
			'meetingTopic'=>$Title,
			'timezone'=>'Asia/Tashkent'
		)
	);
	echo json_encode($z);
	} catch (Exception $ex) {
	echo 'FAILED';
	}
 }
 function index()
 {	
	$error_code = $this->uri->segment(3);
	$datetime = $this->uri->segment(4);
	$error="";
	if($error_code=='1')
	{
		$error= '<div class="alert alert-success" role="alert">Back Date('.$datetime.') Entry Not Allowed</div>';
	}		
	$courses = $this ->courses->GetAllChapter();	
	$content = "";
	foreach($courses as $course) 
    {
		$content .= '<option value="'.$course['ChapterID'].'">'.$course['ChapterTitle'].'</option>';
	}
    $session_data = $this->session->userdata('logged_in');
    $username = $session_data['username'];
	$Medias = $this ->courses->GetAllMedia($username);
	$tableRow = '';
	
	foreach($Medias as $Media) { 
		
		$tableRow = $tableRow .	
		'<tr class="gradeA odd" role="row">				
				<td class="sorting">'.$Media['MediaName'].'</td>
				<td>'.$Media['MediaType'].'</td>
				<td>
					<button type="button" data-id="'.$Media['MediaName'].'" id="fetchMedia" class="btn btn-danger">Select</button>
				</td>				
		</tr>';
	}
	$grid = '<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">							
							<div class="row"><div class="col-sm-12"><table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
									<th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 100px;">File Name</th>
									<th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 80px;" aria-sort="descending">File Type</th>
									<th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" style="width:15%" aria-label="CSS grade: activate to sort column ascending" style="width: 50px;">Action</th>
									</tr>
                                </thead>
                                <tbody>'.$tableRow.'</tbody>
                            </table></div></div>
							</div>
                            
                        </div>
                    </div>
                </div>
            </div>';
	$modal = '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Content</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">'.
					$grid
				  .'</div>
				</div>
			  </div>
			</div>';
	$htmlcontent = '<div class="panel-body">
                            <div class="row">
							'.$error.'
                                <div class="col-lg-12">								
                                    <form action="'.site_url('addmeetings/newtopic').'" method="post" >
                                        <div class="form-group">                                            
							
											<div class="form-row">
												<div class="form-group col-md-6">
													<label>Subject</label>
													<select class="form-control"  id="subject" name="subject" required>
														<option value="">Yet To Select</option>
														<option>Physics</option>
														<option>Chemistry</option>                                                
														<option>Biology</option>                                                
														<option>Maths</option>                                                
													</select>						
												</div>
												<div class="form-group col-md-6">
													<label>Course</label>
													<select class="form-control" id="course"  name="course" required>
													<option value="">Yet To Select</option>
													</select>											
												</div>											
											</div>	
											<div class="form-row">
												<div class="form-group col-md-4">
													<label>Chapter</label>
													<select class="form-control" id="chapter"  name="chapter" required>
													<option value="">Yet To Select</option>
													</select>										
												</div>
												<div class="form-group col-md-4">
												<label>Meeting Title</label>
												<input name="Price"  id="Price" type="hidden">
												<input name="title" id="title" type="text" max="150" class="form-control" required>
												</div>
												<div class="form-group col-md-4">
													<label>Schedule DateTime( Minimum 10 Minute Later)</label>
													<div class="input-group date" id="datetimepicker1">
														<input type="text" class="form-control" id="StartDate" name="StartDate" required/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>	
											
											<div class="form-row">
												<div class="form-group col-md-12">
													<label>Content</label>
													
													<textarea style ="max-width:100%;height:150px" id="text_type" name="topiccontent"  type="text"  class="form-control summernote"></textarea>
													<p class="help-block">Example: Physics</p>
												</div>											
											</div>	

											
                                        </div>                                    
										<div class="form-group col-md-12">										
                                        <button type="submit" id="CreateZoomMeeting" style="background-color:#519CFE; color:white;" class="btn btn-default"><span class="fa fa-video-camera"> Create Zoom Meeting</button>
										<button type="submit" style="background-color:#5CB85C; color:white;display:none;" id="SaveMeeting" class="btn btn-default">Save Meeting</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
										</div>
                                    </form>
                                </div>
								
                                <!-- /.col-lg-6 (nested) -->                                
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
						
						';
	 $data['htmlcontent'] =$htmlcontent.$modal;
	 $data['error'] ='';		 		 
	 $this->load->view('addmeetings_view', array('content'=>$data['htmlcontent']));  
 }
 
 function newtopic()
 {	
	
	$data = array(
		'TopicID'  => null,
		'TopicType'  => '2',
		'TopicTitle'  => $this->input->post('title'),
		'CourseID'  => $this->input->post('chapter'),
		'TopicDesc'  => $this->input->post('topicDesc'),
		'TopicContent'  =>$this->input->post('topiccontent'),
		'StartDate'  => $this->input->post('StartDate'),
		'ContentType'  => 'Text',
		'price'  => $this->input->post('Price')
	);
	$scheduledate = $this->input->post('StartDate');
	//$datetime = new DateTime(str_replace('/', '-', $scheduledate));	
	$date=date_create($scheduledate);
	$js_Date= date_format($date,"Y/m/d H:i:s");
	//$diff_future = abs(strtotime($js_Date)-strtotime(date("/m/d/Y h:i:s")));
	//$minutes = $diff_future/60;
	$minutes = 60;
	date_default_timezone_set("Asia/Karachi");
	 $dteStart = new DateTime($js_Date);
	$dteEnd   = new DateTime(date("Y/m/d h:i:sa"));
	//echo $dteEnd->format("Y/m/d H:i:s"); 
	$dteDiff  = $dteEnd->diff($dteStart);
	$past_date = $dteDiff->format('%R');
	//echo $past_date;
	//$this->courses->add_topic($data);	
	
	if($past_date != '-')
	{
		$this->courses->add_topic($data);	
		redirect(site_url('ManipulateMeetings'));		
	}
	else
	{ 
		//redirect(site_url('addmeetings'));	 			
	}		
  	
 }
  function MarkAttendance()
 {	
	date_default_timezone_set("Asia/Karachi");
	$session_data = $this->session->userdata('logged_in');
    $username = $session_data['username'];
	$data = array(
		'MeetingID'  => $this->input->post('TopicID'),
		'Student'  => $username,		
		'CreationDate'  => date("Y/m/d h:i:sa")
	);
	$Topic = $this ->dmlcourse->GetParticularTopic($this->input->post('TopicID'));
	$scheduledate = $Topic[0]['startdate'];
	$date=date_create($scheduledate);
	$js_Date= date_format($date,"Y/m/d H:i:s");	
	$dteStart = new DateTime($js_Date);
	$dteEnd   = new DateTime(date("Y/m/d h:i:sa"));
	//echo $dteEnd->format("Y/m/d H:i:s"); 
	$dteDiff  = $dteEnd->diff($dteStart);
	$past_date = $dteDiff->format('%R');
	
	$day_left = $dteDiff->format('%d');
	$hrs_left = $dteDiff->format('%h');
	$net_hrs =  $hrs_left + $day_left*24;
	$minutes_left = $dteDiff->format('%i');
	$net_minutes =  $minutes_left + $net_hrs*60;
	$seconds_left = $dteDiff->format('%s');
	$net_seconds = $seconds_left  + $net_minutes*60;
	echo  $net_minutes;
	if($net_minutes >= 0 && $net_minutes <= 15 )
	{
		$this->dmlcourse->mark_attendance($data);
			
		///redirect(site_url('ManipulateMeetings'));		
	}
			
  	
 }
 
 
 function updatetopic()
 {	 
	$content="";
	if($this->input->post('contenttype')=='Text')
	{
			$content = $this->input->post('topiccontent');
	}
	else
	{
			$content = $this->input->post('topiccontent1');
	}
	$data = array(
		'TopicID'  => $this->input->post('TopicID'),
		'TopicType'  => '3',
		'TopicTitle'  => $this->input->post('title'),
		'CourseID'  => $this->input->post('chapter'),
		'TopicDesc'  => $this->input->post('topicDesc'),
		'TopicContent'  =>  $content,
		'StartDate'  => $this->input->post('StartDate'),
		'ContentType'  => $this->input->post('contenttype'),
		'price'  => '0'
	);
	$scheduledate = $this->input->post('SessionSchedule');
	//$datetime = new DateTime(str_replace('/', '-', $scheduledate));	
	$date=date_create($scheduledate);
	$js_Date= date_format($date,"Y/m/d H:i:s");
	//$diff_future = abs(strtotime($js_Date)-strtotime(date("/m/d/Y h:i:s")));
	//$minutes = $diff_future/60;
	$minutes = 60;
	date_default_timezone_set("Asia/Karachi");
	 $dteStart = new DateTime($js_Date);
	$dteEnd   = new DateTime(date("Y/m/d h:i:sa"));
	//echo $dteEnd->format("Y/m/d H:i:s"); 
	$dteDiff  = $dteEnd->diff($dteStart);
	$past_date = $dteDiff->format('%R');
	//echo $past_date;
	
	if($past_date != '-')
	{
		$this->courses->updateTopic($data);	
		redirect(site_url('ManipulateMeetings'));		
	}
	else
	{ 
		//$data['error'] ='<div class="alert alert-success" role="alert">Back Date Entry Not Allowed</div>';		 		 
		//$data['Topics'] = $this ->dmlcourse->GetAllTopics('5');	
		redirect(site_url('addmeetings'));	 			
	}	
		
 }
 function deletetopic($ID)
 {		
	$this->courses->deleteTopics($ID);	
	redirect(site_url('ManipulateMeetings'));
 }
 function edittopic()
 {	 
	
	$session_data = $this->session->userdata('logged_in');
	$username = $session_data['username'];
	$topics = $this->session->userdata('topicdetail');
	$htmlcontent  = null;
	$chapters = $this ->courses->GetChapter_Course($topics[0]['CourseID']);
	$trns = $this ->courses->GetTrnTypes();
	$courses_list = $this ->courses->GetCourses_Subject($topics[0]['Subject'],$username);
	$subject_lists = $this ->courses->GetAllSubjects();
	$content = "";
	$trn_list="";
	$course_list ="";
	$subject_list="";
	/*foreach($topics as $topic) 
    {
		$content .= '<option value="'.$course['ID'].'">'.$course['Title'].'</option>';
	}*/
	$Medias = $this ->courses->GetAllMedia();
	$tableRow = '';
	$contenttypes="";
	
	foreach($Medias as $Media) { 
		
		$tableRow = $tableRow .	
		'<tr class="gradeA odd" role="row">				
				<td class="sorting">'.$Media['MediaName'].'</td>
				<td>'.$Media['MediaType'].'</td>
				<td>
					<button type="button" data-id="'.$Media['MediaName'].'" id="fetchMedia" class="btn btn-danger">Select</button>
				</td>				
		</tr>';
	}
	$grid = '<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">

                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">							
							<div class="row"><div class="col-sm-12"><table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" id="dataTables-example" role="grid" aria-describedby="dataTables-example_info" style="width: 100%;">
                                <thead>
                                    <tr role="row">
									<th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" style="width: 100px;">File Name</th>
									<th class="sorting_desc" tabindex="0" aria-controls="dataTables-example" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 80px;" aria-sort="descending">File Type</th>
									<th class="sorting" tabindex="0" aria-controls="dataTables-example" rowspan="1" style="width:15%" aria-label="CSS grade: activate to sort column ascending" style="width: 50px;">Action</th>
									</tr>
                                </thead>
                                <tbody>'.$tableRow.'</tbody>
                            </table></div></div>
							</div>
                            
                        </div>
                    </div>
                </div>
            </div>';
	$modal = '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			  <div class="modal-dialog" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Content</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div class="modal-body">'.
					$grid
				  .'</div>
				</div>
			  </div>
			</div>';
	  if(isset($topics)) {
			foreach($topics as $topic) { 	
					foreach($subject_lists as $subject_item) 
					{
						if($subject_item['SubjectTitle']==$topic['Subject'])
						{
							$subject_list .= '<option selected value="'.$subject_item['SubjectTitle'].'">'.$subject_item['SubjectTitle'].'</option>';
						}
						else
						{
							$subject_list .= '<option value="'.$subject_item['SubjectTitle'].'">'.$subject_item['SubjectTitle'].'</option>';
						}
					}
					foreach($courses_list as $courses_item) 
					{
						if($courses_item['ID']==$topic['CourseID'])
						{
							$course_list .= '<option selected value="'.$courses_item['ID'].'">'.$courses_item['Title'].'</option>';
						}
						else
						{
							$course_list .= '<option value="'.$courses_item['ID'].'">'.$courses_item['Title'].'</option>';
						}
					}
					
					foreach($chapters as $chapter) 
					{
						if($chapter['ChapterID']==$topic['ChapterID'])
						{
							$content .= '<option selected value="'.$chapter['ChapterID'].'">'.$chapter['ChapterTitle'].'</option>';
						}
						else
						{
							$content .= '<option value="'.$chapter['ChapterID'].'">'.$chapter['ChapterTitle'].'</option>';
						}
					}					
					foreach($trns as $trn)
					{
						if ($topic['TopicType']==$trn['trn_type']) {
							$trn_list .= '<option selected value="'.$trn['trn_type'].'">'.$trn['trn_name'].'</option>';
						}
						else
						{
							$trn_list .= '<option value="'.$trn['trn_type'].'">'.$trn['trn_name'].'</option>';
						}
					}
			
					//$check=($course["Travelling"]!=null)  ?  "checked" :  "";
                    if($topic['contenttype']=='File'){
						$contenttypes ='
						<style>.note-editor{display:none;}</style>
						<div class="btn-group btn-group-toggle" data-toggle="buttons">
												  <label class="btn btn-secondary" id="_text">
													<input type="radio" name="contenttype" value="Text"  autocomplete="off"> Text
												  </label>
												  <label class="btn btn-secondary active" id="_file">
													<input type="radio" name="contenttype" value="File"  autocomplete="off" checked> File
												  </label>											  
												</div>
												</br>
												<label>Content</label>
						<div class="input-group mb-3" id="file_type">
							  <input type="text" class="form-control" id="medianame" name="topiccontent1" value="'.$topic['TopicContent'].'" style="width:88%;" placeholder="File Name" aria-label="File Name" aria-describedby="basic-addon2">
							  <div class="input-group-append">
								<button class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal"  type="button">Load File</button>
							  </div>
						</div>						
						<textarea style ="max-width:100%;height:150px;display:none;" name="topiccontent"  type="text"  class="form-control summernote"></textarea>';
					}
					else
					{
						$contenttypes ='
						<div class="btn-group btn-group-toggle" data-toggle="buttons">
												  <label class="btn btn-secondary active" id="_text">
													<input type="radio" name="contenttype" value="Text"  autocomplete="off" checked> Text
												  </label>
												  <label class="btn btn-secondary" id="_file">
													<input type="radio" name="contenttype" value="File"  autocomplete="off"> File
												  </label>											  
												</div>
												</br>
												<label>Content</label>
						<div class="input-group mb-3" id="file_type" style="display:none;">
							  <input type="text" class="form-control" id="medianame" name="topiccontent1" style="width:88%;" placeholder="File Name" aria-label="File Name" aria-describedby="basic-addon2" disabled>
							  <div class="input-group-append">
								<button class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal"  type="button">Load File</button>
							  </div>
						</div>
						<textarea style ="max-width:100%;height:150px" name="topiccontent"  type="text"  class="form-control summernote" required>'.$topic['TopicContent'].'</textarea>';						
					}
					$htmlcontent = '<div class="panel-body">
                            <div class="row">
                                <div class="col-lg-8">								
                                    <form action="updatetopic" method="post" >
                                        <div class="form-group">                                            
											<input type="hidden" name="TopicID" value="'.$topic['TopicID'].'"/>											
                                            
											
											<label>Subject</label>
											<select class="form-control" value='.$topic['Subject'].'  id="subject" name="subject" required>
												<option value="">Yet To Select</option>
												'.$subject_list.'
                                            </select>						
											<label>Course</label>
											<select class="form-control" value='.$topic['CourseID'].' id="course"  name="course" required>
											<option value="">Yet To Select</option>
											'.$course_list.'
                                            </select>											
											<label>Chapter</label>
											<select class="form-control" id="chapter" value="'.$topic['ChapterID'].'" name="chapter" required>'.$content.'</select>											
											<label>Title</label>
											<input name="title" type="text" value="'.$topic['TopicTitle'].'" max="150" class="form-control" required>
											
											
											<label>Schedule DateTime</label>
											<div class="input-group date" id="datetimepicker1" required>
												<input type="text" class="form-control" id="StartDate" name="StartDate" value="'.$topic['startdate'].'"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											
                                        </div>                                        
                                        <button type="submit" id="CreateZoomMeeting" style="background-color:#519CFE; color:white;" class="btn btn-default"><span class="fa fa-video-camera"> Re-Create Zoom Meeting</button>
										<button type="submit" style="background-color:#5CB85C; color:white;" class="btn btn-default">Update Meeting</button>
                                        <button type="reset" class="btn btn-default">Reset</button>
                                    </form>
                                </div>
								<div class="col-lg-2">
								<img src="'.base_url().'"/Images/jobs-icon.png" style="max-width: 300px;" alt=""/>
								</div>
                                <!-- /.col-lg-6 (nested) -->                                
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>';
	 
	  }	  
	  }
	 $data['htmlcontent'] =$htmlcontent.$modal;
	 $data['error'] ='';		 		 
	 $this->load->view('addmeetings_view', array('content'=>$data['htmlcontent']));     	 
 } 
}
?>