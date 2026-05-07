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
 * test notifier
 * @package local_generic_admin_notifier
 * @copyright  2025 Université de Strasbourg  {@link http://unistra.fr}
 * @author Celine Perves <cperves@unistra.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_generic_admin_notifier;

use advanced_testcase;
use local_generic_admin_notifier\notification;
use local_generic_admin_notifier\admin_notification_list;
use stdClass;

/**
 * notifier tests
 */
final class generic_admin_notifier_test extends advanced_testcase {

    /**
     * test notifier \local_gen
     * @covers \local_generic_admin_notifier\admin_notification_list::add_notification
     * @covers \local_generic_admin_notifier\admin_notification_list::add_error
     * @covers \local_generic_admin_notifier\admin_notification_list::notify_admin
     * @return void
     * @throws \coding_exception
     */
    public function test_notifier(): void {
        $user1 = $this->getDataGenerator()->create_user(['maildisplay' => 1]);
        $user2 = $this->getDataGenerator()->create_user(['maildisplay' => 1]);
        $notification1 = new stdClass();
        $notification1->info1 = 'notification1 info1';
        $notification1->info2 = 'notification1 info2';
        $notification2 = new stdClass();
        $notification2->info1 = 'notification2 info1';
        $notification2->info2 = 'notification2 info2';
        $adminnotificationlist = new admin_notification_list('local_generic_admin_notifier', 'generic_admin_notifier_provider');
        $adminnotificationlist->add_error(new notification($notification1, 'firsteventmessage', $user1));
        $adminnotificationlist->add_error(new notification($notification1, 'secondeventmessage', $user1));
        $adminnotificationlist->add_notification([
            new notification($notification2, 'firsteventmessage', $user2),
            new notification($notification2, 'secondeventmessage', $user2),
        ]);
        $sink = $this->redirectMessages();
        $adminnotificationlist->notify_admin(true);
        $savedmessages = $sink->get_messages();
        $this->assertCount(8, $savedmessages); // 4 messages for admin, 2 for user1 and 2 for user2.
        $sink->clear();
        $sink->close();
    }

    /**
     * setUp tests
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest(true);
        $this->preventResetByRollback(); // Logging waits till the transaction gets committed.
    }
}
