<?php

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\Message\AbstractResponse;

class HostedCompletePurchaseResponse extends AbstractResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        if (isset($this->data['transaction']['status']) && $this->data['transaction']['status'] == 'success') {
            return true;
        }
    }

    /**
     * Get transaction reference
     *
     * @return string
     */
    public function getTransactionReference()
    {
        return isset($this->data['id']) ? $this->data['id'] : '';
    }

    /**
     * Get message from responce
     *
     * @return string
     */
    public function getCode()
    {
        if (isset($this->data['transaction']['errorCode'])) {
            return $this->data['transaction']['errorCode'];
        }
    }

    /**
     * Get message from responce
     *
     * @return string
     */
    public function getMessage()
    {
        if (isset($this->data['transaction']['errorMessage'])) {
            return $this->data['transaction']['errorMessage'];
        }
    }
}