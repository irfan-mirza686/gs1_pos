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
                { data: 'gln_from', name: 'gln_from' },
                { data: 'date_time', name: 'date_time' },
                { data: 'status', name: 'status' },
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

    ////// Add Stock Transfer /////////////
    $(document).on('click', '#addEditStockTransfer', function (e) {
        e.preventDefault();
        var modal = $("#stockTransferModal");
        var url = $(this).attr('data-URL'); $(modal).modal({
            backdrop: 'static'
        });
        // modal.find('.modal-title').text("Create Product")

        modal.find('form').attr('action', url)
        $(modal).modal('show');
    })

    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        var StockDetails = $(this).attr('data-StockDetails');
        var data = JSON.parse(StockDetails);
        var items = data.items;
        // console.log(data.items)
    })

    //////////////
    /// SUbmit Add Brand Form ....
    $(document).on('submit', '#stockTransferForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#stockTransferForm')[0]);
        let btn = $('.stockTransferSaveBtn');
        let btnVal = $('.stockTransferSaveBtn').text();
        let url = $("#stockTransferForm").attr('action');
        let creating = ' Processing...';

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

                addContent(btn, creating);
            }, success: function (resp) {
                $("#stockTransferModal").modal('hide');
                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    swalWithBootstrapButtons.fire(
                        'Done!',
                        resp.message,
                        'success'
                    )
                    $("#selectedProductsTable").html("");
                    showStockTransferRequestList();
                    $("#stockTransferForm")[0].reset();
                } else if (resp.status === 400) {

                    $.each(resp.errors, function (key, value) {
                        $.notify(value, { globalPosition: 'top right', className: 'error' });
                    });
                } else if (resp.status === 422) {

                }
            }, error: function (xhr, textStatus, errorThrown) {
                removeContent(btn, btnVal)
                swalWithBootstrapButtons.fire(
                    'ERROR!',
                    xhr.responseText,
                    'error'
                )
                return false;
            }
        });
    });
    /////////////


    $('#productSearch').on('input', function () {
        var searchQuery = $(this).val().trim();

        if (searchQuery.length > 0) {
            $.ajax({
                url: '/search/products',
                method: 'GET',
                data: { query: searchQuery },
                success: function (response) {
                    displayResults(response);
                }
            });
        } else {
            $('#searchResults').empty();
        }
    });

    function displayResults(products) {
        var resultsContainer = $('#searchResults');
        resultsContainer.empty();

        if (products.length > 0) {
            products.forEach(function (product) {
                var listItem = $('<a href="#" class="list-group-item list-group-item-action"></a>');
                listItem.text(product.productnameenglish);
                listItem.data('product', product);
                listItem.on('click', function (e) {
                    e.preventDefault();
                    addProductToTable($(this).data('product'));
                    resultsContainer.empty();
                });
                resultsContainer.append(listItem);
            });
        } else {
            resultsContainer.append('<p class="text-muted list-group-item">No products found.</p>');
        }
    }

    function addProductToTable(product) {
        var tableBody = $('#selectedProductsTable tbody');
        var existingRow = tableBody.find('tr').filter(function () {
            return $(this).find('td').eq(1).text() === product.barcode;
        });

        if (existingRow.length > 0) {
            var quantityInput = existingRow.find('input[name="quantity"]');
            var currentQuantity = parseInt(quantityInput.val());
            var newQuantity = currentQuantity + 1;  // or you can increment as per your requirement
            quantityInput.val(newQuantity);
        } else {
            var newRow = $('<tr></tr>');
            newRow.append('<td><input type="hidden" class="form-control" name="product_id[]" value="'+ product.productID +'"><input type="hidden" class="form-control" name="productName[]" value="'+ product.productnameenglish +'"><img src="' + product.image + '" alt="' + product.productnameenglish + '" style="width: 50px;"> ' + product.productnameenglish + '</td>');
            newRow.append('<td><input type="hidden" class="form-control" name="barcode[]" value="'+ product.barcode +'">' + product.barcode + '</td>');
            newRow.append('<td><input type="hidden" class="form-control" name="product_type[]" value="'+ product.product_type +'"><input type="number" class="form-control" name="quantity[]" value="1"></td>');
            newRow.append('<td><button type="button" class="btn btn-danger btn-sm">Delete</button></td>');
            newRow.find('button').on('click', function () {
                newRow.remove();
            });
            tableBody.prepend(newRow); // Use prepend to add the row to the top of the table
        }
    }

    ///////////////// View Product Information ////////////////////////
    $(document).on('click', '.viewProductInfo', function (e) {
        e.preventDefault();
        var ProductInfo = JSON.parse($(this).attr('data-ProductInfo'));
        var modal = $("#productInfoModal");
        $('#productnameenglish').text(ProductInfo.productnameenglish);
        $('#BrandName').text(ProductInfo.BrandName);
        $('#unit').text(ProductInfo.unit);
        $('#purchase_price').text(ProductInfo.purchase_price);
        $('#selling_price').text(ProductInfo.selling_price);
        $('#details_page').text(ProductInfo.details_page);
        $('#quantity').text(ProductInfo.quantity);
        $('#size').text(ProductInfo.size);
        $('#barcode').text(ProductInfo.barcode);

        modal.modal('show')
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
