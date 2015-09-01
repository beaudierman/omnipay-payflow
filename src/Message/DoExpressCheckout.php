<?php

namespace Omnipay\Payflow\Message;

class DoExpressCheckout extends SetExpressCheckout {

	protected $action = 'D';

	public function getData()
	{
		$data = $this->getBaseData();
		$data['TENDER'] = 'P';
		$data['AMT'] = $this->getAmount();
		$data['TOKEN'] = $this->getToken() ? $this->getToken() : $this->httpRequest->query->get('token');
		$data['PAYERID'] = $this->getPayerID() ? $this->getPayerID() : $this->httpRequest->query->get('PayerID');

		return $data;
	}
}
