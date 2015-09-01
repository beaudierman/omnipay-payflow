<?php

namespace Omnipay\Payflow\Message;

class GetExpressCheckout extends SetExpressCheckout {

	protected $action = 'G';

	public function getData()
	{
		$data = $this->getBaseData();
		$data['TENDER'] = 'P';
		$data['TOKEN'] = $this->getToken() ? $this->getToken() : $this->httpRequest->query->get('token');

		return $data;
	}
}
