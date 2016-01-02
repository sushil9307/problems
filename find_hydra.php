<?php
/************************************************************************************************************************************

Function Name: getShieldMember
Input Parameters :
	member_list --	key value array of given input
	member_initiator -- initiator of message, which changes every time a new member is found which is not present in shield array
	member_shield -- array of shield members which keeps on adding new shield members
Return : 
	member_shield -- array of real members of shield after all values of initial initiator is traversed.
Description :
	This function finds all the real shield members.
	At starting 'Nick Fury' act as initiator and is the only shield member
	Check if the initiator exists as the key in given key value array i.e member_list
	Then traverse through each member of its respective values
	Check if current member is present or not in current member_shield array
	if not present then repeat the process with this member as initiator and add this member to member_shield
	if the initiator doest not exists as the key in member_list array then return the final member_shield array
	repeat the process until all values of initial initiator is traversed.	
	since every shield member would receive a SOS message from at least one other shield member, in this way we will have shield members in the final array
	
	For example 
	we have Nick Fury as initiator and initial shield member , since it exists as key in member_list, its all values are traversed.
	so at first we get tony stark which is not currently present in member_shield, so again repeat the process.
	this time tony stark will be initiator and (nick fury & tony stark) will be there in member_shield
	Now again tony stark exists as key in member list, so its all values will be traversed
	this time Pepper Potts will be initiator and (nick fury, tony stark & Pepper Potts) will be there in member_shield
	Process will go on and finally give all shield members
**************************************************************************************************************************************/

function getShieldMember($member_list, $member_initiator, $member_shield) 
{
	$member_shield[] = $member_initiator; 
	
	if (array_key_exists($member_initiator, $member_list)) 	// check if initiator exists as key in key value array,member_list
	{
		foreach ($member_list[$member_initiator] as $member) 
		{
			if (!in_array($member, $member_shield)) 		// check if the currently traversed member already exists as shield member
			{
				$member_shield = getShieldMember($member_list, $member, $member_shield); 
			}
		}
	}
  return $member_shield;
}


/*	Main starts here	*/

$input="Nick Fury : Tony Stark, Maria Hill, Norman Osborn
    Hulk : Tony Stark, HawkEye, Rogers
    Rogers : Thor
    Tony Stark: Pepper Potts, Nick Fury
    Agent 13 : Agent-X, Nick Fury, Hitler
    Thor: HawkEye, BlackWidow
    BlackWidow:HawkEye
    Maria Hill : Hulk, Rogers, Nick Fury
    Agent-X : Agent 13, Rogers
    Norman Osborn: Tony Stark, Thor";
	
$input_array = explode("\n", $input);	// get each line as an element of array

$all_receivers = array();	// array to keep all receivers of the given input
$all_senders = array();		// array to keep all senders of the given input

/*create a key value array from input array where key-sender and value-receivers*/
foreach ($input_array as $row) 
{
	$receiver_sender = explode(':', $row);	// produce array of two elements; one sender of message and another will contain all receivers
	$sender = trim($receiver_sender[0]);	// first element will be sender and trim it as it contains white spaces.
	$receivers = explode(',', $receiver_sender[1]); // second element is all receivers separated by comma, explode them to get array of receivers
	for($i=0; $i<count($receivers); $i++)
	{
		$receivers[$i] = trim($receivers[$i]);		// trim each receiver to remove white spaces
		array_push($all_receivers,$receivers[$i]); // get all receivers
	}
	$member_list[$sender] = $receivers; 	// get member list as key value array
	array_push($all_senders,$sender); 		// get all senders
}

$member_all = array_unique(array_merge($all_receivers, $all_senders));	// get all members of given input

$member_shield = array_unique(getShieldMember($member_list, 'Nick Fury', ''));	// call to getShieldMember(nick fury - initiator) and get all shield members

$member_hydra = array_diff($member_all, $member_shield); 	// get all hydra member; minus the already computed shield members from all given members

$output = implode(', ', $member_hydra); 	// get the output in desired format by imploding ', '
echo($output);
?>