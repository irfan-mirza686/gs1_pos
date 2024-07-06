$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function playSound() {
        var audio = new Audio('/sounds/failed.mp3');
        audio.play();
    }
    // $('#barcode').prop('disabled', true);
    // $('#searchCustomer').prop('disabled', true);

    // Click on New or Existing Customer Checkbox ....
    $(document).on('click', '#new_existing_customer', function () {
        if ($(this).is(':checked')) {
            // console.log('Checkbox is checked.');
            $(this).prop('checked', true);
            $(this).val(1);
            $('#searchCustomer').prop('disabled', false);
            $("#customerType").text('Customer Name');
            $('#customerName').val('');
            $('#customerID').val('');
        } else {
            // console.log('Checkbox is not checked.');
            $(this).prop('checked', false);
            $(this).val(0);
            $('#searchCustomer').prop('disabled', true);
            $("#customerType").text('Customer Type');
            var customerName = $(this).attr('data-CustomerName');
            var customerID = $(this).attr('data-CustomerID');
            $('#customerName').val(customerName);
            $('#customerID').val(customerID);
            $('#vat_no').val('');
            $('#mobile').val('');
        }

    })


    function toggleBarcodeInput() {
        const salesLocationValue = $('#salesLocation').val();
        const deliveryValue = $('#delivery').val();
        // console.log(salesLocationValue)
        // console.log(deliveryValue)

        if (salesLocationValue) {
            $('#barcode').prop('disabled', false);
        } else {
            $('#barcode').prop('disabled', true);
        }
    }

    // Check the dropdown values on change
    $('#salesLocation, #delivery').change(function () {
        toggleBarcodeInput();
    });

    // function playNotFoundSound() {
    //     var audio = new Audio('/sound/not_found.mp3');
    //     audio.play();
    // }

    // $('input#barcode').keypress(function () {
    //     console.log($(this).val())
    // })
    var isLoading = false;


    var typingTimer;
    var doneTypingInterval = 1000; // 1 second

    // Scan Barcode ...
    $(document).on('change keyup', '#barcode', function (e) {
        e.preventDefault();

        clearTimeout(typingTimer);

        typingTimer = setTimeout(afterScanedBarcode, doneTypingInterval);
    })

    function afterScanedBarcode() {
        var isLoading = false;
        if (!isLoading) {
            var barcode = $("#barcode").val();
            isLoading = true;
            if (barcode.length > 0) {


                $.ajax({
                    url: '/find/product/by/barcode',
                    method: 'GET',
                    data: {
                        barcode: $("#barcode").val()
                    },
                    async: true,
                    beforSend: function () {
                        $('.barcodeLoader').removeClass('d-none');
                        // $('.barcodeLoader').show();
                    },
                    success: function (resp) {
                        // console.log(resp)
                        // console.log(resp.prodArray)
                        if (resp.status == 404 || resp.status == 422) {
                            // playNotFoundSound();
                            $('.barcodeLoader').addClass('d-none');
                            // $('.barcodeLoader').hide();
                            $("#barcode").val('');
                            var msgType = 'error';
                            var position = 'bottom left';
                            var msgClass = 'bx bx-check-circle';
                            var message = resp.message;
                            var sound = 'sound6';
                            showMsg(msgType, msgClass, message);

                            return false;
                        }
                        var check_value = 0;
                        var table = $('#otherProductsBody');
                        var exist;
                        let updateQty = 1;
                        $(table).find("tr").each(function () {
                            check_value = $(this).data('barcode');
                            // console.log(resp.prodArray)
                            if (resp.prodArray && check_value == barcode) {

                                let getExistingQty = $(this).closest("tr").find(
                                    "input.quantity").val();

                                updateQty = parseInt(getExistingQty) + 1;
                                var existItemPriceIncrease = resp.prodArray.price;


                                if (updateQty > resp.prodArray.quantity) {
                                    updateQty = $(this).closest("tr").find(
                                        "input.productQty").val();
                                    $(".barcodeLoader").hide();
                                    $("#barcode").val('');
                                    var msgType = 'error';
                                    var position = 'bottom left';
                                    var msgClass = 'bx bx-check-circle';
                                    var message = resp.prodArray.productName + ' Product is ' + resp.prodArray.quantity + ' remaining!';
                                    var sound = 'sound5';
                                    showMsg(msgType, msgClass, message);
                                } else {
                                     existItemPriceIncrease = updateQty * resp.prodArray.price;
                                     var calculateVat = (parseInt(existItemPriceIncrease) * resp.prodArray.vat) / 100;
                                     var newSingleTotal = parseInt(existItemPriceIncrease) + parseInt(calculateVat);
                                    $(this).closest("tr").find("input.quantity")
                                        .val(updateQty);
                                        $(this).closest("tr").find("input.vat_total")
                                        .val(calculateVat);

                                        $(this).closest("tr").find("input.single_total")
                                        .val(newSingleTotal);
                                }

                                totalPurchaseAmount();

                                // Calculate Price with Updated Qty....
                                // var price = $(this).closest("tr").find(
                                //     "input.price").val();
                                // var vat = $(this).closest("tr").find(
                                //     "input.vat").val();
                                // var discount = $(this).closest("tr").find(
                                //     "input.discount").val();
                                // var $row = $(this).closest("tr");
                                // rowCalculation(updateQty, price, discount, vat, $row)


                                exist = true;
                            }
                        });
                        if (exist) {
                            // playSound();
                            // console.log("ID exist")
                            $(".barcodeLoader").hide();
                            $("#barcode").val('');
                            var msgType = 'warning';
                            var position = 'bottom left';
                            var msgClass = 'bx bx-check-circle';
                            var message = 'Product Already Exist in list';
                            var sound = 'sound5';
                            showMsg(msgType, msgClass, message);

                            return false;
                        } else {
                            playSound();
                            let rowCount = $('#otherProductsBody tr').length;
                            let count = rowCount + 1;
                            // console.log('total rows ' + rowCount)
                            if (count > 0) {
                                $("#invoiceSubmitBtn").removeClass("disabled");
                            }
                            // console.log(resp.prodArray)
                            // amount = resp.product.price;
                            amount = resp.prodArray.price;

                            $('#otherProductsBody').append('<tr class="delete_add_more_item" data-barcode="' + barcode + '" id="delete_add_more_item">\
                                    <td width="15%">\
                                    <input type="text" name="barcode[]" value="'+ barcode + '" class="form-control form-control-sm rounded-0 barcode text-start" readonly><input type="hidden" value="' + resp.prodArray.product_id + '" name="product_id[]" class="productID"><input type="hidden" value="' + resp.prodArray.product_type + '" name="product_type[]">\
                                    </td>\
                                    <td width="20%">\
                                    <input type="text" name="description[]" value="'+ resp.prodArray.productName + '" class="form-control form-control-sm rounded-0 description text-start" readonly>\
                                    </td>\
                                    <td width="10%">\
                                    <input type="text" name="price[]" value="'+ resp.prodArray.price + '" class="form-control form-control-sm rounded-0 price text-end">\
                                    </td>\
                                    <td width="10%">\
                                    <input type="text" name="quantity[]" value="'+ updateQty + '" class="form-control form-control-sm rounded-0 quantity text-center"><input type="hidden" value="' + resp.prodArray.quantity + '"  class="productQty">\
                                    </td>\
                                    <td width="10%">\
                                    <input type="text" name="discount[]" value="'+ resp.prodArray.disc + '" class="form-control form-control-sm rounded-0 discount text-end">\
                                    </td>\
                                    <td width="10%">\
                                    <input type="text" name="vat[]" value="'+ resp.prodArray.vat + '" class="form-control form-control-sm rounded-0 vat text-end"><input type="hidden" name="vat_total[]" data-vatAmount="' + resp.prodArray.vat_amount + '" value="' + resp.prodArray.vat_amount + '" class="form-control form-control-sm rounded-0 vat_total text-end">\
                                    </td>\
                                    <td width="15%">\
                                    <input type="text" name="single_total[]" value="'+ resp.prodArray.total_with_vat + '" class="form-control form-control-sm rounded-0 single_total text-end" readonly>\
                                    </td>\
                                    <td  class="mt-2 text-center" width="10%"><i class="btn btn-danger rounded-5 shadow btn-sm lni lni-close remove_button"></i> </td>\
                                    </tr>'
                            );
                            $(".barcodeLoader").hide();
                            $("#barcode").val('');
                            totalPurchaseAmount();
                        }
                    },
                    error: function (resp) {
                        $(".barcodeLoader").hide();
                        $("#barcode").val('');
                        var msgType = 'error';
                        var position = 'bottom left';
                        var msgClass = 'bx bx-check-circle';
                        var message = resp.statusText;
                        showMsg(msgType, msgClass, message);
                    },
                    complete: function () {
                        isLoading = false;
                    }
                }); //  END AJAX....
            }
        }
    }

    function rowCalculation(quantity, price, discount, vat, $row) {
        // console.log("quantity " + quantity)
        // console.log("price " + price)
        // console.log("vat " + vat)
        // console.log("discount " + discount)

        // var total = (quantity * price);
        // var afterVatTotal = ((total - discount) * vat) / 100;
        // var minusDiscount = (parseInt(total) + parseInt(afterVatTotal)) - discount;
        // $row.find("input.single_total")
        //     .val(minusDiscount);
        // $row.find("input.net_vat").val(
        //     minusDiscount);
        // $row.find("input.vat_total").val(afterVatTotal);
        totalPurchaseAmount();
    }

    $(document).on('keyup click', '.quantity', function (e) {
        e.preventDefault();
        var productQty = $(this).closest("tr").find("input.productQty").val();
        var quantity = $(this).closest("tr").find("input.quantity").val();
        var productID = $(this).closest("tr").find("input.productID").val();
        var vat_total = $(this).closest("tr").find("input.vat_total").val();
        var single_total = $(this).closest("tr").find("input.single_total").val();
        // var price = $(this).closest("tr").find("input.price").val();
        // var vat = $(this).closest("tr").find("input.vat").val();
        // var discount = $(this).closest("tr").find("input.discount").val();
        var $row = $(this).closest("tr");
        // var sendQty = 1;

        clearTimeout(typingTimer);
        // if (parseInt(quantity) > parseInt(productQty)) {
        //     sendQty = productQty;
        // } else {
        //     sendQty = quantity;
        // }
        // console.log("productQty : " + productQty)
        // console.log("quantityReq : " + quantity)
        // console.log("sendQty : " + sendQty)

        typingTimer = setTimeout(function () {
            // checkProductQty(quantity, productQty, sendQty, productID, price, discount, vat, $row);
            checkProductQty(quantity, productQty, productID,vat_total,single_total,$row);
        }, doneTypingInterval);
    })



    function checkProductQty(quantity, productQty, productID,vat_total,single_total,$row) {
        $.ajax({
            url: "/check-prodcut-stock",
            type: 'GET',
            data: {
                quantity: quantity,
                productID: productID
            },
            success: function (response) {
                // console.log(response);
                if (response.error == true) {
                    $row.find("input.quantity").val(productQty);
                    $row.find("input.vat_total").val(vat_total);
                    $row.find("input.single_total").val(single_total);
                    // rowCalculation(sendQty, price, discount, vat, $row)
                    totalPurchaseAmount();
                    var msgType = 'error';
                    var msgClass = 'bx bx-check-circle';

                    var sound = 'sound6';
                    showMsg(msgType, msgClass, response.message);

                }
            }
        });
    }


    function getAllItems() {
        $('.display-itmes').html("");
        $('.display-total').html("");
        // Get all rows with the class 'delete_add_more_item'
        var rows = $('#otherProductsBody .delete_add_more_item');

        // Initialize an array to hold the row data
        var rowDataArray = [];

        // Iterate over each row and extract data
        rows.each(function () {
            var rowData = {
                barcode: $(this).find('input[name="barcode[]"]').val(),
                productId: $(this).find('input[name="product_id[]"]').val(),
                productType: $(this).find('input[name="product_type[]"]').val(),
                description: $(this).find('input[name="description[]"]').val(),
                price: $(this).find('input[name="price[]"]').val(),
                quantity: $(this).find('input[name="quantity[]"]').val(),
                discount: $(this).find('input[name="discount[]"]').val(),
                vat: $(this).find('input[name="vat[]"]').val(),
                vatTotal: $(this).find('input[name="vat_total[]"]').data('vatamount'),
                singleTotal: $(this).find('input[name="single_total[]"]').val()
            };

            // Push the row data to the array
            rowDataArray.push(rowData);
        });


        // Do something with the rowDataArray
        if (rowDataArray.length <= 0) {
            return false;
        }
        $.each(rowDataArray, function (i, res) {
            $('.display-itmes').append('<div class="d-flex justify-content-between">\
                <div>'+ res.quantity + ' x ' + res.description + '</div>\
                <div>'+ res.singleTotal + '</div>\
                </div>');
        });
        const totalSum = rowDataArray.reduce((sum, product) => {
            return sum + parseFloat(product.singleTotal);
        }, 0);
        $('.display-total').append('<hr><div class="d-flex justify-content-between">\
                                    <div>Subtotal</div>\
                                    <div>'+ totalSum + '</div>\
                                </div>');
        $("#amountDue").val(totalSum);

        // For example, if you want to display it in an alert
        // console.log(JSON.stringify(rowDataArray, null, 2));
    };
    $('.payment-method button').on('click', function (e) {
        e.preventDefault();
    })
    $('.number-pad button').on('click', function (e) {
        e.preventDefault();
        // ($(this).text() == '0') ? '' :
        if ($(this).attr('id') === 'backButton') {
            let currentAmount = $('#amountReceived').val();
            let newAmount = currentAmount.slice(0, -1);
            $('#amountReceived').val(newAmount);

            if (newAmount === "") {
                $('#changeAmount').val("");
            } else {
                let amountDue = parseFloat($('#amountDue').val());
                let amountReceived = parseFloat(newAmount);
                if (amountReceived < amountDue) {
                    $("#invoiceSubmitBtn").addClass("disabled");
                } else {
                    $("#invoiceSubmitBtn").removeClass("disabled");
                }
                if (!isNaN(amountDue) && !isNaN(amountReceived)) {
                    let change = amountReceived - amountDue;
                    $('#changeAmount').val(change.toFixed(2));
                }
            }
        } else {
            let currentAmount = $('#amountReceived').val();
            let newAmount = currentAmount + $(this).text();
            $('#amountReceived').val(newAmount);

            let amountDue = parseFloat($('#amountDue').val());
            let amountReceived = parseFloat(newAmount);


            if (amountReceived < amountDue) {
                $("#invoiceSubmitBtn").addClass("disabled");
            } else {
                $("#invoiceSubmitBtn").removeClass("disabled");
            }

            if (!isNaN(amountDue) && !isNaN(amountReceived)) {
                let change = amountReceived - amountDue;
                $('#changeAmount').val(change.toFixed(2));
            }
        }
    });







    // $(document).on('change keyup', '#barcode', function (evt) {
    //     evt.preventDefault(); // prevent default click action
    //     let barcode = $("#barcode").val();


    //     if (!isLoading) {
    //         isLoading = true;
    //         var time = 500;
    //         setTimeout(function () {
    //             $.ajax({
    //                 url: '/find/product/by/barcode',
    //                 method: 'GET',
    //                 data: {
    //                     barcode: $("#barcode").val()
    //                 },
    //                 async: true,
    //                 beforSend: function () {
    //                     $('.barcodeLoader').removeClass('d-none');
    //                     // $('.barcodeLoader').show();
    //                 },
    //                 success: function (resp) {
    //                     if (resp.status == 404) {
    //                         // playNotFoundSound();
    //                         $('.barcodeLoader').addClass('d-none');
    //                         // $('.barcodeLoader').hide();
    //                         $("#barcode").val('');
    //                         var msgType = 'error';
    //                         var position = 'bottom left';
    //                         var msgClass = 'bx bx-check-circle';
    //                         var message = resp.message;
    //                         var sound = 'sound6';
    //                         showMsg(msgType, position, msgClass, message, sound);

    //                         return false;
    //                     }
    //                     var check_value = 0;
    //                     var table = $('#otherProductsBody');
    //                     var exist;
    //                     let updateQty = 1;
    //                     $(table).find("tr").each(function () {
    //                         check_value = $(this).data('barcode');
    //                         if (resp.product && check_value == resp.product.barcode) {
    //                             let getExistingQty = $(this).closest("tr").find(
    //                                 "input.quantity").val();
    //                             updateQty = parseInt(getExistingQty) + 1;
    //                             $(this).closest("tr").find("input.quantity")
    //                                 .val(updateQty);
    //                             // Calculate Price with Updated Qty....
    //                             var price = $(this).closest("tr").find(
    //                                 "input.price").val();


    //                             var total = (updateQty * price);

    //                             $(this).closest("tr").find("input.single_total")
    //                                 .val(total);
    //                             $(this).closest("tr").find("input.net_vat").val(
    //                                 total);
    //                             totalPurchaseAmount();
    //                             exist = true;
    //                         }
    //                     });
    //                     if (exist) {
    //                         // playSound();
    //                         console.log("ID exist")
    //                         $(".barcodeLoader").hide();
    //                         $("#barcode").val('');
    //                         var msgType = 'warning';
    //                         var position = 'bottom left';
    //                         var msgClass = 'bx bx-check-circle';
    //                         var message = 'Product Already Exist in list';
    //                         var sound = 'sound5';
    //                         showMsg(msgType, position, msgClass, message, sound);

    //                         return false;
    //                     } else {
    //                         playSound();
    //                         let rowCount = $('#otherProductsBody tr').length;
    //                         let count = rowCount + 1;
    //                         // console.log('total rows ' + rowCount)
    //                         if (count > 0) {
    //                             $("#invoiceSubmitBtn").removeClass("disabled");
    //                         }
    //                         // console.log(resp.product)
    //                         // amount = resp.product.price;
    //                         var price = 0;
    //                         if (resp.product.price) {
    //                             price = resp.product.price;
    //                         }
    //                         $('#otherProductsBody').append(
    //                             '<tr class="delete_add_more_item" data-barcode="' +
    //                             resp.product.barcode + '" id="delete_add_more_item">\
    //                                 <td>\
    //                                 <input type="text" name="productName[]" value="' + resp.product.productName + '" class="form-control form-control-sm rounded-0 description text-start" readonly><input type="hidden" value="' +
    //                             resp.product.product_id + '" name="product_id[]">\
    //                                 </td>\
    //                                 <td>\
    //                                 <input type="text" name="type[]" value="' + resp.product.type + '" class="form-control form-control-sm rounded-0 description text-start" readonly>\
    //                                 </td>\
    //                                 <td>\
    //                                 <input type="text" name="barcode[]" value="' + resp.product.barcode +
    //                             '" class="form-control form-control-sm rounded-0 barcode text-start" readonly>\
    //                                 </td>\
    //                                 <td>\
    //                                 <input type="text" name="quantity[]" value="' + updateQty + '" class="form-control form-control-sm rounded-0 quantity text-center">\
    //                                 </td>\
    //                                 <td>\
    //                                 <input type="text" name="price[]" value="' + price + '" class="form-control form-control-sm rounded-0 price text-end">\
    //                                 </td>\
    //                                 <td>\
    //                                 <input type="text" name="single_total[]" value="' + price + '" class="form-control form-control-sm rounded-0 single_total text-end" readonly>\
    //                                 </td>\
    //                                 <td style="float: right;" class="mt-2"><i class="btn btn-danger rounded-5 shadow btn-sm lni lni-close remove_button"></i> </td>\
    //                                 </tr>'
    //                         );
    //                         $(".barcodeLoader").hide();
    //                         $("#barcode").val('');
    //                         totalPurchaseAmount();
    //                     }
    //                 },
    //                 error: function (resp) {
    //                     $(".barcodeLoader").hide();
    //                     $("#barcode").val('');
    //                     var msgType = 'error';
    //                     var position = 'bottom left';
    //                     var msgClass = 'bx bx-check-circle';
    //                     var message = resp.statusText;
    //                     showMsg(msgType, position, msgClass, message);
    //                 },
    //                 complete: function () {
    //                     isLoading = false;
    //                 }
    //             }); //  END AJAX....
    //         }, time);
    //     }

    // });
    ///// END Barcode Scanner....

    // Delete Row .......
    $(document).on("click", ".remove_button", function (event) {
        $(this).closest(".delete_add_more_item").remove();
        totalPurchaseAmount();
        let rowCount = $('#otherProductsBody tr').length;
        // console.log('total rows ' + rowCount)
        if (rowCount < 1) {
            $("#invoiceSubmitBtn").addClass("disabled");
        }
    });
    // calculate sum of amount in invoice
    $(document).on('keyup click', '#tender_amount', function () {
        totalPurchaseAmount();
    });
    /// CashTender PopUP ...
    $(document).on('keyup click', '#cashAmount,#spanAmount', function () {
        var sum = 0;
        var cashAmount = $('#cashAmount').val();
        var spanAmount = $('#spanAmount').val();
        var totalAmount = $("#totalAmount").val();
        var showCashTender = 0;
        var tenderAmount = 0;
        if (!isNaN(cashAmount) && cashAmount.length != 0) {
            sum += parseFloat(cashAmount);
            showCashTender = (parseFloat(spanAmount) + parseFloat(cashAmount)) - totalAmount;
        }

        if (!isNaN(spanAmount) && spanAmount.length != 0) {
            sum += parseFloat(spanAmount);
            tenderAmount = parseFloat(spanAmount) + parseFloat(cashAmount);
        }
        var totalTenderAmount = (parseFloat(spanAmount) + parseFloat(cashAmount));
        if (totalTenderAmount < totalAmount) {
            $("#invoiceSubmitBtn").addClass("disabled");
        } else {
            $("#invoiceSubmitBtn").removeClass("disabled");
        }
        // console.log("chang Chash: " + showCashTender)
        // if (showCashTender < 0 || showCashTender == '') {
        //     $("#invoiceSubmitBtn").addClass("disabled");
        // }else if(showCashTender >= 0){
        //     console.log("chang Chash else: " + showCashTender)
        //     $("#invoiceSubmitBtn").removeClass("disabled");
        // }

        $("#tenderAmount").val(cashAmount);
        $("#showChange").val(showCashTender);
        $("#tenderAmount").val(tenderAmount);
    });


    function totalPurchaseAmount() {
        var sum = 0;
        var totalAmount = 0;
        $(".single_total").each(function () {
            var value = $(this).val();
            var tender_amount = $("input.tender_amount").val();
            if (!isNaN(value) && value.length != 0) {
                sum += parseFloat(value);
                totalAmount = tender_amount - sum;
            }
        });
        $('#net_vat').val(sum);
        // $('#tender_amount').val(sum);
        $('#total_amount').val(sum);
        $('#balance').val(totalAmount);

        var total_vat = 0;
        $(".vat_total").each(function () {
            // var value = $(this).attr('data-vatAmount');
            var value = $(this).val();
            // console.log('vat amount ' + value)
            if (!isNaN(value) && value.length != 0) {
                total_vat += parseFloat(value);
            }
        });

        var total_discount = 0;
        $(".discount").each(function () {
            // var value = $(this).attr('data-vatAmount');
            var value = $(this).val();
            // console.log('vat amount ' + value)
            if (!isNaN(value) && value.length != 0) {
                total_discount += parseFloat(value);
            }
        });

        // console.log("total vat: " + total_vat)
        $('#total_vat').val(total_vat);
    }

    $(document).on('keyup click', '.price,.discount,.quantity,.vat', function () {
        var cost = $(this).closest("tr").find("input.cost").val();
        var price = $(this).closest("tr").find("input.price").val();
        var discount = $(this).closest("tr").find("input.discount").val();
        var quantity = $(this).closest("tr").find("input.quantity").val();
        var vat_total = $(this).closest("tr").find("input.vat_total").val();


        var vat = $(this).closest("tr").find("input.vat").val();
        var total = (quantity * price);
        // console.log('single_total '+single_total)
        // var afterVatTotal = ((parseInt(total) - parseInt(discount)) * 15) / 100;
        var afterVatTotal = ((parseInt(total)) * parseInt(vat)) / 100;
        var minusDiscount = (parseInt(total) + parseInt(afterVatTotal)) - discount;
        //  console.log("afterVatTotal " + afterVatTotal)
        //  console.log("minusDiscount " + minusDiscount)
        $(this).closest("tr").find("input.single_total").val(minusDiscount);
        $(this).closest("tr").find("input.net_vat").val(minusDiscount);
        $(this).closest("tr").find("input.vat_total").val(afterVatTotal);
        totalPurchaseAmount();
    });
    $("#cashTender").on('click', function () {
         var countItems = getAllItems();
         if (countItems === false) {
            var msgType = 'warning';
                            var position = 'top-right';
                            var msgClass = 'bx bx-check-circle';
                            var message = 'add item to cart';
                            showMsg(msgType, msgClass, message);
                            return false;
         }
        // let totalAmount = $("#net_vat").val();
        // let cashAmount = $("#tender_amount").val();
        // let balance = $("#balance").val();
        // $("#totalAmount").val(totalAmount);
        // $("#cashAmount").val(0);
        // $("#spanAmount").val(0);
        // $("#tenderAmount").val(0);
        // $("#showChange").val(0);
        // $("#cashAmount").val(cashAmount);
        // $("#showChange").val(balance);

        $("#amountReceived").val('');
        $("#changeAmount").val('');

        $("#cashTenderModal").modal('show');
        $("#invoiceSubmitBtn").addClass("disabled");
    });


    ////// SUBMIT POS FORM //######################//////////////

    $(document).on('submit', '#posForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#posForm')[0]);
        let url = $("#posForm").attr('action');
        // console.log(formData)
        if (!isLoading) {
            isLoading = true;
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#submitInvoiceSpinner').addClass('spinner-border spinner-border-sm');
                },
                success: function (resp) {

                    if (resp.status == 200) {
                        $(".delivery").html("");
                        $(".delivery").append('<option value="">Choose...</option>');
                        playSound();
                        window.open("/pos/print_invoice_pos/" + resp.print_invoiceNo, "_blank",
                            "scrollbars=1,resizable=1,height=500,width=500");
                        $("#submitInvoiceSpinner").removeClass("spinner-border spinner-border-sm");

                        $("#searchCustomer").val('')
                        // $("#searchCustomer").prop('disabled', true);
                        // $("#new_existing_customer").prop('checked', false);
                        $("#customerName").val(resp.customer.name)
                        $("#customerID").val(resp.customer.id)
                        $("#mobile").val('')
                        $("#vat_no").val('')
                        $("#customerType").text('Customer Type');


                        $("#net_vat").val(0);
                        $("#total_vat").val(0);
                        $("#tender_amount").val(0);
                        $("#balance").val(0);

                        var msgType = 'success';
                        var position = 'top-right';
                        var msgClass = 'bx bx-check-circle';
                        var message = resp.message;
                        showMsg(msgType, msgClass, message);
                        $("#cashTenderModal").modal('hide');
                        // $('#posForm').trigger("reset");
                        $("#otherProductsBody").html('');
                        $("#invoice_no").val(resp.invoice_no);
                    } else if (resp.status === 400) {
                        playSound();
                        $("#submitInvoiceSpinner").removeClass("spinner-border spinner-border-sm");
                        $("#cashTenderModal").modal('hide');
                        /// $("#locationID").select2("val", "");
                        // $('#posForm').trigger("reset");
                        // $("#otherProductsBody").html('');
                        $.each(resp.errors, function (key, value) {
                            // $.notify(value[0], {globalPosition: 'top right',className: 'error'});
                            var msgType = 'warning';
                            var position = 'top-right';
                            var msgClass = 'bx bx-check-circle';
                            var message = value[0];
                            showMsg(msgType, msgClass, message);

                            //         let msgType = 'warning';
                            // let msgClass = 'bx bx-check-circle';
                            // let message = resp.message;
                            // showMsg(msgType, msgClass, message);

                        });
                        return false;
                    } else if (resp.status == 401) {
                        playSound();
                        $("#submitInvoiceSpinner").removeClass("spinner-border spinner-border-sm");
                        $("#cashTenderModal").modal('hide');
                        // $('#posForm').trigger("reset");
                        $("#otherProductsBody").html('');
                        var msgType = 'error';
                        var position = 'bottom right';
                        var msgClass = 'bx bx-check-circle';
                        var message = resp.message;
                        showMsg(msgType, msgClass, message);

                        // $("#locationID").select2("val", "");
                        $('#posForm').trigger("reset");
                        return false;
                    }
                },
                error: function (resp) {
                    playSound();
                    $("#submitInvoiceSpinner").removeClass("spinner-border spinner-border-sm");
                    $("#cashTenderModal").modal('hide');
                    $('#posForm').trigger("reset");
                    $("#otherProductsBody").html('');
                    var msgType = 'error';
                    var position = 'bottom left';
                    var msgClass = 'bx bx-check-circle';
                    var message = resp.statusText;
                    showMsg(msgType, msgClass, message);
                    return false;
                },
                complete: function () {
                    isLoading = false;
                }
            }); // AJAX END.......
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
            size: 'mini',
            msg: message
        });
    }



    $(document).keydown(function (e) {
        if (e.key == "F3" && e.ctrlKey) {
            openCashTender();
        }
    });

    function openCashTender() {
        $("#cashTenderModal").modal('show');
    }

});
