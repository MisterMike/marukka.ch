<?php
	
	
	namespace k4\k4campaigns\services;
	
	
	use Craft;
	use craft\base\Component;
	use craft\queue\jobs\ResaveElements;
	use Exception;
	use k4\k4campaigns\elements\CampaignElement;
	use k4\k4campaigns\K4Campaigns;
	use k4\k4campaigns\models\CampaignTypeModel;
	use k4\k4campaigns\records\CampaignTypeRecord;
	use k4\k4campaigns\records\CampaignRecord;
	use Kint\Kint;
    use putyourlightson\logtofile\LogToFile;
    use Throwable;
	use yii\web\NotFoundHttpException;
	
	/**
	 * Class CouponsService
	 *
	 * @package k4\k4campaigns\services
	 */
	class CampaignTypesService extends Component
	{
		// Public Methods
		// =========================================================================
		
		public function getCampaignTypeById(int $campaignTypeId)
		{
			if (!$campaignTypeId) {
				return null;
			}

			$campaignTypeRecord = CampaignTypeRecord::findOne($campaignTypeId);

			if ($campaignTypeRecord === null) {
				return null;
			}

			/** @var CampaignTypeModel $campaignType */
			$campaignType = CampaignTypeModel::populateModel($campaignTypeRecord, false);

			return $campaignType;

		}
		
		/**
		 * @param string $campaignTypeHandle
		 * @return CampaignTypeModel|null
		 */
		public function getCampaignTypeByHandle(string $campaignTypeHandle)
		{
			if (!$campaignTypeHandle) {
				return null;
			}
			
			$campaignTypeRecord = CampaignTypeRecord::findOne(['handle'=>$campaignTypeHandle]);
			
			if ($campaignTypeRecord === null) {
				return null;
			}
			
			/** @var CampaignTypeModel $campaignType */
            $campaignType = CampaignTypeModel::populateModel($campaignTypeRecord, false);
			
			return $campaignType;
			
		}
		
		
		public function getAllCampaignTypes()
		{
			$campaignTypeRecords = CampaignTypeRecord::find()
				->orderBy(['name' => SORT_ASC])
				->all();
			
			return CampaignTypeModel::populateModels($campaignTypeRecords, false);
		}
		
		public function getAllCampaignTypesForSelect()
		{
			return array_map(function($campaignType)
			{
				return array('label'=>$campaignType->name,'value'=>$campaignType->id);
			},
				$this->getAllCampaignTypes()
			);
		}

		public function deleteCampaignTypeById(int $campaignTypeId): bool
		{
			$campaignType = $this->getCampaignTypeById($campaignTypeId);
			
			if ($campaignType === null) {
				return false;
			}
			
			return $this->deleteCampaignType($campaignType);
		}
		
		public function deleteCampaignType(CampaignTypeModel $campaignType): bool
		{

			$transaction = Craft::$app->getDb()->beginTransaction();

			$campaigns = K4Campaigns::$plugin->campaigns->getCouponsByCampaignTypeId($campaignType->id);

			try {
				// Delete the field layout
                if ($campaignType->fieldLayoutId) {
                    Craft::$app->getFields()->deleteLayoutById($campaignType->fieldLayoutId);
                }

				$elements = Craft::$app->getElements();
				
				foreach ($campaigns as $campaign) {
					$elements->deleteElement($campaign);
				}
				
				// Delete the campaignType
				$campaignTypeRecord = CampaignTypeRecord::findOne($campaignType->id);
				
				if ($campaignTypeRecord !== null) {
					$campaignTypeRecord->delete();
				}
				
				$transaction->commit();
				
			} catch (Throwable $e) {
                LogToFile::error($e->getMessage(), 'k4campaigns');
				$transaction->rollBack();
				
				throw $e;
			}

			return true;
		}
		
		public function saveCampaignType(CampaignTypeModel $campaignType, bool $runValidation = null): bool
		{
			$runValidation = $runValidation ?? true;
			
			$isNew = $campaignType->id === null;
			
			if ($runValidation && !$campaignType->validate()) {
				K4Campaigns::$plugin->log('CampaignType not saved due to validation error.');
				
				return false;
			}
			
			if ($campaignType->id) {
				$campaignTypeRecord = CampaignTypeRecord::findOne($campaignType->id);
				
				if ($campaignTypeRecord === null) {
					throw new NotFoundHttpException("No campaignType exists with the ID '{$campaignType->id}'");
				}
			}
			else {
				$campaignTypeRecord = new CampaignTypeRecord();
			}
			
			$campaignTypeRecord->setAttributes($campaignType->getAttributes(), false);
			
			// Unset ID if null to avoid making postgres mad
			if ($campaignTypeRecord->id === null) {
				unset($campaignTypeRecord->id);
			}
			
			$transaction = Craft::$app->getDb()->beginTransaction();
			
			try {
				// Save the field layout
				$fieldLayout = $campaignType->getFieldLayout();
				Craft::$app->getFields()->saveLayout($fieldLayout);
				
				$campaignType->fieldLayoutId = $fieldLayout->id;
				$campaignTypeRecord->fieldLayoutId = $fieldLayout->id;
				
				// Save the campaignType
				if (!$campaignTypeRecord->save(false)) {
					throw new Exception('Couldn’t save campaignType record.');
				}
				
				// Now that we have a campaignType ID, save it on the model
				if (!$campaignType->id) {
                    $campaignType->id = $campaignTypeRecord->id;
				}
				
				$transaction->commit();
			} catch (Throwable $e) {
                LogToFile::error($e->getMessage(), 'k4campaigns');
				$transaction->rollBack();
				throw $e;
			}
			
			return true;
		}
		
	}
