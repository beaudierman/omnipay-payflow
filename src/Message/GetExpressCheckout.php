<?php

namespace Omnipay\Payflow\Message;

class GetExpressCheckout extends SetExpressCheckout {

	protected $action = 'G';

	public function getData()
	{
		$this->validate('TENDER', 'TOKEN');

		$data = $this->getBaseData();
		$data['TENDER'] = 'P';
		$data['TOKEN'] = $this->getTransactionId();

		return $data;
	}
}
