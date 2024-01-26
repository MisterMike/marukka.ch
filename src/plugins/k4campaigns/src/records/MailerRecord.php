<?php
namespace Craft;

use craft\db\ActiveRecord;

/**
 * Class SproutEmail_MailerRecord
 *
 * @package Craft
 * --
 * @property string $name
 * @property array  $settings
 */
class MailerRecord extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%k4campaigns_mailers}}';
    }

}
