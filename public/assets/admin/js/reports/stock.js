$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','#show_report', function () {
        var product_id = $('#product_id :selected').val();

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        console.log(product_id)
        if (product_id=='') {
            swalWithBootstrapButtons.fire(
                'Warning!',
                'Please select value',
                'warning'
            )
            return false;
        }

        var url = $(this).attr('data-Route');



        let btnVal = $(".showStockBtn").text();

        let btn = $(".showStockBtn");




        $.ajax({
            url: url,
            type: 'POST',
            data: {
                product_id: product_id
            },
            // async: false,
            beforeSend: function () {
                $(btn).text("processing...");
                $(btn).prepend('<i class="fa fa-spinner fa-spin"></i>');
                $(btn).attr("disabled", 'disabled');
            },
            success: function (data) {

                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");
                if (data == 'false') {
                    $("#DocumentResults").html("");
                    swalWithBootstrapButtons.fire(
                        'Not Found!',
                        'there is no data found!',
                        'warning'
                    )
                    return false;
                } else {
                    $(".salesList").show();
                    var source = $("#document-template").html();
                    var template = Handlebars.compile(source);
                    var html = template(data);
                    $('#DocumentResults').html(html);
                    $('[data-toggle="tooltip"]').tooltip();
                }


            }, errro: function () {
                $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");
                swalWithBootstrapButtons.fire(
                    'Error!',
                    'Unauthorized Error',
                    'warning'
                )
                return false;
            }
        });
    });


});
