<?php
use CRM_Qrcodecheckin_ExtensionUtil as E;

class CRM_Qrcodecheckin_Page_QrcodecheckinLanding extends CRM_Core_Page {
  var $participant_id = NULL;
  var $code = NULL;

  public function run() {
    // Set the title first.
    CRM_Utils_System::setTitle(E::ts('QR Code Check-in page'));

    // Now, try to get the participant_id and hash from the URL.
    $config = CRM_Core_Config::singleton();
    $path = $_GET[$config->userFrameworkURLVar] ?? NULL;
    // Get everything after /qrcodecheckin/
    if (preg_match('#/qrcodecheckin/([0-9]+)/([0-9a-f]+)$#', $path, $matches)) {
      $this->participant_id = $matches[1];
      $this->hash = $matches[2];
    }

    Civi::resources()->addVars('qrcodecheckin', ['participant_id' => $this->participant_id]);

    // If we don't have both, refuseAccess with message saying URL might be broken.
    if (empty($this->participant_id) || empty($this->hash)) {
      $this->refuseAccess();
      return FALSE;
    }

    // If we do have them, but they have been altered, send message.
    if (!$this->verifyHash()) {
      $this->refuseAccess();
      return FALSE;
    }

    // Now we know they check out, let's check permission. If they don't have
    // permission to be here, send $pemrission_denied so our template can give
    // them a friendly message that doesn't reveal any information.
    if (!CRM_Core_Permission::check(QRCODECHECKIN_PERM) && !CRM_Core_Permission::check('edit event participants')) {
      $this->assign('has_permission', FALSE);
    }
    else {
      $this->assign('has_permission', TRUE);
      CRM_Core_Resources::singleton()->addScriptFile('net.ourpowerbase.qrcodecheckin', 'qrcodecheckin.js');
      CRM_Core_Resources::singleton()->addStyleFile('net.ourpowerbase.qrcodecheckin', 'qrcodecheckin.css');
      $this->setDetails();
    }
    parent::run();
  }

  private function verifyHash() {
    $expected_hash = qrcodecheckin_get_code($this->participant_id);
    if ($expected_hash != $this->hash) {
      CRM_Core_Error::debug_log_message(E::ts("Qrcodecheckin: denied access, hash mis-match for participant id: %1", [ 1 =>  $this->participant_id]));
      return FALSE;
    }
    return TRUE;
  }

  private function setDetails() {
    $details = \Civi\Api4\Participant::get(FALSE)
      ->addSelect('event_id.title', 'contact_id.display_name', 'status_id:name', 'status_id:label', 'fee_level', 'fee_amount', 'event_id', 'role_id:label')
      ->addWhere('id', '=', $this->participant_id)
      ->execute()
      ->first();
    $this->assign('event_title', $details['event_id.title']);
    $this->assign('display_name', $details['contact_id.display_name']);
    $this->assign('participant_status', $details['status_id:label']);
    $this->assign('fee_level', implode(', ', $details['fee_level'] ?? []));
    $this->assign('fee_amount', $details['fee_amount']);
    $this->assign('role', implode(', ', $details['role_id:label']));
    // Embed afforms. Permission check is false because we're already blocking anonymous users from this function.
    $afforms = \Civi\Api4\Afform::get(FALSE)
      ->addWhere('placement', 'CONTAINS', 'qrcode_landing_page')
      ->addSelect('name')
      ->execute()
      ->column('name');
    if (count($afforms) > 0) {
      $this->assign('afformVars', ['event_id' => $details['event_id'], 'participant_id' => $this->participant_id]);
      foreach ($afforms as $afform) {
        Civi::service('angularjs.loader')->addModules($afform);
        $afformList[$afform] = \CRM_Utils_String::convertStringToDash($afform);
      }
      $this->assign('afformList', $afformList);
    }

    // If auto-update is off, "Registered" is a neutral status, becoming successful when updated to Attended.
    // If auto-update is on, "Registered" is a successful status.
    // "Attended" is always a red flag unless it's as a result of pressing the "Update to Attended" button on this page.
    if ($details['status_id:name'] === 'Registered') {
      $scanAction = \Civi::settings()->get('qrcode_scan_action');
      if ($scanAction !== 'autoupdate') {
        $this->assign('update_button', TRUE);
        $this->assign('status_class', 'qrcheckin-status-not-checked-in');
      }
      else {
        $this->assign('update_button', FALSE);
        \Civi\Api4\Participant::update(FALSE)
          ->addWhere('id', '=', $this->participant_id)
          ->addValue('status_id:name', 'Attended')
          ->execute();
        $this->assign('participant_status', E::ts("Was %1, now Attended", [1 => $details['status_id:label']]));
        $this->assign('status_class', 'qrcheckin-status-success');
      }
    }
    else {
      $this->assign('status_class', 'qrcheckin-status-other');
    }
  }

  private function refuseAccess() {
    CRM_Core_Error::fatal(E::ts("Woops! The link you clicked on appears to be broken. Please check again and ensure it was not split by a line break.") );
  }
}
