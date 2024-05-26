$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showStockTransferRequestList();

    var table; // Define table variable in a wider scope

    function showStockTransferRequestList() {
        // Initialize DataTable and assign to the global 'table' variable
        table = $('.stock-transfer-table').DataTable({
            ajax: {
                url: '/stock/transfer/list',
                dataType: 'json',
                type: 'POST'
            },
            processing: true,
            serverSide: true,
            stateSave: true,
            destroy: true, // Use 'destroy' instead of 'bDestroy'
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'request_no', name: 'request_no' },
                { data: 'gln', name: 'gln' },
                { data: 'date_time', name: 'date_time' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // Function to handle column search
    $(document).on('keyup', '.column-search', function() {
        if (table) {
            var columnIndex = $(this).data('column');
            table.column(columnIndex).search(this.value).draw();
        }
    });

    // select product type before adding the product ...
    $(document).on('click','#addNewProductBtn',function(e){
        e.preventDefault();
       var modal = $("#createProductModal");
        var url = $(this).attr('data-URL');

        $(modal).modal({
            backdrop: 'static'
        });
        modal.find('.modal-title').text("Create Product")

        modal.find('form').attr('action', url)
        $(modal).modal('show');
    })

    $(document).on('click','.edit',function(e){
        e.preventDefault();
        var StockDetails = $(this).attr('data-StockDetails');
        var data = JSON.parse(StockDetails);
        console.log(data.items)
    })

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
