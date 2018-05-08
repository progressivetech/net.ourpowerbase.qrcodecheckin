<?php

/**
 * Settings used by qrcodecheckin.
 */

return array(
  'enabled_qrcode_checkin_events' => array(
    'group_name' => 'QR Code Checkin',
    'group' => 'qrcodecheckin',
    'name' => 'enabled_qrcode_checkin_events',
    'type' => 'Array',
    'default' => array(),
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'List of events that is using QR Code checkins, controls whether QR Codes will be created for each participant.',
    'help_text' => 'If enabled, everytime a contact registers, a QR Code image will be created for them. You can send them their QR Checkin Code via the QR Checkin Code email token.',
	),
  'default_qrcode_checkin_event' => array(
    'group_name' => 'QR Code Checkin',
    'group' => 'qrcodecheckin',
    'name' => 'default_qrcode_checkin_event',
    'type' => 'Integer',
    'default' => NULL,
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'The event that will be used when finding the right QRCode for a given contact (in case they are registered for more than one event.',
    'help_text' => 'If enabled, when sending email to contacts that include the QR Checkin Code token, the QR Code for this event will be used (you can only have one event enabled at a time, enabling this event will disable all other events).',
	),
);
