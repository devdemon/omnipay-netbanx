<?php

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class HostedPurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * When you do a `purchase` the request is never succesful because
     * you need to redirect off-site to complete the purchase.
     *
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return (isset($this->data['id']));
    }

    public function getMessage()
    {
        if (isset($this->data['error']['message'])) {
            return $this->data['error']['message'] . ' (Code: ' . $this->data['error']['code'] . ')';
        }
    }

    public function getRedirectUrl()
    {
        foreach ($this->data['link'] as $link) {
            if ($link['rel'] == 'hosted_payment') {
                return $link['uri'];
            }
        }
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return null;
    }
}