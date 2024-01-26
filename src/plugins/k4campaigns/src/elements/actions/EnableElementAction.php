<?php

namespace k4\k4campaigns\elements\actions;

use Craft;
use craft\base\ElementAction;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Json;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\services\CampaignsService;
use Throwable;

class EnableElementAction extends ElementAction
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function isDestructive(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('k4-campaigns', 'Enable');
    }

    /**
     * @inheritdoc
     */
    public function getConfirmationMessage()
    {
        return Craft::t('k4-campaigns', 'Are you sure you want to enable the selected campaigns?');
    }
    
    /**
     * Performs the action on any elements that match the given criteria.
     *
     * @param ElementQueryInterface $query The element query defining which elements the action should affect.
     *
     * @return bool Whether the action was performed successfully.
     * @throws Throwable
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        /** @var CampaignElement[] $campaigns */
        $campaigns = $query->all();
	    /** @var CampaignsService $campaignsService */
        $campaignsService = K4Campaigns::$plugin->campaigns;

        foreach ($campaigns as $campaign) {
        	$campaign->enabled = true;

	        if (!Craft::$app->getElements()->saveElement($campaign)){
	        	return false;
	        }
	        
            // Log it
            K4Campaigns::$plugin->log('Campaigns enabled by "{username}".');
        }

        $this->setMessage(Craft::t('k4-campaigns', 'Campaigns enabled'));

        return true;
    }
}
