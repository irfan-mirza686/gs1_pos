$(document).ready(function(){

   $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
   getSubscriberNotifications();
   getSubscriberNotice();

 var intervalId = window.setInterval(function(){
  /// call your function here
  // alert('oookk')
  console.log('ok')
  getSubscriberNotifications();
  getSubscriberNotice();
  
}, 60000);

 function getSubscriberNotifications(){
    $('#showSubscriberEmailNotifications').html("");
    $.ajax({
        type:'get',
         url:"email/notifications",
         
         
        success:function(response){
            $("#emailsCount").text(response.countNoti);
            $.each(response.subsNotification, function(key, item) {
                let str = item.message;
                let shortMsg = str.substring(0, 20);//get first 5 chars
               console.log(item) 
               $("#showSubscriberEmailNotifications").append('<a class="dropdown-item" href="javascript:;">\
                  <div class="d-flex align-items-center">\
                    <div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>\
                    </div>\
                    <div class="flex-grow-1 readEmailNotification">\
                      <h6 class="msg-name"> '+item.subject+' <span class="msg-time float-end readNotification" >'+item.time+'\
                      </span></h6>\
                      <p class="msg-info">'+shortMsg+'...</p>\
                    </div>\
                  </div>\
                </a>');
            });
            
        },error:function(){
                console.log("Error");
            }
    });
 }
 /******************************************************/
 $(document).on('click','#markEmailNotifications',function(){
   
  
    $.ajax({
        type:'post',
         url:"mark/email/notifications",
         success:function(response){
            getSubscriberNotifications();
            Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: response.message
                });
         }
     });
 });
 /******************************************************/

 function getSubscriberNotice(){
    $('#showSubscriberNotice').html("");
    $.ajax({
        type:'get',
         url:"notice/notifications",
         
         
        success:function(response){
            $("#noticeCount").text(response.countSubscriberNotice);
            $.each(response.subscriberNotices, function(key, item) {
                let str = item.message;
                let shortMsg = str.substring(0, 20);//get first 5 chars
               console.log(item) 
               $("#showSubscriberNotice").append('<a class="dropdown-item" href="javascript:;">\
                  <div class="d-flex align-items-center">\
                    <div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>\
                    </div>\
                    <div class="flex-grow-1 readEmailNotification">\
                      <h6 class="msg-name"> '+shortMsg+' <span class="msg-time float-end readNotification" >'+item.time+'\
                      </span></h6>\
                    </div>\
                  </div>\
                </a>');
            });
            
        },error:function(){
                console.log("Error");
            }
    });
 }
 /******************************************************/
 $(document).on('click','#markNotices',function(){
   
  
    $.ajax({
        type:'post',
         url:"mark/notice/notifications",
         success:function(response){
            getSubscriberNotice();
            Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: response.message
                });
         }
     });
 });

 /******************************************************/

   $("#updateSubscriberPass").click(function(e){

    e.preventDefault();
    

    var current_pass = $("input[name=current_pass]").val();
    var new_pass = $("input[name=new_pass]").val();
    var confirm_pass = $("input[name=confirm_pass]").val();
    if (current_pass=='') {
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-error',
            msg: 'Enter Current Password.'
        });
        return false;
    }else if (new_pass=='') {
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-error',
            msg: 'Enter New Password.'
        });
        return false;
    }else if (confirm_pass=='') {
        Lobibox.notify('warning', {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: 'bx bx-error',
            msg: 'Enter Confirm Password.'
        });
        return false;
    }
    
    updateSubscriberPass(current_pass,new_pass,confirm_pass);
    
});

    function updateSubscriberPass(current_pass,new_pass,confirm_pass) {


        $.ajax({
         url:"/user/update/password",
         type:'POST',
         data:{         
            "current_pass":current_pass, 
            "new_pass":new_pass,
            "confirm_pass":confirm_pass
        },
        beforeSend:function(){
            $('#spinner').addClass('spinner-border spinner-border-sm');
        },
        success:function(response){
            console.log(response)
            $("#spinner").removeClass("spinner-border spinner-border-sm");
            $("#updateSubscriberPassModal").modal('hide');
            $("input[name=current_pass]").val('');
            $("input[name=new_pass]").val('');
            $("input[name=confirm_pass]").val('');
            if(response.status==200){
                
                
                Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: response.message
                });

            }else if(response.status==422){

              Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: response.message
            });
          }else if(response.status==401){
              Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: response.message
            });
          }
      },
      error:function(error){
        console.log(error)
          Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: error.statusText
            });
      }
  });
    }



});