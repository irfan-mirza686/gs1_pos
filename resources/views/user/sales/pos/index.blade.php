@extends("user.layouts.layout")
@section('title', '| POS')

@section("style")
<link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet" />

@endsection

@section("content")

<!--start page wrapper -->
<!-- <div class="page-wrapper"> -->
<div class="page-content">

    <div class="card">
        <div class="card-header" style="background-color: navy; color: white;">
            <div class="ms-auto">
                <div class="row mt-2" style="font-size: 16px; font-family: sans-serif; font-weight: bold;">
                    <div class="col-md-6 text-left">

                        <span>Sales Entry Form (Direct Invoice)</span>
                    </div>

                    <div class="col-md-6" style="text-align: right;">

                        <div class="ml-2">
                            <span id='ct6'></span>
                            <!-- <input type="text" name="" readonly value=""  id='ct6' style="background-color: #FFFF00" style="border-radius: 5px;"> -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <form id="posForm" method="POST" action="{{route('sale.store')}}">

            @include('user.sales.pos.includes.pos_header')

            <!-- <hr style="border: 1px solid;"> -->
            @include('user.sales.pos.includes.pos_items')
            @include('user.sales.pos.cash_tender_modal')
        </form>
    </div>

    @include('user.sales.pos.create_customer_modal')
</div>
<!-- </div> -->
<!--end page wrapper -->

@endsection

@push("custom-script")
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsEUxB9psxb-LxhYx8hJtF248gj4bx49A"></script>
<script src="{{asset('assets/admin/js/sales/drag_google_mark.js')}}"></script>
<script src="{{asset('assets/admin/js/sales/sales.js')}}"></script>
<script src="{{asset('assets/admin/js/sales/pos_script.js')}}"></script>
<script src="{{asset('assets/admin/js/customer/customer_script.js')}}"></script>
<script>
    function display_ct6() {
        var x = new Date();
        // Convert to UTC
        var utc = x.getTime() + (x.getTimezoneOffset() * 60000);
        // Create new Date object for Saudi time (UTC + 3)
        var saudiTime = new Date(utc + (3600000 * 3));

        var ampm = saudiTime.getHours() >= 12 ? ' PM' : ' AM';
        var hours = saudiTime.getHours() % 12;
        hours = hours ? hours : 12; // Adjust hours for 12-hour format
        var minutes = saudiTime.getMinutes();
        var seconds = saudiTime.getSeconds();

        // Format minutes and seconds to always show two digits
        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        var x1 = (saudiTime.getMonth() + 1) + "/" + saudiTime.getDate() + "/" + saudiTime.getFullYear();
        x1 = x1 + " - " + hours + ":" + minutes + ":" + seconds + ampm;
        document.getElementById('ct6').innerHTML = x1;
        display_c6();
    }

    function display_c6() {
        var refresh = 1000; // Refresh rate in milliseconds
        mytime = setTimeout(display_ct6, refresh);
    }
    display_c6();
</script>

@endpush
