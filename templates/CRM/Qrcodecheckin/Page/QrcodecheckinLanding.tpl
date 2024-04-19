<h3>Event Check In Page</h3>
{if $has_permission == FALSE}
  {* Don't provide any sensitive info if they do not have the right permission, but let them know their code is ok *}
  <p>{ts domain="net.ourpowerbase.qrcodecheckin"}Congratulations! Your QR Code for checkin works. Please present your code to an event registration worker when you arrive.{/ts}</p>
{else}
  <div id="qrcheckin-participant-name">{$display_name}</div>
  <div id="qrcheckin-event-name">Event: {$event_title}</div>
  <div id="qrcheckin-status-line" class="{$status_class}">Current Status: <span id="qrcheckin-status">{$participant_status}</span></div>
  <div id="qrcheckin-fee-level-line">Fee Level: {$fee_level}</div>
  <div id="qrcheckin-fee-amount-line">Fee Amount: {$fee_amount}</div>
  <div id="qrcheckin-role">Role ID: {$role}</div>
  {if $update_button == TRUE}
    <button id="qrcheckin-update-button">{ts domain="net.ourpowerbase.qrcodecheckin"}Update to Attended{/ts}</button>
  {/if}
{/if}
