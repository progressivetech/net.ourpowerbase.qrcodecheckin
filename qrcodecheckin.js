CRM.$('#qrcheckin-update-button').click(function() {
  // Get participant_id from the URL 
  // (/civicrm/qrcodecheckin/123/blahblahhash)
  var reg = /\/qrcodecheckin\/([0-9]+)\//
  var participant_id = reg.exec(window.location.pathname)[1];
  CRM.api3('Qrcodecheckin', 'Checkin', {
    "sequential": 1,
    "participant_id": participant_id
  }).done(function(result) {
    if (result['is_error'] == 0) {
      CRM.$('#qrcheckin-status').html('Attended');
      CRM.$('#qrcheckin-status-line').removeClass( "qrcheckin-status-registered" ).addClass( "qrcheckin-status-attended" );
      CRM.$('#qrcheckin-update-button').hide();
    }
    else {
      console.log(result);
      alert("There was an error updating the status. Sorry.");
    }
  });
});
