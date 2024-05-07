<div class="card-body mt-4">
    <div class="tbl-container bdr">
        <table class="table table-sm table-responsive" id="posTable" width="100%">
            <thead class="rounded-10 shadow" style="background-color: #008CFF; color: white;">
                <tr>

                    <th width="10%">GTIN</th>
                    <th width="15%">Description</th>
                    <th width="5%">Price</th>
                    <th width="12%">Qty</th>
                    <th width="12%">Discount</th>
                    <th width="10%">Vat</th>
                    <th width="15%">Total</th>
                    <th width="10%">Action</th>
                </tr>
            </thead>
            <tbody id="otherProductsBody" class="field_wrapper mt-4">

            </tbody>

            <tbody style="margin-top: 20px;">

                <tr>
                    <td colspan="3">
                        <!-----Start Left Side --------->

                        <table width="100%">
                            <tbody>

                                <!-- Row 1 -->
                                <tr>
                                    <td class="pos-button-gaps">
                                        <button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: #2596be; color: white;">F10 - Open </br>
                                            Drawer</button>
                                    </td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block btn-primary btn-block  rounded-0 pos-buttons-samesize shadow">F6
                                            - PLU </br> Inquiry</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: #2596be; color: white;">F7 - </br>
                                            Department</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: #2596be; color: white;">F4 - Last </br>
                                            Receipt</button></td>
                                </tr>
                                <!-- Row 1 Ends -->

                                <!-- Row 2 Start -->
                                <tr>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: gray; color: white;">F1 - Edit
                                            Qty</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: goldenrod; color: white;">F9 - Old </br>
                                            Invoice</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block btn-info  rounded-0 pos-buttons-samesize shadow">F2
                                            - Delete </br> Line</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block btn-primary  rounded-0 pos-buttons-samesize shadow">F4
                                            - Last </br> Receipt</button></td>
                                </tr>
                                <!-- Row 2 Ends -->

                                <!-- Row 3 Start -->
                                <tr>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow" id="cashTender"
                                            style="background-color: orangered; color: white;">F3 -
                                            Tender </br>
                                            Cash</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: black; color: white;">F8 - Z
                                            Report</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block  rounded-0 pos-buttons-samesize shadow"
                                            style="background-color: red; color: white;">F5 - Return </br>
                                            Items</button></td>
                                    <td class="pos-button-gaps"><button type="button"
                                            class="btn btn-block btn-primary  rounded-0 pos-buttons-samesize shadow">F4
                                            - Last </br> Receipt</button></td>
                                </tr>
                                <!-- Row 3 Ends -->

                            </tbody>
                        </table>

                        <!------Left Side End ------------->
                    </td>
                    <td colspan="2">
                        </tb>

                    <td colspan="9">
                        <!-- Right Side -->
                        <table width="100%">
                            <tbody style="display:table; width:100%;">
                                <!-- Row 1 -->
                                <tr>
                                    <td class="pos-button-gaps text-start" colspan="5"><strong>Net With
                                            VAT:</strong></td>
                                    <td class="pos-right-gaps" colspan="1">
                                        <input type="text" name="" value="0" id="net_vat"
                                            class="form-control form-control-sm rounded-0 text-end net_vat"
                                            style="background-color: #F0F0F0" readonly>

                                    </td>
                                </tr>

                                <!-- Row 1 Ends -->

                                <!-- Row 2 -->
                                <tr>
                                    <td class="pos-button-gaps text-start" colspan="5"><strong>Tender
                                            Amount:</strong></td>
                                    <td class="pos-right-gaps" colspan="1">
                                        <input type="text" name="" value="0.00" id="tender_amount"
                                            class="form-control form-control-sm rounded-0 text-end tender_amount"
                                            style="background-color: #F0F0F0" readonly>

                                    </td>
                                </tr>
                                <!-- Row 2 Ends -->

                                <!-- Row 3 -->
                                <tr>
                                    <td class="pos-button-gaps text-start" colspan="5">
                                        <strong>Balance:</strong></td>
                                    <td class="pos-right-gaps" colspan="1">
                                        <input type="text" name="" value="0.00" id="balance"
                                            class="form-control form-control-sm rounded-0 text-end balance"
                                            style="background-color: #F0F0F0" readonly>

                                    </td>
                                </tr>
                                <!-- Row 3 Ends -->

                            </tbody>
                        </table>

                        <!-- Right Side Ends -->

                    </td>

                </tr>

            </tbody>

        </table>
    </div>

</div>
