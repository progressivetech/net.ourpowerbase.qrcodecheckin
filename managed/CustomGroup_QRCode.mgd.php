<?php
use CRM_K2bQrcode_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_QRCode',
    'entity' => 'CustomGroup',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'QRCode',
        'title' => E::ts('QRCode'),
        'extends' => 'Participant',
      ],
      'match' => ['name'],
    ],
  ],
  [
    'name' => 'CustomGroup_QRCode_CustomField_QRCode_Public_link',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'QRCode',
        'name' => 'QRCode_Public_link',
        'label' => E::ts('QRCode Public link'),
        'data_type' => 'Link',
        'html_type' => 'Link',
        'column_name' => 'qrcode_public_link',
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
