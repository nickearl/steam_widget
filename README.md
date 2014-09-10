steam_widget
============

A simple web widget that displays the online status of a Steam group's members.

This widget queries the Steam API for info about a Steam group and its members, and displays it in a layout similar to a Steam friends list.

For each member, it shows:

- Steam avatar
- Steam player name (links to player's profile)
- Online status (In-Game, Online, Away, Busy, Offline)

See it in action:  www.soaseclan.net/blog


***What You Need***

- PHP 5
- A Steam group pathname, ie http://steamcommunity.com/groups/[Your-Steam-Group-Path]/
- A Steam API Key (get one at http://steamcommunity.com/dev/apikey)


***Installation***

1) Add steamwidget.php and style.css somewhere accessible.  The plugin is optimized to run as an iframe, sidebar widget, Drupal block view, etc

2) Include style.css wherever you're displaying the widget or add the styles to whatever stylesheet will be used.  The !important declarations may be needed if you have a stubborn CMS (ie, Drupal), otherwise they can be removed



***Configuration***

In steamwidget.php, edit these variables in the Config section

- $steam_key  Replace 'XXXXXXXX' with your Steam API key
- $steam_group_path  Replace '[Path]' with your Steam group path
- $show_online_only  Leave this set to false to show all group members, even if they're offline (like a Steam friends list).  If you have lots of members, you may want to set this to "true" to save space
- $groupname  Set this to whatever you want the group name to be displayed as.  To dynamically pull in the group's official display name, set this to '$steamgroupdecoded->groupDetails->groupName'
