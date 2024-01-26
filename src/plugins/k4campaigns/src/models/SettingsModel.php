<?php
/**
 * k4 Coupon Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\models;

use craft\behaviors\EnvAttributeParserBehavior;
use craft\behaviors\FieldLayoutBehavior;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\base\Model;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class SettingsModel extends Model
{
    // Public Properties
    // =========================================================================
	
	/**
	 * @var mixed The memory usage limit per sendout campaignType in bytes or a shorthand byte value (set to -1 for unlimited)
	 */
	public $memoryLimit = '1024M';
	
	/**
	 * @var int The execution time limit per sendout campaignType in seconds (set to 0 for unlimited)
	 */
	public $timeLimit = 3600;
	
	/**
	 * @var mixed The memory usage limit to use when found to be unlimited
	 */
	public $unlimitedMemoryLimit = '4G';

    /**
     * @var string
     */
    public $pluginName;

    /**
     * @var string
     */
    public $mailchimpApiKey;

    /**
     * @var string
     */
    public $mailchimpServerPrefix;

	/**
	 * @var int $campaignGenerateLength
	 */
	public $campaignErrorMessage = "Please provide a valid campaign.";
	
	/**
	 * @var int $campaignGenerateLength
	 */
	public $campaignGenerateLength = 10;
	
	/**
	 * @var int $campaignGenerateNumbers
	 */
	public $campaignGenerateNumbers = false;
	
	/**
	 * @var int $campaignGenerateLower
	 */
	public $campaignGenerateLower = true;
	
	/**
	 * @var int $campaignGenerateUpper
	 */
	public $campaignGenerateUpper = true;
	
	/**
	 * @var int $campaignGenerateUpper
	 */
	public $campaignGenerateSpecial = false;
	

	
    // Public Methods
    // =========================================================================
	
	/**
	 * @inheritdoc
	 */
	public function behaviors(): array
	{
		return [

		];
	}
	
	/**
	 * @return array
	 */
	public function rules(): array
	{
		return [

		];
	}

}
