<h3>Event Check In Page</h3>

<p>

<div id="qrcheckin-participant-name">{$display_name}</div>
<div id="qrcheckin-event-name">Event: {$event_title}</div>
<div id="qrcheckin-status-line" class="{$status_class}">Current Status: <span id="qrcheckin-status">{$participant_status}</span></div>

{ if $update_button }
<button id="qrcheckin-update-button">{ts}Update to Attended{/ts}</button>
{ /if }
