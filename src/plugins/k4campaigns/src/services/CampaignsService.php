<?php
	
	
	namespace k4\k4campaigns\services;
	
	
	use Craft;
	use craft\base\Component;
	use craft\base\ElementInterface;
    use craft\mail\Message;
    use Craft\SproutEmail_RecipientListRelationsModel;
    use Craft\SproutEmail_RecipientListRelationsRecord;
    use DateTime;
    use k4\k4campaigns\elements\CampaignElement;
	use k4\k4campaigns\K4Campaigns;
    use k4\k4campaigns\models\CampaignTypeModel;
    use k4\k4campaigns\models\EmailModel;
    use k4\k4campaigns\records\CampaignRecord;
	use Kint\Kint;
	use yii\db\Expression;

    /**
	 * Class CouponsService
	 *
	 * @package k4\k4campaigns\services
	 */
	class CampaignsService extends Component
	{
        // Public Methods
        // =========================================================================

        /**
         * @param int $campaignId
         * @return CampaignElement|null
         */
        public function getCampaignById(int $campaignId)
        {
            return CampaignElement::find()
                ->id($campaignId)
                ->one();

        }

        /**
         * @param int $campaignTypeId
         * @return CampaignElement[]
         */
        public function getCampaignsByCampaignTypeId(int $campaignTypeId)
        {
            return CampaignElement::find()
                ->where(['campaignTypeId'=>$campaignTypeId])
                ->all();

        }

        /**
         * Output the prepared Email to the page
         *
         * @param CampaignElement $email
         * @param string     $template
         */
        public function showCampaign(CampaignElement $campaign, $fileExtension = 'html')
        {
            if ($fileExtension == 'txt')
            {
                // Render the email templates
                $body = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaign->getCampaignType()->template . '.txt', array(
                    'campaign'  => $campaign
                ));
                $output = $body;
            }
            else
            {
                $htmlBody = K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaign->getCampaignType()->template, array(
                    'campaign'  => $campaign
                ));

                $output = $htmlBody;

            }
            // Output it into a buffer, in case TasksService wants to close the connection prematurely
            ob_start();

            echo $output;

            // End the request
            Craft::$app->end();
        }

        public function getRecipientListsByCampaignId($id)
        {
            if (($lists = SproutEmail_RecipientListRelationsRecord::model()->findAllByAttributes(array('emailId' => $id))))
            {
                return SproutEmail_RecipientListRelationsModel::populateModels($lists);
            }
        }

    }
