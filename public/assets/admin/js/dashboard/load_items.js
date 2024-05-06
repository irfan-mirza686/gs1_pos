$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.viewStockItems', function (e) {
        e.preventDefault();
        let url = $(this).attr('data-url');
        let type = $(this).attr('data-Type');
        let modal = $("#loadItemsModal");
        $.ajax({

            type: 'GET',
            url: url,
            data: { type: type },
            beforeSend: function () {
                $("body").addClass("loading");

            }, success: function (resp) {
                $("body").removeClass("loading");

                $("#loadItemTbody").html("");
                let count = 0;
                $.each(resp.total_stock, function (key, value) {
                    if (value.qty > 0) {
                        count = count + 1;
                        $("#loadItemTbody").append('<tr>\
                        <td>'+ count + '</td>\
                        <td>'+ value.productName + '</td>\
                        <td>'+ value.barcode + '</td>\
                    </tr>');
                    }

                });
                modal.modal('show');
            }, error: function () {
                $("body").removeClass("loading");
            }

        })
    })
});
