$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#show_reportxxx').on('click', function () {

        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var url = $(this).attr('data-Route');

        var all_customers = $("#all_customers").val();
        var user_id = $("#user_id").val();

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
                endDate: endDate
            },
            // async: false,
            beforeSend: function () {
                $(btn).text(" processing...");
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


    ////////////////////////////////////////
    // $('.appendGpc').on('change', function() {
    //     var typedValue = $(this).val();

    //     // Output typed value (useful for real-time feedback or processing)
    //     console.log('Typed Value:', typedValue);
    // });

    $('#appendGpc').select2({
        placeholder: 'Search for GCP Type',
        ajax: {
            url: '/Report/get-gpc', // Replace with your endpoint URL
            method: 'GET',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data) {
                // console.log(data);
                if (Array.isArray(data)) {
                    // If data is already an array
                    return {
                        results: data.map(function(item) {
                            // console.log(item);
                            return { id: item[0].metadata.bricks_title, text: item[0].pageContent };
                        })
                    };
                } else if (data.results && Array.isArray(data.results)) {
                    // If data has a results property that is an array
                    return {
                        results: data.results.map(function(item) {
                            // console.log(item);
                            return { id: item[0].metadata.bricks_title, text: item[0].pageContent };
                        })
                    };
                } else {
                    // If data format is unexpected
                    console.error("Unexpected data format", data);
                    return {
                        results: []
                    };
                }
            },
            cache: true
        },
        minimumInputLength: 1 // Minimum characters to start search
    });

    ////////////////////////
    let myChart = null;
    $('#industry-type').select2();

    $('#show_report').click(function () {
        fetchChartData();
    });

    function fetchChartData() {
        let type = $('#customerType').val();
        let year = $('#startDate').val();
        let gpc = $('#appendGpc').val();

        $('#loader').show(); // Show loader

        $.ajax({
            url: '/Report/get-sale-report',
            method: 'GET',
            data: {
                type: type,
                year: year,
                gpc:  gpc
            },
            success: function (data) {
                renderChart(data);
            },
            complete: function () {
                $('#loader').hide(); // Hide loader
            }
        });
    }

    function renderChart(data) {
        const labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const values = new Array(12).fill(0);

        data.forEach(item => {
            values[item.month - 1] = item.total_sales;
        });

        const ctx = document.getElementById('myChart').getContext('2d');
        // Check if a chart instance already exists
        if (myChart) {
            myChart.destroy(); // Destroy the previous chart instance
        }
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Sales',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }


});
