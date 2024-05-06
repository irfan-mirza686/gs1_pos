$(document).ready(function () {
    $("#appendProducts").select2();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.getProductItems', function (e) {
        e.preventDefault();
        let productID = $(this).attr('data-ProductID');
        let productName = $(this).attr('data-ProductName');

        $("#productName").text(productName)
        loadProductItems(productID);


    })

    $(document).on('click','.updateProductItemSellingPrice',function(e){
        e.preventDefault();
        let productID = $(this).attr('data-ProductID');
        let itemPrice = $(this).attr('data-itemPrice');

        let type = $(this).attr('data-Type');

        let url = $(this).attr('href');

        const modal = $('#updateItemPriceModal');
        $('#updateItemPriceModal').modal({
            backdrop: 'static'
        });
        modal.find('.modal-title').text("Update Item Selling Price")
        modal.find('input[name=selling_price]').val(itemPrice)
        modal.find('input[name=product_id]').val(productID)
        modal.find('select[name=type]').val(type)
        modal.find('form').attr('action', url)
        modal.find('.saveItemPriceBtn').text('Update');

        $(modal).modal('show');
    })

    // Save Product .....
    $(document).on('submit', '#updateItemPriceForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#updateItemPriceForm')[0]);
        let btn = $('.saveItemPriceBtn');
        let btnVal = $('.saveItemPriceBtn').text();
        let url = $("#updateItemPriceForm").attr('action');
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
                $("#unitModal").modal('hide');
                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    $("#updateItemPriceModal").modal('hide');
                    let msgType = 'success';
                    let msgClass = 'bx bx-check-circle';
                    let message = resp.message;
                    showMsg(msgType, msgClass, message);
                    loadProductItems(resp.productID)
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

    ///// Load Product Items....
    function loadProductItems(productID) {
        var table = $('.product-items-table').DataTable({

            "ajax": {
                "url": '/product/items_list',
                "dataType": "json",

                "type": "POST",
                data:{productID:productID}
                // "contentType": "application/json; charset=utf-8"
            },
            processing: true,
            serverSide: true,
            stateSave: true,
            "bDestroy": true,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'type', name: 'type' },
                { data: 'supplier', name: 'supplier' },
                { data: 'barcode', name: 'barcode' },
                { data: 'barcode_2', name: 'barcode_2' },
                { data: 'qty', name: 'qty' },
                { data: 'price', name: 'price' },
                { data: 'selling_price', name: 'selling_price' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    }

    // loadProducts();
    function loadProducts() {
        $("#appendProducts").html('');

        $("#appendProducts").val(null).empty().select2('destroy');
        $.ajax({

            type: 'GET',
            url: '/load/products',
            beforeSend: function () {
                $("body").addClass("loading");

            }, success: function (resp) {
                $("body").removeClass("loading");

                $(".appendProducts").append('<option value="" >-select-</option>');
                $.each(resp.products, function (key, value) {


                    $(".appendProducts").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $("#appendProducts").select2();
            }

        })
    }

    ///############## Display Messages ################/////////

    function addContent(btn, creating) {
        $('#item-spinner').addClass('spinner-border spinner-border-sm');
        $(btn).text(creating);
    }
    function removeContent(btn, btnVal) {
        $("#item-spinner").removeClass("spinner-border spinner-border-sm");
        $(btn).text('');
        $(btn).text(btnVal);
    }

    function showMsg(msgType, msgClass, message) {
        Lobibox.notify(msgType, {
            pauseDelayOnHover: true,
            continueDelayOnInactiveTab: false,
            position: 'top right',
            size:'mini',
            icon: msgClass,
            msg: message
        });
    }

});
