<?php
/**
 * Site Module module for Craft CMS 4.x
 *
 * A simple Module to enhance your site
 *
 * @copyright Copyright (c) 2019-23 Mischa Sprecher
 */


namespace modules\sitemodule\models;

use Craft;
use craft\base\Model;
use craft\validators\ArrayValidator;


/**
 *
 * @property-read void $settings
 */
class Settings extends Model
{
    public array $data = [];
    public string $type = "";
}
