<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns;

use craft\events\RegisterCpNavItemsEvent;
use craft\services\Fields;
use k4\k4campaigns\fields\CampaignTypeField;
use k4\k4campaigns\models\SettingsModel;
use k4\k4campaigns\services\CampaignTypesService;
use k4\k4campaigns\services\CampaignsService;
use k4\k4campaigns\services\EmailService;
use k4\k4campaigns\services\SettingsService;
use k4\k4campaigns\services\MailchimpService;
use k4\k4campaigns\variables\K4CampaignsVariable;
use k4\k4campaigns\twigextensions\K4CampaignsTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\services\Elements;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\Cp;

use Kint\Kint;
use Psr\Log\LogLevel;
use yii\base\Event;

/**
 * Class K4Campaigns
 *
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 *
 * @property  CampaignsService $campaigns
 * @property  SettingsService $settings
 * @property  CampaignTypesService $campaignTypes
 * @property  EmailService $email
 * @property  MailchimpService $mailchimp
 *
 */
class K4Campaigns extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var K4Campaigns
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';
	
	public $hasCpSection = true;
	
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::setAlias('@k4campaigns', $this->getBasePath().'/templates');

	    // Register services as components
	    $this->setComponents([
		    'campaigns' => CampaignsService::class,
		    'settings' => SettingsService::class,
		    'campaignTypes' => CampaignTypesService::class,
            'email' => EmailService::class,
            'mailchimp'=> MailchimpService::class

	    ]);
        
        Craft::$app->view->registerTwigExtension(new K4CampaignsTwigExtension());
	
	    Event::on(
	    	Fields::class,
		    Fields::EVENT_REGISTER_FIELD_TYPES,
		    function(RegisterComponentTypesEvent $event) {
		    $event->types[] = CampaignTypeField::class;
	    });
        
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
	            $event->rules = array_merge($event->rules, $this->getSiteRoutes());
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
	            $event->rules = array_merge($event->rules, $this->getCpRoutes());
            }
        );

        Event::on(
            Elements::class,
            Elements::EVENT_REGISTER_ELEMENT_TYPES,
            function (RegisterComponentTypesEvent $event) {
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('k4campaigns', K4CampaignsVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'k4-campaigns',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
	
    }
	
	/**
	 * @inheritdoc
	 */
	public function getCpNavItem()
	{
		$currentUser = Craft::$app->getUser()->getIdentity();
		$allowAdminChanges = Craft::$app->getConfig()->getGeneral()->allowAdminChanges;
		
		$cpNavItem = $this->getRealCpNavItem();
		$cpNavItem['subnav'] = [];
		
		if ($currentUser->can('k4-campaigns:campaigns')) {
			$cpNavItem['subnav']['campaigns'] = ['label' => Craft::t('k4-campaigns', 'Campaigns'), 'url' => 'k4-campaigns/campaigns'];
		}
		if ($allowAdminChanges && $currentUser->can('k4-campaigns:settings')) {
			$cpNavItem['subnav']['settings'] = ['label' => Craft::t('k4-campaigns', 'Settings'), 'url' => 'k4-campaigns/settings'];
		}
		
		return $cpNavItem;
	}
	
	
	// Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new SettingsModel();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'k4-campaigns/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
	
	/**
	 * Returns the CP routes
	 *
	 * @return array
	 */
	protected function getSiteRoutes(): array
	{
		return [

		];
	}

	/**
	 * Returns the CP routes
	 *
	 * @return array
	 */
	protected function getCpRoutes(): array
	{
		return [
			
			'k4-campaigns'                                                                  => 'k4-campaigns/campaigns/index',
			
			'k4-campaigns/campaigns'                                                        => 'k4-campaigns/campaigns/index',
			'k4-campaigns/campaigns/<campaignTypeHandle:{handle}>/new'                      => 'k4-campaigns/campaigns/new',
            'k4-campaigns/campaigns/<campaignTypeHandle:{handle}>/<campaignId:\d+>'         => 'k4-campaigns/campaigns/edit',
            'k4-campaigns/campaigns/<campaignTypeHandle:{handle}>/<campaignId:\d+>/preview' => 'k4-campaigns/campaigns/preview',
            'k4-campaigns/campaigns/<campaignTypeHandle:{handle}>/<campaignId:\d+>/share'   => 'k4-campaigns/campaigns/share',

			'k4-campaigns/settings/general'                                                 => 'k4-campaigns/settings/edit-general',

			'k4-campaigns/settings/campaign-types/new'                                      => 'k4-campaigns/campaign-types/new',
            'k4-campaigns/settings/campaign-types/<campaignTypeId:\d+>'                     => 'k4-campaigns/campaign-types/edit',
			'k4-campaigns/settings/campaign-types'                                          => 'k4-campaigns/campaign-types/index',
			
            'k4-campaigns/test'                                                             => 'k4-campaigns/test/index'

            // Previews

		];
	}
	
	public function maxPowerLieutenant()
	{
		$settings = $this->getSettings();
		
		// Set memory limit
		@ini_set('memory_limit', $settings->memoryLimit);
		
		// Set time limit
		@set_time_limit($settings->timeLimit);
	}
	

	public function log($messages, $level = LogLevel::Info, $force = false)
	{
        $msg = "";

        if (is_array($messages))
        {
            foreach ($messages as $message)
            {
                $msg .= PHP_EOL . print_r($message, true);
            }
        }
        else
        {
            $msg = $messages;
        }

        parent::log($msg, $level, $force);

	}

	protected function getRealCpNavItem(){

	    if (!$this->getSettings()["pluginName"]){
	        return parent::getCpNavItem();
        }
	    else {

            $ret = [
                'label' => $this->getSettings()["pluginName"],
                'url' => $this->id,
            ];

            if (($iconPath = $this->cpNavIconPath()) !== null) {
                $ret['icon'] = $iconPath;
            }

	        return $ret;
        }
    }

}
