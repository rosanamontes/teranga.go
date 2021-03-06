<?php
/**
 * Elgg mytrips plugin edit action.
 *
* 	Plugin: mytrips Teranga from previous version of @package ElggGroup
*	Author: Rosana Montes Soldado 
*			Universidad de Granada
*	Licence: 	CC-ByNCSA
*	Reference:	Microproyecto CEI BioTIC Ref. 11-2015
* 	Project coordinator: @rosanamontes
*	Website: http://lsi.ugr.es/rosana
* 	Project colaborator: Antonio Moles 
*	
*   Project Derivative:
*	TFG: Desarrollo de un sistema de gestión de paquetería para Teranga Go
*   Advisor: Rosana Montes
*   Student: Ricardo Luzón Fernández
*
*/

elgg_make_sticky_form('mytrips');

/**
 * wrapper for recursive array walk decoding
 */
function profile_array_decoder(&$v) {
	$v = _elgg_html_decode($v);
}

// Get group fields
$input = array();
foreach (elgg_get_config('group') as $shortname => $valuetype) {
	$input[$shortname] = get_input($shortname);

	// @todo treat profile fields as unescaped: don't filter, encode on output
	if (is_array($input[$shortname])) {
		array_walk_recursive($input[$shortname], 'profile_array_decoder');
	} else {
		$input[$shortname] = _elgg_html_decode($input[$shortname]);
	}

	if ($valuetype == 'tags') {
		$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
}

$input['name'] = htmlspecialchars(get_input('name', '', false), ENT_QUOTES, 'UTF-8');

$user = elgg_get_logged_in_user_entity();

$group_guid = (int)get_input('group_guid');
$is_new_group = $group_guid == 0;

if ($is_new_group
		&& (elgg_get_plugin_setting('limited_mytrips', 'mytrips') == 'yes')
		&& !$user->isAdmin()) {
	register_error(elgg_echo("mytrips:cantcreate"));
	forward(REFERER);
}

$group = $group_guid ? get_entity($group_guid) : new ElggGroup();
			
			//Por defecto lo hago destacado
			$group->featured_group = "yes";		
			
			//inicializo arrays de los metadatos
			if(!is_array($group->follower))
			{
				$group->follower = array('_','_');
			}
			if(!is_array($group->preorder))
			{
				$group->preorder = array('_','_');
			}
			if(!is_array($group->confirmed))
			{
				$group->confirmed = array('_','_');
			}
			if(!is_array($group->grade))
			{
				$group->grade = array('_','_');
			}
			if(!is_array($group->summaryPreOrderUserGuid))
			{
				$group->summaryPreOrderUserGuid = array('_','_');
			}
			if(!is_array($group->summaryPreOrderTrayecto))
			{
				$group->summaryPreOrderTrayecto = array('_','_');
			}
			if(!is_array($group->summaryPreOrderBultos))
			{
				$group->summaryPreOrderBultos = array('_','_');
			}
			if(!is_array($group->summaryPreOrderConfirmed))
			{
				$group->summaryPreOrderConfirmed = array('_','_');
			}
			$group->bultosDisponibles=$group->nbultos;


if (elgg_instanceof($group, "group") && !$group->canEdit()) {
	register_error(elgg_echo("mytrips:cantedit"));
	forward(REFERER);
}

// Assume we can edit or this is a new group
if (sizeof($input) > 0) {
	foreach($input as $shortname => $value) {
		// update access collection name if group name changes
		if (!$is_new_group && $shortname == 'name' && $value != $group->name) {
			$group_name = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
			$ac_name = sanitize_string(elgg_echo('mytrips:group') . ": " . $group_name);
			$acl = get_access_collection($group->group_acl);
			if ($acl) {
				// @todo Elgg api does not support updating access collection name
				$db_prefix = elgg_get_config('dbprefix');
				$query = "UPDATE {$db_prefix}access_collections SET name = '$ac_name'
					WHERE id = $group->group_acl";
				update_data($query);
			}
		}

		if ($value === '') {
			// The group profile displays all profile fields that have a value.
			// We don't want to display fields with empty string value, so we
			// remove the metadata completely.
			$group->deleteMetadata($shortname);
			continue;
		}

		$group->$shortname = $value;
	}
}

// Validate create
if (!$group->name) {
	register_error(elgg_echo("mytrips:notitle"));
	forward(REFERER);
}


// Set group tool options
$tool_options = elgg_get_config('group_tool_options');
if ($tool_options) {
	foreach ($tool_options as $group_option) {
		$option_toggle_name = $group_option->name . "_enable";
		$option_default = $group_option->default_on ? 'yes' : 'no';
		$group->$option_toggle_name = get_input($option_toggle_name, $option_default);
	}
}

// Group membership - should these be treated with same constants as access permissions?
$is_public_membership = (get_input('membership') == ACCESS_PUBLIC);
$group->membership = $is_public_membership ? ACCESS_PUBLIC : ACCESS_PRIVATE;

$group->setContentAccessMode(get_input('content_access_mode'));

if ($is_new_group) {
	$group->access_id = ACCESS_PUBLIC;
}

$old_owner_guid = $is_new_group ? 0 : $group->owner_guid;
$new_owner_guid = (int) get_input('owner_guid');

$owner_has_changed = false;
$old_icontime = null;
if (!$is_new_group && $new_owner_guid && $new_owner_guid != $old_owner_guid) {
	// verify new owner is member and old owner/admin is logged in
	if ($group->isMember(get_user($new_owner_guid)) && ($old_owner_guid == $user->guid || $user->isAdmin())) {
		$group->owner_guid = $new_owner_guid;
		if ($group->container_guid == $old_owner_guid) {
			// Even though this action defaults container_guid to the logged in user guid,
			// the group may have initially been created with a custom script that assigned
			// a different container entity. We want to make sure we preserve the original
			// container if it the group is not contained by the original owner.
			$group->container_guid = $new_owner_guid;
		}

		$metadata = elgg_get_metadata(array(
			'guid' => $group_guid,
			'limit' => false,
		));
		if ($metadata) {
			foreach ($metadata as $md) {
				if ($md->owner_guid == $old_owner_guid) {
					$md->owner_guid = $new_owner_guid;
					$md->save();
				}
			}
		}

		// @todo Remove this when #4683 fixed
		$owner_has_changed = true;
		$old_icontime = $group->icontime;
	}
}

$must_move_icons = ($owner_has_changed && $old_icontime);

if ($is_new_group) {
	// if new group, we need to save so group acl gets set in event handler
	if (!$group->save()) {
		register_error(elgg_echo("mytrips:save_error"));
		forward(REFERER);
	} else {
		/*CREO discussions*/
		$topic = new ElggObject();
		$topic->subtype = 'groupforumtopic';
		$topic->title = elgg_echo('mytrips:discussion:title1');
		$topic->description = elgg_echo('mytrips:discussion:description1');
		$topic->status = "open";
		$topic->access_id = "2";
		$topic->container_guid = $group->guid;
		$resultDiscussion = $topic->save();
		elgg_create_river_item(array(
		'view' => 'river/object/groupforumtopic/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $topic->guid,
		));
		
		$topic = new ElggObject();
		$topic->subtype = 'groupforumtopic';
		$topic->title = elgg_echo('mytrips:discussion:title2');
		$topic->description = elgg_echo('mytrips:discussion:description2');
		$topic->status = "open";
		$topic->access_id = "2";
		$topic->container_guid = $group->guid;
		$resultDiscussion = $topic->save();
		elgg_create_river_item(array(
		'view' => 'river/object/groupforumtopic/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $topic->guid,
		));
		if ($group->servicioPaqueteria=="custom:rating:si"){
		$topic = new ElggObject();
		$topic->subtype = 'groupforumtopic';
		$topic->title = elgg_echo('mytrips:discussion:title3');
		$topic->description = elgg_echo('mytrips:discussion:description3');
		$topic->status = "open";
		$topic->access_id = "2";
		$topic->container_guid = $group->guid;
		$resultDiscussion = $topic->save();
		elgg_create_river_item(array(
		'view' => 'river/object/groupforumtopic/create',
		'action_type' => 'create',
		'subject_guid' => elgg_get_logged_in_user_guid(),
		'object_guid' => $topic->guid,
		));	
		}
		
		

	if (!$resultDiscussion) {
		register_error(elgg_echo('discussion:error:notsaved')." | ".$resultDiscussion);
		forward(REFERER);
	}
	}
}

// Invisible group support
// @todo this requires save to be called to create the acl for the group. This
// is an odd requirement and should be removed. Either the acl creation happens
// in the action or the visibility moves to a plugin hook
if (elgg_get_plugin_setting('hidden_mytrips', 'mytrips') == 'yes') {
	$visibility = (int)get_input('vis');

	if ($visibility == ACCESS_PRIVATE) {
		// Make this group visible only to group members. We need to use
		// ACCESS_PRIVATE on the form and convert it to group_acl here
		// because new mytrips do not have acl until they have been saved once.
		$visibility = $group->group_acl;

		// Force all new group content to be available only to members
		$group->setContentAccessMode(ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY);
	}

	$group->access_id = $visibility;
}

if (!$group->save()) {
	register_error(elgg_echo("mytrips:save_error"));
	forward(REFERER);
}

// group saved so clear sticky form
elgg_clear_sticky_form('mytrips');

// group creator needs to be member of new group and river entry created
if ($is_new_group) {

	// @todo this should not be necessary...
	elgg_set_page_owner_guid($group->guid);

	$group->join($user);
	elgg_create_river_item(array(
		'view' => 'river/group/create',
		'action_type' => 'create',
		'subject_guid' => $user->guid,
		'object_guid' => $group->guid,
	));
}

$has_uploaded_icon = (!empty($_FILES['icon']['type']) && substr_count($_FILES['icon']['type'], 'image/'));

if ($has_uploaded_icon) {

	$icon_sizes = elgg_get_config('icon_sizes');

	$prefix = "mytrips/" . $group->guid;

	$filehandler = new ElggFile();
	$filehandler->owner_guid = $group->owner_guid;
	$filehandler->setFilename($prefix . ".jpg");
	$filehandler->open("write");
	$filehandler->write(get_uploaded_file('icon'));
	$filehandler->close();
	$filename = $filehandler->getFilenameOnFilestore();

	$sizes = array('tiny', 'small', 'medium', 'large', 'master');

	$thumbs = array();
	foreach ($sizes as $size) {
		$thumbs[$size] = get_resized_image_from_existing_file(
			$filename,
			$icon_sizes[$size]['w'],
			$icon_sizes[$size]['h'],
			$icon_sizes[$size]['square']
		);
	}

	if ($thumbs['tiny']) { // just checking if resize successful
		$thumb = new ElggFile();
		$thumb->owner_guid = $group->owner_guid;
		$thumb->setMimeType('image/jpeg');

		foreach ($sizes as $size) {
			$thumb->setFilename("{$prefix}{$size}.jpg");
			$thumb->open("write");
			$thumb->write($thumbs[$size]);
			$thumb->close();
		}

		$group->icontime = time();
	}
}

// @todo Remove this when #4683 fixed
if ($must_move_icons) {
	$filehandler = new ElggFile();
	$filehandler->setFilename('mytrips');
	$filehandler->owner_guid = $old_owner_guid;
	$old_path = $filehandler->getFilenameOnFilestore();

	$sizes = array('', 'tiny', 'small', 'medium', 'large');

	if ($has_uploaded_icon) {
		// delete those under old owner
		foreach ($sizes as $size) {
			unlink("$old_path/{$group_guid}{$size}.jpg");
		}
	} else {
		// move existing to new owner
		$filehandler->owner_guid = $group->owner_guid;
		$new_path = $filehandler->getFilenameOnFilestore();

		foreach ($sizes as $size) {
			rename("$old_path/{$group_guid}{$size}.jpg", "$new_path/{$group_guid}{$size}.jpg");
		}
	}

	if ($owner_changed_flag && $old_icontime) { // @todo Remove this when #4683 fixed

		$filehandler = new ElggFile();
		$filehandler->setFilename('mytrips');

		$filehandler->owner_guid = $old_owner_guid;
		$old_path = $filehandler->getFilenameOnFilestore();

		$sizes = array('', 'tiny', 'small', 'medium', 'large');

		foreach($sizes as $size) {
			unlink("$old_path/{$group_guid}{$size}.jpg");
		}
	}

} elseif ($owner_changed_flag && $old_icontime) { // @todo Remove this when #4683 fixed

	$filehandler = new ElggFile();
	$filehandler->setFilename('mytrips');

	$filehandler->owner_guid = $old_owner_guid;
	$old_path = $filehandler->getFilenameOnFilestore();

	$filehandler->owner_guid = $group->owner_guid;
	$new_path = $filehandler->getFilenameOnFilestore();

	$sizes = array('', 'tiny', 'small', 'medium', 'large');

	foreach($sizes as $size) {
		rename("$old_path/{$group_guid}{$size}.jpg", "$new_path/{$group_guid}{$size}.jpg");
	}
}

system_message(elgg_echo("mytrips:saved"));

forward($group->getUrl());
