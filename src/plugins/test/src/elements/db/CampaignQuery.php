<?php

namespace mischasprecher\crafttest\elements\db;

use Craft;
use craft\elements\db\ElementQuery;

/**
 * Campaign query
 */
class CampaignQuery extends ElementQuery
{
    protected function beforePrepare(): bool
    {
        // todo: join the `campaigns` table
        // $this->joinElementTable('campaigns');

        // todo: apply any custom query params
        // ...

        return parent::beforePrepare();
    }
}
