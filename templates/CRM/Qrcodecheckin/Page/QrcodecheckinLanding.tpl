<h3>Event Check In Page</h3>

{ if $has_permission == false }

  {* Don't provide any sensitive info if they do not have the right permission, but let them know their code is ok *}
  <p>{ts}Congratulations! Your QR Code for checkin works. Please present your code to an event registration worker when you arrive.{/ts}</p>

{ else }

  <div id="qrcheckin-participant-name">{$display_name}</div>
  <div id="qrcheckin-event-name">Event: {$event_title}</div>
  <div id="qrcheckin-status-line" class="{$status_class}">Current Status: <span id="qrcheckin-status">{$participant_status}</span></div>

  { if $update_button }
  <button id="qrcheckin-update-button">{ts}Update to Attended{/ts}</button>
  { /if }

{/if}
