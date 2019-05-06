<?php
namespace xavsio4\assorted\devkit\helpers;

use Yii;

/**
 * Css helper class.
 */
class Util
{
    public static function dateTrans($date)
    {
        $newdate = explode('/', $date);
        $newdate = preg_replace('/\s/', '',$newdate[2]).'-'.preg_replace('/\s/', '',$newdate[1]).'-'.preg_replace('/\s/', '',$newdate[0]);
        return $newdate;
    }

    public static function round_up ($value, $places=0) 
    {
      if ($places < 0) { $places = 0; }
      $mult = pow(10, $places);
      return ceil($value * $mult) / $mult;
    }
    
    public function post($url, $params, $user_agent = NULL, $headers = NULL)
    {
            $ch = curl_init($url);  
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $post = http_build_query($params);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            isset($user_agent) ? curl_setopt($ch, CURLOPT_USERAGENT, $user_agent) : NULL;
            isset($headers) ? curl_setopt($ch, CURLOPT_HTTPHEADER, $headers) : NULL;
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;   
    }

    public static function image_to_base64($path_to_image)
    { 
        $type = pathinfo($path_to_image, PATHINFO_EXTENSION);
        $image = file_get_contents($path_to_image);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image);
    } 

    public static function seconds_to_time($seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);
        return $hours.':'.sprintf("%02d",$mins).':'.sprintf("%02d",$secs);
    } 


    public static function base64url_encode($plainText) 
    {
        $base64 = base64_encode($plainText);
        $base64url = strtr($base64, '+/=', '-_,');
        return $base64url;
    }
 
    public static function base64url_decode($plainText) 
    {
        $base64url = strtr($plainText, '-_,', '+/=');
        $base64 = base64_decode($base64url);
        return $base64;
    } 

    public static function getRemoteIPAddress() 
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    

    //distance between 2 points
    public static function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('miles','feet','yards','kilometers','meters'); 
    }

    
    
    /**
     * Returns the browser user agent string.
     *
     * @return string
     */
    public static function getUserAgent()
    {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $browser = strtolower($_SERVER['HTTP_USER_AGENT']);
        } else {
            $browser = 'unknown';
        }
        return $browser;
    }
    
    /**
     * Build VCard (.vcf)
     *
     * @return string
     */
    public static function buildVCard($model, $id)
    {
        // init string
        $string = "BEGIN:VCARD\r\n";
        $string .= "VERSION:3.0\r\n";
        $string .= "REV:" . date("Y-m-d") . "T" . date("H:i:s") . "Z\r\n";
        // loop all properties
        /*$properties = $this->getProperties();
        foreach ($properties as $property) {
            // add to string
            $string .= $this->fold($property['key'] . ':' . $property['value'] . "\r\n");
        }*/
        // add to string
        $string .= "END:VCARD\r\n";
        // return
        return $string;
    }
    
    public static function encode_email($email='info@domain.com', $linkText='Contact Us', $attrs ='class="emailencoder"' )  
	{  
	    // remplazar aroba y puntos  
	    $email = str_replace('@', '&#64;', $email);  
	    $email = str_replace('.', '&#46;', $email);  
	    $email = str_split($email, 5);  
	  
	    $linkText = str_replace('@', '&#64;', $linkText);  
	    $linkText = str_replace('.', '&#46;', $linkText);  
	    $linkText = str_split($linkText, 5);  
	      
	    $part1 = '<a href="ma';  
	    $part2 = 'ilto&#58;';  
	    $part3 = '" '. $attrs .' >';  
	    $part4 = '</a>';  
	  
	    $encoded = '<script type="text/javascript">';  
	    $encoded .= "document.write('$part1');";  
	    $encoded .= "document.write('$part2');";  
	    foreach($email as $e)  
	    {  
	            $encoded .= "document.write('$e');";  
	    }  
	    $encoded .= "document.write('$part3');";  
	    foreach($linkText as $l)  
	    {  
	            $encoded .= "document.write('$l');";  
	    }  
	    $encoded .= "document.write('$part4');";  
	    $encoded .= '</script>';  
	  
	    return $encoded;  
	}
	
	function list_files($dir)  
	{  
	    if(is_dir($dir))  
	    {  
	        if($handle = opendir($dir))  
	        {  
	            while(($file = readdir($handle)) !== false)  
	            {  
	                if($file != "." && $file != ".." && $file != "Thumbs.db")  
	                {  
	                    echo '<a target="_blank" href="'.$dir.$file.'">'.$file.'</a><br>'."\n";  
	                }  
	            }  
	            closedir($handle);  
	        }  
	    }  
	}
	
	function getRealIpAddr()  
	{  
	    if (!emptyempty($_SERVER['HTTP_CLIENT_IP']))  
	    {  
	        $ip=$_SERVER['HTTP_CLIENT_IP'];  
	    }  
	    elseif (!emptyempty($_SERVER['HTTP_X_FORWARDED_FOR']))  
	    //to check ip is pass from proxy  
	    {  
	        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];  
	    }  
	    else  
	    {  
	        $ip=$_SERVER['REMOTE_ADDR'];  
	    }  
	    return $ip;  
	}
        
        function GetIP()
        {
            if ( getenv("HTTP_CLIENT_IP") ) {
                $ip = getenv("HTTP_CLIENT_IP");
            } elseif ( getenv("HTTP_X_FORWARDED_FOR") ) {
                $ip = getenv("HTTP_X_FORWARDED_FOR");
                if ( strstr($ip, ',') ) {
                    $tmp = explode(',', $ip);
                    $ip = trim($tmp[0]);
                }
            } else {
                $ip = getenv("REMOTE_ADDR");
            }
            return $ip;
        }
	
	/****************** 
	*@email - Email address to show gravatar for 
	*@size - size of gravatar 
	*@default - URL of default gravatar to use 
	*@rating - rating of Gravatar(G, PG, R, X) 
	*/  
	function show_gravatar($email, $size, $default, $rating)  
	{  
	    echo '<img src="http://www.gravatar.com/avatar.php?gravatar_id='.md5($email).  
	        '&default='.$default.'&size='.$size.'&rating='.$rating.'" width="'.$size.'px"  
	        height="'.$size.'px" />';  
	} 
	
	function detect_city($ip) 
	{
        
        $default = 'UNKNOWN';

        if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
            $ip = '8.8.8.8';

        $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';
        
        $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
        $ch = curl_init();
        
        $curl_opt = array(
            CURLOPT_FOLLOWLOCATION  => 1,
            CURLOPT_HEADER      => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_USERAGENT   => $curlopt_useragent,
            CURLOPT_URL       => $url,
            CURLOPT_TIMEOUT         => 1,
            CURLOPT_REFERER         => 'http://' . $_SERVER['HTTP_HOST'],
        );
        
        curl_setopt_array($ch, $curl_opt);
        
        $content = curl_exec($ch);
        
        if (!is_null($curl_info)) {
            $curl_info = curl_getinfo($ch);
        }
        
        curl_close($ch);
        
        if ( preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs) )  {
            $city = $regs[1];
        }
        if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  {
            $state = $regs[1];
        }

        if( $city!='' && $state!='' ){
          $location = $city . ', ' . $state;
          return $location;
        }else{
          return $default; 
        }
        
    }
    
    function fb_fan_count($facebook_name)
	{
	    $data = json_decode(file_get_contents("https://graph.facebook.com/".$facebook_name));
	    $likes = $data->likes;
	    return $likes;
	}
	
	function get_client_language($availableLanguages, $default='en')
	{
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$langs=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	
			foreach ($langs as $value){
				$choice=substr($value,0,2);
				if(in_array($choice, $availableLanguages)){
					return $choice;
				}
			}
		} 
		return $default;
	}
	
	function my_twitter($username) 
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
	
	function qr_code($data, $type = "TXT", $size ='150', $ec='L', $margin='0')  
	{
	     $types = array("URL" => "http://", "TEL" => "TEL:", "TXT"=>"", "EMAIL" => "MAILTO:");
	    if(!in_array($type,array("URL", "TEL", "TXT", "EMAIL")))
	    {
	        $type = "TXT";
	    }
	    if (!preg_match('/^'.$types[$type].'/', $data))
	    {
	        $data = str_replace("\\", "", $types[$type]).$data;
	    }
	    $ch = curl_init();
	    $data = urlencode($data);
	    curl_setopt($ch, CURLOPT_URL, 'http://chart.apis.google.com/chart');
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, 'chs='.$size.'x'.$size.'&cht=qr&chld='.$ec.'|'.$margin.'&chl='.$data);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	
	    $response = curl_exec($ch);
	
	    curl_close($ch);
	    return $response;
	}
        
        // take a pdf file and change it to jpeg
        function pdf_to_jpg($pdffile)
        {
            // create Imagick object
            $imagick = new Imagick();
            // Sets the image resolution
            $imagick->setResolution(150, 150);
            $imagick->setCompressionQuality(90);
            // Reads image from PDF
            $imagick->readImage($pdffile[0]);
            // Merges a sequence of images
            $imagick = $imagick->flattenImages();
            // Writes an image
            $imagick->writeImages('name.jpg');
            
            //$imagick->clear(); 
            //$imagick->destroy();
            
            return $imagick;

        }

}