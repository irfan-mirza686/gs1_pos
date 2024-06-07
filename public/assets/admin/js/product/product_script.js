$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showProductsList();

    var table; // Define table variable in a wider scope

    function showProductsList() {
        // Initialize DataTable and assign to the global 'table' variable
        table = $('.products-table').DataTable({
            ajax: {
                url: '/product/list',
                dataType: 'json',
                type: 'POST'
            },
            processing: true,
            serverSide: true,
            stateSave: true,
            destroy: true, // Use 'destroy' instead of 'bDestroy'
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'type', name: 'type' },
                { data: 'image', name: 'image' },
                { data: 'productnameen', name: 'productnameen' },
                { data: 'productnamear', name: 'productnamear' },
                { data: 'brand', name: 'brand' },
                { data: 'barcode', name: 'barcode' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    }

    // Function to handle column search
    $(document).on('keyup', '.column-search', function () {
        if (table) {
            var columnIndex = $(this).data('column');
            table.column(columnIndex).search(this.value).draw();
        }
    });

    // select product type before adding the product ...
    $(document).on('click', '#addNewProductBtn', function (e) {
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

    ///############## Display Messages ################/////////

    $(document).on('click', '#syncProductsBtn', function (e) {
        e.preventDefault();
        let btn = $(this);
        let btnVal = $(this).text();
        let url = $(this).attr('data-URL');
        let creating = ' synchronising...';

        $.ajax({
            type: 'GET',
            url: url,
            contentType: false,
            processData: false,
            beforeSend: function () {

                addContent(btn, creating);
            }, success: function (resp) {
                $("#sliderModal").modal('hide');
                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    let msgType = 'success';
                    let msgClass = 'bx bx-check-circle';
                    let message = resp.message;
                    showMsg(msgType, msgClass, message);
                    showProductsList();
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
    })

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
