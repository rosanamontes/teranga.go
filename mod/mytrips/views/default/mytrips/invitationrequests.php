<?php
/**
 * A user's trip invitations
 *
 * @uses $vars['invitations'] Optional. Array of ElggGroups
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

if (isset($vars['invitations'])) {
	$invitations = $vars['invitations'];
	unset($vars['invitations']);
} else {
	$user = elgg_get_page_owner_entity();
	$vars['limit'] = get_input('limit', elgg_get_config('default_limit'));
	$vars['offset'] = get_input('offset', 0);
	$vars['count'] = mytrips_get_invited_mytrips($user->guid, false, array('count' => true));
	$invitations = mytrips_get_invited_mytrips($user->guid, false, array(
		'limit' => $limit,
		'offset' => $offset
			));
}

$vars['items'] = $invitations;
$vars['item_view'] = 'group/format/invitationrequest';
$vars['no_results'] = elgg_echo('mytrips:invitations:none');

echo elgg_view('page/components/list', $vars);
