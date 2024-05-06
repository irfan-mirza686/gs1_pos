$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showUsersList();

    $(document).on('click', '.updateUserPass', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var code = $(this).attr('data-Code');
        const modal = $('#updateUserPassModal');
        $(modal).modal({
            backdrop: 'static'
        });
        modal.find('form').attr('action', url);
        modal.find('input[name=password]').val(code)
        $(modal).modal('show');
    });

    $(document).on('submit', '#userUpdatePassForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#userUpdatePassForm')[0]);
        let btn = $('.updateUserPassBtn');
        let btnVal = $('.updateUserPassBtn').text();
        let url = $("#userUpdatePassForm").attr('action');
        let creating = 'Processing...';

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

                addContent(btn, creating);
            }, success: function (resp) {
                $("#updateUserPassModal").modal('hide');
                removeContent(btn, btnVal);

                

                if (resp.status === 200) {
                    Lobibox.notify('default', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        position: 'center top',
                        size: 'mini',
                        msg: resp.message
                    });
                    showUsersList();
                }  else if (resp.status === 422) {
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


    function showUsersList() {
        
        var table = $('.admins-table').DataTable({

            "ajax": {
                "url": '/users/list',
                "dataType": "json",

                "type": "POST"
                // "contentType": "application/json; charset=utf-8"
            },
            processing: true,
            serverSide: true,
            stateSave: true,
            "bDestroy": true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'image', name: 'image'},
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'group_id', name: 'group' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    }  /// END FUNCTION....

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