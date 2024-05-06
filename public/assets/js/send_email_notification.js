$(document).ready(function(){

   $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

   $(document).on('click','#sendEmailBtn',function(e){
    e.preventDefault();
    let formData = new FormData($('#emailNotificationForm')[0]);

    $.ajax({
        type: "POST",
        url: "/admin/email/notification",
        data: formData,
        contentType: false,
        processData: false,
        beforeSend:function(){

            $('#emailSpinner').addClass('spinner-border spinner-border-sm');
        },
        success: function(resp){
          if(resp.status==200){
            $("#emailSpinner").removeClass("spinner-border spinner-border-sm");
                $("#mail").modal('hide');
               
            
                
                Lobibox.notify('success', {
                    pauseDelayOnHover: true,
                    continueDelayOnInactiveTab: false,
                    position: 'top right',
                    icon: 'bx bx-check-circle',
                    msg: resp.message
                });
                 $('#emailNotificationForm').trigger("reset");
            }else if (resp.status==422) {
            console.log(resp.error.attachment)
             $("#emailSpinner").removeClass("spinner-border spinner-border-sm");
                $("#mail").modal('hide');
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: resp.error.attachment
            });
            $('#emailNotificationForm').trigger("reset");
          }else if (resp.status==500) {
            $("#emailSpinner").removeClass("spinner-border spinner-border-sm");
                $("#mail").modal('hide');
            Lobibox.notify('error', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: resp.error
            });
        $('#emailNotificationForm').trigger("reset");
          }
        }
    });

   });

});