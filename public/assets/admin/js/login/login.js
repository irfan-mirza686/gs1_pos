$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /// Check Email Form ....
    $(document).on('submit', '#checkLoginEmailForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#checkLoginEmailForm')[0]);
        let btn = $('.loginEmailBtn');
        let btnVal = $('.loginEmailBtn').text();
        let url = $("#checkLoginEmailForm").attr('action');
        let creating = ' Processing...';
        let email = formData.get('email');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

                $(btn).text(creating);
                $(btn).prepend('<i class="fa fa-spinner fa-spin"></i>');
                $(btn).attr("disabled", 'disabled');
            }, success: function (resp) {
                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");

                if (resp.status === 200) {
                    $(".memberLoginActivity").html("");
                    const modal = $("#activityPopup");
                    $(".memberLoginActivity").append('<option value="">-select-</option>');
                    $.each(resp.data, function (i, item) {
                        $(".memberLoginActivity").append('<option value="' + item.cr_activity + '" >&nbsp;&nbsp;&nbsp;' + item.cr_activity + '</option>');
                    });
                    modal.find('input[name=email]').val(email);
                    modal.modal('show');
                }
            }, error: function (xhr, textStatus, errorThrown) {

                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");
                $.notify(xhr.responseJSON.error, { globalPosition: 'top right', className: 'error' });
                return false;
            }
        });
    });

    // Activity FOrm submit....
    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#activityForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#activityForm')[0]);
        let btn = $('.loginActivityBtn');
        let btnVal = $('.loginActivityBtn').text();
        let url = $("#activityForm").attr('action');
        let creating = ' Processing...';
        let email = formData.get('email');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {

                $(btn).text(creating);
                $(btn).prepend('<i class="fa fa-spinner fa-spin"></i>');
                $(btn).attr("disabled", 'disabled');
            }, success: function (resp) {
                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");

                if (resp.status === 200) {
                    $(".memberLoginActivity").html("");
                    const modal = $("#activityPopup");
                    modal.modal('hide');

                    swalWithBootstrapButtons.fire(
                        'Done!',
                        resp.message,
                        'success'
                    )
                    setTimeout(function() {
                        window.location.href = "dashboard";
                    }, 2000);
                }
            }, error: function (xhr, textStatus, errorThrown) {

                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");
                $.notify(xhr.responseJSON.error, { globalPosition: 'top right', className: 'error' });
                return false;
            }
        });
    });
});
