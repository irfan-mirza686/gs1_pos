$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.openSalePrint',function(e){
        e.preventDefault();
        var order_no = $(this).attr('data-OrderNo');
        window.open("/pos/print_invoice_pos/" + order_no, "_blank",
                            "scrollbars=1,resizable=1,height=500,width=500");
    })
    showSalesList();


    function showSalesList() {

        var table = $('.sales-table').DataTable({

            "ajax": {
                "url": '/sales/list',
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
                { data: 'order_no', name: 'order_no' },
                { data: 'customer', name: 'customer' },
                { data: 'total', name: 'total' },
                { data: 'paid_amount', name: 'paid_amount' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    }  /// END FUNCTION....


    // Customer Autocomplete......
    $("#searchCustomer").autocomplete({
        source: "/autocomplete/customer",
        minLength: 1,
        response: function (event, ui) {
            console.log(ui.content)
            if (ui.content.length === 0) {
                $('#createCustomerModal').modal({
                    backdrop: 'static'
                });
                $("#createCustomerModal").modal('show');
            }
        },
        select: function (event, ui) {
            var item = ui.item;
            if (item) {
                $("#customerName").val(item.customerName);
                $("#customerID").val(item.customerID);
                $("#searchCustomer").val(item.customerMobile);
                $("#mobile").val(item.customerMobile);
            }
        }
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
