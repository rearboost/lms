<h1>
    Create Meeting
</h1>

<form action="../../../Zoom_Api.php" method="POST">

    <div>
        <label>Topic</label>
        <input type="text" name="topic" id="topic">
    </div>

    <div>
        <label>Ajenda</label>
        <input type="text" name="ajenda" id="ajenda">
    </div>

    <div>
        <label>Type</label>
        <input type="text" name="type" id="type">
    </div>

    <div>
        <label>Start Time</label>
        <input type="datetime-local" name="start_date" id="start_date">
    </div>

    <div>
        <label>Time Zone</label>
        <input type="text" name="timezone" id="timezone">
    </div>

    <div>
        <label>Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div>
        <label>Duration</label>
        <input type="text" name="duration" id="duration">
    </div>

    <div>
        <button id="add-meeting" name="add-meeting">Add Meeting</button>
    </div>

</form>


<!-- <script>
    $(document).ready(function() {
        $('#datetimepicker1').datetimepicker({
            minDate: moment().add(10, 'minutes')
        });
    });

    $("#StartDate").change(function() {
        var startdate = $("#StartDate").val();
        var currentdate = new Date();
        console.log('Schdule Date ' + startdate);
        console.log('Current Date ' + currentdate);
        if (startdate < currentdate) {
            alert('Back Date not allow.');
        }
    });
    var site_url = "<?php echo site_url('addmeetings'); ?>" + "/";
    $("#subject").change(function() {
        $("#course").load(site_url + "GetCourses/" + $("#subject").val());
    });
    $("#course").change(function() {
        $("#chapter").load(site_url + "GetChapters/" + $("#course").val());
    });

    $("#_file").click(function() {

        $(".note-editor").hide();

        $("#file_type").show();
    });
    $("#_text").click(function() {

        $(".note-editor").show();

        $("#file_type").hide();
    });


    // code to read selected table row cell data (values).
    $("#dataTables-example").on('click', '#fetchMedia', function() {
        // get the current row
        $("#medianame").val($(this).attr("data-id"));
        $("#exampleModal").modal("hide");
    });

    $("#CreateZoomMeeting").click(function() {
        var schedule_Date = $("#StartDate").val();
        var Title = $("#title").val();
        alert(schedule_Date);
        var valid = this.form.checkValidity();
        if (valid) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: site_url + "CreateZoomMeeting",
                dataType: "json",
                data: {
                    MeetingDate: schedule_Date,
                    MeetingTitle: Title
                },
                success: function(data) {
                    var Info = '<strong>Meeting Url : </strong><a href="' + data.join_url + '" meeting-id="' + data.id + '" id="JoinZoomMeeting" target="_blank" class="btn btn-default">Join Meeting</a></br><strong>Password:</strong>' + data.password
                    $("#text_type").summernote('code', Info);
                    $("#Price").val(data.id);
                    $("#SaveMeeting").show();
                    $("#CreateZoomMeeting").hide();
                }
            });
        }
    });
</script> -->