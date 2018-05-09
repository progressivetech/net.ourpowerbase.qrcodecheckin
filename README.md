# QR Code Checkin

![Screenshot](/images/screenshot.png)

QRCode Checkin allows you to send an email that contains a scanable code to the registered participants for your event.

Your registration workers can use any freely available QR Code scanning software on their phones to scan the code and open the encoded web address on their browser.

When they do, they will get the status information about the registration, for example:


![Registered attendee with button to update status](/images/qrcode-checkin-registered.png)

With one click, the registration worker can change their status from registered to attended.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Usage

Once enabled, each event configuration screen will have a new checkbox underneath the existing "Is this Event Active?" checkbox:

![Checkbox to enable QR Code checkin for this event](/images/qrcode-event-configuration.png)

This setting can only be set on one event at a time.

After setting the checkbox for your event, search for all contacts that are registered for the event and place them in a group.

Then, send an email to the group, that includes the qrcodecheckin.qrcode_img token:

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

## Requirements

* PHP v5.4+
* CiviCRM 4.7 

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
`
## Known Issues

None so far.

