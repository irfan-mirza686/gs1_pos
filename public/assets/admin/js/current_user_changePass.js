$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#currentUserUpdatePassBtn', function (e) {
        e.preventDefault();
        const modal = $('#updateCurrentUserPassModal');
        $(modal).modal({
            backdrop: 'static'
        });
        $(modal).modal('show');
    });

    $(document).on('submit', '#currentUserChangePassForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#currentUserChangePassForm')[0]);
        let btn = $('.updateCurrentUserPassBtn');
        let btnVal = $('.updateCurrentUserPassBtn').text();
        let creating = 'Processing...';

        $.ajax({
            type: 'POST',
            url: '/update/password',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

                addContent(btn, creating);
            }, success: function (resp) {
                $("#updateCurrentUserPassModal").modal('hide');
                
                removeContent(btn, btnVal);

                

                if (resp.status === 200) {
                    $("#currentUserChangePassForm")[0].reset();
                    Lobibox.notify('default', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'center top',
                        size: 'mini',
                        msg: resp.message
                    });
                    showUsersList();
                }  else if (resp.status === 400) {
                    let msgType = 'warning';
                    let msgClass = 'bx bx-error';
                    $.each(resp.errors, function (key, value) {
                        showMsg(msgType, msgClass, value);
                    });
                } else if (resp.status === 422) {
                    let msgType = 'error';
                    let msgClass = 'bx bx-error';
                    let message = resp.message;
                    showMsg(msgType, msgClass, message);
                }
            }, error: function (resp) {
                let msgType = 'error';
                let msgClass = 'bx bx-error';
                let message = resp.statusText;
                showMsg(msgType, msgClass, message);
                removeContent(btn, btnVal);

            }
        });
    });

    ///############## Display Messages ################/////////

    function addContent(btn, creating) {
        $('#spinner').addClass('spinner-border spinner-border-sm');
        $(btn).text(creating);
    }
    function removeContent(btn, btnVal) {
        $("#spinner").removeClass("spinner-border spinner-border-sm");
        $(btn).text('');
        $(btn).text(btnVal);
    }

    function showMsg(msgType, msgClass, message) {
        Lobibox.notify(msgType, {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            icon: msgClass,
            msg: message
        });
    }

});