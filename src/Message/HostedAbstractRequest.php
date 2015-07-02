<?php

namespace Omnipay\NetBanx\Message;

use Guzzle\Common\Event;
use Omnipay\Common\Message\AbstractRequest;

abstract class HostedAbstractRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://api.netbanx.com/hosted/v1/';
    protected $testEndpoint = 'https://api.test.netbanx.com/hosted/v1/';

    /**
     * Setter for Key ID
     *
     * @param string $value
     * @return $this
     */
    public function setKeyId($value)
    {
        return $this->setParameter('keyId', $value);
    }

    /**
     * Getter for Key ID
     *
     * @return string
     */
    public function getKeyId()
    {
        return $this->getParameter('keyId');
    }

    /**
     * Setter for Key Password
     *
     * @param string $value
     * @return $this
     */
    public function setKeyPassword($value)
    {
        return $this->setParameter('keyPassword', $value);
    }

    /**
     * Getter for Key Password
     *
     * @return string
     */
    public function getKeyPassword()
    {
        return $this->getParameter('keyPassword');
    }

    /**
     * Get Stored Data Mode
     *
     * @return string
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    protected function sendRequest($method, $endpoint, $data = null)
    {
        $this->httpClient->getEventDispatcher()->addListener('request.error', function (Event $event) {
            /**
             * @var \Guzzle\Http\Message\Response $response
             */
            $response = $event['response'];

            if ($response->isClientError()) {
                $event->stopPropagation();
            }
        });

        $httpRequest = $this->httpClient->createRequest(
            $method,
            $this->getEndpoint() . $endpoint,
            array(
                'Content-Type' => 'application/json',
            ),
            json_encode($data),
            array(
                'auth' => array($this->getKeyId(), $this->getKeyPassword(), 'Basic'),
                //'proxy'   => 'http://localhost:8888'
            )
        );

        return $httpRequest->send();
    }
}