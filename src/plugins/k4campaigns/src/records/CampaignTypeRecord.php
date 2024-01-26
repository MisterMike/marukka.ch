<?php
	
	
	namespace k4\k4campaigns\records;
	
	use craft\records\User;
	use craft\db\ActiveRecord;
	use someNamespaceA\NamespacedClass;
	use yii\db\ActiveQueryInterface;
	
	/**
	 * Class CampaignTypeRecord
	 *
	 * @package k4\k4campaigns\records
	 *
     * @property int    $id
     * @property string $name
     * @property string $handle
     * @property int $enabled
     * @property string $titleFormat
     * @property string $urlFormat
     * @property bool   $hasUrls
     * @property bool   $hasAdvancedTitles
     * @property string $template
     * @property string $templateTxt
     * @property string $templateCopyPaste
     * @property int    $fieldLayoutId
     * @property string $uid
	 */
	class CampaignTypeRecord extends ActiveRecord
	{
		// Public Static Methods
		// =========================================================================
		
		/**
		 * @inheritdoc
		 */
		public static function tableName()
		{
			return '{{%k4campaigns_campaigntypes}}';
		}

	}
