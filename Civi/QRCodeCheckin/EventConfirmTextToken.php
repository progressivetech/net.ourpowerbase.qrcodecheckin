<?php

namespace Civi\QRCodeCheckin;
use CRM_Qrcodecheckin_ExtensionUtil as E;
use Civi\Core\Service\AutoService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Add the QR code to event confirmation email text if the event is configured to do so.
 *
 * @service civi.qrcodecheckin.tokens
 */

class EventConfirmTextToken extends AutoService implements EventSubscriberInterface {
  public static function getSubscribedEvents() {
    return [
      'civi.token.eval' => ['addQrCodetoEventConfirmText', -100],
    ];
  }

  public function addQrCodetoEventConfirmText(\Civi\Token\Event\TokenValueEvent $e): void {
    $activeTokens = $e->getTokenProcessor()->getMessageTokens();
    if (!in_array('event', array_keys($activeTokens ?? []))) {
      return;
    }
    $eventTokens = $activeTokens['event'] ?? [];
    if (!in_array('confirm_email_text', $eventTokens)) {
      return;
    }
    $eventId = $e->getTokenProcessor()->context['eventId'];
    $qrcode_confirmation_events = \Civi::settings()->get('qrcode_confirmation_events');
    if (!in_array($eventId, $qrcode_confirmation_events ?? [])) {
      return;
    }

    foreach ($e->getRows() as $rowNum => $row) {
      $contactId = $row->context['contactId'] ?? $row->tokenProcessor->context['contactId'] ?? NULL;
      $links = qrcodecheckin_get_qrcode_links($eventId, $contactId);
      if (empty($links)) {
        continue;
      }
      $html = qrcodecheckin_generate_url_token($links);
      $originalText = $row->tokenProcessor->rowValues[$rowNum]['text/html']['event']['confirm_email_text'] ?? '';
      $row->format('text/html')->tokens('event', 'confirm_email_text', $originalText . $html);
    }
  }

}
