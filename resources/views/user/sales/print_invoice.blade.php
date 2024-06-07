<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url({{ public_path('assets/fonts/Amiri-Regular.ttf') }}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Amiri';
            src: url({{ public_path('assets/fonts/Amiri-Bold.ttf') }}) format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: 'Amiri', sans-serif;
            direction: ltr;
        }
        .page-break {
            page-break-after: always;
        }
        .tableS {
            width: 90%;
            margin: 20px auto;
            font-family: 'Amiri', sans-serif;
            font-size: 11px !important;
        }
        .tableS tr td {
            padding: 2px;
        }
        .tableS tr td.noborder {
            border: none;
        }
        .removeborder {
            border: none !important;
        }
        .page {
            width: 100%;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            font-weight: normal;
            font-size: 9px !important;
            font-family: 'Amiri', sans-serif;
            page-break-inside: avoid;
        }
        .grandtotalFont {
            font-size: 16px; /* Adjusted font size */
        }
        @page {
            size: auto;
            margin: 0;
            font-family: 'Amiri', sans-serif;
        }
        @media print {
            html, body {
                width: 100%;
                height: auto;
                font-size: 8px;
                margin: 0 auto;
                font-family: 'Amiri', sans-serif;
            }
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-inside: avoid;
            }
            .removeborder {
                border: none;
            }
            .form-horizontal, label {
                font-weight: normal;
                font-size: 9px !important;
            }
            .testing {
                display: block;
                page-break-after: always !important;
            }
            .tableStyle {
                page-break-after: always !important;
            }
            .tableStyle:last-child {
                page-break-after: none;
            }
        }
    </style>
</head>
<body>
<div class="body">
    <div class="page">
        <table class="tableS" cellspacing="10">
            <tr>
                <td width="50%">
                    <img src="{{($apiInvoice=='true')?public_path('assets/uploads/gs_logo.png'):asset('assets/uploads/gs_logo.png')}}" width="120" height="70" alt="">
                </td>
                <td width="50%">
                    <strong>Saudi Leather Industries Factory Company Limited</strong>
                    <br><br>
                    <strong>{{formatArabicText('شركة المصنع السعودي للصناعات الجلدية المحدودة')}}</strong>
                </td>
            </tr>
        </table>
        <table class="tableS" cellspacing="10">
            <thead>
                <tr>
                    <td colspan="5" class="noborder"><strong>Customer:</strong> {{ @$getInvoiceData->customer->name }}</td>
                    <td colspan="5" class="noborder" align="right"></td>
                </tr>
                <tr>
                    <td colspan="5" class="noborder"><strong>Vat #:</strong> {{ @$getInvoiceData->customer->vat }}</td>
                    <td colspan="5" class="noborder" align="right"></td>
                </tr>
                <tr>
                    <td colspan="5" class="noborder"><strong>Receipt:</strong> {{ @$getInvoiceData->order_no }}</td>
                    <td colspan="5" class="noborder" align="right"><strong>:{{formatArabicText('رقم الفاتورة')}}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="noborder"><strong>Date:</strong> {{ @$getInvoiceData->date }}</td>
                    <td colspan="9" class="noborder" align="right">{{ date('h:i:s A', strtotime(@$getInvoiceData->time)) }} <strong>:{{formatArabicText('التاریخ')}}</strong></td>
                </tr>
            </thead>
        </table>
        <table class="tableS" cellspacing="10">
            <thead>
                <tr>
                    <td width="5%"><strong>#</strong></td>
                    <td width="20%"><strong>{{formatArabicText('بیان')}}</strong></td>
                    <td width="10%"><strong>{{formatArabicText('الكمية')}}</strong></td>
                    <td width="10%"><strong>{{formatArabicText('السعر')}}</strong></td>
                    <td width="10%"><strong>{{formatArabicText('تخفيض')}}</strong></td>
                    <td width="10%"><strong>{{formatArabicText('ضريبة القيمة المضافة')}}</strong></td>
                    <td width="35%" style="text-align: right;"><strong>{{formatArabicText('المجموع')}}</strong></td>
                </tr>
                <tr>
                    <td width="5%"><strong>#</strong></td>
                    <td width="45%"><strong>Description</strong></td>
                    <td width="10%"><strong>Qty</strong></td>
                    <td width="10%"><strong>Price</strong></td>
                    <td width="10%"><strong>Discount</strong></td>
                    <td width="10%"><strong>Vat</strong></td>
                    <td width="10%" style="text-align: right;"><strong>Total</strong></td>
                </tr>
            </thead>
            <tbody>
                @php $counter = 0; @endphp
                @foreach($getInvoiceData->items as $print)
                    @php $counter++; @endphp
                    <tr>
                        <td width="5%">{{ $counter }}</td>
                        <td class="kitchen" width="25%">
                            {{formatArabicText('أحذية السلامة')}} <br>
                            <span style="font-size: 8px;">1 - {{ $print['barcode'] }}</span>
                        </td>
                        <td class="kitchen" width="5%">{{ $print['qty'] }}</td>
                        <td class="kitchen" width="20%">{{ $print['price'] }}</td>
                        <td class="kitchen" width="5%">{{ $print['discount'] }}</td>
                        <td class="kitchen" width="5%">{{ $print['vat'] }}</td>
                        <td class="kitchen" width="20%" style="text-align: right;">{{ number_format($print['sub_total']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="page-break"></div>
        <table class="tableS kitchen" cellspacing="5">
            <tr>
                <td colspan="3"><strong>Vat (15%):</strong></td>
                <td><strong>{{formatArabicText('ضريبة القيمة المضافة')}}</strong></td>
                <td class="grandtotalFont" style="text-align: right;"><strong>{{ number_format(@$totalVat) }}</strong></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Total Amount:</strong></td>
                <td><strong>{{formatArabicText('ضريبة القيمة المضافة')}}</strong></td>
                <td class="grandtotalFont" style="text-align: right;"><strong>{{ number_format(@$getInvoiceData->total) }}</strong></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Paid Amount:</strong></td>
                <td><strong>{{formatArabicText('المدفوع')}}</strong></td>
                <td class="grandtotalFont" style="text-align: right;"><strong>{{ number_format(@$getInvoiceData->tender_amount) }}</strong></td>
            </tr>
            <tr>
                <td colspan="3"><strong>Change Due:</strong></td>
                <td><strong>{{formatArabicText('المتبقي')}}</strong></td>
                <td class="grandtotalFont" style="text-align: right;"><strong>{{ number_format(@$getInvoiceData->change_amount) }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" align="center">
                    <strong>{!! DNS2D::getBarcodeHTML(@$base64, 'QRCODE', 3, 3) !!}</strong><br>
                </td>
            </tr>
        </table>
    </div>
</div>
@if($apiInvoice == 'false')
<p align="center"><input type="button" id="pr" value="Print" onclick="printpage()" class="btn btn-success" /> </p>
@endif
<script type="text/javascript">
    function printpage() {
        var printButton = document.getElementById("pr");
        document.title = "";
        document.URL = "";
        window.print();
        printButton.style.visibility = 'visible';
    }
</script>
</body>
</html>
