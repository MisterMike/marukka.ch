<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\controllers;

use craft\web\Response;
use DateTime;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\events\ImportEvent;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\web\Controller;
use k4\k4campaigns\services\CampaignsService;
use k4\k4campaigns\services\ImportsService;
use Kint\Kint;
use MailchimpMarketing\ApiClient;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class TestController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {
        $result = K4Campaigns::$plugin->mailchimp->getRecipientLists();

        //$result = K4Campaigns::$plugin->mailchimp->getListStatsById('1218c22773');
        //$result = K4Campaigns::$plugin->mailchimp->getRecipientListById('1218c22773');
        //$result = K4Campaigns::$plugin->mailchimp->getRecipientsByListId('1218c22773');

        $campaign = K4Campaigns::$plugin->campaigns->getCampaignById(93417);
        K4Campaigns::$plugin->mailchimp->sendCampaign($campaign);

//        Kint::dump($result);
//        die();



//        $client = new ApiClient();
//
//        $client->setConfig([
//            'apiKey' => K4Campaigns::$plugin->getSettings()["mailchimpApiKey"],
//            'server' => K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"]
//        ]);
//
//        $options = array(
//            "type" => "regular",
//            "recipients" => array("list_id" => 'abb1d731d9'),
//            "settings" => array(
//                'subject_line'  => 'campaign subject',
//                'title'         => 'campaign title',
//                'from_name'     => 'Christian Hiller',
//                'reply_to'      => 'digital@kreisvier.ch',
//            ),
//            "tracking" => array(
//                'opens'       => true,
//                'html_clicks' => true,
//                'text_clicks' => false
//            )
//        );
//
//        $content = array(
//            'html' => '<p>ABC</p>',
//            'text' => 'ABC',
//        );
//
//        $response = $client->campaigns->create($options);
//        Kint::dump($response);
//        $response = $client->campaigns->setContent($response->id,$content);
//        Kint::dump($response);
//        $client->campaigns->send($response->id);

        return $this->asJson([]);
    }

	
}
