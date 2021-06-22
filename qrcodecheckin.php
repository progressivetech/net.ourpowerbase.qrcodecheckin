<?php

require_once 'qrcodecheckin.civix.php';
use CRM_Qrcodecheckin_ExtensionUtil as E;

define('QRCODECHECKIN_PERM', 'check-in participants via qrcode');
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function qrcodecheckin_civicrm_config(&$config) {
  _qrcodecheckin_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function qrcodecheckin_civicrm_xmlMenu(&$files) {
  _qrcodecheckin_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function qrcodecheckin_civicrm_install() {
  _qrcodecheckin_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function qrcodecheckin_civicrm_postInstall() {
  _qrcodecheckin_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function qrcodecheckin_civicrm_uninstall() {
  // Ensure directory for qr codes is cleaned up.
  $civiConfig = CRM_Core_Config::singleton();
  $dir = $civiConfig->imageUploadDir . '/qrcodecheckin/';
  if (!file_exists($dir)) {
    $files = array_diff(scandir($dir), ['.','..']);
    foreach ($files as $file) {
      if (is_dir("$dir/$file")) {
        // This is an error, but don't let it gum up the removal of the extension.
        $msg = ts("Found directory in qrcodecheckin folder, I expected only QR code image files. I'm not deleting the folder. I am proceeding with uninstalling the extension.");
        CRM_Core_Error::debug_log_message($msg);
        $session = CRM_Core_Session::singleton();
        $session->setStatus($msg);
        return;
      }
      unlink("$dir/$file");
    }
    mkdir($civiConfig->imageUploadDir . '/qrcodecheckin/');
  }
  _qrcodecheckin_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function qrcodecheckin_civicrm_enable() {
  // Ensure directory for qr codes is available.
  $civiConfig = CRM_Core_Config::singleton();
  if (!file_exists($civiConfig->imageUploadDir . '/qrcodecheckin/')) {
    mkdir($civiConfig->imageUploadDir . '/qrcodecheckin/');
  }

   _qrcodecheckin_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function qrcodecheckin_civicrm_disable() {
  _qrcodecheckin_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function qrcodecheckin_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _qrcodecheckin_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function qrcodecheckin_civicrm_managed(&$entities) {
  _qrcodecheckin_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function qrcodecheckin_civicrm_caseTypes(&$caseTypes) {
  _qrcodecheckin_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function qrcodecheckin_civicrm_angularModules(&$angularModules) {
  _qrcodecheckin_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function qrcodecheckin_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _qrcodecheckin_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function qrcodecheckin_civicrm_entityTypes(&$entityTypes) {
  _qrcodecheckin_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function qrcodecheckin_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_EventInfo') {
    // This form is called once as part of the regular page load and again via an ajax snippet.
    // We only want the new fields loaded once - so limit ourselves to the ajax snippet load.
    if (CRM_Utils_Request::retrieve('snippet', 'String', $form) == 'json') {
      $templatePath = realpath(dirname(__FILE__)."/templates");
      // Add the field element in the form
      $form->add('checkbox', 'qrcode_enabled_event', ts('Enable QR Code tokens for this Event'));
      // dynamically insert a template block in the page
      CRM_Core_Region::instance('page-body')->add([
        'template' => "{$templatePath}/qrcode-checkin-event-options.tpl"
      ]);

      $qrcode_events = Civi::settings()->get('qrcode_events');
      $event_id = intval($form->getVar('_id'));
      if (in_array($event_id, $qrcode_events)) {
        $defaults['qrcode_enabled_event'] = 1;
      }
      else {
        $defaults['qrcode_enabled_event'] = 0;
      }
      $form->setDefaults($defaults);
    }
  }

}

/**
 * Implements hook__civicrm_postProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postProcess/
 */
function qrcodecheckin_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_EventInfo') {
    $vals = $form->_submitValues;
    $event_id = intval($form->getVar('_id'));
    $qrcode_enabled_event = array_key_exists('qrcode_enabled_event', $vals) ? TRUE : FALSE;

    // Add/Remove event ID to/from array of QR-enabled events as required
    $qrcode_events = Civi::settings()->get('qrcode_events');
    if ($qrcode_enabled_event) {
      // Add event ID to array of QR-enabled
      if (!in_array($event_id, $qrcode_events)) {
        $qrcode_events[] = $event_id;
        Civi::settings()->set('qrcode_events', $qrcode_events);
      }
    }
    else if (in_array($event_id, $qrcode_events)) {
      // Remove event ID from array
      $qrcode_events = array_diff($qrcode_events, [$event_id]);
      Civi::settings()->set('qrcode_events', $qrcode_events);
    }
  }
}


/**
 * Create a hash based on the participant id.
 */
function qrcodecheckin_get_code($participant_id) {
  $sql = "SELECT hash FROM civicrm_contact c JOIN civicrm_participant p ON c.id = p.contact_id
   WHERE p.id = %0";
  $dao = CRM_Core_DAO::executeQuery($sql, [0 => [$participant_id, 'Integer']]);
  if ($dao->N == 0) {
    return FALSE;
  }
  $dao->fetch();
  $user_hash = $dao->hash;
  return hash('sha256', $participant_id + $user_hash + CIVICRM_SITE_KEY);
}

/**
 * Get URL for checkin.
 *
 * This is the URL that the QR Code points to when it is
 * read. See qrcodecheckin_get_image_url for the URL of the image
 * file that displays the QR Code.
 */
function qrcodecheckin_get_url($code, $participant_id) {
  $query = NULL;
  $absolute = TRUE;
  return CRM_Utils_System::url('civicrm/qrcodecheckin/' . $participant_id . '/' . $code, $query, $absolute);
}

/**
 * Get QRCode image data.
 */
function qrcodecheckin_get_image_data($url, $base64 = TRUE) {
  require_once __DIR__ . '/vendor/autoload.php';
  $options = new chillerlan\QRCode\QROptions(
    [
      'outputType' => chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
      'imageBase64' => $base64 
    ]
  );
  return (new chillerlan\QRCode\QRCode($options))->render($url);
}

/**
 * Helper to return absolute URL to qrcode image file.
 * 
 * This is the URL to the image file containing the QR code.
 */
function qrcodecheckin_get_image_url($code) {
  $civiConfig = CRM_Core_Config::singleton();
  return $civiConfig->imageUploadURL . '/qrcodecheckin/' . $code . '.png';
}

/**
 * Helper to return absolute file system path to qrcode image file.
 * 
 * This is the path to the image file containing the QR code.
 */
function qrcodecheckin_get_path($code) {
  $civiConfig = CRM_Core_Config::singleton();
  return $civiConfig->imageUploadDir . '/qrcodecheckin/' . $code . '.png';
}

/**
 * Create the qr image file
 */
function qrcodecheckin_create_image($code, $participant_id) {
  $path = qrcodecheckin_get_path($code);
  if (!file_exists($path)) {
    // Since we are saving a file, we don't want base64 data.
    $url = qrcodecheckin_get_url($code, $participant_id);
    $base64 = FALSE;
    $data = qrcodecheckin_get_image_data($url, $base64);
    file_put_contents($path, $data);
  }
}

/**
 * Delete qrcode image if it exists.
 */
function qrcodecheckin_delete_image($code) {
  $path = qrcodecheckin_get_path($code);
  if (file_exists($path)) {
    unlink($path);
  }
}

/**
 * Implements hook_civicrm_permission(&$permissions)
 */
function qrcodecheckin_civicrm_permission(&$permissions) {
  $prefix = ts('QR Code Checkin') . ': ';
  $permissions[QRCODECHECKIN_PERM] = [
    $prefix . ts(QRCODECHECKIN_PERM),
    ts('Access the page presented by the QR Code and click to change participant status to attended'),
  ];
}

/**
 * Implements hook_civicrm_tokens.
 */
function qrcodecheckin_civicrm_tokens(&$tokens) {
  $qrcode_events = Civi::settings()->get('qrcode_events');
  if (empty($qrcode_events)) {
    return;
  }
  // There are QR enabled events so let's define tokens for each of them
  $events = \Civi\Api4\Event::get(FALSE)
    ->addSelect('id', 'title')
    ->addWhere('is_active', '=', TRUE)
    ->addWhere('start_date', '>', date('Y-m-d'))
    ->addWhere('is_online_registration', '=', TRUE)
    ->addWhere('id', 'IN', $qrcode_events)
    ->execute();
  foreach ($events as $event) {
    watchdog('qrcode', 'event: %s', ['%s' => print_r($event, TRUE)], WATCHDOG_NOTICE);
    $token_name = 'qrcodecheckin.qrcode_url_' . $event['id'];
    $token_desc = 'QRCode link for event ' . $event['title'];
    $tokens['qrcodecheckin'][] = [$token_name => ts($token_desc)];
    $token_name = 'qrcodecheckin.qrcode_html_' . $event['id'];
    $token_desc = 'QRCode image and link for event ' . $event['title'];
    $tokens['qrcodecheckin'][] = [$token_name => ts($token_desc)];
  }
}

/**
 * Implements hook_civicrm_tokenValues.
 */
function qrcodecheckin_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = [], $context = null) {
  if (array_key_exists('qrcodecheckin', $tokens)) {
    $tokens['qrcodecheckin'];
    $event_ids = [];
    foreach ($tokens['qrcodecheckin'] as $token) {
      $event_ids[] = preg_replace('/\D/', '', $token);
    }
    foreach($cids as $contact_id) {
      // Allow token values to be overridden by extensions
      $handled = FALSE;
      CRM_Qrcodecheckin_Hook::tokenValues($values[$contact_id], $contact_id, $handled);
      if ($handled) {
        // Hook processed the qrcode tokens for us
        continue;
      }

      $participant_id = qrcodecheckin_participant_id_for_contact_id($contact_id, $event_id);
      if ($participant_id) {
        $code = qrcodecheckin_get_code($participant_id);
        // First ensure the image file is created.
        qrcodecheckin_create_image($code, $participant_id);

        // Get the absolute link to the image that will display the QR code.
        $query = NULL;
        $absolute = TRUE;
        $link = qrcodecheckin_get_image_url($code); 

        $values[$contact_id]['qrcodecheckin.qrcode_url'] = $link;
        $values[$contact_id]['qrcodecheckin.qrcode_html'] = '<div>' .
          '<img alt="QR Code with link to checkin page" src="' . $link .
          '"></div><div>You should see a QR code above which will be used '.
          'to quickly check you into the event. If you do not see a code '.
          'display above, please enable the display of images in your email '.
          'program or try accessing it <a href="' . $link . '">directly</a>. '.
          'You may want to take a screen grab of your QR Code in case you need '.
          'to display it when you do not have Internet access.</div>';
      }
    }
  }
}

/**
 * Fetch participant_id from contact_id
 */
function qrcodecheckin_participant_id_for_contact_id($contact_id) {
  $event_id = civicrm_api3('Setting', 'getvalue', ['name' => 'default_qrcode_checkin_event']);
  if (!$event_id) {
    // We haven't set a default event to generate QR Codes. We don't need to crash, just don't return participant ID and qrcode tokens will be blank.
    return NULL;
  }

  $sql = "SELECT p.id FROM civicrm_contact c JOIN civicrm_participant p 
    ON c.id = p.contact_id WHERE is_deleted = 0 AND c.id = %0 AND p.event_id = %1";
  $params = [
    0 => [$contact_id, 'Integer'],
    1 => [$event_id, 'Integer']
  ];
  $dao = CRM_Core_DAO::executeQuery($sql, $params);
  if ($dao->N == 0) {
    return NULL;
  }
  $dao->fetch();
  return $dao->id;
}
