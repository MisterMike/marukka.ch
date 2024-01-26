<?php

namespace k4\k4campaigns\elements\db;

use k4\k4campaigns\elements\CampaignElement;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\records\CampaignTypeRecord;
use k4\k4campaigns\records\CampaignRecord;
use Kint\Kint;
use Solspace\Freeform\Elements\Db\SubmissionQuery;
use yii\db\Connection;

/**
 * CampaignElementQuery
 *
 * @method CampaignElement[]|array all($db = null)
 * @method CampaignElement|array|null one($db = null)
 * @method CampaignElement|array|null nth(int $n, Connection $db = null)
 *
 */
class CampaignElementQuery extends ElementQuery
{
    // Properties
    // =========================================================================

    // General parameters
    // -------------------------------------------------------------------------

	/**
	 * @var int
	 */
	public $campaignTypeId;

    /**
     * @var boolean
     */
    public $enabled;

    /**
     * @var boolean
     */
    public $campaignTypeHandle;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        //Kint::dump($this);

    }


    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        //Kint::dump($this);
    }

	public function campaignTypeId($campaignTypeId): CampaignElementQuery
	{
		$this->campaignTypeId = $campaignTypeId;
		
		return $this;
	}

    public function enabled(bool $enabled): CampaignElementQuery
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function campaignTypeHandle(string $handle): CampaignElementQuery
    {
        $this->campaignHandle = $handle;

        return $this;
    }

	// Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
    	
        $this->joinElementTable('k4campaigns_campaigns');
        
	    $this->query->select([
		    'k4campaigns_campaigns.campaignTypeId',
		    'k4campaigns_campaigns.dateCreated',
		    'k4campaigns_campaigns.dateSent',
            'k4campaigns_campaigns.dateUpdated',
            'k4campaigns_campaigns.subjectLine',
            'k4campaigns_campaigns.fromEmail',
            'k4campaigns_campaigns.fromName',
            'k4campaigns_campaigns.replyToEmail',
            'k4campaigns_campaigns.listSettings'
	    ]);



        $this->subQuery->innerJoin(CampaignTypeRecord::tableName().' AS k4campaigns_campaigntypes', '[[k4campaigns_campaigntypes.id]] = [[k4campaigns_campaigns.campaignTypeId]]');

	    if ($this->campaignTypeId) {
		    $this->subQuery->andWhere(Db::parseParam('k4campaigns_campaigntypes.id', $this->campaignTypeId));
	    }

        if ($this->campaignTypeHandle) {
            $this->subQuery->andWhere(Db::parseParam('k4campaigns_campaigntypes.handle', $this->campaignTypeHandle));
        }

        return parent::beforePrepare();
    }

    /**
     * @inheritdoc
     */
    protected function statusCondition(string $status)
    {
    	return parent::statusCondition($status);
    }
    
}
