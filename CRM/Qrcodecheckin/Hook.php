<?php

/**
 * Class CRM_Qrcodecheckin_Hook
 *
 * This class implements hooks for Qrcodecheckin
 */
class CRM_Qrcodecheckin_Hook {

  /**
   * This hook allows to alter qrcodecheckin tokens.
   *
   * @param array $values Array of token values for current contactId
   * @param int $contactId
   * @param bool $handled - Set to TRUE if your hook handled the token values, FALSE to allow default handling
   *
   * @return mixed
   */
  public static function tokenValues(&$values, $contactId, &$handled) {
    return CRM_Utils_Hook::singleton()
      ->invoke(['values', 'contactId', 'handled'], $values, $contactId, $handled, CRM_Utils_Hook::$_nullObject,
        CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, 'civicrm_qrcodecheckin_tokenValues');
  }

}
