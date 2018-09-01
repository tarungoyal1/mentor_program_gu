$(document).ready(function() {
      // Initialize the plugin
   $('#my_popup').popup({
      opacity: 0.5,
      transition: 'all 0.3s'
  });
});

$(document).ready(function(){
$(".my_popup_open").click(function(){
     var stenr = $(this).attr('id');

    $("#sendingtoLabel").html("Sending to student of enroll:"+stenr+"<br />");
    $("#enroll").val(stenr);
    $("#emailSub").val("");
    $("#emailBody").val("");
    $("#pag_flash").css("visibility", "hidden");
  });
});





$(document).ready(function(){
$("#sendMail").click(function(){
     $("#pag_flash").html('<img  width="32" height="32" src="/mentor/style/comment.gif" />');
     $("#pag_flash").css("visibility", "visible");
   var mid = $("#mid").val();
   var enroll = $("#enroll").val();
   var mailSub = $("#emailSub").val();
   var mailBody = $("#emailBody").val();

   // alert(mid+enroll+mailSub+mailBody);
   var dataString = 'mid=' + mid + '&studEnroll=' + enroll + '&mSub=' + mailSub + '&mbody=' + mailBody;
   // var nextPage = +page+1;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/sendMessage.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#pag_flash").html(errorMessage);                         
                    }else {
                      $("#pag_flash").html(result);                          
                    }
            
      }
  });
});
});

$(document).ready(function(){
$("#contCrSend").click(function(){
     $("#pag_flash_cc").html('<img  width="32" height="32" src="/mentor/style/comment.gif" />');
     $("#pag_flash_cc").css("visibility", "visible");
   var mid = $("#ccMid").val();
   var mailSub = $("#contCrSub").val();
   var mailBody = $("#contCrBody").val();

   // alert(mid+enroll+mailSub+mailBody);
   var dataString = 'mid=' + mid + '&mSub=' + mailSub + '&mbody=' + mailBody;
   // var nextPage = +page+1;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/contactcr.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#pag_flash_cc").html(errorMessage);                         
                    }else {
                      $("#pag_flash_cc").html(result);                          
                    }
                    $("#contCrSub").val("");
                    $("#contCrBody").val("");
            
      }
  });
});
});

///fetch students
$(document).ready(function(){
$("#fetchStud").click(function(){
     $("#pag_flash").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#pag_flash").css("visibility", "visible");
   var progId = $("#program_id").val();
   var yearId = $("#year_id").val();
   var semId = $("#sem_id").val();
   // var mailBody = $("#contCrBody").val();

   // alert(mid+enroll+mailSub+mailBody);
   var dataString = 'pid=' + progId + '&yid=' + yearId + '&sid=' + semId;
   // var nextPage = +page+1;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/fetchStud.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#pag_flash").html(errorMessage);                         
                    }else {
                      $("#fetchedData").html(result); 
                      $("#pag_flash").css("visibility", "hidden");                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
            
      }
  });
});
});

//

$(document).on("click",".setting_popup", function () {
     $("#setting_pag_flash").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#setting_pag_flash").css("visibility", "visible");
    var stenr = this.id;
    $("#setting_studEnroll").html("Student enroll number: "+stenr+"<br />");
    var dataString = 'enroll=' + stenr;
    //pull all mentors
    $.ajax({
             type: "POST",
             url: "/mentor/public/settingFetch.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#setting_pag_flash").html(errorMessage);                         
                    }else {
                      $("#settingData").html(result); 
                      $("#setting_pag_flash").css("visibility", "hidden");                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
      }
  });
    //update mentor of current student
    $(document).on("click","#settingSave", function () {
       var mId = $("#assignMid").val();
       $("#setting_pag_flash").css("visibility", "visible");
       $("#setting_pag_flash").html(mId);
      var dataString = 'enroll=' + stenr + '&mid=' + mId;
       $.ajax({
             type: "POST",
             url: "/mentor/public/saveSettingMentor.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#setting_pag_flash").html(errorMessage);                         
                    }else {  
                      $("#setting_pag_flash").html(result); 
                                             
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
      }
  });

 });
});

///fetch mentor activity
$(document).ready(function(){
$("#trackFetch").click(function(){
     $("#track_pag_flash").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#track_pag_flash").css("visibility", "visible");
   var mid = $("#trackMid").val();
   // alert(mid);
   var dataString = 'mid=' + mid;
   $.ajax({
             type: "POST",
             url: "/mentor/public/fma.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
                 var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#track_pag_flash").html(errorMessage);
                        $("#track_pag_flash").css("visibility", "hidden");                          
                    }else {
                      $("#trackData").html(result); 
                      $("#track_pag_flash").css("visibility", "hidden");                         
                    }
            
           }
   });
 });
});

//contact a mentor by cr

$(document).ready(function(){
$("#contMentorSend").click(function(){
     $("#pag_flash_cc").html('<img  width="32" height="32" src="/mentor/style/comment.gif" />');
     $("#pag_flash_cc").css("visibility", "visible");
   var mid = $("#contMentorId").val();
   var mailSub = $("#contMentorSub").val();
   var mailBody = $("#contMentorBody").val();

   // alert(mid+enroll+mailSub+mailBody);
   var dataString = 'mid=' + mid + '&mSub=' + mailSub + '&mbody=' + mailBody;
   // var nextPage = +page+1;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/contactmentor.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#pag_flash_cc").html(errorMessage);                         
                    }else {
                      $("#pag_flash_cc").html(result);                          
                    }
                    $("#contMentorId").val(0);
                    $("#contMentorSub").val("");
                    $("#contMentorBody").val("");
            
      }
  });
});
});

//manage student
$(document).ready(function(){
$("#manageSubmit").click(function(){
     $("#manage_flash_cc").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#manage_flash_cc").css("visibility", "visible");
   var studenroll = $("#manageEnroll").val();

   // alert(mid+enroll+mailSub+mailBody);
   var dataString = 'enroll=' + studenroll;
   // var nextPage = +page+1;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/manageStudentAjax.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#manage_flash_cc").html(errorMessage);                         
                    }else {
                      $("#manageSudDetails").html(result); 
                      $("#manage_flash_cc").css("visibility", "hidden");                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
            
      }

  });


    $(document).on("click","#manage_delete", function () {
     $("#delete_pag_flash").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#delete_pag_flash").css("visibility", "visible");
    var student_enroll = $("#managehiddenenroll").val();
    // $("#setting_studEnroll").html("Student enroll number: "+stenr+"<br />");
    var dataString = 'enroll=' + student_enroll;
    // alert(dataString);
    $.ajax({
             type: "POST",
             url: "/mentor/public/deleteStudent.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#delete_pag_flash").html(errorMessage);                         
                    }else {
                      $("#delete_pag_flash").html(result); 
                      // $("#delete_pag_flash").css("visibility", "hidden");                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
      }
  });
    });




  $(document).on("click",".manage_popup", function () {
     $("#manage_pag_flash").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#manage_pag_flash").css("visibility", "visible");
    var stenr = this.id;
    // $("#setting_studEnroll").html("Student enroll number: "+stenr+"<br />");
    var dataString = 'enroll=' + stenr;
    // alert(dataString);
    $.ajax({
             type: "POST",
             url: "/mentor/public/manageStudentedit.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#manage_pag_flash").html(errorMessage);                         
                    }else {
                      $("#manageData").html(result); 
                      $("#manage_pag_flash").css("visibility", "hidden");                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
      }
  });
    //update mentor of current student
    $(document).on("click","#manageUpdateBtn", function () {

       var studname = $("#manageEditName").val();
       var studcourse = $("#manage_program_id").val();
       var studSem = $("#manage_sem_id").val();
       var studSession = $("#manage_session_id").val();
       var studemail = $("#manage_email").val();
       var studMentor = $("#manageAssignMid").val();
       var studMentorqtype = $("#mng_mt_qtype").val();

       $("#manage_pag_flash").css("visibility", "visible");
       // $("#manage_pag_flash").html(mId);
      var dataString = 'enroll=' + stenr + '&name=' + studname + '&prog=' + studcourse+ '&sem=' + studSem+ '&year=' + studSession+ '&email=' + studemail+ '&mentor=' + studMentor + '&mentorqtype=' + studMentorqtype;
      // alert(dataString);
       $.ajax({
             type: "POST",
             url: "/mentor/public/manageStudentUpdate.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#manage_pag_flash").html(errorMessage);                         
                    }else {  
                      $("#manage_pag_flash").html(result); 
                                             
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
      }
  });

 });
});


});
});

///add students
$(document).ready(function(){
$("#addStudSubmit").click(function(){
     $("#add_flash_cc").html('<img  width="32" height="32" src="/mentor/style/facebook.gif" />');
     $("#add_flash_cc").css("visibility", "visible");

       var studenroll = $("#addStudEnroll").val();
       var studadms = $("#addStudAdms").val();
       var studname = $("#addStudName").val();
       var studcourse = $("#add_program_id").val();
       var studSem = $("#add_sem_id").val();
       var studSession = $("#add_session_id").val();
       var studemail = $("#addStudEmail").val();
       var studMentor = $("#addStudMentorID").val();

     var dataString = 'enroll=' + studenroll + '&adms=' + studadms + '&name=' + studname + '&prog=' + studcourse+ '&sem=' + studSem+ '&year=' + studSession+ '&email=' + studemail+ '&mentor=' + studMentor;

   // $("#pag_flash").delay(400).fadeOut(400).html('<img src="../images/like_loader.gif" />');
   $.ajax({
             type: "POST",
             url: "/mentor/public/addStudent.php",
            data: dataString,
            cache: false,
            async: true,
            success: function(result){          
            
              var position=result.indexOf("||");
                    var warningMessage=result.substring(0,position);
                    if(warningMessage=='error'){
                      var errorMessage=result.substring(position+2);
                        $("#add_flash_cc").html(errorMessage);                         
                    }else {
                      $("#add_flash_cc").html(result);                         
                    }
                    // $("#contCrSub").val("");
                    // $("#contCrBody").val("");
            
      }
  });
});
});
