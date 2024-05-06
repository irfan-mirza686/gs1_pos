$(document).ready(function(){

   $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

   $(document).on('click','#sendNoticeBtn',function(e){
    e.preventDefault();
    let formData = new FormData($('#NoticeNotificationForm')[0]);



    $.ajax({
        type: "POST",
        url: "/admin/notice/notification",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend:function(){

            $('#noticeSpinner').addClass('spinner-border spinner-border-sm');
        },
        success: function(resp){
          if(resp.status==200){
            $("#noticeSpinner").removeClass("spinner-border spinner-border-sm");
                $("#sms").modal('hide');
               
            
                
                Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: resp.message
                });
                 $('#NoticeNotificationForm').trigger("reset");
            }else if (resp.status==422) {
                console.log(resp)
            console.log(resp.error)
             $("#noticeSpinner").removeClass("spinner-border spinner-border-sm");
                $("#sms").modal('hide');
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: resp.error.message
            });
            $('#NoticeNotificationForm').trigger("reset");
          }else if (resp.status==500) {
            $("#noticeSpinner").removeClass("spinner-border spinner-border-sm");
                $("#sms").modal('hide');
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: resp.error
            });
        $('#NoticeNotificationForm').trigger("reset");
          }
        }
    });

   });

});