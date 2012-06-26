<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
&lt;html 
 &lt;head>
  &lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8" /&gt;
  &lt;title&gt;
   &lt;?php echo $this->lang->line('payment_receipt'); ?&gt;
  &lt;/title&gt;
  &lt;style type="text/css"&gt;

   body {
    font-family: Verdana, Geneva, sans-serif;
    margin-left: 35px;
    margin-right: 45px;
   }

   th {
    border: 1px solid #666666;
    background-color: #D3D3D3;
   }

  &lt;/style&gt;
 &lt;/head&gt;
 &lt;body&gt;

  <table width="100%">
   <tr>

    <td width="40%" valign="top">
     &lt;?php echo invoice_to_client_name($invoice); ?&gt;<br />
     &lt;?php echo invoice_to_address($invoice); ?&gt;<br />
     &lt;?php if (invoice_to_address_2($invoice)) { echo invoice_from_address_2($invoice) . '<br />'; } ?&gt;
     &lt;?php echo invoice_to_city_state_zip($invoice); ?&gt;
    </td>

    <td width="60%" valign="top">
     <h1  right;">&lt;?php echo $this->lang->line('payment_receipt'); ?&gt;</h1>
    </td>

   </tr>
   
  </table>

  <br />

  <table width="100%">
   <tr>
    <th  20%;">&lt;?php echo $this->lang->line('date'); ?&gt;</th>
    <th  20%;">&lt;?php echo $this->lang->line('invoice_number'); ?&gt;</th>
    <th  45%;">&lt;?php echo $this->lang->line('notes'); ?&gt;</th>
    <th  15%;">&lt;?php echo $this->lang->line('paid'); ?&gt;</th>
   </tr>
   &lt;?php foreach ($invoice_payments as $payment) { ?&gt;
   <tr>
    <td  center;">&lt;?php echo format_date($payment->payment_date); ?&gt;</td>
    <td  center;">&lt;?php echo $invoice->invoice_number; ?&gt;</td>
    <td>&lt;?php echo nl2br($payment->payment_note); ?&gt;</td>
    <td><div  right; margin-right: 10px;">&lt;?php echo display_currency($payment->payment_amount); ?&gt;</div></td>
   </tr>
   &lt;?php } ?&gt;
   <tr>
    <td colspan="3"><div  right;">&lt;?php echo $this->lang->line('total'); ?&gt;</div></td>
    <td><div  right; margin-right: 10px;">&lt;?php echo display_currency($invoice->invoice_paid); ?&gt;</div></td>
   </tr>
  </table>

 &lt;/body&gt;
&lt;/html&gt;