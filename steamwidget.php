<?php

/*Steam Status Widget v1.1
By Nicholas Earl
September 2014
*/

libxml_use_internal_errors(true);

//Config section
$steam_key = '29E8803C5F981B2123CBBA99AD31E30D'; //Put your Steam API key here
$steam_group_path = 'SOASE'; //Put your Steam Group path here
$show_online_only = false; //Set to true to show only online players, set to false to show all players
$groupname = $steam_group_path; //Set display name of group.  Can use '$steamgroupdecoded->groupDetails->groupName' (without quotes) to dynamicaly pull the group name


//Get all group member steam IDs
$steamgroup = url_get_contents('http://steamcommunity.com/groups/'.$steam_group_path.'/memberslistxml/?xml=1');

if ($steamgroup AND $steamgroup !== false AND !empty($steamgroup)){
	$steamgroupdecoded = simplexml_load_string($steamgroup);
}

//Create a comma-separated string of group member Steam IDs
$steamids = '';
if (!empty($steamdgroupdecoded) AND property_exists ('members', $steamgroupdecoded)){
	foreach($steamgroupdecoded->members->steamID64 as $value){
		$steamids = ''.$steamids.''.$value.',';
	}
}

//Get player status info
$steamraw = url_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$steam_key.'&steamids='.$steamids);
$steamdecoded = json_decode($steamraw, true);

//Alphabetize by player name
$steamdecodedsort = array();

if( !empty($steamdecoded) AND array_key_exists('response', $steamdecoded)){
	foreach ($steamdecoded['response']['players'] as $key => $value) {
		$steamdecodedsort[$key] = $value['personaname'];
	}
	array_multisort($steamdecodedsort, SORT_ASC, $steamdecoded['response']['players']);


	echo '<div id="steamwidget">';
	echo '<div id="steamwidget-title-area"><div id="steam-widget-title-text">'.$groupname.'</div></div><br>';

	//Display any players in-game
	foreach($steamdecoded['response']['players'] as $value){
		if (array_key_exists('gameextrainfo', $value)) {
		echo '<img src='.$value['avatar'].' class="steamavatar-ingame">';
		echo '<div class="steamplayer-ingame">';
		echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>In-Game<br>'.$value['gameextrainfo'].'</div><br>';
		}
	}

	//Display any online players
	foreach($steamdecoded['response']['players'] as $value){
		if (array_key_exists('gameextrainfo', $value) == false AND $value['personastate'] != 0 AND $value['personastate'] != 2 AND $value['personastate'] != 3 AND $value['personastate'] != 4) {
		echo '<img src='.$value['avatar'].' class="steamavatar-online">';
		echo '<div class="steamplayer-online">';
		echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>Online</div><br>';
		}
	}

	//Display any away players
	foreach($steamdecoded['response']['players'] as $value){
		if (array_key_exists('gameextrainfo', $value) == false AND $value['personastate'] == 3) {
		echo '<img src='.$value['avatar'].' class="steamavatar-online">';
		echo '<div class="steamplayer-online">';
		echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>Away</div><br>';
		}
	}

	//Display any busy players
	foreach($steamdecoded['response']['players'] as $value){
		if (array_key_exists('gameextrainfo', $value) == false AND $value['personastate'] == 2) {
		echo '<img src='.$value['avatar'].' class="steamavatar-online">';
		echo '<div class="steamplayer-online">';
		echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>Busy</div><br>';
		}
	}

	//Display any snoozed players
	foreach($steamdecoded['response']['players'] as $value){
		if (array_key_exists('gameextrainfo', $value) == false AND $value['personastate'] == 4) {
		echo '<img src='.$value['avatar'].' class="steamavatar-online">';
		echo '<div class="steamplayer-online">';
		echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>Snooze</div><br>';
		}
	}

	//Display offline players
	if ($show_online_only == false){
		foreach($steamdecoded['response']['players'] as $value){
			if ($value['personastate'] == 0) {
			echo '<img src='.$value['avatar'].' class="steamavatar-offline">';
			echo '<div class="steamplayer-offline">';
			echo '<a href="'.$value['profileurl'].'">'.$value['personaname'].'</a><br>Offline</div><br>';
			}
		}
	}


	echo '</div>';
}else{
	echo '<div id="steamwidget-error">Steam is down!</div>';
}

//Functions

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}


?>