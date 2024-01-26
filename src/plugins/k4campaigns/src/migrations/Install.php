<?php
/**
 * k4 Campaign Master plugin for Craft CMS 3.x
 *
 * manage campaigns
 *
 * @link      https://www.kreisvier.ch
 * @copyright Copyright (c) 2019 Christian Hiller
 */

namespace k4\k4campaigns\migrations;

use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Christian Hiller
 * @package   K4Campaigns
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;
    
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @return bool
     */
    protected function createTables()
    {
        $sproutCt = Craft::$app->db->schema->getTableSchema('{{%sproutemail_campaigntype}}');
        $sproutC = Craft::$app->db->schema->getTableSchema('{{%sproutemail_campaignemails}}');

        if ($sproutC !== null && $sproutCt !== null) {
            Craft::$app->db->createCommand('RENAME TABLE `rhe_sproutemail_campaigntype` TO `rhe_k4campaigns_campaigntypes`;')->execute();
            Craft::$app->db->createCommand('RENAME TABLE `rhe_sproutemail_campaignemails` TO `rhe_k4campaigns_campaigns`;')->execute();

            $campaigns = CampaignElement::findAll();

            foreach ($campaigns as $campaign) {
                Craft::$app->db->createCommand('UPDATE `rhe_elements` SET `type` = "k4\\\k4campaigns\\\elements\\\CampaignElement",`fieldLayoutId` = '.$campaign->campaigntype->fieldLayoutId.' WHERE `id` = '.$campaign->id)->execute();
            }

            return false;
        }

        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%k4campaigns_campaigns}}');
        if ($tableSchema === null) {
        	
            $this->createTable(
	            '{{%k4campaigns_campaigns}}',
                [
                    'id' => $this->primaryKey(),
                    'subjectLine' => $this->string(),
                    'campaignTypeId' => $this->integer(),
                    'recipients' => $this->string(),
                    'emailSettings' => $this->string(),
                    'listSettings' => $this->string(),
                    'fromName' => $this->string(),
                    'fromEmail' => $this->string(),
                    'replyToEmail' => $this->string(),
                    'enableFileAttachments' => $this->boolean(),
                    'sent' => $this->boolean(),
                    'dateSent' => $this->dateTime(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid()
                ]
            );
        }

	    $tableSchema = Craft::$app->db->schema->getTableSchema('{{%k4campaigns_campaigntypes}}');
	    if ($tableSchema === null) {
		
		    $this->createTable('{{%k4campaigns_campaigntypes}}', [
			    'id' => $this->primaryKey(),
			    'name' => $this->string(),
			    'handle' => $this->string(),
			    'mailer' => $this->string(),
			    'titleFormat' => $this->string(),
			    'urlFormat' => $this->string(),
			    'hasUrls' => $this->boolean()->defaultValue(true),
			    'hasAdvancedTitles' => $this->boolean()->defaultValue(true),
			    'template' => $this->string(500),
			    'templateCopyPaste' => $this->string(),
			    'dateCreated' => $this->dateTime()->notNull(),
			    'dateUpdated' => $this->dateTime()->notNull(),
                'fieldLayoutId' => $this->integer(),
			    'uid' => $this->uid(),
		    ]);
	    }

        return true;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {
	    $this->createIndex(null, '{{%k4campaigns_campaigns}}', 'campaignTypeId', false);
	    $this->createIndex(null, '{{%k4campaigns_campaigntypes}}', 'handle', true);
	    

    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {
	    $this->addForeignKey(null, '{{%k4campaigns_campaigns}}', 'id', '{{%elements}}', 'id', 'CASCADE');
	    $this->addForeignKey(null, '{{%k4campaigns_campaigns}}', 'campaignTypeId', '{{%k4campaigns_campaigntypes}}', 'id', 'CASCADE');
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * @return void
     */
    protected function removeTables()
    {
        $this->dropTableIfExists('{{%k4campaigns_campaigns}}');
        $this->dropTableIfExists('{{%k4campaigns_campaigntypes}}');

    }
}
