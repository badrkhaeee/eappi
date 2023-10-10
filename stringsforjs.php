<?php
// --------------------------------------------------------
//  This is a part of the Sparx Systems Pro Cloud Server.
//  Copyright (C) Sparx Systems Pty Ltd
//  All rights reserved.
//
//  This source code can be used only under terms and 
//  conditions of the accompanying license agreement.
// --------------------------------------------------------
	require_once __DIR__ . '/security.php';
	require_once __DIR__ . '/globals.php';
	echo '<div id="jsmess_modelroot">' . _h(_glt('ModelRoot')) . '</div>';
	echo '<div id="error_navigating_to_home">' . _h(_glt('Error navigating to home')) . '</div>';
	echo '<div id="unable_to_load_objects_for_other_models">' . _h(_glt('Unable to load objects for other models')) . '</div>';
	echo '<div id="invalid_full_webea_url">' . _h(_glt('Invalid full WebEA URL')) . '</div>';
	echo '<div id="error_while_searching">' . _h(_glt('Error while searching')) . '</div>';
	echo '<div id="unable_to_retrieve_object_details">' . _h(_glt('Unable to retrieve object details')) . '</div>';
	echo '<div id="error_selecting_diagram_object">' . _h(_glt('Error selecting diagram object')) . '</div>';
	echo '<div id="unable_to_retrieve_server_details">' . _h(_glt('Unable to retrieve server details')) . '</div>';
	echo '<div id="error_retrieving_server_details">' . _h(_glt('Error retrieving server details')) . '</div>';
	echo '<div id="guid_not_found_in_model">' . _h(_glt('GUID not found in model')) . '</div>';
	echo '<div id="error_occurred_while_locating_guid">' . _h(_glt('Error occurred while locating GUID')) . '</div>';
	echo '<div id="invalid_guid_format">' . _h(_glt('Invalid GUID format')) . '</div>';
	echo '<div id="blank_guid_supplied">' . _h(_glt('Blank GUID supplied')) . '</div>';
	echo '<div id="invalid_webea_guid">' . _h(_glt('Invalid WebEA GUID')) . '</div>';
	echo '<div id="error_setting_discussion_state">' . _h(_glt('Error setting discussion state')) . '</div>';
	echo '<div id="miniprops_is_disabled">' . _h(_glt('Mini Properties is disabled')) . '</div>';
	echo '<div id="matrix_profile_incomplete">' . _h(_glt('Matrix profile is incomplete')) . '</div>';
	echo '<div id="navbar_show_browser">' . _h(_glt('Show Browser')) . '</div>';
	echo '<div id="navbar_hide_browser">' . _h(_glt('Hide Browser')) . '</div>';
	echo '<div id="navbar_show_properties">' . _h(_glt('Show Properties View')) . '</div>';
	echo '<div id="navbar_hide_properties">' . _h(_glt('Hide Properties View')) . '</div>';
?>