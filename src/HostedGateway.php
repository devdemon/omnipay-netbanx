<?php

namespace Omnipay\NetBanx;

use Omnipay\Common\AbstractGateway;

/**
 * NetBanx Class
 */
class HostedGateway extends AbstractGateway
{
    /**
     * Get name of the gateway
     *
     * @return string
     */
    public function getName()
    {
        return 'NetBanx Hosted Payments';
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters()
    {
        return array(
            'keyId' => '',
            'keyPassword' => '',
            'testMode' => false,
        );
    }

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
     * Create a new charge (combined authorize + capture).
     *
     * @param array An array of options
     * @return \Omnipay\ResponseInterface
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\HostedPurchaseRequest', $parameters);
    }

    /**
     * Complete Purchase
     *
     * @param  array                      $parameters An array of options
     * @return \Omnipay\ResponseInterface
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\NetBanx\Message\HostedCompletePurchaseRequest', $parameters);
    }
}