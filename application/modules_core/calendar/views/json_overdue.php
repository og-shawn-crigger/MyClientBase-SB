<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

foreach($overdue as $invoice) {

        $invoice_date = date("Y-m-d", $invoice->invoice_due_date);

        $inv[] = array(
                'id'    => $invoice->invoice_id,
                'title' => $invoice->client_name . ' ('. display_currency($invoice->invoice_total) .')',
                'start' => $invoice_date,
                'url'   => './invoices/edit/invoice_id/'. $invoice->invoice_id,

        );

}

$invoices = $inv;


echo json_encode( $invoices );

?>