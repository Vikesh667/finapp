<!-- <!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tax Invoice</title>

    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 13px;
        }

        .page-border {
            width: 100%;
            height: auto;
            border: 1px solid #000;
            padding: 10px;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .no-border td,
        .no-border th {
            border: none !important;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .stamp {
            border: 1px dashed #444;
            width: 110px;
            height: 70px;
            text-align: center;
            padding-top: 25px;
            margin: auto;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
            font-style: italic;
        }
    </style>
</head>

<body>

    <div class="page-border">

        <div class="title">TAX INVOICE</div>

       
        <table>
            <tr>
                <td width="70%">
                    <b>VMIT Technologies Pvt Ltd</b><br>
                    24/2 Ramball Yadav Chawl, Gautam Nagar,<br>
                    Thane West - 400604, India<br>
                   <strong> GSTIN: 27AAGCV4108B1ZO</strong>
                </td>
                <td class="center">
                    ORIGINAL FOR RECIPIENT<br><br>
                    <img src="https://via.placeholder.com/90" height="90">
                </td>
            </tr>
        </table>

       
        <table>
            <tr>
                <td><b>Invoice No:</b> INV-001</td>
                <td><b>Date:</b> 17-Jul-2025</td>
            </tr>
            <tr>
                <td><b>State Code:</b> 27 (Maharashtra)</td>
                <td><b>Mode:</b> B2B</td>
            </tr>
        </table>

       
        <table>
            <tr>
                <th>Bill To</th>
            </tr>
            <tr>
                <td>
                    AXIOMA SMART GADGETS Pvt Ltd <br>
                    GHAR ROAD BHAGWATI COMPLEX,F4, <br>
                     Meerut,250001,Uttar Pradesh,<br>
                     NA FIRST FLOOR,Retail Business <br>
                   <strong> GSTIN: 24ABCD1234K9Z1</strong>
                </td>
            </tr>
        </table>

       
        <table style="margin-top:10px;">
            <tr>
                <th>Sr</th>
                <th>HSN | SAC CODE</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Rate</th>
                <th>Amount (₹)</th>
            </tr>
            <tr>
                <td class="center">1</td>
                <td>998314</td>
                <td>Software License</td>
                <td class="center">26331</td>
                <td class="right">38.00</td>
                <td class="rigth"></td>
                <td class="right">100,000.00</td>
            </tr>
        </table>

        <table style="width:100%; margin-top:10px; border-collapse: collapse;">
            <tr>
              
                <td style="width:60%; border:1px solid #000; padding:10px; vertical-align:top;">
                    <strong>Terms & Conditions:</strong>
                    <ul style="margin:5px 0 0 15px; padding:0; line-height:18px;">
                        <li>No refunds after activation.</li>
                        <li>Invoice valid only after full payment.</li>
                        <li>Warranty applicable as per service plan.</li>
                        <li>Support available via email or ticket only.</li>
                        <li>All disputes subject to Thane jurisdiction.</li>
                    </ul>
                </td>

                
                <td style="width:40%; border:1px solid #000; padding:0;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <th style="border:1px solid #000; padding:6px;">Description</th>
                            <th style="border:1px solid #000; padding:6px; text-align:right;">Amount (₹)</th>
                        </tr>

                        <tr>
                            <td style="border:1px solid #000; padding:6px;">Total</td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;">₹100,000.00</td>
                        </tr>

                        
                        <tr>
                            <td style="border:1px solid #000; padding:6px;">CGST (9%)</td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;">₹9,000.00</td>
                        </tr>

                        <tr>
                            <td style="border:1px solid #000; padding:6px;">SGST (9%)</td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;">₹9,000.00</td>
                        </tr>


                        <tr>
                            <td style="border:1px solid #000; padding:6px;">IGST (18%)</td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;">₹0.00</td>
                        </tr>


                        <tr>
                            <td style="border:1px solid #000; padding:6px;">Round Off</td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;">₹0.00</td>
                        </tr>

                        <tr style="background:#f5f5f5; font-weight:bold;">
                            <td style="border:1px solid #000; padding:6px;"><strong>Total Due</strong></td>
                            <td style="border:1px solid #000; padding:6px; text-align:right;"><strong>₹118,000.00</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>


        <table style="width:100%; margin-top:10px; border-collapse: collapse;">
            <tr>

               
                <td style="width:65%; border:1px solid #000; padding:0; vertical-align:top;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <th colspan="2" style="border:1px solid #000; padding:6px; background:#f5f5f5; text-align:center;">
                                BANK DETAILS
                            </th>
                        </tr>
                        <tr>
                            <td style="border:1px solid #000; padding:6px;"><strong>Bank Name</strong></td>
                            <td style="border:1px solid #000; padding:6px;">Kotak Mahindra Bank</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #000; padding:6px;"><strong>Account Holder</strong></td>
                            <td style="border:1px solid #000; padding:6px;"> VMIT Technologies Pvt Ltd</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #000; padding:6px;"><strong>Account Number</strong></td>
                            <td style="border:1px solid #000; padding:6px;">123456789012</td>
                        </tr>
                        <tr>
                            <td style="border:1px solid #000; padding:6px;"><strong>IFSC Code</strong></td>
                            <td style="border:1px solid #000; padding:6px;">KKBK0001234</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border:1px solid #000; padding:6px;">
                                <strong>Amount in Words:</strong> One Lakh Eighteen Thousand Only
                            </td>
                        </tr>
                    </table>
                </td>

              
                <td style="width:35%; border:1px solid #000; text-align:center; vertical-align:top; padding:15px;">
                    <div style="border:1px dashed #444; width:130px; height:90px; margin:0 auto; display:flex; justify-content:center; align-items:center;">
                        STAMP
                    </div>
                    <br>
                    <strong>Authorized Signature</strong>
                </td>
            </tr>
        </table>


        <div class="footer">Thank you for your business.</div>

    </div>

</body>

</html> -->
<!DOCTYPE html>
<html>

<head>
    <title>Invoice <?= $invoice['invoice_no'] ?></title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .page {
            border: 1px solid #000;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="page">

        <h2 class="center">TAX INVOICE</h2>

        <table>
            <tr>
                <td width="70%">
                    <b>VMIT Technologies Pvt. Ltd.</b><br>
                    24/2 Ramball Yadav Chawl, Gautam Nagar,<br>
                    Thane (W), Maharashtra - 400604<br>
                    <b>GSTIN:</b> 27AAGCV4108B1ZO
                </td>
                <td class="center">
                    <b>ORIGINAL FOR RECIPIENT</b><br><br>
                    <img src="https://via.placeholder.com/90">
                </td>
            </tr>
        </table>

        <table style="margin-top:5px;">
            <tr>
                <td><b>Invoice No:</b> <?= $invoice['invoice_no'] ?></td>
                <td><b>Date:</b> <?= date("d-m-Y", strtotime($invoice['date'])) ?></td>
            </tr>
            <tr>
                <td><b>Invoice Type:</b> <?= $invoice['gst_applied'] ? 'Tax Invoice (GST Applicable)' : 'Invoice (Without GST)' ?></td>
                <td><b>State Code:</b> <?= $invoice['client']['state_code'] ?? 'N/A' ?></td>
            </tr>
        </table>

        <table style="margin-top:10px;">
            <tr>
                <th colspan="2">Bill To</th>
            </tr>
            <tr>
                <td>
                    <b><?= $invoice['customer']['name'] ?></b><br>
                    <?= $invoice['customer']['address'] ?><br>

                    <!-- Only show GST if customer is registered -->
                    <?php if (!empty($invoice['gst_number'])): ?>
                        <b>Customer GSTIN:</b> <?= $invoice['gst_number'] ?>
                    <?php else: ?>
                        <b>Customer GSTIN:</b> Not Registered
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <table style="margin-top:10px;">
            <tr>
                <th>Sr</th>
                <th>HSN/SAC</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Rate (₹)</th>
                <th>Amount (₹)</th>
            </tr>
            <tr>
                <td class="center">1</td>
                <td>998314</td>
                <td>Purchase of License Codes</td>
                <td class="center"><?= $invoice['total_code'] ?></td>
                <td class="right"><?= number_format($invoice['rate'], 2) ?></td>
                <td class="right"><?= number_format($invoice['base_amount'], 2) ?></td>
            </tr>
        </table>

        <table style="margin-top:10px;">
            <tr>
                <th>Description</th>
                <th class="right">Amount (₹)</th>
            </tr>
            <tr>
                <td>Subtotal</td>
                <td class="right"><?= number_format($invoice['base_amount'], 2) ?></td>
            </tr>

            <?php if ($invoice['gst_applied']): ?>

                <?php if ($invoice['igst'] > 0): ?>
                    <tr>
                        <td>IGST (18%)</td>
                        <td class="right"><?= number_format($invoice['igst'], 2) ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td>CGST (9%)</td>
                        <td class="right"><?= number_format($invoice['cgst'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>SGST (9%)</td>
                        <td class="right"><?= number_format($invoice['sgst'], 2) ?></td>
                    </tr>
                <?php endif; ?>

            <?php else: ?>
                <tr>
                    <td colspan="2" class="center" style="font-size:11px;">GST Not Applicable on this Invoice</td>
                </tr>
            <?php endif; ?>

            <tr class="bold">
                <td>Total Invoice Amount</td>
                <td class="right"><?= number_format($invoice['grand_total'], 2) ?></td>
            </tr>
            <tr>
                <td>Amount Paid</td>
                <td class="right"><?= number_format($invoice['paid_amount'], 2) ?></td>
            </tr>
            <tr>
                <td><b>Balance Due</b></td>
                <td class="right"><b><?= number_format($invoice['remaining_amount'], 2) ?></b></td>
            </tr>
        </table>

        <table style="margin-top:15px;">
            <tr>
                <td width="60%">
                    <strong>Terms & Conditions:</strong><br>
                    • No refund or replacement after code delivery.<br>
                    • Invoice becomes valid only after full payment.<br>
                    • Software License is non-transferable.<br>
                    • Subject to Thane, Maharashtra jurisdiction.
                </td>

                <td class="center">
                    <div style="border:1px dashed #444;width:120px;height:70px;margin:auto;display:flex;align-items:center;justify-content:center;">
                        STAMP
                    </div>
                    <br><b>Authorized Signatory</b>
                </td>
            </tr>
        </table>

        <p class="center" style="margin-top:10px;font-size:12px;"><i>Thank you for choosing VMIT Technologies!</i></p>

    </div>
</body>

</html>