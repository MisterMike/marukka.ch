<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */
namespace k4\k4campaigns\elements\actions;

use Craft;
use craft\base\ElementAction;
use craft\elements\actions\Delete;
use craft\elements\db\ElementQueryInterface;
use craft\helpers\Json;

/**
 * DeleteCoupons
 *
 *
 * @property void   $triggerHtml
 * @property string $triggerLabel
 * @property mixed  $confirmationMessage
 */
class PrepareCampaignAction extends ElementAction
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('k4-campaigns', 'Prepare');
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

    /**
     * @inheritdoc
     */
    public function getTriggerHtml()
    {
        $type = Json::encode(static::class);

        $js = <<<EOD
(function()
{
new Campaign.PrepareModal();


})();
EOD;

        Craft::$app->getView()->registerJs($js);
    }

}
