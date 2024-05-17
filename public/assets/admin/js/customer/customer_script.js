$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    showCustomersList();

    function showCustomersList() {

        var table = $('.customers-table').DataTable({

            "ajax": {
                "url": '/customers/list',
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
                { data: 'name', name: 'name' },
                { data: 'mobile', name: 'mobile' },
                { data: 'address', name: 'address' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    }  /// END FUNCTION....

    $(document).on('submit', '#registerCustomerForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#registerCustomerForm')[0]);
        let btn = $('.saveCustomerBtn');
        let btnVal = $('.saveCustomerBtn').text();
        let url = $("#registerCustomerForm").attr('action');
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
                $("#createCustomerModal").modal('hide');
                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    $(".delivery").html("");
                    // console.log(resp.customer)
                    $("#registerCustomerForm")[0].reset();
                    $("#customerName").val(resp.customer.name);
                    $("#customerID").val(resp.customer.id);
                    $("#searchCustomer").val(resp.customer.mobile);
                    $("#mobile").val(resp.customer.mobile);
                    $("#vat_no").val(resp.customer.vat);
                    // const addresses = JSON.parse(resp.customer.address);
                $(".delivery").append('<option value="">Choose...</option>');
                // console.log(resp.customer)
                        $.each(resp.customer.customer_address, function (i, val) {
                            // console.log(val)


                            $(".delivery").append('<option value="' + val.address + '">&nbsp;&nbsp;&nbsp;' + val.address + '</option>');

                        });

                    let msgType = 'success';
                    let msgClass = 'bx bx-check-circle';
                    let message = resp.message;
                    showMsg(msgType, msgClass, message);
                } else if (resp.status === 400) {
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
