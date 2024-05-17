$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    showBrandsList();

    $(document).on('click', '.add', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        const modal = $('#brandModal');
        $('#brandModal').modal({
            backdrop: 'static'
        });
        modal.find('.modal-title').text("Add Brand")
        modal.find('input[name=name]').val('')
        modal.find('select[name=status]').val('')

        modal.find('form').attr('action', url)
        modal.find('.saveBrandBtn').text('Save');

        $("#brandModal").modal('show');
    });

    $(document).on('click', '.edit', function (e) {
        e.preventDefault();
        let url = $(this).attr("href");
        const modal = $('#brandModal');
        $('#brandModal').modal({
            backdrop: 'static'
        });

        let brandID = $(this).attr('data-id');
        let name = $(this).attr('data-name');
        let status = $(this).attr('data-status');


        // let url = "/admin/country/update/" + countryID;

        modal.find('.modal-title').text("Update Brand")
        modal.find('input[name=brand_id]').val(brandID)
        modal.find('input[name=name]').val(name)
        modal.find('select[name=status]').val(status)

        modal.find('form').attr('action', url);
        modal.find('.saveBrandtn').text('Update');
        modal.modal('show');
    })

    $(document).on('submit', '#addBrandForm', function (e) {
        e.preventDefault();

        let formData = new FormData($('#addBrandForm')[0]);
        let btn = $('.saveBrandtn');
        let btnVal = $('.saveBrandtn').text();
        let url = $("#addBrandForm").attr('action');
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
                $("#brandModal").modal('hide');
                removeContent(btn, btnVal);
                if (resp.status === 200) {
                    let msgType = 'success';
                    let msgClass = 'bx bx-check-circle';
                    let message = resp.message;
                    showMsg(msgType, msgClass, message);
                    showBrandsList();
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
    });


    function showBrandsList() {

        var table = $('.brands-table').DataTable({

            "ajax": {
                "url": '/brands/list',
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
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    }  /// END FUNCTION....


    /////////// Delete Country \\\\\\\\\\\\\\\\\\\\\\\
    $(document).on('click','.del',function(e){
        e.preventDefault();
        let url = $(this).attr('href');
        console.log(url)

        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: 'Delete Record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes do it!',
            cancelButtonText: 'No cancel it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'delete',
                    url: url,

                    success: function (response) {
                        if (response.status == 200) {
                            showBrandsList();
                            swalWithBootstrapButtons.fire(
                                'Data',
                                response.message,
                                'success'
                            )
                        } else if (response.status == 422) {
                            swalWithBootstrapButtons.fire(
                                'Data',
                                response.message,
                                'error'
                            )
                        }
                    },
                    error: function () {

                    }
                });
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Data Safed',
                    'error'
                )
            }
        })

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
