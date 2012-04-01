<?php
$xmlArray = array();
// rearrange things to be compatible with the Xml::fromArray()
$event['Event']['attribute'] = $event['Attribute'];
unset($event['Attribute']);

// cleanup the array from things we do not want to expose
unset($event['Event']['user_id']);
// hide the private fields is we are not in serversync mode
if ('true' != Configure::read('CyDefSIG.serversync')) {
    unset($event['Event']['private']);
    foreach($event['Event']['attribute'] as $key => $value) {
        unset($event['Event']['attribute'][$key]['private']);
    }
}
// hide the org field is we are not in showorg mode
if ('true' != Configure::read('CyDefSIG.showorg') && !$isAdmin) {
    unset($event['Event']['org']);
}

// build up a list of the related events
foreach ($relatedEvents as $relatedEvent) {
    $event['Event']['relatedevent'][] = $relatedEvent['Event'];
}

// display the XML to the user
$xmlArray['CyDefSIG']['event'][] = $event['Event'];
$xmlObject = Xml::fromArray($xmlArray, array('format' => 'tags'));
echo $xmlObject->asXML();
