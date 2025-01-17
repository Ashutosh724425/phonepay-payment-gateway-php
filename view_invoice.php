<?php
session_start();
include '../Invoice.php';
require_once '../dompdf/src/Autoloader.php';
require '../vendor/autoload.php';

use Dompdf\Dompdf;

$invoice = new Invoice();
$invoice->checkLoggedIn();

if (!empty($_GET['invoice_id']) && $_GET['invoice_id']) {
  $invoiceValues = $invoice->getInvoice($_GET['invoice_id']);
  $invoiceItems = $invoice->getInvoiceItems($_GET['invoice_id']);
}

$invoiceDate = date("d/M/Y", strtotime($invoiceValues['order_date']));
$finalAmount = $invoiceValues['order_total_after_tax'];
$finalDueAmount = $invoiceValues['order_total_amount_due'];

ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <style>
    .bg-slate-100 {
      background-color: #f1f5f9;
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .text-main {
      color: #1bc587;
    }

    .border-main {
      border-color: #1bc587;
    }

    .w-full {
      width: 100%;
    }

    blockquote,
    dl,
    dd,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    hr,
    figure,
    p,
    pre {
      margin: 0;
    }

    .text-center {
      text-align: center;
    }

    .border-main {
      border-color: #1bc587;
    }

    .whitespace-nowrap {
      white-space: nowrap;
    }

    .bg-main {
      background-color: #1bc587;
    }

    .pb-3 {
      padding-bottom: 1.75rem;
    }

    .border-b {
      border-bottom: 1px solid #e5e7eb;
    }

    .border-b-2 {
      border-bottom: 1px solid #1bc587;
    }

    .table {
      display: table;
    }

    .pl-2 {
      padding-left: 0.5rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .p-3 {
      padding: 0.75rem;
    }

    .pl-4 {
      padding-left: 1rem;
    }

    .border-b-2 {
      border-bottom-width: 2px;
    }

    .align-top {
      vertical-align: top;
    }

    .border-r {
      border-right: 1px solid #e5e7eb;
    }

    .text-right {
      text-align: right;
    }

    .pr-4 {
      padding-right: 1rem;
    }

    .pl-3,
    .pl-2,
    .text-right,
    .py-3 {
      padding: 0.5rem;
    }

    .border-spacing-0 {
      --tw-border-spacing-x: 0px;
      --tw-border-spacing-y: 0px;
      border-spacing: var(--tw-border-spacing-x) var(--tw-border-spacing-y);
    }

    .fixed {
      position: fixed;
    }

    .bottom-0 {
      bottom: 0px;
    }

    .left-0 {
      left: 0px;
    }

    *,
    ::before,
    ::after {
      --tw-border-spacing-x: 0;
      --tw-border-spacing-y: 0;
      --tw-translate-x: 0;
      --tw-translate-y: 0;
      --tw-rotate: 0;
      --tw-skew-x: 0;
      --tw-skew-y: 0;
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      --tw-pan-x: ;
      --tw-pan-y: ;
      --tw-pinch-zoom: ;
      --tw-scroll-snap-strictness: proximity;
      --tw-gradient-from-position: ;
      --tw-gradient-via-position: ;
      --tw-gradient-to-position: ;
      --tw-ordinal: ;
      --tw-slashed-zero: ;
      --tw-numeric-figure: ;
      --tw-numeric-spacing: ;
      --tw-numeric-fraction: ;
      --tw-ring-inset: ;
      --tw-ring-offset-width: 0px;
      --tw-ring-offset-color: #fff;
      --tw-ring-color: #1bc587;
      --tw-ring-offset-shadow: 0 0 #0000;
      --tw-ring-shadow: 0 0 #0000;
      --tw-shadow: 0 0 #0000;
      --tw-shadow-colored: 0 0 #0000;
      --tw-blur: ;
      --tw-brightness: ;
      --tw-contrast: ;
      --tw-grayscale: ;
      --tw-hue-rotate: ;
      --tw-invert: ;
      --tw-saturate: ;
      --tw-sepia: ;
      --tw-drop-shadow: ;
      --tw-backdrop-blur: ;
      --tw-backdrop-brightness: ;
      --tw-backdrop-contrast: ;
      --tw-backdrop-grayscale: ;
      --tw-backdrop-hue-rotate: ;
      --tw-backdrop-invert: ;
      --tw-backdrop-opacity: ;
      --tw-backdrop-saturate: ;
      --tw-backdrop-sepia: ;
    }

    ::backdrop {
      --tw-border-spacing-x: 0;
      --tw-border-spacing-y: 0;
      --tw-translate-x: 0;
      --tw-translate-y: 0;
      --tw-rotate: 0;
      --tw-skew-x: 0;
      --tw-skew-y: 0;
      --tw-scale-x: 1;
      --tw-scale-y: 1;
      --tw-pan-x: ;
      --tw-pan-y: ;
      --tw-pinch-zoom: ;
      --tw-scroll-snap-strictness: proximity;
      --tw-gradient-from-position: ;
      --tw-gradient-via-position: ;
      --tw-gradient-to-position: ;
      --tw-ordinal: ;
      --tw-slashed-zero: ;
      --tw-numeric-figure: ;
      --tw-numeric-spacing: ;
      --tw-numeric-fraction: ;
      --tw-ring-inset: ;
      --tw-ring-offset-width: 0px;
      --tw-ring-offset-color: #fff;
      --tw-ring-color: #1bc587;
      --tw-ring-offset-shadow: 0 0 #0000;
      --tw-ring-shadow: 0 0 #0000;
      --tw-shadow: 0 0 #0000;
      --tw-shadow-colored: 0 0 #0000;
      --tw-blur: ;
      --tw-brightness: ;
      --tw-contrast: ;
      --tw-grayscale: ;
      --tw-hue-rotate: ;
      --tw-invert: ;
      --tw-saturate: ;
      --tw-sepia: ;
      --tw-drop-shadow: ;
      --tw-backdrop-blur: ;
      --tw-backdrop-brightness: ;
      --tw-backdrop-contrast: ;
      --tw-backdrop-grayscale: ;
      --tw-backdrop-hue-rotate: ;
      --tw-backdrop-invert: ;
      --tw-backdrop-opacity: ;
      --tw-backdrop-saturate: ;
      --tw-backdrop-sepia: ;
    }

    .h-12 {
      height: 3rem;
      background-color: transparent;
    }

    .w-1\/2 {
      width: 50%;
    }


    .px-2 {
      padding-left: 0.3rem;
      padding-right: 0.3rem;
    }


    .py-3 {
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
    }

    fieldset {
      margin: 0;
      padding: 0;
    }

    legend {
      padding: 0;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .align-top {
      vertical-align: top;
    }

    .text-sm {
      font-size: 0.875rem;
      line-height: 1.25rem;
    }

    .text-xs {
      font-size: 0.75rem;
      line-height: 1rem;
    }

    .font-bold {
      font-weight: 700;
    }

    .italic {
      font-style: italic;
    }

    .text-main {
      color: #1bc587;
    }

    .text-neutral-600 {
      color: #180e0e;
    }

    .text-neutral-700 {
      color: hwb(0 0% 100%);
    }

    .text-slate-300 {
      color: #000000;
    }

    .text-slate-400 {
      color: #000000;
    }

    .text-white {
      color: #fff;
    }

    ol,
    ul,
    menu {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    /*
Reset default styling for dialogs.
*/

    dialog {
      padding: 0;
    }

    /*
Prevent resizing textareas horizontally by default.
*/

    textarea {
      resize: vertical;
    }



    input::placeholder,
    textarea::placeholder {
      opacity: 1;
      /* 1 */
      color: #9ca3af;
      /* 2 */
    }

    /*
Set the default cursor for buttons.
*/


    :disabled {
      cursor: default;
    }



    ol,
    ul,
    menu {
      list-style: none;
      margin: 0;
      padding: 0;
    }


    .print {
      box-sizing: border-box;
      /* 1 */
      border-width: 0;
      /* 2 */
      border-style: solid;
      /* 2 */
      border-color: #e5e7eb;

    }

    .p {
      margin-top: 20px;
      margin-bottom: 20px;
      padding-left: 7px;
      padding-right: 7px;
      padding-top: 20px;
      padding-bottom: 20px;

    }

    img,
    svg,
    video,
    canvas,
    audio,
    iframe,
    embed,
    object {
      display: block;
      /* 1 */
      vertical-align: middle;
      /* 2 */
    }


    [hidden] {
      display: none;
    }

    .page-break {
      page-break-after: always;
    }

    footer {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #f1f5f9;
      /* Adjust background color as needed */
      text-align: center;
      font-size: 0.75rem;
      /* Adjust font size as needed */

      /* Adjust padding as needed */
    }


    @media print {
      body {
        size: A4;
        margin: 0;
      }

      @page {
        margin: 0;
      }
    }

    @media print {
      .page-break {
        page-break-after: always;
      }
    }


    body {

      font-family: "Dejavu Serif";
    }
  </style>
  <link rel="stylesheet" href="lib/fonts/DejaVuSerif.ttf">
</head>

<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <!-- Invoice Header -->
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                  <img src="data:image/jpeg;base64,<?= base64_encode(file_get_contents('../img/alws logo.jpg')) ?>" style="width:240px;height:auto;" class="h-12" />

                </div>
              </td>
              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <p class="whitespace-nowrap text-slate-400 text-right">Date</p>
                          <p class="whitespace-nowrap font-bold text-main text-right"><?= $invoiceDate ?></p>
                        </td>
                        <td class="pl-4">
                          <p class="whitespace-nowrap text-slate-400 text-right">Invoice</p>
                          <p class="whitespace-nowrap font-bold text-main text-right">#ALWS<?= $invoiceValues['order_id'] ?></p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Invoice From and To -->
      <div class="bg-slate-100 px-14 py-6 text-sm p">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top text-sm text-neutral-600">
                <h3 class="font-bold">Invoice From</h3>
                <p><?= $_SESSION["user"] ?></p>
                <p><?= $_SESSION["address"] ?></p>
                <p><?= $_SESSION["email"] ?></p>
              </td>
              <td class="w-1/2 align-top text-right text-sm text-neutral-600">
                <h3 class="font-bold">Invoice To</h3>
                <p><?= $invoiceValues['order_receiver_name'] ?></p>
                <p><?= $invoiceValues['order_receiver_address'] ?></p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Invoice Items -->
      <div class="px-14 py-10 text-sm text-neutral-700 print">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pl-3 font-bold text-main">#</td>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Product details</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Price</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Total</td>
            </tr>
          </thead>
          <tbody>
            <?php
            $count = 0;
            foreach ($invoiceItems as $invoiceItem) {
              $count++;
              echo '<tr>
                                <td class="border-b py-3 pl-3">' . $count . '</td>
                                <td class="border-b py-3 pl-2">' . $invoiceItem["item_name"] . '</td>
                                <td class="border-b py-3 pl-2 text-right">&#8377;' . $invoiceItem["order_item_price"] . '</td>
                                <td class="border-b py-3 pl-2 text-right">&#8377;' . $invoiceItem["order_item_final_amount"] . '</td>
                            </tr>';
            }
            ?>
            <tr>
              <td colspan="4">
                <table class="w-full border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td class="w-full"></td>
                      <td>
                        <table class="w-full border-collapse border-spacing-0">
                          <tbody>
                            <tr>
                              <td class="border-b p-3">
                                <div class="whitespace-nowrap text-slate-400"><b>Net Total:</b></div>
                              </td>
                              <td class="border-b p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main">&#8377;<?= $invoiceValues['order_total_after_tax'] ?></div>
                              </td>
                            </tr>
                            <?php if ($invoiceValues["discount"] > 0): ?>
                              <tr>
                                <td class="border-b p-3">
                                  <div class="whitespace-nowrap text-slate-400"><b>Discount:</b></div>
                                </td>
                                <td class="border-b p-3 text-right">
                                  <div class="whitespace-nowrap font-bold text-main">&#8377; <?= $invoiceValues["discount"] ?></div>
                                </td>
                              </tr>
                            <?php endif; ?>
                            <tr>
                              <td class="border-b p-3">
                                <div class="whitespace-nowrap text-slate-400"><b>Final Amount:</b></div>
                              </td>
                              <td class="border-b p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main">&#8377;<?= $finalAmount ?></div>
                              </td>
                            </tr>
                            <?php if ($invoiceValues["discount"] > 0): ?>
                              <tr>
                                <td class="border-b p-3">
                                  <div class="whitespace-nowrap text-slate-400"><b>Amount Paid:</b></div>
                                </td>
                                <td class="border-b p-3 text-right">
                                  <div class="whitespace-nowrap font-bold text-main">&#8377; <?= $invoiceValues["order_amount_paid"] ?></div>
                                </td>
                              </tr>
                            <?php endif; ?>
                            <?php if ($invoiceValues["order_total_tax"] > 0): ?>
                              <tr>
                                <td class="border-b p-3">
                                  <div class="whitespace-nowrap text-slate-400"><b>GST:</b></div>
                                </td>
                                <td class="border-b p-3 text-right">
                                  <div class="whitespace-nowrap font-bold text-main">&#8377; <?= $invoiceValues["order_total_tax"] ?></div>
                                </td>
                              </tr>
                            <?php endif; ?>
                            <tr>
                              <td class="bg-main p-3">
                                <div class="whitespace-nowrap font-bold text-white"><b style="color:black;">Amount Due:</b></div>
                              </td>
                              <td class="bg-main p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-white">&#8377;<?= $finalDueAmount ?></div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Thank You Message -->
      <div class="px-14 text-sm text-neutral-700">
        <center><br><br><b style="text-align:center;color:#1bc587;font-size:25px;"> Thank You For Your Business!</b></center>
      </div>

      <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
        Alightway Solutions Pvt. Ltd
        <span class="text-slate-300 px-2">|</span>
        contact@alightwaysolutions.com
        <span class="text-slate-300 px-2">|</span>
        +91-9999122033
      </footer>

      <div class="page-break"></div>



      <div class="bg-slate-100 px-14 py-6 text-sm p">
        <div class="text-center">
          <strong>Terms and Conditions</strong>
        </div>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700 print">
        <ol style="margin: 10px;">
          <li style="margin-top:8px"><strong>1. Payment Terms:</strong> Payment for all invoices is required within 7 days from the date of issuance, unless otherwise agreed upon in writing by both parties.</li>
          <li style="margin-top:8px"><strong>2. Payment Methods:</strong> Payments can be made via bank transfer, credit card, UPI, etc.</li>
          <li style="margin-top:8px"><strong>3. Taxes:</strong> Prices listed on the invoice do not include applicable taxes. The client is responsible for any taxes imposed by local authorities.</li>
          <li style="margin-top:8px"><strong>4. Disputes:</strong> Any disputes regarding the invoice must be raised in writing within 5 days of receipt of the invoice. Failure to do so will be deemed as an acceptance of the invoice.</li>
          <li style="margin-top:8px"><strong>5. Late Fees:</strong> In the event of non-payment, the client will be liable for all costs incurred in the recovery of the outstanding amount, including but not limited to legal fees and collection agency charges.</li>
          <li style="margin-top:8px"><strong>6. Cancellation Policy:</strong> Cancellations made after the work is delivered will incur a cancellation fee of 15% of the total invoice amount.</li>
          <li style="margin-top:8px"><strong>7. Intellectual Property:</strong> All intellectual property rights, including but not limited to copyrights and trademarks, associated with the services provided remain the property of Alightway Solutions.</li>
          <li style="margin-top:8px"><strong>8. Confidentiality:</strong> Both parties agree to keep confidential any information disclosed during the course of the business relationship.</li>
          <li style="margin-top:8px"><strong>9. Termination:</strong> Either side can end the agreement by giving written notice to the other party. Termination will be effective immediately days from the date of notification.</li>
          <li style="margin-top:8px"><strong>10. Governing Law:</strong> This agreement shall be governed by and construed in accordance with the laws of the State of Uttar Pradesh, India.</li>
          <li style="margin-top:8px"><strong>11. Modification:</strong> These terms and conditions may be modified or amended at any time by Alightway Solutions with prior written notice.</li>
        </ol>
      </div>

      <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
        Alightway Solutions Pvt. Ltd
        <span class="text-slate-300 px-2">|</span>
        contact@alightwaysolutions.com
        <span class="text-slate-300 px-2">|</span>
        +91-9999122033
      </footer>
    </div>
  </div>
</body>

</html>

<?php
// Capture the HTML content and store it in a variable
$html = ob_get_clean();

// Initialize Dompdf and load the HTML content
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF
$dompdf->stream('invoice.pdf', array('Attachment' => 0));
?>