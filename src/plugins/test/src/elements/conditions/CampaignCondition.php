<?php

namespace mischasprecher\crafttest\elements\conditions;

use Craft;
use craft\elements\conditions\ElementCondition;

/**
 * Campaign condition
 */
class CampaignCondition extends ElementCondition
{
    protected function conditionRuleTypes(): array
    {
        return array_merge(parent::conditionRuleTypes(), [
            // ...
        ]);
    }
}
