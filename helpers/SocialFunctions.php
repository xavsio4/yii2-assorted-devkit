<?php

namespace xavsio4\assorted\devkit\helpers;

use Yii;

/**
 * A useful helper function for time calculations
 *
 * @author Richard Jung <richard@coding.toys>
 * @since 1.0
 */
class TimeHelper extends \yii\helpers\Inflector
{


	public static function my_twitter($username) 
	{
	 	$no_of_tweets = 1;
	 	$feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $no_of_tweets;
	 	$xml = simplexml_load_file($feed);
		foreach($xml->children() as $child) {
			foreach ($child as $value) {
				if($value->getName() == "link") $link = $value['href'];
				if($value->getName() == "content") {
					$content = $value . "";
			echo '<p class="twit">'.$content.' <a class="twt" href="'.$link.'" title="">&nbsp; </a></p>';
				}	
			}
		}	
	}
	

	public static function fb_fan_count($facebook_name)
	{
	    $data = json_decode(file_get_contents("https://graph.facebook.com/".$facebook_name));
	    $likes = $data->likes;
	    return $likes;
	}


}