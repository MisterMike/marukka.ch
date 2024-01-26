<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\variables;

use craft\elements\db\ElementQueryInterface;
use craft\helpers\Db;
use craft\helpers\UrlHelper;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\models\CampaignTypeModel;
use k4\k4campaigns\K4Campaigns;

use Craft;
use k4\k4campaigns\records\CampaignTypeRecord;
use k4\k4campaigns\services\MailchimpService;
use Kint\Kint;
use MailchimpMarketing\ApiClient;
use yii\base\InvalidConfigException;


/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class K4CampaignsVariable
{
    /**
     * @var ElementQueryInterface
     */
    public $entries;

    /**
     * @var \craft\console\Application|\craft\web\Application
     */
    public $app;

    /**
     * @var K4Campaigns $plugin
     */
    public $plugin;

    /**
     * SproutEmailVariable constructor.
     */
    public function __construct()
    {
        $this->plugin = K4Campaigns::$plugin;
        $this->app = Craft::$app;
        $this->entries = CampaignElement::find();

            //CampaignElement::class;
    }



    // Public Methods
    // =========================================================================


	public function getAllCampaignTypes()
	{
		return K4Campaigns::$plugin->campaignTypes->getAllCampaignTypes();
	}

    public function getAllCampaignByHandle($handle)
    {
        $type = CampaignTypeRecord::find()->where('handle = "'.$handle.'"')->one();

        return CampaignElement::find()->where('campaignTypeId = "'.$type->id.'"');
    }


    /**
     * @param int $id
     * @return CampaignTypeModel|null
     */
    public function getCampaignTypeById(int $id)
    {
        return K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($id);
    }

    /**
     * Returns a Campaign Email by id
     *
     * @param $campaignId
     *
     * @return CampaignElement|null
     */
    public function getCampaignById($campaignId)
    {
        return K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);
    }


    /**
     * Returns a Campaign Email Share URL and Token
     *
     * @param $emailId
     * @param $campaignTypeId
     *
     * @return array|string
     */
    public function getCampaignShareUrl($campaignId, $campaignTypeId)
    {
        $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($campaignTypeId);

        return UrlHelper::url('k4-campaigns/campaigns/'.$campaignType->handle.'/'.$campaignId.'/share');
    }

    /**
     * Returns a Mailchimp Mailer
     *
     * @return MailchimpService
     */
    public function getMailer()
    {
        //$client = new \Mailchimp(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"], array('ssl_verifypeer' => false));

        $client = new ApiClient();

        $client->setConfig([
            'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
            'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
        ]);

        K4Campaigns::$plugin->mailchimp->setClient($client);

        return K4Campaigns::$plugin->mailchimp;
    }

    /**
     * Returns a Mailchimp Mailer
     *
     * @return string
     */
    public function getTitle()
    {
        if (!$this->plugin->getSettings()["pluginName"]){
            return $this->plugin->name;

        }
        else {
            return $this->plugin->getSettings()["pluginName"];
        }

    }



}
