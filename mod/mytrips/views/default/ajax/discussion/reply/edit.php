<?php
/**
 * Elgg ajax edit form for a discussion reply
 *
* 	Plugin: mytripsTeranga
*	Author: Rosana Montes Soldado from previous version of @package ElggGroups
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
*/

$guid = elgg_extract('guid', $vars);
$hidden = elgg_extract('hidden', $vars, true);

$reply = get_entity($guid);
if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply') || !$reply->canEdit()) {
	return false;
}

$form_vars = array(
	'class' => ($hidden ? 'hidden ' : '') . 'mvl',
	'id' => "edit-discussion-reply-{$guid}",
);
$body_vars = array('entity' => $reply);
echo elgg_view_form('discussion/reply/save', $form_vars, $body_vars);