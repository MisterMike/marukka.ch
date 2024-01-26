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

use craft\elements\User;
use craft\errors\ElementNotFoundException;
use craft\helpers\DateTimeHelper;
use craft\helpers\ElementHelper;
use craft\helpers\FileHelper;
use craft\helpers\Json;
use craft\helpers\UrlHelper;
use craft\queue\Queue;

use HttpException;
use k4\k4campaigns\models\CampaignTypeModel;

use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\web\Controller;
use k4\k4campaigns\models\EmailModel;
use Kint\Kint;

use putyourlightson\logtofile\LogToFile;
use Throwable;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use function Craft\craft;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class CampaignsController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected int|bool|array $allowAnonymous = ['view-shared-campaign'];

    // Public Methods
    // =========================================================================
	
	/**
	 * @param string|null $siteHandle
	 *
	 * @return Response
	 */
	public function actionIndex(string $siteHandle = null): Response
	{
		/**
		 * @var User $user
		 */
		//$user = User::find()->id(1)->one();
		//Kint::dump($user);
		
		// Render the template
		return $this->renderTemplate('k4-campaigns/campaigns/index.twig');
	}
	
	public function actionNew(string $campaignTypeHandle): Response
	{
		$variables = [];

        $request = Craft::$app->getRequest();

        /** @var CampaignTypeModel $campaignType */
        $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeByHandle($campaignTypeHandle);

		$campaign = new CampaignElement();
        $campaign->campaignTypeId = $campaignType->id;
        $campaign->fieldLayoutId = $campaignType->fieldLayoutId;

		$variables["campaign"] = $campaign;
		$variables["campaignTypes"] = K4Campaigns::$plugin->campaignTypes->getAllCampaignTypesForSelect();
        $variables['campaignTypeHandle'] = $campaignTypeHandle;
        $variables['campaignType'] = $campaignType;

        $variables['fields'] = $campaignType->getFieldLayout()->getFields();

		// Full page form variables
		$variables['fullPageForm'] = true;
		$variables['continueEditingUrl'] = 'k4-campaigns/campaigns/{id}';
		$variables['saveShortcutRedirect'] = $variables['continueEditingUrl'];

        $variables['showPreviewBtn'] = true;

        // Render the template
		return $this->renderTemplate('k4-campaigns/campaigns/new', $variables);
	}
	
	public function actionEdit(string $campaignTypeHandle, int $campaignId = null, CampaignElement $campaign = null): Response
	{
        $request = Craft::$app->getRequest();

		if ($campaign === null) {
			if ($campaignId !== null) {
				/**
				 * @var CampaignElement $campaign
				 */
				$campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);
				
				if ($campaign === null) {
					throw new NotFoundHttpException(Craft::t('k4-campaigns', 'Coupon not found.'));
				}
			}
			else {
				$this->redirect('k4-campaigns/campaigns/new');
			}
		}
        $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeByHandle($campaignTypeHandle);

        $variables = [];
		
		$variables["campaign"] = $campaign;
		$variables["campaignTypes"] = K4Campaigns::$plugin->campaignTypes->getAllCampaignTypesForSelect();

        $variables['campaignTypeHandle'] = $campaignTypeHandle;
        $variables['campaignId'] = $campaignId;
        $variables['campaign'] = $campaign;
        $variables['campaignType'] = $campaignType;


        $variables['fields'] = $campaign->getFieldLayout() ? $campaign->getFieldLayout()->getFields() : [];

        // Enable live preview?
        if (!$request->isMobileBrowser(true) && K4Campaigns::$plugin->email->doesSiteTemplateExist($campaign->getCampaignType()->template))
        {
            $parameters = Json::encode([
                'fields' => '#fields > div > .field',
                'extraFields' => '#settings',
                'previewUrl' => $campaign->getUrl(),
                'previewAction' => Craft::$app->security->hashData('k4-campaigns/campaigns/live-preview'),
                'previewParams' => [
                    'campaignId' => $campaign->id,
                    'campaignTypeId' => $campaign->getCampaignType()->id,
                    'siteId' => Craft::$app->getSites()->currentSite->id
                ]
            ]);
            $this->getView()->registerJs('Craft.LivePreview.init('.$parameters.');');

            $variables['showPreviewBtn'] = true;

            // Set share URL
            if ($campaign->id !== null && $campaign->enabled) {
                $variables['shareUrl'] = $campaign->getUrl();
            }
        }
        else {
            $variables['showPreviewBtn'] = false;
        }

		// Determine which actions should be available
		// ---------------------------------------------------------------------

		$variables['actions'] = [];

		$variables['actions'][0][] = [
			'action' => 'k4-campaigns/campaigns/delete-campaign',
			'destructive' => 'true',
			'redirect' => 'k4-campaigns/campaigns',
			'label' => Craft::t('k4-campaigns', 'Delete…'),
			'confirm' => Craft::t('k4-campaigns', 'Are you sure you want to delete this campaign?')
		];
		
		
		// Full page form variables
		$variables['fullPageForm'] = true;
		$variables['continueEditingUrl'] = 'k4-campaigns/campaigns/{id}';
		$variables['saveShortcutRedirect'] = $variables['continueEditingUrl'];

		// Render the template
		return $this->renderTemplate('k4-campaigns/campaigns/edit', $variables);
	}
	
	
	/**
	 * @return Response|null
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 * @throws ElementNotFoundException
	 * @throws Exception
	 * @throws BadRequestHttpException
	 */
	public function actionSaveCampaign()
	{
		$this->requirePostRequest();

        $campaign = $this->_getCampaign();
		$request = Craft::$app->getRequest();

        $campaign = $this->_populateCampaign($campaign);

        if (!Craft::$app->getElements()->saveElement($campaign)) {
            if ($request->getAcceptsJson()) {
                return $this->asJson([
                    'errors' => $campaign->getErrors(),
                ]);
            }

            Craft::$app->getSession()->setError(Craft::t('k4-campaigns', 'Couldn’t save campaign.'));

            // Send the campaign back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'campaign' => $campaign
            ]);

            return null;
        }

        if ($request->getAcceptsJson()) {
            $return = [];

            $return['success'] = true;
            $return['id'] = $campaign->id;
            $return['title'] = $campaign->title;

            if (!$request->getIsConsoleRequest() && $request->getIsCpRequest()) {
                $return['cpEditUrl'] = $campaign->getCpEditUrl();
            }

            $return['dateCreated'] = DateTimeHelper::toIso8601($campaign->dateCreated);
            $return['dateUpdated'] = DateTimeHelper::toIso8601($campaign->dateUpdated);

            return $this->asJson($return);
        }

        Craft::$app->getSession()->setNotice(Craft::t('k4-campaigns', 'Campaign saved.'));

        return $this->redirectToPostedUrl($campaign);
	}
	
	/**
	 * Deletes a contact
	 *
	 * @return Response|null
	 * @throws BadRequestHttpException
	 * @throws NotFoundHttpException
	 * @throws Throwable
	 */
	public function actionDeleteCampaign()
	{
		$this->requirePostRequest();
		
		$campaign = $this->_getPostedCampaign();
		
		if (!Craft::$app->getElements()->deleteElement($campaign)) {
            if (Craft::$app->getRequest()->getAcceptsJson()) {
				return $this->asJson(['success' => false]);
			}
			
			Craft::$app->getSession()->setError(Craft::t('k4-campaigns', 'Couldn’t delete campaign.'));
			
			// Send the contact back to the template
			Craft::$app->getUrlManager()->setRouteParams([
				'campaign' => $campaign
			]);
			
			return null;
		}

        if (Craft::$app->getRequest()->getAcceptsJson()) {
			return $this->asJson(['success' => true]);
		}
		
		Craft::$app->getSession()->setNotice(Craft::t('k4-campaigns', 'Campaign deleted.'));
		
		return $this->redirectToPostedUrl($campaign);
	}
	
	private function _getPostedCampaign(): CampaignElement
	{
		$campaignId = Craft::$app->getRequest()->getRequiredBodyParam('campaignId');
		$campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);
		
		if ($campaign === null) {
			throw new NotFoundHttpException(Craft::t('k4-campaigns', 'Campaign not found.'));
		}
		
		return $campaign;
	}

    /**
     * Populates a campaign with post data.
     *
     * @param CampaignElement $campaign
     *
     * @return void
     * @throws InvalidConfigException
     */
    private function _populateCampaign(CampaignElement $campaign)
    {
        $request = Craft::$app->getRequest();

        // Set the field layout ID
        $campaign->fieldLayoutId = $campaign->getCampaignType()->fieldLayoutId;

        // Set the field locations
        $fieldsLocation = $request->getParam('fieldsLocation', 'fields');

        $campaign->setFieldValuesFromRequest($fieldsLocation);

        if ($campaign->getCampaignType()->titleFormat)
        {
            $subjectLine = Craft::$app->view->renderObjectTemplate($campaign->getCampaignType()->titleFormat, $campaign);
        }
        else
        {
            $subjectLine = $request->getBodyParam('subjectLine');

        }

        $campaign->setAttributes([
            'subjectLine'      => $subjectLine,
            'fromName'         => $request->getBodyParam('fromName'),
            'fromEmail'        => $request->getBodyParam('fromEmail'),
            'replyToEmail'     => $request->getBodyParam('replyToEmail'),
            'listSettings'     => json_encode($request->getBodyParam('listSettings'))
        ],false);



        // Set the title, slug, enabled

        $campaign->title = $subjectLine;
        $campaign->slug = $request->getBodyParam('slug', $campaign->slug);
        $campaign->enabled = (bool)$request->getBodyParam('enabled', $campaign->enabled);

        if (empty($campaign->slug))
        {
            $campaign->slug = ElementHelper::createSlug($subjectLine);
        }

        return $campaign;
    }

    /**
     * Gets a campaign or creates one if none supplied.
     *
     * @return CampaignElement
     * @throws NotFoundHttpException
     * @throws BadRequestHttpException
     */
    private function _getCampaign(): CampaignElement
    {
        $request = Craft::$app->getRequest();

        $campaignId = $request->getBodyParam('campaignId');

        if ($campaignId) {
            $campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);

            if (!$campaign) {
                throw new NotFoundHttpException(Craft::t('k4campaigns', 'Campaign not found.'));
            }
        }
        else {
            $campaign = new CampaignElement();
            $campaign->campaignTypeId = $request->getRequiredBodyParam('campaignTypeId');
        }

        return $campaign;
    }

    /**
     * Fetch or create a SproutEmail_CampaignEmailModel
     *
     * @return CampaignElement
     *@throws Exception
     */
    protected function getCampaignModel()
    {
        $request = Craft::$app->getRequest();

        $campaignId   = $request->getBodyParam('campaignId');
        $saveAsNew = $request->getBodyParam('saveAsNew');

        if ($campaignId && !$saveAsNew && $campaignId !== 'new')
        {
            $campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);

            if (!$campaign)
            {
                throw new Exception(Craft::t('k4-campaigns','No entry exists with the ID “{id}”', array('id' => $campaignId)));
            }
        }
        else
        {
            $campaign = new CampaignElement();
        }

        return $campaign;
    }

    /**
     * Redirects the client to a URL for viewing an entry/draft on the front end.
     *
     * @param mixed $campaignId
     *
     * @return null
     * @throws HttpException
     */
    public function actionShare($campaignId = null, $type = 'html')
    {
        if ($campaignId)
        {
            $campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);

            if (!$campaign)
            {
                throw new HttpException(404);
            }
        }
        else
        {
            throw new HttpException(404);
        }

        // Create the token and redirect to the entry URL with the token in place
        $token = Craft::$app->getTokens()->createToken(array(
            'action' => 'k4-campaigns/campaigns/shared-campaign',
            'params' => array(
                'campaignId' => $campaignId,
                'type'    => $type
            )
        ));

        $url = UrlHelper::url($campaign->getUrl(),array('type'=>$type));

        return $this->showCampaign($campaign, $type);
    }

    /**
     * @param null $campaignType
     * @param null $campaignId
     *
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPreview($campaignType = null, $campaignId = null): Response
    {
        return $this->renderTemplate('k4-campaigns/campaigns/preview', [
            'campaignType' => $campaignType,
            'campaignId' => $campaignId
        ]);
    }


    /**
     * Prepare the viewing of a shared Campaign Email
     *
     * @param null $emailId
     * @param null $type
     *
     * @throws HttpException
     */
    public function actionViewSharedCampaignEmail($emailId = null, $type = null)
    {
        $this->requireToken();

        if ($campaignEmail = K4Campaigns::$plugin->campaigns->getCampaignById($emailId))
        {
            $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($campaignEmail->campaignTypeId);

            $object    = null;
            $email     = new EmailModel();
            $template  = $campaignType->template;
            $extension = ($type != null && $type == 'text') ? 'txt' : 'html';

            $email = K4Campaigns::$plugin->email->renderEmailTemplates($email, $template, $campaignEmail, $object);

            return $this->showCampaign($email, $extension);
        }

        throw new HttpException(404);
    }

    /**
     * Previews a campaign.
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function actionLivePreviewBackup(): Response
    {
        $this->requirePostRequest();

        $campaign = $this->_getCampaign();

        $this->_populateCampaign($campaign);

        // Have this campaign override any freshly queried campaigns with the same ID/site ID
        if ($campaign->id !== null) {
            Craft::$app->getElements()->setPlaceholderElement($campaign);
        }

        $this->getView()->getTwig()->disableStrictVariables();

        return $this->renderTemplate($campaign->getCampaignType()->template, [
            'entry' => $campaign
        ]);
    }


    /**
     * Prepare Live Preview for Campaign Email
     *
     * @throws HttpException
     */
    public function actionLivePreview()
    {
        $this->requirePostRequest();
        $request = Craft::$app->getRequest();

        $campaignId = Craft::$app->request->getBodyParam('campaignId');

        if ($campaignId)
        {
            $campaign = K4Campaigns::$plugin->campaigns->getCampaignById($campaignId);

            if (!$campaign)
            {
                throw new HttpException(404);
            }
        }
        else
        {
            $campaignTypeId = Craft::$app->request->getBodyParam('campaignTypeId');

            $campaign                 = new CampaignElement();
            $campaign->campaignTypeId = $campaignTypeId;
        }

        /**
         * @todo - make sure this works with the updated titleFormat behavior
         */
        $campaign->subjectLine         = Craft::$app->request->getBodyParam('subjectLine', $campaign->subjectLine);
        $campaign->title = $campaign->subjectLine;

        // Set the title, slug, enabled
        $campaign->slug = $request->getBodyParam('slug', $campaign->slug);

        // Set the field layout ID
        $campaign->fieldLayoutId = $campaign->getCampaignType()->fieldLayoutId;

        // Set the field locations
        $fieldsLocation = $request->getParam('fieldsLocation', 'fields');
        $campaign->setFieldValuesFromRequest($fieldsLocation);

        // Prepare variables to render email templates
        // -------------------------------------------
        $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($campaign->campaignTypeId);

        // Have this campaign override any freshly queried campaigns with the same ID/site ID
        if ($campaign->id !== null) {
            Craft::$app->getElements()->setPlaceholderElement($campaign);
        }

        $this->getView()->getTwig()->disableStrictVariables();

        return $this->renderTemplate($campaign->getCampaignType()->template, [
            'campaign' => $campaign
        ]);

    }

    /**
     * Output the prepared Email to the page
     *
     * @param CampaignElement $email
     * @param string     $template
     */
    protected function showCampaign(CampaignElement $campaign, $fileExtension = 'html')
    {
        if ($fileExtension == 'txt')
        {
            // Render the email templates
            $body = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaign->getCampaignType()->template . '.txt', array(
                'campaign'  => $campaign
            ));

            Craft::$app->response->headers->set('Content-Type','text/plain');

            echo '<pre style="word-wrap: break-word; white-space: pre-wrap;">'.$body.'</pre>';

            return true;

        }
        else
        {
            $htmlBody = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaign->getCampaignType()->template, array(
                'campaign'  => $campaign,
                'campaignType' => $campaign->getCampaignType()
            ));

            return $this->asRaw($htmlBody);

        }
    }

    public function actionSend()
    {
        $this->requirePostRequest();

        $campaign = $this->_getCampaign();
        $request = Craft::$app->getRequest();

        try {
            K4Campaigns::$plugin->mailchimp->sendCampaign($campaign);
        } catch (\Exception $e) {
            LogToFile::error($e->getMessage(), 'k4campaigns');
            return $this->asJson(array('success'=>false,'error'=>$e->getMessage(),'trace'=>$e->getTraceAsString()));
        }

        $campaign->dateSent = DateTimeHelper::currentUTCDateTime();

        if (!Craft::$app->getElements()->saveElement($campaign)) {
            if ($request->getAcceptsJson()) {
                return $this->asJson(['success'=>false,'error'=> $campaign->getErrors()]);
            }
        }

        return $this->asJson(array('success'=>true,'message'=>'newsletter sent.'));
    }

    public function actionPrepareModal()
    {
        $this->requirePostRequest();

        $campaign = $this->_getCampaign();
        $request = Craft::$app->getRequest();

        $variables = array(
            "campaign" => $campaign
        );

        Craft::$app->view->setTemplatesPath(Craft::getAlias('@templates'));
        $html = Craft::$app->view->renderTemplate('k4-campaigns/campaigns/prepare.twig', $variables);

      return $this->asJson(array('html'=>$html));
    }

}
