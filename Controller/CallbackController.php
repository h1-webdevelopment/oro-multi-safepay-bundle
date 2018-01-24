<?php
/*
 * (c) H1 Webdevelopment <contact@h1.nl>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace H1\OroMultiSafepayBundle\Controller;

use Guzzle\Http\Client;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class CallbackController extends Controller
{
    /**
     * @Route(
     *     "/notify/{accessIdentifier}/{accessToken}",
     *     name="h1_oro_multi_savepay_callback_notify",
     *     requirements={"accessIdentifier"="[a-zA-Z0-9\-]+", "accessToken"="[a-zA-Z0-9\-]+"}
     * )
     * @ParamConverter(
     *     "paymentTransaction",
     *     options={"accessIdentifier" = "accessIdentifier", "accessToken" = "accessToken"}
     * )
     * @Method("GET")
     * @param Request $request
     * @param PaymentTransaction $paymentTransaction
     * @return Response
     */
    public function notifyAction(PaymentTransaction $paymentTransaction, Request $request): Response
    {
        /**
         * #todo, this controller should be obselete
         *
         * When oro fixes the callback url remove this controller.
         * And change the callback url in the multiSavepay request.
         */

        $url = $this->get('router')->generate('oro_payment_callback_notify', [
            'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
            'accessToken' => $paymentTransaction->getAccessToken(),
        ], Router::ABSOLUTE_URL);

        $client = new Client();
        $request = $client->post($url);
        $response = $request->send();

        return new Response($response->getBody(true), $response->getStatusCode());
    }
}
