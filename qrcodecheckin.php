<?php

require_once 'qrcodecheckin.civix.php';
use Civi\Api4\Participant;
use CRM_Qrcodecheckin_ExtensionUtil as E;

define('QRCODECHECKIN_PERM', 'check-in participants via qrcode');
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function qrcodecheckin_civicrm_config(&$config) {
  _qrcodecheckin_civix_civicrm_config($config);
  Civi::dispatcher()->addListener('civi.token.list', 'qrcodecheckin_register_tokens');
  Civi::dispatcher()->addListener('civi.token.eval', 'qrcodecheckin_evaluate_tokens');
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
        $msg = E::ts("Found directory in qrcodecheckin folder, I expected only QR code image files. I'm not deleting the folder. I am proceeding with uninstalling the extension.");
        CRM_Core_Error::debug_log_message($msg);
        $session = CRM_Core_Session::singleton();
        $session->setStatus($msg);
        return;
      }
      unlink("$dir/$file");
    }
    mkdir($civiConfig->imageUploadDir . '/qrcodecheckin/');
  }
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
      $form->add('checkbox', 'qrcode_enabled_event', E::ts('Enable QR Code tokens for this Event'));
      $form->add('checkbox', 'qrcode_confirmation_event', E::ts('Add QR Code to confirmation emails'));
      // dynamically insert a template block in the page
      CRM_Core_Region::instance('page-body')->add([
        'template' => "{$templatePath}/qrcode-checkin-event-options.tpl"
      ]);

      $qrcode_events = \Civi::settings()->get('qrcode_events');
      $qrcode_confirmation_events = \Civi::settings()->get('qrcode_confirmation_events') ?? [];
      $event_id = intval($form->getVar('_id'));
      if (in_array($event_id, $qrcode_events)) {
        $defaults['qrcode_enabled_event'] = 1;
      }
      else {
        $defaults['qrcode_enabled_event'] = 0;
      }
      $defaults['qrcode_confirmation_event'] = in_array($event_id, $qrcode_confirmation_events) ? 1 : 0;
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
    $qrcode_confirmation_event = array_key_exists('qrcode_confirmation_event', $vals) ? TRUE : FALSE;

    // Add/Remove event ID to/from array of QR-enabled events as required
    $qrcode_events = \Civi::settings()->get('qrcode_events');
    if ($qrcode_enabled_event) {
      // Add event ID to array of QR-enabled
      if (!in_array($event_id, $qrcode_events)) {
        $qrcode_events[] = $event_id;
        \Civi::settings()->set('qrcode_events', $qrcode_events);
      }
    }
    else if (in_array($event_id, $qrcode_events)) {
      // Remove event ID from array
      $qrcode_events = array_diff($qrcode_events, [$event_id]);
      \Civi::settings()->set('qrcode_events', $qrcode_events);
    }

    $qrcode_confirmation_events = \Civi::settings()->get('qrcode_confirmation_events') ?? [];
    if ($qrcode_confirmation_event) {
      // Add event ID to array of QR-enabled
      if (!in_array($event_id, $qrcode_confirmation_events)) {
        $qrcode_confirmation_events[] = $event_id;
        \Civi::settings()->set('qrcode_confirmation_events', $qrcode_confirmation_events);
      }
    }
    else if (in_array($event_id, $qrcode_confirmation_events)) {
      // Remove event ID from array
      $qrcode_confirmation_events = array_diff($qrcode_confirmation_events, [$event_id]);
      \Civi::settings()->set('qrcode_confirmation_events', $qrcode_confirmation_events);
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
  return hash('sha256', $participant_id . $user_hash . CIVICRM_SITE_KEY);
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
  $fragment = NULL;
  $htmlize = FALSE;
  $frontend = TRUE;
  return CRM_Utils_System::url('civicrm/qrcodecheckin/' . $participant_id . '/' . $code, $query, $absolute, $fragment, $htmlize, $frontend);
}

/**
 * Get QRCode image data.
 */
function qrcodecheckin_get_image_data($url, $base64 = TRUE) {
  require_once __DIR__ . '/vendor/autoload.php';
  $options = new chillerlan\QRCode\QROptions(
    [
      'outputType' => chillerlan\QRCode\QRCode::OUTPUT_IMAGE_PNG,
      'imageBase64' => $base64,
      'imageTransparent' => FALSE,
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
  return CRM_Utils_File::addTrailingSlash($civiConfig->imageUploadURL) . 'qrcodecheckin/' . $code . '.png';
}

/**
 * Helper to return absolute file system path to qrcode image file.
 *
 * This is the path to the image file containing the QR code.
 */
function qrcodecheckin_get_path($code) {
  $civiConfig = CRM_Core_Config::singleton();
  return CRM_Utils_File::addTrailingSlash($civiConfig->imageUploadDir) . 'qrcodecheckin/' . $code . '.png';
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
    Participant::update(FALSE)
      ->addValue('QRCode.QRCode_Public_link', qrcodecheckin_get_image_url($path))
      ->addWhere('id', '=', $participant_id)
      ->execute();
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
  $prefix = E::ts('QR Code Checkin') . ': ';
  $permissions[QRCODECHECKIN_PERM] = [
    'label' => $prefix . E::ts(QRCODECHECKIN_PERM),
    'description' => E::ts('Access the page presented by the QR Code and click to change participant status to attended'),
  ];
}

/**
 * Register the QR code tokens.
 */
function qrcodecheckin_register_tokens(\Civi\Token\Event\TokenRegisterEvent $e): void {
  $qrcode_events = \Civi::settings()->get('qrcode_events');
  if (empty($qrcode_events)) {
    return;
  }
  // There are QR enabled events so let's define tokens for each of them
  $events = \Civi\Api4\Event::get(FALSE)
    ->addSelect('id', 'title')
    ->addClause('OR', ['end_date', 'IS NULL'], ['end_date', '>', date('Y-m-d')])
    ->addWhere('is_active', '=', TRUE)
    ->addWhere('id', 'IN', $qrcode_events)
    ->setLimit(0)
    ->execute();
  foreach ($events as $event) {
    $e->entity('qrcodecheckin')
      ->register('qrcodecheckin.qrcode_url_' . $event['id'], E::ts('QRCode link for event ') . $event['title'])
      ->register('qrcode_html_' . $event['id'], E::ts('QRCode image and link for event ') . $event['title']);
  }

}


/**
 * Evaluate the QR code tokens.
 */
function qrcodecheckin_evaluate_tokens(\Civi\Token\Event\TokenValueEvent $e) {
  $tokens = $e->getTokenProcessor()->getMessageTokens();
  if (array_key_exists('qrcodecheckin', $tokens)) {
    $event_ids = [];
    foreach ($tokens['qrcodecheckin'] as $token) {
      $event_ids[] = preg_replace('/\D/', '', $token);
    }
    foreach ($e->getRows() as $row) {
      // FIXME: We should eventually expose these as participant tokens.
      if (empty($row->context['contactId'])) {
        continue;
      }
      $row->format('text/html');
      $contact_id = $row->context['contactId'];
      foreach ($event_ids as $event_id) {
        $participant_id = qrcodecheckin_participant_id_for_contact_id($contact_id, $event_id);
        if ($participant_id) {
          $code = qrcodecheckin_get_code($participant_id);
          // First ensure the image file is created.
          qrcodecheckin_create_image($code, $participant_id);

          // Get the absolute link to the image that will display the QR code.
          $link = qrcodecheckin_get_image_url($code);
          $row->tokens('qrcodecheckin', 'qrcode_url_' . $event_id, $link);
          $row->tokens('qrcodecheckin', 'qrcode_html_' . $event_id, E::ts('<div><img alt="QR Code with link to checkin page" src="%1"></div><div>You should see a QR code above which will be used to quickly check you into the event. If you do not see a code display above, please enable the display of images in your email program or try accessing it <a href="%1">directly</a>. You may want to take a screen grab of your QR Code in case you need to display it when you do not have Internet access.</div>', [
            1 => $link,
          ]));
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu/
 */
function qrcodecheckin_civicrm_navigationMenu(&$menu) {
    _qrcodecheckin_civix_insert_navigation_menu($menu, 'Administer/CiviEvent', [
        'label' => E::ts('QR Code Checkin Settings'),
        'name' => 'qrcodecheckin_settings',
        'url' => CRM_Utils_System::url('civicrm/admin/setting/qrcode', ['reset' => TRUE]),
        'permission' => 'administer CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
    ]);
}

/**
 * Fetch participant_id from contact_id
 */
function qrcodecheckin_participant_id_for_contact_id($contact_id, $event_id) {

  $sql = "SELECT p.id FROM civicrm_contact c JOIN civicrm_participant p
    ON c.id = p.contact_id WHERE c.is_deleted = 0 AND c.id = %0 AND p.event_id = %1";
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
