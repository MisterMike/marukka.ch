<?php

namespace k4\k4campaigns\models;

use craft\behaviors\FieldLayoutBehavior;
use craft\helpers\UrlHelper;
use craft\models\FieldLayout;
use k4\k4campaigns\elements\CampaignElement;

/**
 * Class CampaignTypeModel
 * @package k4\k4campaigns\models
 *
 *
 */
class MailerModel extends BaseModel {

    /**
     * @var integer $id
     */
    public $id;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var string $handle
     */
    public $handle;


    /**
     * Use the handle as the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->handle;
    }


    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'handle'], 'string', 'max' => 255],
        ];
    }

    /**
     * Returns the CP edit URL.
     *
     * @return string
     */
    public function getCpEditUrl(): string
    {
        return UrlHelper::cpUrl('k4-campaigns/settings/mailers/'.$this->id);
    }



}
