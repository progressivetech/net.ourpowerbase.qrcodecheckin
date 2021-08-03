<?php

/**
 * Settings used by qrcodecheckin.
 */

use CRM_Qrcodecheckin_ExtensionUtil as E;

return [
  'qrcode_events' => [
    'group_name' => 'QR Code Checkin',
    'group' => 'qrcodecheckin',
    'name' => 'qrcode_events',
    'type' => 'String',
    'serialize' => CRM_Core_DAO::SERIALIZE_JSON,
    'default' => [],
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('The events that will use QRCodes for a given contact (can be more than one event).'),
    'help_text' => E::ts('If enabled, when sending email to contacts you can include a QR Checkin Code token for this event.'),
	],
];
