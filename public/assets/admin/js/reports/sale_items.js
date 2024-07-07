$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#show_report').on('click', function () {

        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var url = $(this).attr('data-Route');

        var product_id = $('#product_id :selected').val();



        let btnVal = $(".showUserSaleBtn").text();

        let btn = $(".showUserSaleBtn");

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })


        $.ajax({
            url: url,
            type: 'POST',
            data: {
                startDate: startDate,
                endDate: endDate,
                product_id:product_id
            },
            // async: false,
            beforeSend: function () {
                $(btn).text("processing...");
                $(btn).prepend('<i class="fa fa-spinner fa-spin"></i>');
                $(btn).attr("disabled", 'disabled');
            },
            success: function (data) {

                removeContent(btn,btnVal)
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
                removeContent(btn,btnVal);
                swalWithBootstrapButtons.fire(
                    'Error!',
                    'Unauthorized Error',
                    'warning'
                )
                return false;
            }
        });
    });

    function removeContent(btn,btnVal){
        $(btn).text(btnVal);
                $(btn).find(".fa-spinner").remove();
                $(btn).removeAttr("disabled");
    }

});
