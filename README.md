FCMSimple
===
A PHP class to send messages to devices registered through Firebase Cloud Messaging (FCM).

- Adapted from the code available at https://github.com/mattg888/GCM-PHP-Server-Push-Message/blob/master/GCMPushMessage.php with some modifications to work with FCM instead of GCM.
- Add new feature `fixDevices`.

Usage
---
```
require_once("FCMSimple.php");

$fcm = new FCMSimple($serverKey);
$fcm->setDevices($devices);
$response = $fcm->send($message);
$newDevices = $fcm->fixDevices();

// $serverKey  Your FCM server key
// $devices    An array of registered device tokens
// $message    The mesasge you want to push out
// $newDevices New array of fixed ids

// You should then assign $newDevices to $devices
```

License
---
```
Copyright 2016 Khalid Alharisi

    Licensed under the GNU General Public License v3.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

       https://www.gnu.org/licenses/gpl-3.0.txt

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
```
