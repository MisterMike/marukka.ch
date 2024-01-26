<?php
namespace k4\k4campaigns\services;

use Craft;
use craft\base\Component;
use craft\base\Model;
use craft\web\View;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\models\CampaignTypeModel;
use Kint\Kint;
use MailchimpMarketing\ApiClient;
use putyourlightson\logtofile\LogToFile;

/**
 * Class SproutEmailMailchimpService
 *
 * @package Craft
 */
class MailchimpService extends Component
{
	/**
	 * @var Model
	 */
	protected $settings;

	/**
	 * @var ApiClient
	 */
	protected $client;

    /**
     * Loads the mail chimp library and associated dependencies
     */
    public function init()
    {

    }

    /**
	 * @param Model $settings
	 */
	public function setSettings($settings)
	{
		$this->settings = $settings;
	}

	/**
	 * @param ApiClient $client
	 */
	public function setClient(ApiClient $client)
	{
		$this->client = $client;
	}

	/**
	 * @return array|null
	 */
	public function getRecipientLists()
	{
		try
		{
		    if (empty($this->client)){

                $client = new ApiClient();

                $client->setConfig([
                    'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                    'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
                ]);
                $this->setClient($client);

            }

			$lists = $this->client->lists->getAllLists();

            //$oldClient = new \Mailchimp(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"], array('ssl_verifypeer' => false));
            //$oldLists = $oldClient->lists->getList();

            if (!$lists->lists) return false;

            $lists = $this->getObjectArrayAsArray($lists->lists);

		    //Kint::dump($listsNeu);
            //Kint::dump($oldLists['data']);
		    //die();

			return $lists;

		}
		catch (\Exception $e)
		{
            LogToFile::error($e->getMessage(), 'k4campaigns');

			if ($e->getMessage() == 'API call to lists/list failed: SSL certificate problem: unable to get local issuer certificate')
			{
				return false;
			}
			else
			{
				return $e->getMessage();
			}
		}
	}

    /**
     * Renders the recipient list UI for this mailer
     *
     * @return string Rendered HTML content
     */
    public function getRecipientListsHtml($values = null)
    {
        $lists = $this->getRecipientLists();
        //Kint::dump($lists);

        $options  = array();
        $selected = array();

        if ($lists === false)
        {
            return Craft::$app->view->render('k4campaigns/settings/mailers/mailchimp/lists/sslerror');
        }

        if (!is_array($lists))
        {
            return $lists;
        }

        if (!count($lists))
        {
            return Craft::$app->view->render('k4campaigns/settings/mailers/mailchimp/lists/norecipientlists');
        }

        if (count($lists))
        {
            foreach ($lists as $list)
            {
                if (isset($list['id']) && isset($list['name']))
                {
                    $length = 0;

                    if ($lists = $this->getListStatsById($list['id']))
                    {
                        $length = number_format($list['stats']['member_count']);
                    }
                    $prefix = Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"]);
                    $listUrl = "https://".$prefix.".admin.mailchimp.com/lists/members/?id=" . $list['web_id'];

                    $options[] = array(
                        'label' => '<a target="_blank" href="'.$listUrl.'">'.$list['name'].' ('.$length.')</a>',
                        'value' => $list['id']
                    );
                }
            }
        }

        if (is_array($values) && count($values))
        {
            foreach ($values as $value)
            {
                $selected[] = $value;
            }
        }

        return html_entity_decode(Craft::$app->view->renderTemplateMacro(
            '_includes/forms',
            'checkboxGroup',
            array(
                array(
                    'id'      => 'recipientLists',
                    'name'    => 'listSettings[listIds]',
                    'options' => $options,
                    'values'  => $selected,
                )
            ),
            'cp'
        ));
    }

    /**
     * Renders the recipient list UI for this mailer for prepare Modal
     *
     * @return string Rendered HTML content
     */
    public function getRecipientListsHtmlForPrepareModal($values = null)
    {
        $lists = $this->getRecipientLists();

        if (empty($values))
        {
            Craft::$app->view->setTemplatesPath(Craft::getAlias('@k4campaigns'));
            return Craft::$app->view->renderTemplate('settings/mailers/mailchimp/lists/norecipientlists.twig');
        }

        $lists = array_filter($lists, function($list) use ($values) {
            if (!is_array($values)) return false;
            return in_array($list['id'],$values);
        });

        $html = '<ul>';

        if ($lists === false)
        {
            Craft::$app->view->setTemplatesPath(Craft::getAlias('@k4campaigns'));
            return Craft::$app->view->renderTemplate('k4-campaigns/settings/mailers/mailchimp/lists/sslerror.twig');
        }

        if (!is_array($lists))
        {
            return $lists;
        }

        if (!count($lists))
        {
            Craft::$app->view->setTemplatesPath(Craft::getAlias('@k4campaigns'));
            return Craft::$app->view->renderTemplate('settings/mailers/mailchimp/lists/norecipientlists.twig');
        }

        if (count($lists))
        {
            foreach ($lists as $list)
            {
                if (isset($list['id']) && isset($list['name']))
                {
                    $length = 0;

                    if ($lists = $this->getListStatsById($list['id']))
                    {
                        $length = number_format($lists['member_count']);
                    }
                    $prefix = Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"]);
                    $listUrl = "https://".$prefix.".admin.mailchimp.com/lists/members/?id=" . $list['web_id'];
                    $string = sprintf('<a target="_blank" href="%s">%s (%s)</a>', $listUrl, $list['name'], $length);
                    $html .= '<li>'.$string.'</li>';
                }
            }
        }
        $html .= '</ul>';

        return $html;
    }


    /**
	 * @param $id
	 *
	 * @throws \Exception
	 * @return array|null
	 */
	public function getRecipientListById($id)
	{
		$params = array('list_id' => $id);

        if (empty($this->client)){

            $client = new ApiClient();

            $client->setConfig([
                'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
            ]);
            $this->setClient($client);
        }

        try
		{
            //$oldClient = new \Mailchimp(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"], array('ssl_verifypeer' => false));
            //$oldLists = $oldClient->lists->getList($params);

            $list = $this->getObjectAsArray($this->client->lists->getList($id));

            return $list;
		}
		catch (\Exception $e)
		{
            LogToFile::error($e->getMessage(), 'k4campaigns');

		    if ($e->getCode() === 404) return false;

			throw $e;
		}
	}

	public function getListStatsById($id)
	{
		$params = array('list_id' => $id);

        if (empty($this->client)){

            $client = new ApiClient();

            $client->setConfig([
                'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
            ]);
            $this->setClient($client);
        }

        try
		{
            //$oldClient = new \Mailchimp(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"], array('ssl_verifypeer' => false));
            //$oldLists = $oldClient->lists->getList($params);
            //$oldStats = $oldLists['data'][0]['stats'];

			$stats = $this->getObjectAsArray($this->client->lists->getList($id)->stats);

			//Kint::dump($oldStats,$stats);
			//die();

			return $stats;
		}
		catch (\Exception $e)
		{
            LogToFile::error($e->getMessage(), 'k4campaigns');

            if ($e->getCode() === 404) return false;

            throw $e;
		}
	}

	/**
	 * @param $id
	 *
	 * @throws \Exception
	 * @return array|null
	 */
	public function getRecipientsByListId($id)
	{
        if (empty($this->client)){

            $client = new ApiClient();

            $client->setConfig([
                'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
            ]);
            $this->setClient($client);
        }

        try
		{
            $oldClient = new \Mailchimp(Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]), array('ssl_verifypeer' => false));
            $oldMembers = $oldClient->lists->members($id);

            $members = $this->getObjectArrayAsArray($this->client->lists->getListMembersInfo($id)->members);

            //Kint::dump($oldMembers,$members);

            return $members;

		}
		catch (\Exception $e)
		{
            LogToFile::error($e->getMessage(), 'k4campaigns');

            if ($e->getCode() === 404) return false;

            throw $e;
		}
	}

	/**
	 * @param CampaignElement $campaign
	 * @param CampaignTypeModel  $campaignType
	 * @param bool $sendOnExport
	 *
	 * @return array
	 * @throws \Exception
	 */
	public function sendCampaign($campaign, $sendOnExport = true)
	{
		$lists       = json_decode($campaign->listSettings)->listIds;

        $campaignType = $campaign->getCampaignType();

		$campaignIds = array();

		//Kint::$expanded = true;
		//Kint::dump($lists);
		//die();

		if ($lists && count($lists))
		{

			$params = array(
				'campaign'      => $campaign,
				'campaignType'  => $campaignType,
				'recipient' => array(
					'firstName' => 'First',
					'lastName'  => 'Last',
					'email'     => 'user@domain.com'
				),

				// @deprecate - in favor of `email` in v3
				'entry'     => $campaign
			);

            try
            {
                $content = array(
                    'html' => K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaignType->template, $params),
                    'text' => K4Campaigns::$plugin->email->renderSiteTemplateIfExists($campaignType->template . '.txt', $params),
                );
            }
            catch (\Exception $e)
            {
                LogToFile::error($e->getMessage(), 'k4campaigns');

                throw new \Exception($e->getMessage());
            }


			foreach ($lists as $list)
			{
				$options['list_id'] = $list;

//				if ($this->settings->inlineCss)
//				{
//					$options['inline_css'] = true;
//				}

				try
				{
                    if (empty($this->client)){

                        $client = new ApiClient();

                        $client->setConfig([
                            'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                            'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
                        ]);
                        $this->setClient($client);
                    }

                    //$client = new \Mailchimp(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"], array('ssl_verifypeer' => false));
                    //$this->setClient($client);

                    //Kint::$expanded = true;
                    //Kint::dump($options);
                    //die();

                    $options = array(
                        "type" => "regular",
                        "recipients" => array("list_id" => $list),
                        "settings" => array(
                            'subject_line'  => $campaign->title,
                            'title'         => $campaign->title,
                            'from_name'     => $campaign->fromName,
                            'reply_to'      => $campaign->fromEmail,
                        ),
                        "tracking" => array(
                            'opens'       => true,
                            'html_clicks' => true,
                            'text_clicks' => false
                        )
                    );

                    $campaignResult  = $this->client->campaigns->create($options);
					$campaignIds[] = $campaignResult->id;

				}
				catch (\Exception $e)
				{
                    LogToFile::error($e->getMessage(), 'k4campaigns');
					throw $e;
				}
			}
		}

		if (count($campaignIds) && $sendOnExport)
		{
			foreach ($campaignIds as $mailchimpCampaignId)
			{
				try
				{
                    $response = $this->client->campaigns->setContent($mailchimpCampaignId,$content);
                    $this->client->campaigns->send($mailchimpCampaignId);

				}
				catch (\Exception $e)
				{
                    LogToFile::error($e->getMessage(), 'k4campaigns');
					throw $e;
				}
			}
		}


		return array('ids' => $campaignIds);
	}

	/**
	 * Sends a previously created/exported campaign via its mail chimp campaign id
	 *
	 * @param string $mailchimpCampaignId
	 *
	 * @throws \Exception
	 * @return true
	 */
	public function send($mailchimpCampaignId)
	{
		try
		{
            if (empty($this->client)){

                $client = new ApiClient();

                $client->setConfig([
                    'apiKey' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpApiKey"]),
                    'server' => Craft::parseEnv(K4Campaigns::$plugin->getSettings()["mailchimpServerPrefix"])
                ]);
                $this->setClient($client);
            }

			$this->client->campaigns->send($mailchimpCampaignId);

			return true;
		}
		catch (\Exception $e)
		{
            LogToFile::error($e->getMessage(), 'k4campaigns');
			throw $e;
		}
	}

	protected function getObjectArrayAsArray($object){
        $arr = array_map(function($o){
            return $this->getObjectAsArray($o);
        },
            $object
        );
        return $arr;
    }

    protected function getObjectAsArray($object)
    {
        return json_decode(json_encode($object),true);
    }
}
