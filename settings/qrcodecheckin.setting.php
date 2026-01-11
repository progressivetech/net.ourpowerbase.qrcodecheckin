<?php

/**
 * Settings used by qrcodecheckin.
 */

use CRM_Qrcodecheckin_ExtensionUtil as E;

return [
  'qrcode_events' => [
    'type' => 'String',
    'serialize' => CRM_Core_DAO::SERIALIZE_JSON,
    'default' => [],
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('The events that will use QRCodes for a given contact (can be more than one event).'),
    'help_text' => E::ts('If enabled, when sending email to contacts you can include a QR Checkin Code token for this event.'),
	],
  'qrcode_scan_action' => [
    'name' => 'qrcode_scan_action',
    'title' => E::ts('QR Code Scan Action'),
    'type' => 'String',
    'html_type' => 'Select',
    'default' => 'button',
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('The action to take when a QR code is scanned.'),
    'help_text' => E::ts('Choose whether scanning a QR code automatically checks in the participant, or presents an "Attended" button.'),
    'options' => ['button' => E::ts('Show Button'), 'autoupdate' => E::ts('Automatically Check In')],
    'settings_pages' => [
      'qrcode' => [
        'weight' => 10,
      ],
    ],
  ],
];
