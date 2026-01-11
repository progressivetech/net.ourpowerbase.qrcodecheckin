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
    $sql = "SELECT title, display_name, st.name as participant_status, fee_level, fee_amount, role_id FROM civicrm_contact c
        JOIN civicrm_participant p ON c.id = p.contact_id
        JOIN civicrm_event e ON e.id = p.event_id
        JOIN civicrm_participant_status_type st ON st.id = p.status_id
        WHERE p.id = %0";
    $dao = CRM_Core_DAO::executeQuery($sql, array(0 => array($this->participant_id, 'Integer')));
    $dao->fetch();
    $this->assign('event_title', $dao->title);
    $this->assign('display_name', $dao->display_name);
    $this->assign('participant_status', $dao->participant_status);
    $this->assign('fee_level', $dao->fee_level);
    $this->assign('fee_amount', $dao->fee_amount);
    $roles = CRM_Core_PseudoConstant::get('CRM_Event_DAO_Participant', 'role_id');
    $this->assign('role', $roles[$dao->role_id]);

    if ($dao->participant_status === 'Registered') {
      $scanAction = \Civi::settings()->get('qrcode_scan_action');
      if ($scanAction !== 'autoupdate') {
        $this->assign('update_button', TRUE);
      }
      else {
        $this->assign('update_button', FALSE);
        \Civi\Api4\Participant::update(FALSE)
          ->addWhere('id', '=', $this->participant_id)
          ->addValue('status_id:name', 'Attended')
          ->execute();
      }
      $this->assign('participant_status', "Was $dao->participant_status, now Attended");
      $this->assign('status_class', 'qrcheckin-status-registered');
    }
    elseif ($dao->participant_status == 'Attended') {
      $this->assign('status_class', 'qrcheckin-status-attended');
    }
    else {
      $this->assign('status_class', 'qrcheckin-status-other');
    }
  }

  private function getDisplayName() {
    $sql = "SELECT display_name FROM civicrm_contact c JOIN civicrm_participant p ON c.id = p.contact_id
      WHERE p.id = %0";
    $dao = CRM_Core_DAO::executeQuery($sql, array(0 => array($this->participant_id, 'Integer')));
    $dao->fetch();
    return $dao->display_name;
  }

  private function refuseAccess() {
    CRM_Core_Error::fatal(E::ts("Woops! The link you clicked on appears to be broken. Please check again and ensure it was not split by a line break.") );
  }
}
