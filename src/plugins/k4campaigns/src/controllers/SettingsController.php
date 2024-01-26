<?php

namespace k4\k4campaigns\controllers;

use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\models\SettingsModel;
use k4\k4campaigns\elements\CampaignElement;

use Craft;
use craft\web\Controller;
use craft\helpers\ArrayHelper;
use craft\helpers\MailerHelper;
use craft\errors\MissingComponentException;
use craft\mail\transportadapters\BaseTransportAdapter;
use craft\mail\transportadapters\Sendmail;
use craft\mail\transportadapters\TransportAdapterInterface;
use Kint\Kint;
use Solspace\Freeform\Freeform;
use Throwable;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class SettingsController
 *
 * @package k4\k4campaigns\controllers
 */
class SettingsController extends Controller
{
    // Properties
    // =========================================================================

    /**
     * @var SettingsModel $_settings
     */
    private $_settings;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     * @throws ForbiddenHttpException
     */
    public function init()
    {
        // Require permission for all actions
        $this->requirePermission('k4-campaigns:settings');

        if (!Craft::$app->getConfig()->getGeneral()->allowAdminChanges) {
            throw new ForbiddenHttpException(Craft::t('k4-campaigns', 'Administrative changes are disallowed in this environment.'));
        }

        $this->_settings = K4Campaigns::$plugin->getSettings();

        parent::init();
    }

    /**
     * @param SettingsModel $settings The settings being edited, if there were any validation errors.
     *
     * @return Response
     */
    public function actionEditGeneral(SettingsModel $settings = null): Response
    {
        if ($settings === null) {
            $settings = $this->_settings;
        }

        return $this->renderTemplate('k4-campaigns/settings/general', [
            'settings' => $settings,
            'config' => Craft::$app->getConfig()->getConfigFromFile('k4-campaigns'),
        ]);
    }

    /**
     * @return Response|null
     * @throws BadRequestHttpException
     */
    public function actionSaveGeneral()
    {
        $this->requirePostRequest();

        $settings = $this->_settings;

        // Set the simple stuff
        $settings->mailchimpApiKey = Craft::$app->getRequest()->getBodyParam('mailchimpApiKey', $settings->mailchimpApiKey);
        $settings->mailchimpServerPrefix = Craft::$app->getRequest()->getBodyParam('mailchimpServerPrefix', $settings->mailchimpServerPrefix);
        $settings->pluginName = Craft::$app->getRequest()->getBodyParam('pluginName', $settings->pluginName);


        // Save it
        if (!K4Campaigns::$plugin->settings->saveSettings($settings)) {
            Craft::$app->getSession()->setError(Craft::t('k4-campaigns', 'Couldn’t save general settings.'));

            // Send the settings back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'settings' => $settings
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('k4-campaigns', 'General settings saved.'));

        return $this->redirectToPostedUrl();
    }
    
    /**
     * @return Response|null
     * @throws BadRequestHttpException
     * @throws Exception
     */
    public function actionSaveCampaign()
    {
        $this->requirePostRequest();

        $settings = $this->_settings;

        // Set the simple stuff
        //$settings->emailFieldLabel = Craft::$app->getRequest()->getBodyParam('emailFieldLabel', $settings->emailFieldLabel);

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayoutFromPost();
        $fieldLayout->type = CampaignElement::class;
        Craft::$app->getFields()->saveLayout($fieldLayout);

        // Save it
        if (!K4Campaigns::$plugin->settings->saveSettings($settings)) {
            Craft::$app->getSession()->setError(Craft::t('k4-campaigns', 'Couldn’t save campaign settings.'));

            // Send the settings back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'settings' => $settings
            ]);

            return null;
        }

        Craft::$app->getSession()->setNotice(Craft::t('k4-campaigns', 'Campaign settings saved.'));

        return $this->redirectToPostedUrl();
    }
	

	
}
