<?php

namespace bubbstore\Installments;

use PHPUnit_Framework_TestCase;
use bubbstore\Installments\Installments;
use bubbstore\Installments\Exceptions\InstallmentsException;

class InstallmentsTest extends PHPUnit_Framework_TestCase
{

	public function test_installments_taxes_array_exception()
	{

		$this->expectException(InstallmentsException::class);

		$installments = new Installments;
		$result = $installments->setAmount(100)->setTaxes('a')->get();

	}

	public function test_installments_keys()
	{
		$installments = new Installments;
		$result = $installments->setAmount(100)->setTaxes($this->getFakeTaxes())->get();

		$this->assertArrayHasKey('max_installment', $result);
		$this->assertArrayHasKey('max_installment_value', $result);
		$this->assertArrayHasKey('amount', $result);
		$this->assertArrayHasKey('text', $result);
		$this->assertArrayHasKey('text_with_tax', $result);
		$this->assertArrayHasKey('text_discount_percent', $result);
		$this->assertArrayHasKey('text_discount', $result);
		$this->assertArrayHasKey('installments', $result);
	}

	public function test_installments_result()
	{

		$installments = new Installments;
		$result = $installments->setAmount(100)->setTaxes($this->getFakeTaxes())->get();

		$this->assertEquals(100, $result['amount']);
		$this->assertEquals(12, $result['max_installment']);
		$this->assertNull($result['text_discount']);
		$this->assertNull($result['text_discount_percent']);
		
	}

	private function getFakeTaxes($percentDiscount = 0, $tax = 0)
	{
		return [
			['installment' => 1, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 2, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 3, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 4, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 5, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 6, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 7, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 8, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 9, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 10, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 11, 'percent_discount' => $percentDiscount, 'tax' => $tax],
			['installment' => 12, 'percent_discount' => $percentDiscount, 'tax' => $tax],
		];
	}

}