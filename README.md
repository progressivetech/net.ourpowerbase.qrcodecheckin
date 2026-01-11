# QR Code Checkin

QRCode Checkin allows you to send an email that contains a scanable code to the registered participants for your event.

Your registration workers can use any freely available QR Code scanning software on their phones to scan the code and open the encoded web address on their browser.

When they do, they will get the status information about the registration, for example:


![Registered attendee with button to update status](/images/qrcode-checkin-registered.png)

With one click, the registration worker can change their status from registered to attended.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Usage

Once enabled, each event configuration screen will have two new checkboxes underneath the existing "Is this Event Active?" checkbox:

![Checkbox to enable QR Code checkin for this event](/images/qrcode-event-configuration.png)

**Add QR Code to confirmation emails**, **Enable QR Code tokens for this event**
The first option will automatically add the QR code to receipt emails. The second option adds a token to the token browser, which can be inserted into a mailing.

These settings can be set on any number of events at a time.

If tokens are enabled, there will be a token for each QR Code-enabled event that:

 * is set to active
 * allows online registration
 * has a start date later than "now" (ie. at the time of composing your email)

![Same email that include QR Code checkin token](/images/qrcode-compose-email.png)

Recipients will get an email that includes the QR Code as an embedded image:

![User's view of the QR Code in their email](/images/qrcode-view-email.png)

Now onto the event... At the event, be sure to have all registration workers download a QR Code scanner to their phones (there are plenty of free scanners available for Android, [here's one called QR Code Reader](https://play.google.com/store/apps/details?id=me.scan.android.client&hl=en) and on the iPhone it is built into the camera - so no extra software necessary).

Next, the registration worker should login to CiviCRM on their phones.

Since registration workers are often volunteers who should not have full access to your CiviCRM installation, you can create a role for them that must minimally have the following permissions:

 * administer CiviCRM (yes, this is a big one, but without additional permissions there is not a lot they can do with it)
 * access AJAX API
 * check-in participants via qrcode (this permission is provided by the extension)

When a registration worker scans a QR Code, they will see a web address and be given the option to open it in their web browser.

In their web browser, they will be presented with clear information about the participant status, for example:

![Registrant status with button to updat](/images/qrcode-checkin-registered.png)

The registration worker can simply click the button to switch them to attended and off they go.

If they have already checked in and have been coded as Attended (uh oh - someone re-using a registration qr code?), you will see:

![Registrant status with button to updat](/images/qrcode-checkin-attended.png)

If they have any other status, it will be displayed in red:

![Registrant status with button to updat](/images/qrcode-checkin-pending.png)

## Tokens

If enabled, tokens are generated for each event configured to use QR Codes. There are two tokens per event you can use:
* qrcodecheckin.qrcode_html_<eventID> - An HTML block to embed into your email containing the QRCode image and supporting text.
* qrcodecheckin.qrcode_url_<eventID> - contains the direct URL to the QRCode image on the server.

When composing your email the tokens are searchable by your event's name (you don't need to know the event ID).

## Changing contents of QRCode / Tokens

If you wish to override the values of the qrcode tokens / change the contents of the QR Code you can implement 
`hook_civicrm_qrcodecheckin_tokenValues`. You'll need to iterate through an array of possible tokens as they are dynamically
determined by virtue of the events that have QR support enabled.

eg.
```
function myextension_civicrm_qrcodecheckin_tokenValues(&$values, $contact_id, &$handled) {
  foreach ($values as $key => $value) {
    $event_id = preg_replace('/\D/', '', $key);
    $link = 'http://example.org/qrcodes/' . $event_id . '/' . $contact_id . '/myqrcode.png';
    if (strpos($key, 'url')) {
      $value = $link;
    }
    else {
      $value = '<p><img alt="QR Code with participant details" src="' . $link . '">Overirrden HTML</p>';
    }
  }
  // If we handled the generation of the QRCode and URL set $handled=TRUE
  $handled = TRUE;
}
```


## Requirements

* PHP v8.2+
* CiviCRM 6 

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl net.ourpowerbase.qrcodecheckin@https://github.com/progresssivetech/net.ourpowerbase.qrcodecheckin/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/progressivetech/net.ourpowerbase.qrcodecheckin.git
cv en qrcodecheckin
```

## Known Issues

None so far.

