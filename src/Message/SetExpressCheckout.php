<?php

namespace Omnipay\Payflow\Message;

use Omnipay\Common\Message\AbstractRequest;

class SetExpressCheckout extends AbstractRequest {

	protected $liveEndpoint = 'https://payflowpro.paypal.com';
	protected $testEndpoint = 'https://pilot-payflowpro.paypal.com';
	protected $action = 'S';
	protected $trxtype = 'A';

	public function getUsername()
	{
		return $this->getParameter('username');
	}

	public function setUsername($value)
	{
		return $this->setParameter('username', $value);
	}

	public function getPassword()
	{
		return $this->getParameter('password');
	}

	public function setPassword($value)
	{
		return $this->setParameter('password', $value);
	}

	public function getVendor()
	{
		return $this->getParameter('vendor');
	}

	public function setVendor($value)
	{
		return $this->setParameter('vendor', $value);
	}

	public function getPartner()
	{
		return $this->getParameter('partner');
	}

	public function setPartner($value)
	{
		return $this->setParameter('partner', $value);
	}

	public function getComment1()
	{
		return $this->getDescription();
	}

	public function setComment1($value)
	{
		return $this->setDescription($value);
	}

	public function getComment2()
	{
		return $this->getParameter('comment2');
	}

	public function setComment2($value)
	{
		return $this->setParameter('comment2', $value);
	}

	public function getReturnUrl()
	{
		return $this->getParameter('returnUrl');
	}

	public function setReturnUrl($value)
	{
		return $this->setParameter('returnUrl', $value);
	}

	public function getCancelUrl()
	{
		return $this->getParameter('cancelUrl');
	}

	public function setCancelUrl($value)
	{
		return $this->setParameter('cancelUrl', $value);
	}

	protected function getBaseData()
	{
		$data = array();
		$data['TRXTYPE'] = $this->trxtype;;
		$data['ACTION'] = $this->action;
		$data['USER'] = $this->getUsername();
		$data['PWD'] = $this->getPassword();
		$data['VENDOR'] = $this->getVendor();
		$data['PARTNER'] = $this->getPartner();

		return $data;
	}

	public function getData()
	{
		$this->validate('amount', 'returnUrl', 'cancelUrl');

		$data = $this->getBaseData();
		$data['TENDER'] = 'P';
		$data['AMT'] = $this->getAmount();
		$data['CANCELURL'] = $this->getCancelUrl();
		$data['RETURNURL'] = $this->getReturnUrl();

		$data = array_merge($data, $this->getItemData());

		return $data;
	}

	protected function getItemData()
	{
		$data = array();
		$items = $this->getItems();
		if($items)
		{
			foreach($items as $n => $item)
			{
				$data["L_NAME$n"] = $item->getName();
				$data["L_DESC$n"] = $item->getDescription();
				$data["L_COST$n"] = $this->formatCurrency($item->getPrice());
				$data["L_QTY$n"] = $item->getQuantity();
				$data["L_AMT$n"] = $item->getQuantity() * $this->formatCurrency($item->getPrice());
			}
		}

		return $data;
	}

	public function sendData($data)
	{
		$httpResponse = $this->httpClient->post(
			$this->getEndpoint(),
			null,
			$this->encodeData($data)
		)->send();

		return $this->response = new Response($this, $httpResponse->getBody());
	}

	/**
	 * Encode absurd name value pair format
	 */
	public function encodeData(array $data)
	{
		$output = array();
		foreach($data as $key => $value)
		{
			$output[] = $key . '[' . strlen($value) . ']=' . $value;
		}

		return implode('&', $output);
	}

	protected function getEndpoint()
	{
		return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
	}
}
