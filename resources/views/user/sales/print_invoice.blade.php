
<div class="body">
 <div class="page" >
  <br>
  <br>
  <table width="80%" cellpadding="10" class="tableS" cellspacing="10" style="font-family: Times New Roman; font-size: 10.5px !important;margin-left:20px" >
   <!-- <tr>
    <td colspan="2" style="text-align:center" class="noborder"><img src="{{asset('images/tariq.png')}}" width="150" alt="PRA"></td>

  </tr> -->

</table>
<table width="90%" cellpadding="10" class="tableS" cellspacing="10" style="font-family: 'Open Sans', 'Martel Sans', sans-serif; font-size: 11px !important;margin-left:20px;">
<tr>
    <td width="50%">
        <img src="{{asset('assets/uploads/slic-logo.png')}}" width="120" height="100" alt="">
    </td>
    <td width="50%"><strong>Saudi Leather Industries Factory Company Limited</strong></br></br>
    <strong>
    شركة المصنع السعودي للصناعات الجلدية المحدودة
    </strong>
</td>

</tr>
<!-- <tr>
    <td width="50%"></td>
    <td width="50%">
    شركة المصنع السعودي للصناعات الجلدية المحدودة
    </td>
</tr> -->
</table>
<table width="90%" cellpadding="10" class="tableS" cellspacing="10" style="font-family: 'Open Sans', 'Martel Sans', sans-serif; font-size: 11px !important;margin-left:20px;">
  <thead>

  <tr>
    <td colspan="5" class="noborder"><strong>Customer:</strong> {{@$getInvoiceData->customer->name}}
    </td>
    <td colspan="5"  class="noborder" align="right"> </td>
  </tr>

  <tr>
    <td colspan="5" class="noborder"><strong>Vat #:</strong> {{@$getInvoiceData->customer->vat}}
    </td>
    <td colspan="5"  class="noborder" align="right"> </td>
  </tr>

  <tr>
    <td colspan="5" class="noborder"><strong>Receipt:</strong> {{@$getInvoiceData->order_no}}
    </td>
    <td colspan="5"  class="noborder" align="right"><strong>:رقم الفاتورة</strong>   </td>
  </tr>

   <tr>
    <td colspan="3" class="noborder"><strong>Date:</strong> {{@$getInvoiceData->date}}
    </td>
    <td colspan="9"  class="noborder" align="right">{{date('h:i:s A',strtotime(@$getInvoiceData->time))}} <strong> :التاریخ</strong>   </td>
  </tr>
  <!-- <tr>
    <td class="noborder" colspan="3"><strong>Invoice No:</strong> {{@$getInvoiceData->invoice_no}}</td>
   <td colspan="5" class="noborder" style="text-align: right;"><strong>Customer:</strong> {{@$getInvoiceData->customer->name}}
    </td>
  </tr> -->


  <tr>
   <td colspan="5" class="noborder">&nbsp;</td>
 </tr>
 <tr>

<td width="5"><strong>#.</strong></td>
<td width="20"><strong>بیان</strong></td>
<!-- <td width="15"><strong>Barcode</strong></td> -->
<td width="10"><strong>الكمية</strong></td>
<td id="kitchenph" width="10"><strong>السعر</strong></td>
<td width="10"><strong>تخفيض</strong></td>
<td width="10"><strong>ضريبة القيمة المضافة</strong></td>

<td id="kitchentotalh" width="35" style="text-align: right;"><strong>المجموع</strong></td>

</tr>
 <tr>

  <td width="5"><strong>#.</strong></td>
  <td width="45"><strong>Description</strong></td>
  <!-- <td width="15"><strong>Barcode</strong></td> -->
  <td width="10"><strong>Qty</strong></td>
  <td id="kitchenph" width="10"><strong>Price</strong></td>
  <td width="10"><strong>Discount</strong></td>
  <td width="10"><strong>Vat</strong></td>

  <td id="kitchentotalh" width="10" style="text-align: right;"><strong>Total</strong></td>

</tr>
</thead>



<?php
$counter = 0;
$addons = $getInvoiceData->items;
if( isset($addons) && is_array($addons) && ( count($addons) >= 1) ):
?>
@foreach($addons as $print)
<?php

$counter = $counter+1;
?>
<tr>

  <td width="5">{{$counter}}</td>
  <td class="kitchen" width="25">
  أحذية السلامة </br>
  <span style="font-size: 8px;">1 - {{$print['barcode']}}</span>
</td>
  <!-- <td width="20">{{$print['barcode']}}</td> -->

  <td class="kitchen" width="5">{{$print['qty']}}</td>
  <td class="kitchen" width="20">{{$print['price']}}</td>
  <td class="kitchen" width="5">{{$print['discount']}}</td>
  <td class="kitchen" width="5">{{$print['vat']}}</td>

  <td class="kitchen" width="20" style="text-align: right;">{{number_format($print['sub_total'])}}</td>

</tr>
@endforeach
<?php endif;  ?>



</table>

<table style="page-break-inside:avoid;font-family: 'Open Sans', 'Martel Sans', sans-serif; font-size: 10.5px !important;margin-left:20px" width="90%" cellpadding="5" class="tableS kitchen" cellspacing="5" id="kitchen">



 <hr>



 <tr>
  <td colspan="3"><strong>Gross:</strong></td>
  <td><strong>المجموع (ريال)</strong></td>
  <?php $withoutVatTotal = $getInvoiceData->total - $totalVat; ?>
  <td class="grandtotalFont"style="text-align: right;"><strong>{{number_format(@$withoutVatTotal)}}</strong></td>
</tr>

<tr>
  <td colspan="3"><strong>Vat (15%):</strong></td>
  <td><strong>ضريبة القيمة المضافة</strong></td>
  <td class="grandtotalFont"style="text-align: right;"><strong>{{number_format(@$totalVat)}}</strong></td>
</tr>

<tr>
  <td colspan="3"><strong>Paid Amount:</strong></td>
  <td><strong>المدفوع</strong></td>
  <td class="grandtotalFont"style="text-align: right;"><strong>{{number_format(@$getInvoiceData->cashAmount)}}</strong></td>
</tr>

<tr>
  <td colspan="3"><strong>Change Due:</strong></td>
  <td><strong>المتبقي</strong></td>
  <td class="grandtotalFont"style="text-align: right;"><strong>{{number_format(@$getInvoiceData->change_amount)}}</strong></td>
</tr>






<tr>
  <td class="removeborder"></td>
  <td class="removeborder">&nbsp;</td>
  <td class="removeborder"></td>
  <td class="removeborder"></td>
  <td class="removeborder"></td>
</tr>
<?php
$cr = 'AQVaYXRjYQIPMzAwNDU2NDE2NTAwMDAzAxQyMDIxLTEyLTAxVDE5OjAwOjA5WgQGMTAwLjAwBQUxNS4wMA==';
$zatcaP2 = 'AQVJcmZhbgIPMzAwNDU2NDE2NTAwMDAzAxQyMDIxLTEyLTAxVDE5OjAwOjA5WgQGMTAwLjAwBQUxNS4wMAYIb2sgb2sgb2sHCTEyMzQ1Njc4OQgEMDY4NgkCSVQKCU91dFNlbGxlcgsIUGFraXN0YW4MBFNlbGwNCFBha2lzdGFuDgJJVA==';


?>
<tr>
  <td colspan="5" align="center">
    <strong><?php echo DNS2D::getBarcodeHTML(@$base64, 'QRCODE', 3,3);?> </strong><br>

  </td>

  </tr>
<tr>
  <td class="removeborder"></td>
  <td class="removeborder">&nbsp;</td>
  <td class="removeborder"></td>
  <td class="removeborder"></td>
  <td class="removeborder"></td>
</tr>




<!-- <tr>
  <td colspan="5" align="center">
    <strong>IrFan Mirza, +923366667686</strong><br>

  </td>

  </tr> -->



</table>


</div>

</div>
<p align="center"><input type="button" id="pr" value="Print" onclick="printpage()" class="btn btn-success" /> </p>



</center>













<script type="text/javascript">
  function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("pr");
       // var printButtonk = document.getElementById("prK");
        //Set the print button visibility to 'hidden'
       // printButton.style.visibility = 'hidden';
       // printButtonk.style.visibility = 'hidden';
       document.title = "";
       document.URL   = "";

        //Print the page content
        window.print()
        //Set the print button to 'visible' again
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
       // printButtonk.style.visibility = 'visible';


     }
   </script>

   <script type="text/javascript">
    function printpageK() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("pr");
        //var printButtonk = document.getElementById("prK");
        var kitchen = document.getElementsByClassName("kitchen");
        //Set the print button visibility to 'hidden'
        for(var i = 0; i < kitchen.length; i++){
          kitchen[i].style.visibility = "hidden";
        }
        //printButton.style.visibility = 'hidden';
        //printButtonk.style.visibility = 'hidden';

        document.title = "";
        document.URL   = "";

        //Print the page content
        window.print()
        //Set the print button to 'visible' again
        //[Delete this line if you want it to stay hidden after printing]
        printButton.style.visibility = 'visible';
       // printButtonk.style.visibility = 'visible';
       for(var i = 0; i < kitchen.length; i++){
        kitchen[i].style.visibility = "visible";
      }

    }
  </script>


  <style>
    .tableS { margin-left: 20px; margin-top:10px; font-family:Verdana, Geneva, sans-serif; }
    .tableS tr td  {  padding:2px; font-family:Verdana, Geneva, sans-serif; }
    .tableS tr td.noborder { border:none;  }

    .removeborder {border:none !important; }
    body {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      background-color: #FAFAFA;
      font: 12pt "Tahoma";
      font-family:Verdana, Geneva, sans-serif;

    }
    * {
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      font-family:Verdana, Geneva, sans-serif;
    }
    .page {

     width: 12cm;
     height: auto;

     margin: 10mm auto;


     background: white;
     box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);

     font-weight: normal;
     font-size: 9px !important;
     font-family:Verdana, Geneva, sans-serif;

   }
   .font_size
   {
    font-size: 8em "tahoma";
    font-family:Verdana, Geneva, sans-serif;
  }
  .subpage {
    padding: 1cm;
    width: 15cm;
    height: 15.8cm;
    font-family:Verdana, Geneva, sans-serif;

  }

  .grandtotalFont { font-size:10em "tahoma"; }

  @page  {
    size: auto;
    margin:0;
    margin-top: 0;
    font-family:Verdana, Geneva, sans-serif;
  }






  @media  print {
    html, body {
      width: 10cm;
      height: auto;
      font-size: 8px;
      margin: 0 auto;
      font-family:Verdana, Geneva, sans-serif;

    }



    table {
      -fs-table-paginate: paginate;
      font-family:Verdana, Geneva, sans-serif;
    }


    .page {
      margin: 0;
      border: initial;
      border-radius: initial;
      width: initial;
      min-height: initial;
      box-shadow: initial;
      background: initial;
      font-family:Verdana, Geneva, sans-serif;
    }
    .removeborder {border:none; }


    .form-horizontal,label{
      font-weight: normal;
      font-size: 9px !important;
      font-family:Verdana, Geneva, sans-serif;

    }
    .testing {
     display: block;
     font-family:Verdana, Geneva, sans-serif;
     /* page-break-after: always !important;*/
   }
   .tableStyle {

    page-break-after: always !important;
    font-family:Verdana, Geneva, sans-serif;

  }
  .tableStyle:last-child {
   page-break-after: none;
   font-family:Verdana, Geneva, sans-serif;
 }

 .page table tr td  {   padding:2px; font-family:Verdana, Geneva, sans-serif; }
 .form-horizontal,label{
  font-weight: normal;
  font-size: 9px !important;
  font-family:Verdana, Geneva, sans-serif;

}

.grandtotalFont { font-size:10em "tahoma"; }

}
</style>
<script type="text/javascript">
  window.addEventListener("load", window.print());
</script>
