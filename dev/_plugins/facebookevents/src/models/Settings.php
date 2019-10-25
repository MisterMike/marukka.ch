<?php
/**
 * FacebookEvents plugin for Craft CMS 3.x
 *
 * A Simple Plugin to load Facebook Events using Graph SDK
 *
 * @link      parhelia.ch
 * @copyright Copyright (c) 2019 Mischa Sprecher
 */

namespace nibiru\facebookevents\models;

use nibiru\facebookevents\FacebookEvents;

use Craft;
use craft\base\Model;

/**
 * FacebookEvents Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Mischa Sprecher
 * @package   FacebookEvents
 * @since     0.1.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $facebookAppId;
    public $facebookSecret;
    public $facebookPage;
    public $facebookAccessToken;
    public $facebookFilter;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['facebookAppId', 'string'],
            ['facebookSecret', 'string'],
            ['facebookPage', 'string'],
            ['facebookAccessToken', 'string'],
            ['facebookFilter', 'boolean']
        ];
    }
}
