<?php
/**
 * Site Module module for Craft CMS 4.x
 *
 * A simple Module to enhance your site
 *
 * @copyright Copyright (c) 2019-23 Mischa Sprecher
 */

namespace modules\sitemodule;


use craft\base\Model;
use modules\sitemodule\assetbundles\sitemodule\SiteModuleAsset;
use modules\sitemodule\fields\ConfigValuesField as ConfigValuesField;

use modules\sitemodule\models\Settings;

use Craft;
use craft\controllers\UsersController;
use craft\events\LoginFailureEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\events\RegisterEmailMessagesEvent;
use craft\models\SystemMessage;
use craft\services\SystemMessages;
use craft\services\Fields;
use craft\i18n\PhpMessageSource;
use craft\web\View;
        

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

/**
 *
 * @author    Mischa Sprecher
 * @package   SiteModule
 * @since     1.0.0
 *
 */
class SiteModule extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * @var Model|bool|null The model used to store the plugin’s settings
     * @see getSettings()
     */
    private bool|null|Model $_settings = null;


    /**
     *
     * @var SiteModule
     */
    public static SiteModule $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/sitemodule', $this->getBasePath());
        $this->controllerNamespace = 'modules\sitemodule\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/sitemodule/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Base template directory
        Event::on(
            View::class,
            View::EVENT_REGISTER_CP_TEMPLATE_ROOTS,
            function (RegisterTemplateRootsEvent $e) {
                if (is_dir($baseDir = $this->getBasePath().DIRECTORY_SEPARATOR.'templates')) {
                    $e->roots[$this->id] = $baseDir;
                }
            }
        );

        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ConfigValuesField::class;
            }
        );

        // register custom system message
        // Event::on(
        //     SystemMessages::class, 
        //     SystemMessages::EVENT_REGISTER_MESSAGES, 
        //     function(RegisterEmailMessagesEvent $e) {
        //     $e->messages[] = new SystemMessage([
        //         'key' => self::MESSAGE_KEY_LOGIN_FAILURE,
        //         'heading' => 'When there is a failed login attempt',
        //         'subject' => 'There has been a failed login attempt',
        //         'body' => file_get_contents(Craft::getAlias('@ontherocks/emails/approve.txt')),
        //     ]);
        // });

        Event::on(
            UsersController::class,
            UsersController::EVENT_LOGIN_FAILURE,
            function (LoginFailureEvent $event) {

                // available in $event: 'authError','message','user'

                // get users ip address
                $userIp = craft()->request->getIpAddress() ?? null;

                // send email to user
                $mailer = Craft::$app->getMailer();

                $mailer
                    ->composeFromKey(Module::MESSAGE_KEY_LOGIN_FAILURE, [
                        'message' => $message,
                    ])
                    ->setFrom($mailer->from)
                    ->setTo($recipe->author->email)
                    ->send();
            }
        );

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    /**
     *
     */
    public function init()
    {
        parent::init();
        self::$instance = $this;

        // Load our AssetBundle
        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                View::class,
                View::EVENT_BEFORE_RENDER_TEMPLATE,
                function (TemplateEvent $event) {
                    try {
                        Craft::$app->getView()->registerAssetBundle(SiteModuleAsset::class);
                    } catch (InvalidConfigException $e) {
                        Craft::error(
                            'Error registering AssetBundle - '.$e->getMessage(),
                            __METHOD__
                        );
                    }
                }
            );
        }

/**
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'site-module',
                '{name} module loaded',
                ['name' => 'Site Module']
            ),
            __METHOD__
        );
    }


    public function getSettings(): ?Model
    {
        if (!isset($this->_settings)) {
            $this->_settings = $this->createSettingsModel() ?: false;
        }

        return $this->_settings ?: null;
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel(): Model
    {
        return new Settings();
    }
}
