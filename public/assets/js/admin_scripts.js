$(document).ready(function(){

   $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
     

   $("#updatePass").click(function(e){

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
    
    updateAdminPass(current_pass,new_pass,confirm_pass);
    
});

    function updateAdminPass(current_pass,new_pass,confirm_pass) {


        $.ajax({
         url:"/admin/update/password",
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
            $("#updateAdminPassModal").modal('hide');
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
      }
  });
    }



});