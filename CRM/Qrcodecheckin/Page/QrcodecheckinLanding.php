<?php
use CRM_Qrcodecheckin_ExtensionUtil as E;

class CRM_Qrcodecheckin_Page_QrcodecheckinLanding extends CRM_Core_Page {
  var $participant_id = NULL;
  var $code = NULL;

  public function run() {
    
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(E::ts('QR Code Check-in page'));
    $config = CRM_Core_Config::singleton();
    $path = CRM_Utils_Array::value($config->userFrameworkURLVar, $_GET);
    // Get everything after /qrcodecheckin/
    if (preg_match('#/qrcodecheckin/([0-9]+)/([0-9a-f]+)$#', $path, $matches)) {
      $this->participant_id = $matches[1]; 
      $this->hash = $matches[2]; 
    }
    if (empty($this->participant_id) || empty($this->hash)) {
      $this->refuseAccess();
      return FALSE;
    }

    if (!$this->verifyHash()) {
      $this->refuseAccess();
      return FALSE;
    }

    CRM_Core_Resources::singleton()->addScriptFile('net.ourpowerbase.qrcodecheckin', 'qrcodecheckin.js');
    CRM_Core_Resources::singleton()->addStyleFile('net.ourpowerbase.qrcodecheckin', 'qrcodecheckin.css');
    $this->setDetails();
    parent::run();
  }

  private function verifyHash() {
    $expected_hash = qrcodecheckin_get_code($this->participant_id);
    if ($expected_hash != $this->hash) {
      CRM_Core_Error::debug_log_message("Qrcodecheckin: denied access, hash mis-match for participant id: " . $this->participant_id);
      return FALSE;
    }
    return TRUE;
  }

  private function setDetails() {
    $sql = "SELECT title, display_name, st.name as participant_status FROM civicrm_contact c 
        JOIN civicrm_participant p ON c.id = p.contact_id
        JOIN civicrm_event e ON e.id = p.event_id
        JOIN civicrm_participant_status_type st ON st.id = p.status_id
        WHERE p.id = %0";
    $dao = CRM_Core_DAO::executeQuery($sql, array(0 => array($this->participant_id, 'Integer')));
    $dao->fetch();
    $this->assign('event_title', $dao->title);
    $this->assign('display_name', $dao->display_name);
    $this->assign('participant_status', $dao->participant_status);

    if ($dao->participant_status == 'Registered') {
      $this->assign('update_button', TRUE);
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
    CRM_Core_Error::fatal(ts("Woops! The link you clicked on appears to be broken. Please check again and ensure it was not split by a line break.") );
  }
}
