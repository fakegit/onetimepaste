<?php
/*
* OneTimePaste
*
* @author Alberto Gonzalez Iniesta
* @copyright 2014 Alberto Gonzalez Iniesta <agi@inittab.org>
*
*     This program is free software: you can redistribute it and/or modify
*     it under the terms of the GNU General Public License as published by
*     the Free Software Foundation, either version 3 of the License, or
*     (at your option) any later version.
*
*     This program is distributed in the hope that it will be useful,
*     but WITHOUT ANY WARRANTY; without even the implied warranty of
*     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*     GNU General Public License for more details.
*
*     You should have received a copy of the GNU General Public License
*     along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/
include "config.php";
# Do not use cookies
ini_set("session.use_cookies",0);
ini_set("session.use_only_cookies",0);
# Check all goes thru index.php
define('INCLUDED_FROM_INDEX', True);

# Do not include header when recovering files
if (!isset($_GET["fileid"]) || !strlen($_GET["fileid"]) > 0) {
	include "templates/head.php";
# ... unless we require view confirmation
} else {
	if (isset($prompt2show) && $prompt2show == true && (!isset($_GET['ok']) || $_GET['ok'] != 'ok')) {
		include "templates/head.php";
	}
}

# Running this software without https makes little sense
if (isset($force_https) && $force_https == true && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
	include "templates/not_without_ssl.php";
} elseif(isset($_GET["id"]) && strlen($_GET["id"]) > 0) {
	if (isset($prompt2show) && $prompt2show == true && (!isset($_GET['ok']) || $_GET['ok'] != 'ok')) {
		include "confirmation.php";
	} else {
		include "recover_msg.php";
	}
} elseif (isset($_GET["fileid"]) && strlen($_GET["fileid"]) > 0) {
	if (isset($prompt2show) && $prompt2show == true && (!isset($_GET['ok']) || $_GET['ok'] != 'ok')) {
		include "confirmation.php";
	} else {
		include "recover_file.php";
	}
} elseif (isset($_POST["submitmsg"]) && isset($_POST["message"]) && strlen($_POST["message"]) > 0) {
	# Check session to avoid multiple posts
	ini_set("session.use_trans_sid",0); # Disallow sending php_session_id via request
	session_start();
	$_SESSION['count'] += 1; # Increase session counter

	if (isset($_SESSION['count']) && $_SESSION['count'] > 1) {
		include "templates/save_foot.php";
	} else {
		include "save_msg.php";
	}
} elseif (isset($_POST["submitfile"]) && isset($_FILES) && sizeof($_FILES) > 0 &&
        	strlen(trim($_FILES['file']['name'])) > 0 && $_FILES['file']['size'] > 0) {
	# Check session to avoid multiple posts
	ini_set("session.use_trans_sid",0); # Disallow sending php_session_id via request
	session_start();
	$_SESSION['count'] += 1; # Increase session counter

	if (isset($_SESSION['count']) && $_SESSION['count'] > 1) {
		include "templates/save_foot.php";
	} else {
		include "save_file.php";
	}
} else {
	# Start session to control multiple posts
	ini_set("session.use_trans_sid",1); # Allow sending php_session_id via request
	session_start();
	$_SESSION['count'] = 0; # Init session counter

	include "enter_msg.php";
}

include "templates/foot.php";
?>
