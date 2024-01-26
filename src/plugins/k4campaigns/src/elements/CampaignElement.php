<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\elements;

use craft\behaviors\FieldLayoutBehavior;
use craft\elements\actions\Delete;
use craft\elements\actions\Edit;
use craft\elements\actions\Restore;
use craft\elements\User;
use craft\helpers\DateTimeHelper;
use craft\helpers\UrlHelper;
use DateTime;
use k4\k4campaigns\elements\actions\EnableElementAction;
use k4\k4campaigns\elements\actions\HardDeleteCampaigns;
use k4\k4campaigns\elements\db\CampaignElementQuery;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\base\Element;
use craft\elements\db\ElementQuery;
use craft\elements\db\ElementQueryInterface;
use k4\k4campaigns\models\BaseModel;
use k4\k4campaigns\models\CampaignTypeModel;
use k4\k4campaigns\records\CampaignTypeRecord;
use k4\k4campaigns\records\CampaignRecord;
use k4\k4campaigns\services\MailchimpService;
use Kint\Kint;
use yii\base\InvalidConfigException;
use function Craft\craft;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 *
 * @property int         $id
 * @property string      $subjectLine
 * @property int         $campaignTypeId
 * @property string      $fromName
 * @property string      $fromEmail
 * @property string      $replyToEmail
 * @property string      $listSettings
 * @property bool        $sent
 * @property DateTime    $dateScheduled
 * @property DateTime    $dateSent
 *
 * --
 * @property string|null $uri
 * @property string      $slug
 *
 * @property CampaignTypeModel $campaignType
 */
class CampaignElement extends Element
{
	// Constants
	// =========================================================================
	
    // Public Properties
    // =========================================================================

    /**
     * Ready     - Campaign is setup and is enabled
     * Disabled  - Campaign should not be sendable or displayed to public
     * Pending   - Campaign is setup but Entry is disabled
     * Scheduled - Campaign has a scheduled date in the future
     * Sent      - Campaign has a sent date in the past
     *
     */
    const STATUS_READY     = 'ready';
    const STATUS_DISABLED  = 'disabled';
    const STATUS_PENDING   = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_SENT      = 'sent';

    /**
     * @var string
     */
    public $subjectLine;
	/**
	 * @var integer
	 */
	public $campaignTypeId;

    /**
     * @var DateTime
     */
    public $dateScheduled;

    /**
     * @var DateTime
     */
    public $dateSent;


    /**
     * @var CampaignTypeModel CampaignType
     */
    private $_campaignType;

    /**
     * @var string
     */
    public $fromName;

    /**
     * @var string
     */
    public $fromEmail;

    /**
     * @var string
     */
    public $replyToEmail;

    /**
     * @var string
     */
    public $listSettings;

    /**
     * @var string
     */
    public $uri;

    /**
     * @var string
     */
    public $slug;


	// Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('k4-campaigns', 'Campaign');
    }

    /**
     * @inheritdoc
     */
    public static function hasContent(): bool
    {
        return true;
    }
	
	/**
	 * @inheritdoc
	 */
	public static function hasStatuses(): bool
	{
		return true;
	}
	
	/**
     * @inheritdoc
     */
    public static function hasTitles(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function hasUris(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function isLocalized(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_READY => Craft::t('k4-campaigns', 'Ready'),
            self::STATUS_DISABLED => Craft::t('k4-campaigns', 'Disabled'),
            self::STATUS_PENDING => Craft::t('k4-campaigns', 'Pending'),
            self::STATUS_SCHEDULED => Craft::t('k4-campaigns', 'Scheduled'),
            self::STATUS_SENT => Craft::t('k4-campaigns', 'Sent')
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find(): ElementQueryInterface
    {
        return new CampaignElementQuery(static::class);
    }

    /**
     * @inheritdoc
     */
    protected static function defineSources(string $context = null): array
    {
        $sources = [
            [
                'key' => '*',
                'label' => Craft::t('k4-campaigns', 'All Campaigns'),
                'criteria' => []
            ]
        ];

        $sources[] = ['heading' => Craft::t('k4-campaigns', 'Campaign Types')];


        $campaignTypes = K4Campaigns::$plugin->campaignTypes->getAllCampaignTypes();

        foreach ($campaignTypes as $campaignType) {
            $sources[] = [
                'key' => 'campaignType:'.$campaignType->id,
                'label' => $campaignType->name,
                'data' => [
                    'handle' => $campaignType->handle
                ],
                'criteria' => [
                    'campaignTypeId' => $campaignType->id
                ]
            ];
        }


        return $sources;
    }

    /**
     * @inheritdoc
     */
    protected static function defineActions(string $source = null): array
    {
        $actions = [];

        $elementsService = Craft::$app->getElements();

        $actions[] = HardDeleteCampaigns::class;

        // Edit
        $actions[] = $elementsService->createAction([
            'type' => Edit::class,
            'label' => Craft::t('k4-campaigns', 'Edit campaign'),
        ]);

        // Delete
        $actions[] = $elementsService->createAction([
            'type' => Delete::class,
            'confirmationMessage' => Craft::t('k4-campaigns', 'Are you sure you want to delete the selected campaigns?'),
            'successMessage' => Craft::t('k4-campaigns', 'Campaigns deleted.'),
        ]);

        // Restore
        $actions[] = $elementsService->createAction([
            'type' => Restore::class,
            'successMessage' => Craft::t('k4-campaigns', 'Campaigns restored.'),
            'partialSuccessMessage' => Craft::t('k4-campaigns', 'Some campaigns restored.'),
            'failMessage' => Craft::t('k4-campaigns', 'Campaigns not restored.'),
        ]);

        // Enable
        $actions[] = EnableElementAction::class;

        return $actions;
    }


    /**
	 * @inheritdoc
	 */
	public function getIsEditable(): bool
	{
		return true;
	}

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function getCpEditUrl()
    {
        return UrlHelper::cpUrl('k4-campaigns/campaigns/'.$this->getCampaignType()->handle.'/'.$this->id);

    }


	// Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
	    $rules = parent::rules();
	    $rules[] = [['subjectLine','slug','fromName','fromEmail','replyToEmail','listSettings'], 'string'];
        $rules[] = [['campaignTypeId'], 'integer'];


	    return $rules;
    }
	

    /**
     * @inheritdoc
     */
    public function getCampaignType()
    {
        if ($this->_campaignType !== null) {
            return $this->_campaignType;
        }

        if ($this->campaignTypeId === null) {
            throw new InvalidConfigException('CampaignType is missing its campaignType ID');
        }

        $campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById($this->campaignTypeId);

        if ($campaignType === null) {
            throw new InvalidConfigException('Invalid campaignType type ID: '.$this->campaignTypeId);
        }

        $this->_campaignType = $campaignType;

        return $campaignType;
    }



    // Indexes, etc.
    // -------------------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function getEditorHtml(): string
    {
	    $html = Craft::$app->getView()->renderTemplate('k4-campaigns/campaigns/_includes/fields.twig', [
		    'campaign' => $this
	    ]);

        // Set the field layout ID
        $this->fieldLayoutId = $this->getCampaignType()->fieldLayoutId;

        $html .= parent::getEditorHtml();

        return $html;
    }
	
	/**
	 * @inheritdoc
	 */
	protected static function defineSortOptions(): array
	{
		return [
            'subjectLine' => Craft::t('k4-campaigns', 'Betreff'),
            'dateCreated' => Craft::t('k4-campaigns', 'Erstellungsdatum'),
			'dateSent' => Craft::t('k4-campaigns', 'Gesendet')
		];
	}

	/**
	 * @inheritdoc
	 */
	protected static function defineTableAttributes(): array
	{
		
		$attributes = [
            'subjectLine' => ['label' => Craft::t('app', 'Betreff')],
            'content' => ['label' => Craft::t('app', 'Inhalt')],
            'recipients' => ['label' => Craft::t('app', 'Empfänger')],
			'dateCreated' => ['label' => Craft::t('app', 'Erstellungsdatum')],
            'dateSent' => ['label' => Craft::t('app', 'Gesendet')],
            'preview' => ['label' => Craft::t('app', 'Preview'), 'icon' => 'view'],
            'link' => ['label' => Craft::t('app', 'Link'), 'icon' => 'world'],
            'send' => ['label' => Craft::t('app', 'Send')]
		];
		
		return $attributes;
	}
 
	/**
	 * @inheritdoc
	 */
	protected static function defineDefaultTableAttributes(string $source): array
	{
		$attributes = ['subjectLine','dateCreated','dateSent','send','preview','link'];
		
		return $attributes;
	}
	
	/**
	 * @inheritdoc
	 * @throws InvalidConfigException
	 */
	protected function tableAttributeHtml(string $attribute): string
	{
        $campaignType = $this->campaignType;

		switch ($attribute) {

			case 'preview':

                return Craft::$app->view->renderTemplate('k4-campaigns/_partials/previewLinks.twig', array(
                    'campaign'        => $this,
                    'campaignType' => $campaignType,
                    'type'         => 'html'
                ));

            case 'send':

                return '<button onclick="new Campaign.PrepareModal('.$this->id.');" class="prepare btn small">'.Craft::t('k4-campaigns','Prepare').'</button>';

            case 'dateSent':

                if (empty($this->dateSent)) return '';
                return date_format(date_create($this->dateSent),'d.m.Y');

            case 'recipients':

                $values = json_decode($this->listSettings)->listIds;
                $lists = K4Campaigns::$plugin->mailchimp->getRecipientLists();

                $lists = array_filter($lists, function($list) use ($values) {
                    if (!is_array($values)) return false;
                    return in_array($list['id'],$values);
                });

                if ( empty($lists) ) {
                    return '<span data-icon="remove"></span>';
                } else {
                    return '<span data-icon="check"></span>';
                }
            case 'content':

                if (true){
                    return '<span data-icon="check"></span>';
                } else {
                    return '<span data-icon="remove"></span>';
                }


		}
		
		return parent::tableAttributeHtml($attribute);
	}
	
	
	
	// Events
    // -------------------------------------------------------------------------

    /**
     * @inheritdoc
     */
    public function beforeSave(bool $isNew): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterSave(bool $isNew)
    {
	    if ($isNew) {
		    $campaignRecord = new CampaignRecord();
            $campaignRecord->id = $this->id;
            $campaignRecord->campaignTypeId = $this->campaignTypeId;
	    }
	    else {
		    $campaignRecord = CampaignRecord::findOne($this->id);
	    }
	
	    if ($campaignRecord) {
		    // Set attributes
            $campaignRecord->subjectLine = $this->subjectLine;
            $campaignRecord->campaignTypeId = $this->campaignTypeId;
            $campaignRecord->fromName = $this->fromName;
            $campaignRecord->fromEmail = $this->fromEmail;
            $campaignRecord->replyToEmail = $this->replyToEmail;
            $campaignRecord->listSettings = $this->listSettings;
            $campaignRecord->dateSent = $this->dateSent;
		
		    $campaignRecord->save(false);
	    }
	
	    parent::afterSave($isNew);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
    }

    /**
     * @inheritdoc
     */
    public function beforeMoveInStructure(int $structureId): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterMoveInStructure(int $structureId)
    {
    }

//    protected function previewTargets(): array
//    {
//        $previewTargets = [
//            [
//                'label' => Craft::t('app', 'Primary {type} page', [
//                    'type' => static::lowerDisplayName(),
//                ]),
//                'url' => 'https://www.google.de',
//            ],
//        ];
//        return $previewTargets;
//    }

    protected function route()
    {
        $type = Craft::$app->getRequest()->getQueryParam('type');
        $extension = '';
        if ($type === 'txt') $extension = ".txt";
        return [
            'templates/render', [
                'template' => $this->getCampaignType()->template.$extension,
                'variables' => [
                    'campaign' => $this,
                    'campaignType' => $this->getCampaignType(),
                    'browserVersionUrl' => $this->url
                ]
            ]
        ];
    }


	/**
	 * @inheritdoc
	 */
	public function getStatus()
	{
		if ($this->archived) {
			return self::STATUS_ARCHIVED;
		}
		
		if (!$this->enabled) {
			return self::STATUS_DISABLED;
		}

        if ($this->enabled)
        {
            $currentTime   = DateTimeHelper::currentTimeStamp();
            $dateScheduled = $this->dateScheduled !== null ? $this->dateScheduled->getTimestamp() : null;

            if ($this->dateSent != null)
            {
                return static::STATUS_SENT;
            }

            if ($this->dateScheduled != null && $dateScheduled > $currentTime && $this->dateSent == null)
            {
                return static::STATUS_SCHEDULED;
            }

            return static::STATUS_PENDING;
        }

		return self::STATUS_ENABLED;
	}

//    /**
//     * Determine if this Campaign Email has lists that it will be sent to
//     *
//     * @return bool
//     */
//    public function isListReady()
//    {
//        $mailer = $this->getMailer();
//
//        if ($mailer->hasLists())
//        {
//            if (empty($this->listSettings['listIds']))
//            {
//                return false;
//            }
//        }
//
//        return true;
//    }

    /**
     * Determine if the content of this email is ready to be sent.
     *
     * Confirm that content and templates are valid.
     *
     * @return bool
     */
    public function isContentReady()
    {
        $campaignType = $this->getCampaignType();

        // @todo - update recipient info to be dynamic
        $params = array(
            'campaign'        => $this,
            'campaignType' => $campaignType,
            'recipient'    => array(
                'firstName' => 'First',
                'lastName'  => 'Last',
                'email'     => 'user@domain.com'
            )
        );

        $html = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaignType->template, $params);

        $text = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaignType->template . '.txt', $params);

        if ($html == null || $text == null )
        {
            return false;
        }

        return true;
    }

    /**
     * Determine if this Campaign Email is ready enough to be tested (a campaign can be tested before we assign a list)
     *
     * @return bool
     */
    public function isReadyToTest()
    {
        return (bool) ($this->getMailer() && $this->isContentReady());
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function getUriFormat()
    {
        $campaignType = $this->getCampaignType();

        if ($campaignType && $campaignType->hasUrls)
        {
            return $campaignType->urlFormat;
        }

        return false;
    }
}
