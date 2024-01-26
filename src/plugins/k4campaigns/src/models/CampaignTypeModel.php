<?php
	
	namespace k4\k4campaigns\models;
	
	use Craft;
    use craft\behaviors\FieldLayoutBehavior;
    use craft\helpers\UrlHelper;
    use craft\models\FieldLayout;
    use k4\k4campaigns\elements\CampaignElement;

    /**
     * Class CampaignTypeModel
     * @package k4\k4campaigns\models
     *
     * @mixin FieldLayoutBehavior
     *
     * @method FieldLayout getFieldLayout()
     * @method setFieldLayout(FieldLayout $fieldLayout)
     */
	class CampaignTypeModel extends BaseModel {
		
		/**
		 * @var integer $id
		 */
		public $id;

		/**
		 * @var string $name
		 */
		public $name;
		
		/**
		 * @var string $handle
		 */
		public $handle;

        /**
         * @var bool $enabled
         */
        public $enabled;

        /**
         * @var string|null $titleFormat
         */
        public $titleFormat;

        /**
         * @var string|null $urlFormat
         */
        public $urlFormat;

        /**
         * @var bool $hasUrls
         */
        public $hasUrls;

        /**
         * @var bool $hasAdvancedTitles
         */
        public $hasAdvancedTitles;

        /**
         * @var string $template
         */
        public $template;

        /**
         * @var string $templateTxt
         */
        public $templateTxt;

        /**
         * @var string $templateCopyPaste
         */
        public $templateCopyPaste;

        /**
         * @var int|null Field layout ID
         */
        public $fieldLayoutId;

        /**
         * @var int $emailId
         */
        public $emailId;


        /**
		 * Use the handle as the string representation.
		 *
		 * @return string
		 */
		public function __toString(): string
		{
			return (string)$this->handle;
		}

        /**
         * @inheritdoc
         */
        public function behaviors(): array
        {
            return [
                'fieldLayout' => [
                    'class' => FieldLayoutBehavior::class,
                    'elementType' => CampaignElement::class
                ],
            ];
        }

        /**
		 * @inheritdoc
		 */
		public function rules(): array
		{
			return [
				[['id','fieldLayoutId','emailId'], 'integer'],
				[['name', 'handle','titleFormat','urlFormat','template','templateTxt','templateCopyPaste'], 'string', 'max' => 255],
				[['enabled','hasUrls','hasAdvancedTitles'], 'boolean']
			];
		}
		
		/**
		 * Returns the CP edit URL.
		 *
		 * @return string
		 */
		public function getCpEditUrl(): string
		{
			return UrlHelper::cpUrl('k4-campaigns/settings/campaign-types/'.$this->id);
		}
		
	}
