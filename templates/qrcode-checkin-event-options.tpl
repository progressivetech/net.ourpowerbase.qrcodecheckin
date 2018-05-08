<table>
  <tr id="enable-qrcode-checkin-tr">
    <td>&nbsp;</td>
    <td>
      {$form.enable_qrcode_checkin.html}
      {$form.enable_qrcode_checkin.label}
      <div class="description">{ts}If enabled, everytime a contact registers, a QR Code image will be created for them. You can send them their QR Checkin Code via the QR Checkin Code email token.{/ts}</div>
    </td>
  </tr>
  <tr id="default-qrcode-checkin-event-tr">
    <td>&nbsp;</td>
    <td>
      {$form.default_qrcode_checkin_event.html}
      {$form.default_qrcode_checkin_event.label}
      <div class="description">{ts}If enabled, when sending email to contacts that include the QR Checkin Code token, the QR Code for this event will be used (you can only have one event enabled at a time, enabling this event will disable all other events).{/ts}</div>
    </td>
  </tr>
</table>

{* reposition the above block after #someOtherBlock *}
<script type="text/javascript">
  console.log("Hi new?");
    CRM.$('tr#enable-qrcode-checkin-tr').insertAfter('tr.crm-event-manage-eventinfo-form-block-is_active');
    CRM.$('tr#default-qrcode-checkin-event-tr').insertAfter('tr#enable-qrcode-checkin-tr')
</script>

