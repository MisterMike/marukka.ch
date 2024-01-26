<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\K4TwigExtensionsTrait;

class ThumbalizerExtension extends AbstractExtension
{
    use K4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Thumbalizer');
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            $this->addFunction('thumbalizer'),
        ];
    }

    /**
     * @param string $string
     * @return bool
     */
    public function Thumbalizer(string $string):bool
    {
        return file_exists(\Craft::getAlias('@templates', false) . '/' . $string);
    }

    public function thumbalizr($url, $options = array()) {

        /* useage: <img src="<?php echo thumbalizr('https://browshot.com/', array('width' => 300, 'size' => 'page')); ?> */
        /* " title="Screenshot done with Thumbalizr"> */

    $embed_key = 'MY_EMBED_API_KEY'; # replace it with you Embed API key
    $secret = 'MY_SECRET'; # replace it with your Secret
    
    $query = 'url=' . urlencode($url);
    
    foreach($options as $key => $value) { 
        $query .= '&' . trim($key) . '=' . urlencode(trim($value)); 
        
    }
    
    
    $token = md5($query . $secret);
    

    return "https://api.thumbalizr.com/api/v1/embed/$embed_key/$token/?$query";
}


}
