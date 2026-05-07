# Genric admin notifier local plugin
This plugin is a set of class in order to send notification to moodle admins.
This classes are for use in other plugin.
## Use
To use it in your plugin you must follow the following steps
### Install files
uncompress this plugin in the moodle local directory
### Add dependency to your plugin
* add `$plugin->dependencies = ['local_generic_admin_notifier' => ANY_VERSION];` to your version.php plugin
* not required but cleaner
### Add a message notifier for your plugin
* create or edit db/messages.php uder your plugin directory
* add a message notifier
  * you can use the db/messages.php of the local generic_admin_notifier as example
  * replacing `generic_admin_notifier_provider` by your unique message notifier name
  * for example 'my_plugin_error_notifier'
* add a string to the lang/en plugin file for the message provider string
  * messageprovider:<the_name_of_your_previously_created_message_provider>
  * in our example messageprovider:my_plugin_error_notifier
### Use the class
* you can use tests/generic_admin_notifier_test.php as example
## create and store notifications
* you'll need to create a admin_notifier_list object
```shell
$adminnotificationlist = new admin_notifier_list('local_generic_admin_notifier', 'generic_admin_notifier_provider');
```
  * first argument is your plugin name
  * second is your eroor message provider name created in messages.php
* Then you'll be able to add a admin_notifier object in it
```shell
$adminnotificationlist->add_error(new admin_notifier($notificationinfos, 'firsteventmessage', $user1));
```
* firsteventmessage will have to be entered in your plugin lang file
* $notificationinfos is the object containing informations usefull for 'firsteventmessage' message lang string
  * e.g :
    * $notificationinfos = (object) ['username' => 'username1', 'info1' => 'important information 1', 'info2' => 'important information 2' ]
    * 'Genric admin notifier secondeventmessage {$a->username}  {$a->info1} {$a->info2}'; 
* you can hava has many eventmessage chain as you need
  * don't forget to put them in your plugin lang message
## send notifications
* juste launch `$adminnotificationlist->notify();`
* if you have user infos in your notificationinfos object you can send notification to user with `$adminnotificationlist->notify(true);`