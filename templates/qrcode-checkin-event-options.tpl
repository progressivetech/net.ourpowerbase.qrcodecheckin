<table>
  <tr id="qrcode-enabled-event-tr">
    <td>&nbsp;</td>
    <td>
      {$form.qrcode_enabled_event.html}
      {$form.qrcode_enabled_event.label}
      <div class="help">{ts domain="net.ourpowerbase.qrcodecheckin"}If enabled, when sending email to contacts you can include a QR Checkin Code token for this event.{/ts}</div>
    </td>
  </tr>
</table>

<script type="text/javascript">
    CRM.$('tr#qrcode-enabled-event-tr').insertAfter('tr.crm-event-manage-eventinfo-form-block-is_active');
</script>

