<?php
	/**
	 * Section Field plugin for Craft 3.0
	 * @copyright Copyright Charlie Development
	 */
	
	namespace k4\k4campaigns\fields;
	
	use Craft;
	use craft\base\ElementInterface;
	use craft\base\Field;
    use k4\k4campaigns\K4Campaigns;
    use k4\k4campaigns\models\CampaignTypeModel;
	use yii\db\Schema;
	
	/**
	 * This field allows a selection from a configured set of sections.
	 */
	class CampaignTypeField extends Field
	{
		/**
		 * @inheritDoc
		 */
		public static function displayName(): string
		{
			return Craft::t('k4-campaigns','k4 Campaigns - Type');
		}
		
		/**
		 * @inheritDoc
		 */
		public static function defaultSelectionLabel(): string
		{
			return Craft::t('k4-campaigns','Add a Type');
		}
		
		/**
		 * @inheritDoc
		 */
		public function getContentColumnType(): string
		{
			return Schema::TYPE_INTEGER;
		}
		
		/**
		 * @inheritdoc
		 */
		protected function optionsSettingLabel(): string
		{
			return Craft::t('k4-campaigns','k4 Campaigns Options');
		}
		
		/**
		 * @inheritDoc IFieldType::getInputHtml()
		 *
		 * @param mixed $value
		 * @param ElementInterface|null $element
		 *
		 * @return string
		 */
		public function getInputHtml($value, ElementInterface $element = null): string
		{
			$campaignTypes = K4Campaigns::$plugin->campaignTypes->getAllCampaignTypes();

            $campaignTypeOptions = [
                '' => Craft::t('k4-campaigns','Select a campaign type')
            ];

			/** @var CampaignTypeModel $campaignType */
			foreach ($campaignTypes as $campaignType) {
				if (\is_array($campaignType)) {
					$campaignTypeOptions[(int) $campaignType['id']] = $campaignType['name'];
				} else if ($campaignType instanceof CampaignTypeModel) {
					$campaignTypeOptions[(int) $campaignType->id] = $campaignType->name;
				}
			}
			
			return \Craft::$app->view->renderTemplate(
				'k4-campaigns/_components/fields/batch',
				[
					'name'         => $this->handle,
					'batches'        => $campaignTypes,
					'batchOptions'  => $campaignTypeOptions,
					'selectedBatch' => $value instanceof CampaignTypeModel ? $value->id : null,
				]
			);
		}
		
		/**
		 * @inheritDoc
		 */
		public function serializeValue($value, ElementInterface $element = null)
		{
			if ($value instanceof CampaignTypeModel) {
				return $value->id;
			}
			
			return parent::serializeValue($value, $element);
		}
		
		/**
		 * @inheritDoc
		 */
		public function normalizeValue($value, ElementInterface $element = null)
		{
			if ($value instanceof CampaignTypeModel) {
				return $value;
			}
			
			$campaignType = K4Campaigns::$plugin->campaignTypes->getCampaignTypeById((int) $value);
			
			if (empty($campaignType)) return null;
			
			return $campaignType;
		}
	}
