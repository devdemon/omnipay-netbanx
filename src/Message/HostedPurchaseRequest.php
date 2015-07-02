<?php

namespace Omnipay\NetBanx\Message;

class HostedPurchaseRequest extends HostedAbstractRequest
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        $this->validate('amount', 'returnUrl');

        $data = array();
        $data['merchantRefNum'] = $this->getTransactionReference();
        $data['currencyCode'] = strtoupper($this->getCurrency());
        $data['totalAmount'] = $this->getAmountInteger();

        if ($this->getPaymentMethod()) {
            $data['paymentMethod'] = $this->getPaymentMethod();
        }

        // Callbacks
        $data['callback'] = array();
        $data['callback'][] = array(
            'format' => 'get',
            'rel' => 'on_success',
            'uri' => $this->getNotifyUrl(),
            'returnKeys' => array('id', 'transaction.amount', 'transaction.status', 'transaction.merchantRefNum')
        );

        $returnKeys = array('id', 'transaction.amount', 'transaction.status', 'transaction.errorMessage', 'transaction.errorCode');

        // Redirects
        $data['redirect'] = array();
        $data['redirect'][] = array('rel' => 'on_success', 'uri' => $this->getReturnUrl(), 'returnKeys' => $returnKeys);
        $data['redirect'][] = array('rel' => 'on_error', 'uri' => $this->getReturnUrl(), 'returnKeys' => $returnKeys);
        $data['redirect'][] = array('rel' => 'on_decline', 'uri' => $this->getReturnUrl(), 'returnKeys' => $returnKeys);

        // Shipping info
        if ($this->getCard()) {
            $card = $this->getCard();
            $data['shippingDetails']['recipientName'] = $card->getShippingName();
            $data['shippingDetails']['street'] = $card->getShippingAddress1();
            $data['shippingDetails']['street2'] = $card->getShippingAddress2();
            $data['shippingDetails']['zip'] = $card->getShippingPostcode();
            $data['shippingDetails']['state'] = $card->getShippingState();
            $data['shippingDetails']['city'] = $card->getShippingCity();
            $data['shippingDetails']['country'] = $card->getShippingCountry();
            $data['shippingDetails']['phone'] = $card->getShippingPhone();

            // Empty values are not allowed
            $data['shippingDetails'] = array_filter($data['shippingDetails']);

            // If no Zip/Country, just remove it
            if (!isset($data['shippingDetails']['zip']) || !isset($data['shippingDetails']['country'])) {
                unset($data['shippingDetails']);
            }
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('POST', 'orders', $data);

        return $this->response = new HostedPurchaseResponse($this, $httpResponse->json());
    }
}