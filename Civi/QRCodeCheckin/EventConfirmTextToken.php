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
      $participantId = $row->tokenProcessor->context['participantId'];
      $code = qrcodecheckin_get_code($participantId);
      // First ensure the image file is created.
      qrcodecheckin_create_image($code, $participantId);

      // Get the absolute link to the image that will display the QR code.
      $link = qrcodecheckin_get_image_url($code);

      $html = E::ts('<p><img alt="QR Code with link to checkin page" src="%1">You should see a QR code above which will be used to quickly check you into the event. If you do not see a code display above, please enable the display of images in your email program or try accessing it <a href="%1">directly</a>. You may want to take a screen grab of your QR Code in case you need to display it when you do not have Internet access.</p>', [1 => $link]);
      $originalText = $row->tokenProcessor->rowValues[$rowNum]['text/html']['event']['confirm_email_text'] ?? '';
      $row->format('text/html')->tokens('event', 'confirm_email_text', $originalText . $html);
    }
  }

}