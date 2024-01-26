<?php

namespace k4\k4campaigns\controllers;

use craft\elements\User;
use craft\errors\ElementNotFoundException;
use craft\helpers\DateTimeHelper;
use craft\helpers\FileHelper;
use craft\queue\Queue;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\models\CampaignTypeModel;
use k4\k4campaigns\models\ImportModel;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\web\Controller;

use k4\k4campaigns\models\SettingsModel;
use Kint\Kint;
use Solspace\Freeform\Freeform;
use Throwable;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class CampaignTypesController extends Controller
{
	/**
	 * @var SettingsModel $_settings
	 */
	private $_settings;
	
	
	// Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];
    
	/**
	 * @inheritdoc
	 * @throws ForbiddenHttpException
	 */
	public function init()
	{
		// Require permission
		$this->requirePermission('k4-campaigns:generateCoupons');
		
		$this->_settings = K4Campaigns::$plugin->getSettings();

        parent::init();
	}
	
	// Public Methods
    // =========================================================================
	
	/**
	 * @param string|null $siteHandle
	 *
	 * @return Response
	 */
	public function actionIndex(string $siteHandle = null): Response
	{
		$variables = [];
		$variables["campaignTypes"] = K4Campaigns::$plugin->campaignTypes->getAllCampaignTypes();
		
		return $this->renderTemplate('k4-campaigns/settings/campaign-types/index.twig',$variables);
	}
	
	public function actionNew(): Response
	{
		$variables = [];
		
		$campaignType = new CampaignTypeModel();

		$variables["campaignType"] = $campaignType;
		
		// Add fields from first field layout tab
		$fieldLayoutTabs = $campaignType->getFieldLayout()->getTabs();
		$fieldLayoutTab = isset($fieldLayoutTabs[0]) ? $fieldLayoutTabs[0] : null;
		$variables['fields'] = $fieldLayoutTab !== null ? $fieldLayoutTab->getFields() : [];
		
		
		// Full page form variables
		$variables['fullPageForm'] = true;
		$variables['continueEditingUrl'] = 'k4-campaigns/campaigns/{id}';
		$variables['saveShortcutRedirect'] = $variables['continueEditingUrl'];
		
		// Render the template
		return $this->renderTemplate('k4-campaigns/settings/campaign-types/new.twig', $variables);
	}
	
	public function actionEdit(int $campaignTypeId, CampaignTypeModel $campaignType = null): Response
	{
		if ($campaignType === null) {
			if ($campaignTypeId !== null) {
				/**
				 * @var CampaignTypeModel $campaignType
				 */
				$campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($campaignTypeId);
				
				if ($campaignType === null) {
					throw new NotFoundHttpException(Craft::t('k4-campaigns', 'CampaignType not found.'));
				}
			}
			else {
				$this->redirect('k4-campaigns/settings/campaign-types/new');
			}
		}
		
		
		$variables = [];
		
		$variables["campaignType"] = $campaignType;

		
		// Add fields from first field layout tab
		//$fieldLayoutTabs = $campaignType->getFieldLayout()->getTabs();
		//$fieldLayoutTab = isset($fieldLayoutTabs[0]) ? $fieldLayoutTabs[0] : null;
		//$variables['fields'] = $fieldLayoutTab !== null ? $fieldLayoutTab->getFields() : [];
		
		// Determine which actions should be available
		// ---------------------------------------------------------------------
		
		$variables['actions'] = [];
		
		$variables['actions'][0][] = [
			'action' => 'k4-campaigns/campaign-types/delete-campaign-type',
			'destructive' => 'true',
			'redirect' => 'k4-campaigns/settings/campaign-types',
			'label' => Craft::t('k4-campaigns', 'Delete…'),
			'confirm' => Craft::t('k4-campaigns', 'Are you sure you really want to delete this campaign type and all including campaigns ?')
		];
		
		
		// Full page form variables
		$variables['fullPageForm'] = true;
		$variables['continueEditingUrl'] = 'k4-campaigns/settings/campaign-types/{id}';
		$variables['saveShortcutRedirect'] = $variables['continueEditingUrl'];
		
		$variables['settings'] = $this->_settings;
		
		// Render the template
		return $this->renderTemplate('k4-campaigns/settings/campaign-types/edit', $variables);
	}
	
	/**
	 * @return Response|null
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 * @throws ElementNotFoundException
	 * @throws Exception
	 * @throws BadRequestHttpException
	 */
	public function actionSaveCampaignType()
	{
		$this->requirePostRequest();
		
		$request = Craft::$app->getRequest();
		
		$campaignTypeId = $request->getBodyParam('campaignTypeId');
		
		if ($campaignTypeId) {
			$campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($campaignTypeId);
			
			if ($campaignType === null) {
				throw new NotFoundHttpException(Craft::t('k4-campaigns', 'Coupon not found.'));
			}
		}
		else {
			$campaignType = new CampaignTypeModel();
		}

		// Set the attributes, defaulting to the existing values for whatever is missing from the post data
		$campaignType->enabled = $request->getBodyParam('enabled');
		$campaignType->name = $request->getBodyParam('name');
		$campaignType->handle = $request->getBodyParam('handle');
		$campaignType->titleFormat = $request->getBodyParam('titleFormat');
		$campaignType->urlFormat = $request->getBodyParam('urlFormat');
		$campaignType->hasUrls = $request->getBodyParam('hasUrls');
		$campaignType->hasAdvancedTitles = $request->getBodyParam('hasAdvancedTitles');
		$campaignType->template = $request->getBodyParam('template');
		$campaignType->templateCopyPaste = $request->getBodyParam('templateCopyPaste');






        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayoutFromPost();
        $fieldLayout->type = CampaignElement::class;

        $campaignType->setFieldLayout($fieldLayout);

		// Save it
		if (!K4Campaigns::$plugin->campaignTypes->saveCampaignType($campaignType)) {
            if ($request->getAcceptsJson()) {
				return $this->asJson([
					'errors' => $campaignType->getErrors(),
				]);
			}

			Craft::$app->getSession()->setError(Craft::t('k4-campaigns', 'Couldn’t save campaignType.'));
			
			// Send the contact back to the template
			Craft::$app->getUrlManager()->setRouteParams([
				'campaignType' => $campaignType
			]);
			
			return null;
		}

        if (Craft::$app->getRequest()->getAcceptsJson()) {
			$return = [];
			
			$return['success'] = true;
			$return['id'] = $campaignType->id;
			
			
			if (!$request->getIsConsoleRequest() && $request->getIsCpRequest()) {
				$return['cpEditUrl'] = $campaignType->getCpEditUrl();
			}
			
			$return['dateCreated'] = DateTimeHelper::toIso8601($campaignType->dateCreated);
			$return['dateUpdated'] = DateTimeHelper::toIso8601($campaignType->dateUpdated);
			
			return $this->asJson($return);
		}
		
		Craft::$app->getSession()->setNotice(Craft::t('k4-campaigns', 'CampaignType saved.'));
		
		return $this->redirectToPostedUrl($campaignType);
	}
	
	/**
	 * Deletes a campaignType
	 *
	 * @return Response
	 * @throws BadRequestHttpException
	 * @throws Throwable
	 */
	public function actionDelete(): Response
	{
		$this->requirePostRequest();
		$this->requireAcceptsJson();
		
		$campaignTypeId = Craft::$app->getRequest()->getRequiredBodyParam('id');
		
		K4Campaigns::$plugin->campaignTypes->deleteCampaignTypeById($campaignTypeId);
		
		return $this->asJson(['success' => true]);
	}

	
}
