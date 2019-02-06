CRM.$('#qrcheckin-update-button').click(function() {
  var participant_id;

  // Try Drupal first.
  // We expect: /civicrm/qrcodecheckin/123/blahblahhash
  // We want: 123
  var reg_drupal = /\/qrcodecheckin\/([0-9]+)\//
  var match = reg_drupal.exec(window.location.pathname);
  if (match) {
    participant_id = match[1];
  }
  else {
    // Try wordpress
    // We expect: /wp-admin/admin.php?page=CiviCRM&q=civicrm%2Fqrcodecheckin%2F65%2Fa21855da08cb102d1d217c53dc5824a3a795c1c1a44e971bf01ab9da3a2acbbf
    // We want: 65
    //var reg_wordpress = /\/wp-admin\/admin\.php/
    var reg_wordpress_path = /test\.html/
    var reg_wordpress_extract_q = /[?&]q=([^&]+)(&|$)/ 
    var reg_wordpress_extract_id = /^civicrm\/qrcodecheckin\/([0-9]+)\//
    var path_match = reg_wordpress_path.exec(window.location.pathname);
    if (path_match) {
      // Now we have to extract the query string q.
      var q_match = reg_wordpress_extract_q.exec(location.search);
      if (q_match) {
        // Now URL decode it.
        q = decodeURIComponent(q_match[1].replace(/\+/g, " "));
        // Now we have: civicrm/qrcodecheckin/65/a21855da08cb102d1d217c53dc5824a3a795c1c1a44e971bf01ab9da3a2acbbf
        participant_match = reg_wordpress_extract_id.exec(q);
        if (participant_match) {
          participant_id = participant_match[1];
        }
      }
    }
  }
 
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
