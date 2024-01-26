<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */
namespace k4\k4campaigns\elements\actions;

use Craft;
use craft\elements\actions\Delete;
use craft\elements\db\ElementQueryInterface;

/**
 * DeleteCoupons
 *
 *
 * @property void   $triggerHtml
 * @property string $triggerLabel
 * @property mixed  $confirmationMessage
 */
class HardDeleteCampaigns extends Delete
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('k4-campaigns', 'Delete permanently');
    }

    /**
     * @inheritdoc
     */
    public function getConfirmationMessage()
    {
        return Craft::t('k4-campaigns', 'Are you sure you want to permanently delete the selected campaigns? This action cannot be undone.');
    }

    /**
     * @inheritdoc
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        $elementsService = Craft::$app->getElements();

        foreach ($query->all() as $campaign) {
            $elementsService->deleteElement($campaign, true);
        }

        $this->setMessage(Craft::t('k4-campaigns', 'Campaigns permanently deleted.'));

        return true;
    }
}
