<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * notification list for administrator class
 * @package local_generic_admin_notifier
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_generic_admin_notifier;

use core_user;

/**
 * notification list for administrator class
 */
class admin_notification_list {
    /**
     * @var concerned moodle component
     */
    private $component;
    /**
     * @var array notification list
     */
    private $notificationlist;

    /**
     * @var message provider
     */
    private $messageprovider;

    /**
     * constructor
     * @param string $component moodle component
     * @param string $messageprovider message provider classname
     */
    public function __construct($component, $messageprovider) {
        $this->component = $component;
        $this->messageprovider = $messageprovider;
        $this->notificationlist = [];
    }

    /**
     * add an admin_notification error to list
     * @param notification $adminnotification notification object
     * @return void
     */
    public function add_error($adminnotification/*admin_notification*/) {
        $this->notificationlist[] = $adminnotification;
    }

    /**
     * add admin_notification array to the notificationlist
     * @param array  $notification an array of admin_notification_list
     * @return void
     */
    public function add_notification($notifications) {
        $this->notificationlist = array_merge($this->notificationlist, $notifications);
    }

    /**
     * is there any notification/erros into the notification list
     * @return int|null
     */
    public function has_errors() {
        return count($this->notificationlist);
    }

    /**
     * notify admin of each notification in notification list through moodle notification system
     * @param boolean $notifyuser  notifiy concerned user
     * @return void
     * @throws \coding_exception
     */
    public function notify_admin($notifyuser = false) {
        global $SITE, $CFG;
        // Error message.
        if (count($this->notificationlist)) {
            $eventdata = null;
            $fullmessage = '';
            $user = false;
            foreach ($this->notificationlist as $currentnotification) {
                if ($eventdata == null) {
                    $user = $currentnotification->get_user();
                    // Current messaging.
                    $eventdata = new \core\message\message();
                    $eventdata->component = $this->component;
                    $eventdata->name = $this->messageprovider;
                    $eventdata->userfrom = core_user::get_noreply_user();
                    $eventdata->subject = get_string('admin_notifier_mail_subject', $this->component);
                    $eventdata->fullmessageformat = FORMAT_HTML;
                    $eventdata->notification = '1';
                    $eventdata->contexturl = $CFG->wwwroot;
                    $eventdata->contexturlname = $SITE->fullname;
                    $eventdata->replyto = core_user::get_noreply_user()->email;
                    $eventdata->fullmessage = get_string(
                        'admin_notifier_' . $currentnotification->get_message(),
                        $this->component,
                        $currentnotification->get_notification_infos()
                    );
                    $eventdata->courseid = SITEID;
                }
                $eventdata->fullmessagehtml =
                    str_replace('\n', '<br/>', $eventdata->fullmessage ?? '');
                // For owner.
                if ($notifyuser && !empty($user)) {
                    $eventdata->userto = $user;
                    message_send($eventdata);
                }
                // For admins.
                $admins = get_admins();
                foreach ($admins as $admin) {
                    $eventdata->userto = $admin;
                    message_send($eventdata);
                }
            }
        }
    }
}
