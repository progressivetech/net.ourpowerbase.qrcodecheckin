<?php
use CRM_Qrcodecheckin_ExtensionUtil as E;

/**
 * Qrcodecheckin.Checkin API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_qrcodecheckin_Checkin_spec(&$spec) {
  $spec['participant_id']['api.required'] = 1;
}

/**
 * Qrcodecheckin.Checkin API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_qrcodecheckin_Checkin($params) {
  // Ensure they have the right permission. NOTE: we bypass normal permissions
  // and allow the user to update the status even if they don't have full event
  // registration privileges. That is so we can allow volunteers to do this one
  // simple thing without having to give them privileges to update participants
  // fully.
  if (!CRM_Core_Permission::check(QRCODECHECKIN_PERM) && !CRM_Core_Permission::check('edit event participants')) {
    throw new API_Exception('You do not have the proper permissions to do this.', 1);
  }

  if (!array_key_exists('participant_id', $params)) {
    throw new API_Exception('Please pass participant_id', 1);
  }

  $returnValues = \Civi\Api4\Participant::update(FALSE)
    ->addValue('id', $params['participant_id'])
    ->addValue('status_id:name', 'Attended')
    ->execute();

  return civicrm_api3_create_success($returnValues);
}
