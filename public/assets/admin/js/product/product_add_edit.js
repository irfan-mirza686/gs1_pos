$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).on('change', '#product_type', function (e) {
        e.preventDefault();
        let productType = $(this).val();
        if (productType === 'gs1') {
            $('.gs1').show();
        } else {
            $('.gs1').hide();
        }
    })

    var typingTimer;
    var doneTypingInterval = 1000; // 1 second

    // Search GPC base on Product Name keywords...
    $(document).on('keyup', '#productNameEn', function (e) {
        e.preventDefault();
        // $("#appendGpc").val(null).empty().select2('destroy');
        $(".appendGpc").html("");
        let search = $(this).val();
        clearTimeout(typingTimer);
        // typingTimer = setTimeout(doneTyping(search), doneTypingInterval);
        typingTimer = setTimeout(function() {
            doneTyping(search);
        }, doneTypingInterval);


    })

    // When user stops typing, do something
    function doneTyping(search) {
        // console.log(search)
        $.ajax({
            url: '/get-gpc-based-on-productname',
            type: 'POST',
            data: {
                search: search
            },
            // async: false,
            beforeSend: function () {

            },
            success: function (resp) {
                if (resp.status===200) {

                    $(".appendGpc").append('<option value="">Choose...</option>');
                        $.each(resp.data, function (i, val) {
                            console.log(val[0].pageContent)
                            $(".appendGpc").append('<option value="' + val[0].metadata.bricks_title + '">&nbsp;&nbsp;&nbsp;' + val[0].pageContent + '</option>');
                        });
                        // $("#appendGpc").select2();
                }

            }, errro: function () {

                $.notify("Unauthorized Error", { globalPosition: 'top right', className: 'error' });
                return false;
            }
        });
    }

    /// Get HS Codes based on selected GPC or Product Name...
    $(document).on('change','.appendGpc',function(e){
        e.preventDefault();
        $(".appendHscodes").html("");
        var title = $(this).val();
        var productName = $("#productNameEn").val();
        $.ajax({
            url: '/hscodes-based-on-selected-gpc-productname',
            type: 'POST',
            data: {
                title: title,
                productName:productName
            },
            // async: false,
            beforeSend: function () {

            },
            success: function (resp) {
                if (resp.status===200) {

                    $(".appendHscodes").append('<option value="">Choose...</option>');
                        $.each(resp.data, function (i, val) {
                            // console.log(val[0].metadata)
                            $(".appendHscodes").append('<option value="' + val[0].pageContent + '">&nbsp;&nbsp;&nbsp;' + val[0].pageContent + '</option>');
                        });
                        // $("#appendGpc").select2();
                }

            }, errro: function () {

                $.notify("Unauthorized Error", { globalPosition: 'top right', className: 'error' });
                return false;
            }
        });
    })

});
