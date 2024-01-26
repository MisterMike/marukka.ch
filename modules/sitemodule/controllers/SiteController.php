<?php
/**
 * Site Module module for Craft CMS 4.x
 *
 * A simple Module to enhance your site
 *
 * @copyright Copyright (c) 2019-23 Mischa Sprecher
 */

namespace modules\sitemodule\controllers;

use Craft;
use craft\web\Controller;

use yii\web\Response;

/**
 * @author    nystudio107
 * @package   SiteModule
 * @since     1.0.0
 */
class SiteController extends Controller
{
    // Constants
    // =========================================================================

    const GQL_TOKEN_NAME = 'Site GQL Token';

    // Protected Properties
    // =========================================================================

    protected array|int|bool $allowAnonymous = [
        'get-csrf',
        'get-gql-token',
        'get-field-options',
        'resend-verification-email'
    ];

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */

    public function actionDoSomething()
    {
        $result = 'Welcome to my SiteController actionDoSomething() method';

        return $result;
    }

    public function beforeAction($action): bool
    {

        // Disable CSRF validation for get-csrf POST requests
        if ($action->id === 'get-csrf') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     * @return json Response
     * usage: /actions/k4-site-module/k4-site/get-csrf 
     */
    public function actionGetCsrf(): Response
    {

        if (!Craft::$app->getRequest()->getIsAjax()) {
            return $this->asJson([
                'error' => 'not an ajax request',
            ]);
        }

        return $this->asJson([
            'name' => Craft::$app->getConfig()->getGeneral()->csrfTokenName,
            'value' => Craft::$app->getRequest()->getCsrfToken(),
            'referrer' => Craft::$app->getRequest()->getReferrer(),
        ]);
    }

    /**
     * @return Response
     */
    public function actionGetGqlToken(): Response
    {
        $result = null;
        $tokens = Craft::$app->getGql()->getTokens();
        foreach ($tokens as $token) {
            if ($token->name === self::GQL_TOKEN_NAME) {
                $result = $token->accessToken;
            }
        }

        return $this->asJson([
            'token' => $result,
        ]);
    }

    /**
     * Return all of the field options from the passed in array of $fieldHandles
     *
     * @return Response
     */
    public function actionGetFieldOptions(): Response
    {
        $result = [];
        $request = Craft::$app->getRequest();
        $fieldHandles = $request->getBodyParam('fieldHandles');
        foreach ($fieldHandles as $fieldHandle) {
            $field = Craft::$app->getFields()->getFieldByHandle($fieldHandle);
            if ($field) {
                $result[$fieldHandle] = $field->options;
            }
        }

        return $this->asJson($result);
    }

    public function actionResendVerificationEmail(): Response
    {
        $referrer = Craft::$app->sites->currentSite->baseUrl;

        if (!empty(Craft::$app->request->referrer)) $referrer = Craft::$app->sites->currentSite->baseUrl;

        // shouldnt be logged in
        if (Craft::$app->getUser()) {
            Craft::$app->session->setError(Craft::t('site','Error.'));
            return $this->redirect($referrer);
        }

        // email address should be delivered
        $email = Craft::$app->request->get('email');
        if (empty($email)) {
            Craft::$app->session->setError(Craft::t('site','Error.'));
            return $this->redirect($referrer);
        }

        // a user with that email should exist
        $user = Craft::$app->users->getUserByUsernameOrEmail($email);
        if (empty($user)) {
            Craft::$app->session->setError(Craft::t('site','Error.'));
            return $this->redirect($referrer);
        }

        // send the activation email again
        Craft::$app->users->sendActivationEmail($user);
        Craft::$app->session->setNotice(Craft::t('site','Dir wurde eine E-Mail mit einem Link zur Aktivierung deines Kundenkontos zugesendet.'));

        return $this->redirect($referrer);

    }
}
