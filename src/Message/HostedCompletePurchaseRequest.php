<?php

namespace Omnipay\NetBanx\Message;

use Omnipay\Common\Exception\InvalidRequestException;

class HostedCompletePurchaseRequest extends HostedAbstractRequest
{
    public function getData()
    {
        $data = array();
        $data['id'] = $this->httpRequest->get('id');

        if (empty($data['id'])) {
            throw new InvalidRequestException("The id parameter is required");
        }

        return $data;
    }

    public function sendData($data)
    {
        $httpResponse = $this->sendRequest('GET', 'orders/' . $data['id']);

        return $this->response = new HostedCompletePurchaseResponse($this, $httpResponse->json());
    }
}