# Installments

Biblioteca que facilita a manipulação de parcelamento de valores.

## Instalação via composer

`composer require bubbstore/installments`

## Como utilizar

```php
<?php

use bubbstore\Installments;
use bubbstore\Exceptions\InstallmentsException;

try {

	// Definimos um array com as taxas e descontos para cada número de parcela 
	$taxes = [
		['installment' => 1, 'percent_discount' => 0, 'tax' => 0],
	    ['installment' => 2, 'percent_discount' => 0, 'tax' => 0],
	    ['installment' => 3, 'percent_discount' => 0, 'tax' => 0],
	];
	
	$installments = new Installments;
	$result = $installments->setAmount(200)
				->setTaxes($taxes)
				->setMinInstallmentValue(15.00)
				->get();

	exit(var_dump($result));

} catch (InstallmentsException $e) {
	echo $e->getMessage();
}

```

O resultado esperado será:

```json
{  
   "max_installment":3,
   "max_installment_value":66.67,
   "amount":200,
   "text":"3x de R$ 66,67 sem juros",
   "text_with_tax":"3x de R$ 66,67",
   "text_discount_percent":null,
   "text_discount":null,
   "installments":[  
		{  
			"amount":200,
			"amount_formated":"R$ 200,00",
			"base_value":200,
			"tax":0,
			"tax_value":0,
			"discount_percent":0,
			"discount_value":0,
			"discount_value_formated":"R$ 0,00",
			"installment":1,
			"installment_value":200,
			"installment_value_formated":"R$ 200,00",
			"text":"1x de R$ 200,00 sem juros",
			"text_with_tax":"1x de R$ 200,00",
			"text_discount_percent":null,
			"text_discount":null
		},
		{  
			"amount":200,
			"amount_formated":"R$ 200,00",
			"base_value":200,
			"tax":0,
			"tax_value":0,
			"discount_percent":0,
			"discount_value":0,
			"discount_value_formated":"R$ 0,00",
			"installment":2,
			"installment_value":100,
			"installment_value_formated":"R$ 100,00",
			"text":"2x de R$ 100,00 sem juros",
			"text_with_tax":"2x de R$ 100,00",
			"text_discount_percent":null,
			"text_discount":null
		},
		{  
			"amount":200,
			"amount_formated":"R$ 200,00",
			"base_value":200,
			"tax":0,
			"tax_value":0,
			"discount_percent":0,
			"discount_value":0,
			"discount_value_formated":"R$ 0,00",
			"installment":3,
			"installment_value":66.67,
			"installment_value_formated":"R$ 66,67",
			"text":"3x de R$ 66,67 sem juros",
			"text_with_tax":"3x de R$ 66,67",
			"text_discount_percent":null,
			"text_discount":null
		}
	]
}
```

## Change log

Consulte [CHANGELOG](.github/CHANGELOG.md) para obter mais informações sobre o que mudou recentemente.

## Contribuindo

Consulte [CONTRIBUTING](.github/CONTRIBUTING.md) para obter mais detalhes.

## Segurança

Se você descobrir quaisquer problemas relacionados à segurança, envie um e-mail para contato@bubbstore.com.br em vez de usar as issues.