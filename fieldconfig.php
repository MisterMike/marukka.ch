<?php
use itscoding\facebookconnector\FacebookConnector;
use craft\elements\Entry;

return function (Entry $entry) {
   
    //you can do some fancy stuff with callables 👍 !
    $description = function() use ($entry){
      return $entry->teaserSubTitle;  
    };
    
    //callables will not work here!
    return [
        //if true, the message is posted {default:true}, use a field: $entry->post_on_facebook
        'post_on_facebook' => false,
        //official doc https://developers.facebook.com/docs/graph-api/reference/v2.10/post
        //craft field to get the message to post from {default:''}
        'message' => $entry->message,
        //link to the entry page {default:$entry->getUrl()}
        'link' => $entry->getUrl(),
        //field to get the img url from {default:''} --> no image
        'picture' => (count($entry->fb_image) > 0) ? Craft::$app->request->getHostInfo() . $entry->fb_image->first()->getUrl() : '',
        //field to get the caption from {default:''}
        'caption' => $entry->getAuthor()->getName(),
        //field to get the description from {default:''}
        'description' => $description() 
    ];
}