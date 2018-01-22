<?php

namespace bubbstore\Installments;

use bubbstore\Installments\InstallmentsException;

class Installments
{

    /**
     * @var array
     */
    private $taxes;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $minInstallmentValue;

    /**
     * @var float
     */
    private $maxInstallmentsWithoutTax;

    /**
     * @var int
     */
    private $maxInstallments;

    /**
     * @var string
     */
    private $symbol = 'R$';

    /**
     * @var bool
     */
    private $showList = true;

    public function setMaxInstallments($value)
    {
        $this->maxInstallments = $value;
        return $this;
    }

    public function getMaxInstallments()
    {
        return $this->maxInstallments;
    }

    public function setShowList($value)
    {
        $this->showList = $value;
        return $this;
    }

    public function getShowList()
    {
        return $this->showList;
    }

    public function setTaxes($value)
    {
        $this->taxes = $value;
        return $this;
    }

    public function getTaxes()
    {
        return $this->taxes;
    }

    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setMinInstallmentValue($value)
    {
        $this->minInstallmentValue = $value;
        return $this;
    }

    public function getMinInstallmentValue()
    {
        return $this->minInstallmentValue;
    }

    public function setMaxInstallmentsWithoutTax($value)
    {
        $this->maxInstallmentsWithoutTax = $value;
        return $this;
    }

    public function getMaxInstallmentsWithoutTax()
    {
        return $this->maxInstallmentsWithoutTax;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setSymbol($value)
    {
        $this->symbol = $value;
        return $this;
    }

    /**
     * get
     * Return the installments array
     * @return array
     * @author Lucas Colette
     */
    public function get()
    {
        $installments = [];

        if (count($this->getTaxes()) == 0) {
            return null;
        }
        
        for ($i = 1; $i <= count($this->getTaxes()); $i++) {
            $key = $i-1;
            $installment = $this->getTaxes()[$key]['installment'];
            $discount = $this->getTaxes()[$key]['percent_discount'];

            $installmentByTax = $installment <= $this->getMaxInstallmentsWithoutTax() ? 0 : $installment - $this->getMaxInstallmentsWithoutTax();

            $total = $this->getAmount() * pow((1 + ($this->getTaxes()[$key]['tax'] / 100)), $installmentByTax);

            // Apply discount
            $total = $total - ($total * $discount) / 100;
            $totalFormated = sprintf('%s %s', $this->getSymbol(), number_format($total, 2, ',', '.'));

            $taxValue = $total - $this->getAmount();

            $installmentValue = $total / $installment;

            if ($installmentValue >= $this->getMinInstallmentValue() || $installment == 1) {
                $installmentValueFormated = sprintf('%s %s', $this->getSymbol(), number_format($installmentValue, 2, ',', '.'));
                $taxText = $this->getTaxes()[$key]['tax'] == 0 ? 'sem juros' : 'com juros';
                $discountValue = $this->getAmount() - $total;

                $discountValueFormated = sprintf('%s %s', $this->getSymbol(), number_format($discountValue, 2, ',', '.'));
                $amountFormated = sprintf('%s %s', $this->getSymbol(), number_format($total, 2, ',', '.'));

                $data = [
                    'amount' => (float) number_format($total, 2, '.', ''),
                    'amount_formated' => $amountFormated,
                    'base_value' => (float) number_format($this->getAmount(), 2, '.', ''),
                    'tax' => $this->getTaxes()[$key]['tax'],
                    'tax_value' => $taxValue,
                    'discount_percent' => (float) number_format($discount, 2, '.', ''),
                    'discount_value' => (float) number_format($discountValue, 2, '.', ''),
                    'discount_value_formated' => $discountValueFormated,
                    'installment' => $installment,
                    'installment_value' => (float) number_format($installmentValue, 2, '.', ''),
                    'installment_value_formated' => $installmentValueFormated,
                    'text' => sprintf('%sx de %s %s', $installment, $installmentValueFormated, $taxText),
                    'text_with_tax' => sprintf('%sx de %s', $installment, $installmentValueFormated),
                    'text_discount_percent' => $discount > 0 ? ceil($discount).'% de desconto à vista no cartão' : null,
                    'text_discount' => $discount > 0 ? $totalFormated.' à vista no cartão' : null
                ];

                array_push($installments, $data);
            }
        }

        // Get the maximum values
        $reverse = array_reverse($installments, false);

        $this->setMaxInstallments($reverse[0]['installment']);

        $arr = [
            'max_installment' => $reverse[0]['installment'],
            'max_installment_value' => $reverse[0]['installment_value'],
            'amount' => $reverse[0]['amount'],
            'text' => $reverse[0]['text'],
            'text_with_tax' => $reverse[0]['text_with_tax'],
            'text_discount_percent' => $installments[0]['text_discount_percent'],
            'text_discount' => $installments[0]['text_discount'],
        ];

        if ($this->getShowList()) {
            $arr['installments'] = $installments;
        }

        return $arr;
    }

    /**
     * getValuesByInstallment
     * Return the indexes by installment number
     * @param  int $installment
     * @return array
     */
    public function getValuesByInstallment($installment)
    {
        if (isset($this->get()['installments'][$installment-1])) {
            return $this->get()['installments'][$installment-1];
        }

        throw new InstallmentsException('Parcelamento inválido, o máximo são '. $this->getMaxInstallments().' parcelas');
    }
}
