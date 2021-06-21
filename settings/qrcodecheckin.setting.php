<?php

/**
 * Settings used by qrcodecheckin.
 */

return array(
  'qrcode_enabled_events' => array(
    'group_name' => 'QR Code Checkin',
    'group' => 'qrcodecheckin',
    'name' => 'qrcode_enabled_events',
    'type' => 'String',
    'serialize' => CRM_Core_DAO::SERIALIZE_JSON,
    'default' => NULL,
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'The event that will be used when finding the right QRCode for a given contact (in case they are registered for more than one event).',
    'help_text' => 'If enabled, when sending email to contacts that include the QR Checkin Code token, the QR Code for this event will be used (you can only have one event enabled at a time, enabling this event will disable all other events).',
	),
);
