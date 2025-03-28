(function ($, ts){
CRM.$('#qrcheckin-update-button').click(function() {
  const participant_id = CRM.vars.qrcodecheckin.participant_id;
 
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
      alert(ts("There was an error updating the status. Sorry."));
    }
  });
});
}(CRM.$, CRM.ts('net.ourpowerbase.qrcodecheckin')));