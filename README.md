
Installation Guide via Composer:

```bash
composer require nanoblocktech/payment-gateways
```

```php
$payment = Payment::getInstance(new PayStack("PAYMENT_PRIVATE_KEY"));
```

```php
$result = $payment->listCustomers();
$result = $payment->findCustomer('CUS_jbf8bq0kbkrk3sc');
```
```php
$result = $payment->createCustomer([
    'metadata' => [=
        'id' => "customer id"
    ],
    'first_name' => "",
    'last_name' => "",
    'phone' => "",
    'email' => ""
]);
if($result->status){
//Do something
}
```
