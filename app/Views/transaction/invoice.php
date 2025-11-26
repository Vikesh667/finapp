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
            margin: 0;
            padding: 0;
            background: #eee;
        }

        .page {
            border: 1px solid #000;
            padding: 10px;
            width: 100%;
            max-width: 710px;
            /* 👈 Final safe width for HTML2PDF (A4) */
            margin: auto;
            background: #fff;
            box-sizing: border-box;
            margin-top: 40px;
            margin-bottom: 40px;
        }


        /* Prevent cut during PDF export */
        html,
        body {
            height: auto !important;
            overflow: visible !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
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

        /* ---------- Responsive UI ---------- */
        @media screen and (max-width: 780px) {
            .top-grid {
                display: block !important;
            }

            .company-block,
            .logo-block {
                width: 100% !important;
                text-align: center !important;
                margin-bottom: 12px;
            }

            table,
            th,
            td {
                font-size: 11.5px;
            }
        }

        /* Hide buttons in print mode */
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div id="invoicePage" class="page">

        <h2 class="center">TAX INVOICE</h2>
        <?php $company = $invoice['company']; ?>

        <!-- ─────────── Top 2 Column ─────────── -->
        <div class="top-grid" style="display:grid; grid-template-columns:60% 35%; gap:10px; align-items:start;">

            <!-- LEFT Company Info -->
            <div class="company-block" style="line-height:18px;">
                <strong style="font-size:18px;"><?= esc($company['company_name']) ?></strong><br>
                <div style="max-width:280px; word-wrap:break-word;">
                    <?= nl2br(esc($company['address'])) ?>
                </div>
                <strong>GSTIN:</strong> <?= esc($company['gst_number']) ?><br>
                <strong>State:</strong> <?= esc($company['state']) ?> <br>
                <strong>State Code : </strong><?= esc($invoice['seller_state_code']) ?>
            </div>

            <!-- RIGHT Logo + Label -->
            <div class="logo-block" style="text-align:center;">
                <strong style="display:block; margin-bottom:6px;">ORIGINAL FOR RECIPIENT</strong>

                <?php if (!empty($company['logo'])): ?>
                    <img src="<?= base_url('assets/uploads/company/' . $company['logo']) ?>"
                        style="width:320px; max-width:100%; height:110px; object-fit:contain; border:1px solid #ccc; padding:4px; border-radius:4px;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/320x95">
                <?php endif; ?>
            </div>

        </div>


        <!-- ─────────── Invoice Info ─────────── -->
        <table>
            <tr>
                <td><b>Invoice No:</b> <?= $invoice['invoice_no'] ?></td>
                <td><b>Date:</b> <?= date("d-m-Y", strtotime($invoice['date'])) ?></td>
                <td><b>Invoice Type:</b> <?= $invoice['gst_applied'] ? 'Tax Invoice (GST)' : 'Invoice (Without GST)' ?></td>
            </tr>
        </table>

        <!-- ─────────── Customer Details ─────────── -->
        <table>
            <tr>
                <th colspan="2">Bill To</th>
            </tr>
            <tr>
                <td>
                    <b><?= esc($invoice['customer']['name']) ?></b><br>
                    <?= nl2br(esc($invoice['customer']['address'])) ?><br>
                    <b>Customer GSTIN:</b> <?= $invoice['gst_number'] ?: 'Not Registered' ?>
                </td>
                <td><b> State:</b> <?= esc($invoice['customer']['state']) ?> |
                    <b>State Code:</b> <?= esc($invoice['customer_state_code']) ?>
                </td>
            </tr>
        </table>

        <!-- ─────────── Item Table ─────────── -->
        <table>
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
                <td><?= esc($invoice['remark']) ?></td>
                <td class="center"><?= $invoice['total_code'] ?></td>
                <td class="right"><?= number_format($invoice['rate'], 2) ?></td>
                <td class="right"><?= number_format($invoice['base_amount'], 2) ?></td>
            </tr>
        </table>

        <!-- ─────────── Terms Left + Amount Right ─────────── -->
        <table>
            <tr>
                <td width="55%">
                    <strong>Terms & Conditions:</strong><br>
                    <div style="font-size:12px; line-height:12px; white-space:pre-line;">
                        <?= nl2br(esc($invoice['terms'])) ?>
                    </div>
                </td>
                <td width="45%">
                    <table style="margin:0;">
                        <tr>
                            <th>Description</th>
                            <th class="right">Amount (₹)</th>
                        </tr>
                        <tr>
                            <td>Subtotal</td>
                            <td class="right"><?= number_format($invoice['base_amount'], 2) ?></td>
                        </tr>
                        <tr>
                            <td>CGST (9%)</td>
                            <td class="right"><?= number_format($invoice['cgst'], 2) ?></td>
                        </tr>
                        <tr>
                            <td>SGST (9%)</td>
                            <td class="right"><?= number_format($invoice['sgst'], 2) ?></td>
                        </tr>
                        <tr>
                            <td>IGST (18%)</td>
                            <td class="right"><?= number_format($invoice['igst'], 2) ?></td>
                        </tr>
                        <tr class="bold">
                            <td>Total Invoice</td>
                            <td class="right"><?= number_format($invoice['grand_total'], 2) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- ─────────── Bank Left + Stamp Right ─────────── -->
        <table>
            <tr>
                <td width="60%">
                    <strong>Bank Details:</strong><br>
                    <div style="font-size:12px; line-height:17px;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="font-weight:bold; width:140px;">Bank Name:</td>
                                <td><?= esc($invoice['banks']['bank_name']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Bank Holder Name:</td>
                                <td><?= esc($invoice['banks']['account_holder_name']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">Account No.:</td>
                                <td><?= esc($invoice['banks']['account_no']) ?></td>
                            </tr>
                            <tr>
                                <td style="font-weight:bold;">IFSC:</td>
                                <td><?= esc($invoice['banks']['ifsc_code']) ?></td>
                            </tr>
                        </table>
                    </div>



                </td>

                <td width="40%" class="center">
                    <div style="width:200px; height:120px;
                margin:auto; display:flex; flex-direction:column;
                align-items:center; justify-content:center;">

                        <span style="font-size:10px; margin-bottom:4px;">
                            For VMIT Technologies PVT LTD
                        </span>

                        <div style="position:relative; width:100px; height:60px;">
                            <!-- Stamp -->
                            <img src="<?= base_url('assets/img/vmit-stamp.jpg') ?>"
                                alt="Stamp"
                                style="width:100px; opacity:0.92;">

                            <!-- Signature overlapping -->
                            <img src="<?= base_url('assets/img/vmit-sign.png') ?>"
                                alt="Signature"
                                style="width:85px; position:absolute;right:70px; top:16px">
                        </div>
                    </div>

                    <br><b>Authorized Signatory</b>
                </td>


            </tr>
        </table>

        <p class="center" style="margin-top:10px; font-size:12px;">
            <i>Thank you for choosing <?= esc($company['company_name']) ?>!</i>
        </p>

        <!-- ─────────── Buttons bottom ─────────── -->
        <div class="center no-print" style="margin-top:18px;">
            <button id="downloadPDF"
                style="background:#0066ff; color:#fff; padding:9px 16px;
                       border-radius:6px; border:none;">
                📄 Download Invoice
            </button>
            <button onclick="shareWhatsApp()"
                style="background:#25D366; color:#fff; padding:9px 16px;
                       border-radius:6px; border:none; margin-left:8px;">
                💬 WhatsApp Share
            </button>
        </div>
    </div>


    <!-- JS for PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        document.getElementById("downloadPDF").addEventListener("click", function() {
            const invoice = document.getElementById("invoicePage");

            const opt = {
                margin: 0,
                filename: "Invoice_<?= $invoice['invoice_no'] ?>.pdf",
                image: {
                    type: "jpeg",
                    quality: 1
                },
                html2canvas: {
                    scale: 3,
                    useCORS: true
                },
                jsPDF: {
                    unit: "mm",
                    format: "a4",
                    orientation: "portrait"
                }
            };

            // Hide buttons during PDF render
            document.querySelectorAll(".no-print").forEach(el => el.style.display = "none");

            html2pdf(invoice, {
                margin: [8, 6, 8, 6], // top, left, bottom, right
                filename: "Invoice_<?= $invoice['invoice_no'] ?>.pdf",
                image: {
                    type: "jpeg",
                    quality: 1
                },
                html2canvas: {
                    scale: 3,
                    useCORS: true,
                    scrollY: 0, // 👈 FIX TOP CUT
                    scrollX: 0
                },
                jsPDF: {
                    unit: "mm",
                    format: "a4",
                    orientation: "portrait"
                },
                pagebreak: {
                    mode: ["avoid-all", "css", "legacy"], // 👈 PREVENT CUTTING MID ROW/TABLE
                }
            }).save();

        });

        /* WhatsApp Share */
        function shareWhatsApp() {
            const msg = `📄 Invoice Ready\nInvoice No: <?= $invoice['invoice_no'] ?>\nAmount: ₹<?= number_format($invoice['grand_total'], 2) ?>\n🔗 ${window.location.href}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, "_blank");
        }
    </script>

</body>

</html>