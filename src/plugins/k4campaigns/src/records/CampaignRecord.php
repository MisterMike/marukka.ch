<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\records;

use craft\records\Element;
use craft\records\User;
use DateTime;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;

/**
 * CouponRecord
 *
 * @property int $id ID
 * @property int|null $campaignTypeId User ID
 * @property string $subjectLine
 * @property string $fromName
 * @property string $fromEmail
 * @property string $replyToEmail
 * @property string $listSettings
 * @property DateTime $dateSent
 * @property ActiveQuery $element
 * @property ActiveQuery $campaignType
 *
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class CampaignRecord extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%k4campaigns_campaigns}}';
    }
	
	/**
	 * Returns the related element.
	 *
	 * @return ActiveQueryInterface
	 */
	public function getElement(): ActiveQueryInterface
	{
		return $this->hasOne(Element::class, ['id' => 'id']);
	}

	/**
	 * Returns the related campaignType record.
	 *
	 * @return ActiveQueryInterface
	 */
	public function getCampaignType(): ActiveQueryInterface
	{
		return $this->hasOne(CampaignTypeRecord::class, ['id' => 'campaignTypeId']);
	}
}
