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
 * Notification entity
 * @package local_generic_admin_notifier
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_generic_admin_notifier;

/**
 * Notification entity
 */
class notification {
    /**
     * @var notification infos object
     */
    private $notificationinfos;

    /**
     * return notificationinfos
     * @return notification
     */
    public function get_notification_infos() {
        return $this->notificationinfos;
    }

    /**
     * @var message
     */
    private $message;

    /**
     * message getter
     * @return message
     */
    public function get_message() {
        return $this->message;
    }

    /**
     * @var user
     */
    private $user;

    /**
     * user getter
     * @return user
     */
    public function get_user() {
        return $this->user;
    }

    /**
     * constructor
     * @param $notificationinfos
     * @param $message
     * @param $user
     */
    public function __construct($notificationinfos, $message, $user) {
        $this->notificationinfos = $notificationinfos;
        $this->user = $user;
        $this->message = $message;
    }
}
