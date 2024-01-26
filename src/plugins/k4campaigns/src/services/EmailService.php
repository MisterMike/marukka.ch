<?php


namespace k4\k4campaigns\services;


use Craft;
use craft\base\Component;
use craft\base\ElementInterface;
use DateTime;
use k4\k4campaigns\elements\CampaignElement;
use k4\k4campaigns\K4Campaigns;
use k4\k4campaigns\models\CampaignTypeModel;
use k4\k4campaigns\models\EmailModel;
use k4\k4campaigns\records\CampaignRecord;
use Kint\Kint;
use Psr\Log\LogLevel;
use putyourlightson\logtofile\LogToFile;
use yii\db\Expression;

/**
 * Class CouponsService
 *
 * @package k4\k4campaigns\services
 */
class EmailService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Renders a site template when using it in control panel context
     *
     * @param string $template
     * @param array  $variables
     *
     * @return null|string
     */
    public function renderSiteTemplateIfExists($template, array $variables = array())
    {
        $renderedTemplate = null;

        // @todo Craft 3 - figure out why this is necessary
        // If a blank template is passed in, Craft renders the index template
        // If a template is set specifically to the value `test` Craft also
        // appears to render the index template.
        if (empty($template))
        {
            return $renderedTemplate;
        }


        try
        {
            Craft::$app->view->setTemplatesPath(Craft::getAlias('@templates'));
            $renderedTemplate = Craft::$app->view->renderTemplate($template, $variables);

        }
        catch (\Exception $e)
        {
            // Specify template .html if no .txt
            $message = $e->getMessage();

            if (strpos($template, '.txt') === false)
            {
                $message = str_replace($template, $template . '.twig', $message);
            }

            LogToFile::error($e->getMessage(), 'k4campaigns');
            Craft::error($message,'k4-campaigns');
            Craft::$app->getErrorHandler()->logException($e);

        }

        return $renderedTemplate;
    }

    /**
     * Returns whether or not a site template exists
     *
     * @param $template
     *
     * @return bool
     */
    public function doesSiteTemplateExist($template)
    {
        // Set Craft to the site template mode
        $view = Craft::$app->getView();
        $oldTemplateMode = $view->getTemplateMode();
        $view->setTemplateMode($view::TEMPLATE_MODE_SITE);

        // Do the templates exist?
        $templatesExist = $template !== null && $view->doesTemplateExist((string)$template);

        // Restore the original template mode
        $view->setTemplateMode($oldTemplateMode);

        return $templatesExist;
    }

    /**
     * @param CampaignElement $campaignElement
     * @param string     $template
     * @param            $notification
     * @param            $object
     *
     * @return CampaignElement
     */
    public function renderEmailTemplates(EmailModel $emailModel, $template = '', $notification, $object)
    {
        $emailService = K4Campaigns::$plugin->email;

        // Render Email Entry fields that have dynamic values
        $emailModel->subject   = $emailService->renderObjectTemplateSafely($notification->subjectLine, $object);
        $emailModel->fromName  = $emailService->renderObjectTemplateSafely($notification->fromName, $object);
        $emailModel->fromEmail = $emailService->renderObjectTemplateSafely($notification->fromEmail, $object);
        $emailModel->replyTo   = $emailService->renderObjectTemplateSafely($notification->replyToEmail, $object);

        // Render the email templates
        $emailModel->body = $emailService->renderSiteTemplateIfExists($template . '.txt', array(
            'email'  => $notification,
            'object' => $object
        ));

        $emailModel->htmlBody = $emailService->renderSiteTemplateIfExists($template, array(
            'email'  => $notification,
            'object' => $object
        ));

        $styleTags = array();

        // Add placeholder style tags to avoid CSS brackets conflicting with Craft object syntax shorthand i.e. {title}
        $htmlBody = $this->addPlaceholderStyleTags($emailModel->htmlBody, $styleTags);

        // Some Twig code in our email fields may need us to decode
        // entities so our email doesn't throw errors when we try to
        // render the field objects. Example: {variable|date("Y/m/d")}
        $emailModel->body = htmlspecialchars_decode($emailModel->body);
        $htmlBody         = htmlspecialchars_decode($htmlBody);

        // Process the results of the template sonce more, to render any dynamic objects used in fields
        $emailModel->body     = $emailService->renderObjectTemplateSafely($emailModel->body, $object);
        $emailModel->htmlBody = $emailService->renderObjectTemplateSafely($htmlBody, $object);

        $emailModel->htmlBody = $this->removePlaceholderStyleTags($emailModel->htmlBody, $styleTags);

        // @todo Craft 3 - update error handling
        $templateError = $emailService->getError('template');

        if (!empty($templateError))
        {
            $emailModel->htmlBody = $templateError;
        }

        return $emailModel;
    }

    /**
     * Allows us to render an object template without creating a fatal error
     *
     * @param string $string
     * @param object $object
     *
     * @return string
     */
    public function renderObjectTemplateSafely($string, $object)
    {
        try
        {
            return Craft::$app->view->renderObjectTemplate($string, $object);
        }
        catch (\Exception $e)
        {
            LogToFile::error($e->getMessage(), 'k4campaigns');
            $this->error(Craft::t('Cannot render template. Check template file and object variables.'), 'template');
        }
    }

    /**
     * Logs an error in cases where it makes more sense than to throw an exception
     *
     * @param mixed $message
     * @param array $variables
     */
    public function error($message, $key = '', array $variables = array())
    {
        if (is_string($message))
        {
            $message = Craft::t($message, $variables);
        }
        else
        {
            $message = print_r($message, true);
        }

        if (!empty($key))
        {
            $this->error[$key] = $message;
        }
        else
        {
            $this->error = $message;
        }

        K4Campaigns::log($message, LogLevel::ERROR);
    }

    /**
     * Add placeholder style tags to avoid CSS brackets conflicting with Craft object syntax shorthand i.e. {title}
     *
     * @param $htmlBody
     * @param $styleTags
     *
     * @return mixed
     */
    public function addPlaceholderStyleTags($htmlBody, &$styleTags)
    {
        // Get the style tag
        preg_match_all("/<style\\b[^>]*>(.*?)<\\/style>/s", $htmlBody, $matches);

        $results = array();

        if (!empty($matches))
        {
            $tags = $matches[0];

            // Temporarily replace with style tags with a random string
            if (!empty($tags))
            {
                $i = 0;
                foreach ($tags as $tag)
                {
                    $key = "<!-- %style$i% -->";

                    $styleTags[$key] = $tag;

                    $htmlBody = str_replace($tag, $key, $htmlBody);

                    $i++;
                }
            }
        }

        return $htmlBody;
    }

    /**
     * Put back the style tag after object is rendered if style tag is found
     *
     * @param $htmlBody
     * @param $styleTags
     *
     * @return mixed
     */
    public function removePlaceholderStyleTags($htmlBody, $styleTags)
    {
        if (!empty($styleTags))
        {
            foreach ($styleTags as $key => $tag)
            {
                $htmlBody = str_replace($key, $tag, $htmlBody);
            }
        }

        return $htmlBody;
    }

    /**
     * @return mixed error
     */
    public function getError($key = '')
    {
        if (!empty($key) && isset($this->error[$key]))
        {
            return $this->error[$key];
        }
        else
        {
            return $this->error;
        }
    }


}
