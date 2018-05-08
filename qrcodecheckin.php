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

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function qrcodecheckin_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function qrcodecheckin_civicrm_navigationMenu(&$menu) {
  _qrcodecheckin_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _qrcodecheckin_civix_navigationMenu($menu);
} // */

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function qrcodecheckin_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_EventInfo') {
    // This form is called once as part of the regular page load and again via an ajax snippet.
    // We only want the new fields loaded once - so limit ourselves to the ajax snippet load.
    if (CRM_Utils_Request::retrieve('snippet', 'String', $this) == 'json') {
      $templatePath = realpath(dirname(__FILE__)."/templates");
      // Add the field element in the form
      $form->add('checkbox', 'enable_qrcode_checkin', ts('Enable QR Code checkin for this event'));
      $form->add('checkbox', 'default_qrcode_checkin_event', ts('When generating tokens, use this Event'));
      // dynamically insert a template block in the page
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => "{$templatePath}/qrcode-checkin-event-options.tpl"
      ));

      $default_event_setting = civicrm_api3('Setting', 'getvalue', array('name' => 'default_qrcode_checkin_event'));
      $enabled_events_setting = civicrm_api3('Setting', 'getvalue', array('name' => 'enabled_qrcode_checkin_events'));
      $event_id = intval($form->getVar('_id'));
      if (in_array($event_id, $enabled_events_setting)) {
        $defaults['enable_qrcode_checkin'] = 1;
      }
      if ($default_event_setting == $event_id) {
        $defaults['default_qrcode_checkin_event'] = 1;
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
    $enable_qrcode_checkin = array_key_exists('enable_qrcode_checkin', $vals) ? TRUE : FALSE;
    $default_qrcode_checkin_event = array_key_exists('default_qrcode_checkin_event', $vals) ? TRUE : FALSE;

    // Handle Default setting.
    $default_event_setting = civicrm_api3('Setting', 'getvalue', array('name' => 'default_qrcode_checkin_event'));
    if ($default_qrcode_checkin_event) {
      if ($default_event_setting != $event_id) {
        // Update
        civicrm_api3('Setting', 'create', array('default_qrcode_checkin_event' => $event_id));
      }
    }
    else {
      if ($default_event_setting == $event_id) {
        civicrm_api3('Setting', 'create', array('default_qrcode_checkin_event' => NULL));
      }
    }
    
    // Handle Enabled.
    $enabled_events_setting = civicrm_api3('Setting', 'getvalue', array('name' => 'enabled_qrcode_checkin_events'));
    if ($enable_qrcode_checkin) {
      if (!in_array($event_id, $enabled_events_setting)) {
        $enabled_events_setting[] = $event_id;
        civicrm_api3('Setting', 'create', array('enabled_qrcode_checkin_events' => $enabled_events_setting));
      }
    }
    else {
      if (in_array($event_id, $enabled_events_setting)) {
        $key = array_search($event_id, $enabled_events_setting);
        if ($key !== FALSE) {
          unset($enabled_events_setting[$key]);
          civicrm_api3('Setting', 'create', array('enabled_qrcode_checkin_events' => $enabled_events_setting));
        }
      }
    }

  }
}

/**
 * Implements hook_civicrm_post().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_post/
 */
function qrcodecheckin_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  $delete_ops = array('delete', 'trash');
  $create_ops = array('create', 'edit', 'restore');

  if ($objectName == 'Participant') {
    $enabled_events_setting = civicrm_api3('Setting', 'getvalue', array('name' => 'enabled_qrcode_checkin_events'));
    if (in_array($objectRef->event_id, $enabled_events_setting)) {
      $participant_id = $objectId;
      if (in_array($op, $create_ops)) {
        // Ensure the qrcode image file is created.
        $code = qrcodecheckin_get_code($participant_id);
        qrcodecheckin_create_image($code, $participant_id);
      }
      elseif (in_array($op, $delete_ops)) {
        $code = qrcodecheckin_get_code($participant_id);
        qrcodecheckin_delete_image($code);
      }
    }
  }
}

/**
 * Create a hash based on the participant id.
 */
function qrcodecheckin_get_code($participant_id) {
  $sql = "SELECT hash FROM civicrm_contact c JOIN civicrm_participant p ON c.id = p.contact_id
   WHERE p.id = %0";
  $dao = CRM_Core_DAO::executeQuery($sql, array(0 => array($participant_id, 'Integer')));
  if ($dao->N == 0) {
    return FALSE;
  }
  $dao->fetch();
  $user_hash = $dao->hash;
  return hash('sha256', $participant_id + $user_hash + CIVICRM_SITE_KEY);
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
    dpm("Url: $url");
  }
}

/**
 * Get URL
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
    array(
      'outputType' => chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
      'imageBase64' => $base64 
    )
  );
  return (new chillerlan\QRCode\QRCode($options))->render($url);
}

/**
 * Helper to return full file path to qrcode.
 */
function qrcodecheckin_get_path($code) {
  $civiConfig = CRM_Core_Config::singleton();
  return $civiConfig->imageUploadDir . '/qrcodecheckin/' . $code . '.png';
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
  $permissions[QRCODECHECKIN_PERM] = array(
    $prefix . ts(QRCODECHECKIN_PERM),
    ts('Access the page presented by the QR Code and click to change participant status to attended'),
  );
}

/**
 * Implements hook_civicrm_tokens.
 */
function qrcodecheckin_civicrm_tokens(&$tokens) {
  $tokens['qrcodecheckin'] = array(
    'qrcodecheckin.qrcode_img' => ts("HTML image tag with qrcode embedded in it."),
    'qrcodecheckin.qrcode_url' => ts("URL to the qrcode image file (useful for text version of email)."),
    'qrcodecheckin.qrcode_html' => ts("Block of HTML code with both image and link"),
  );
}

/**
 * Implements hook_civicrm_tokenValues.
 */
function qrcodecheckin_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {

  if (array_key_exists('qrcodecheckin', $tokens)) {
    foreach($cids as $contact_id) {
      $participant_id = qrcodecheckin_participant_id_for_contact_id($contact_id);
      if ($participant_id) {
        $code = qrcodecheckin_get_code($participant_id);
        // First ensure the image file is created.
        qrcodecheckin_create_image($code, $participant_id);
        // Now, get the data for an embedded image.
        $url = qrcodecheckin_get_url($code, $participant_id);
        $base64 = TRUE;
        $data = qrcodecheckin_get_image_data($url, $base64);
        $img = '<img src="' . $data . '">';
        $values[$contact_id]['qrcodecheckin.qrcode_img'] = $img;
        $values[$contact_id]['qrcodecheckin.qrcode_link'] = $url;;
        $values[$contact_id]['qrcodecheckin.qrcode_html'] = '<div>' . $img . '</div><div>Trouble reading your QR Code? Trying accessing it <a href="' . $url . '">directly</a></div>';
      }
    }
  }
}

/**
 * Fetch participant_id from contact_id
 */
function qrcodecheckin_participant_id_for_contact_id($contact_id) {
  $event_id = civicrm_api3('Setting', 'getvalue', array('name' => 'default_qrcode_checkin_event'));
  $sql = "SELECT p.id FROM civicrm_contact c JOIN civicrm_participant p 
    ON c.id = p.contact_id WHERE is_deleted = 0 AND c.id = %0 AND p.event_id = %1";
  $params = array(
    0 => array($contact_id, 'Integer'),
    1 => array($event_id, 'Integer')
  );
  $dao = CRM_Core_DAO::executeQuery($sql, $params);
  if ($dao->N == 0) {
    return NULL;
  }
  $dao->fetch();
  return $dao->id;
}
