Easy to use PHP payment gateway library designed for PHP 8 and above.
The primary goal of this library is to facilitate seamless transitions between different payment merchants,
allowing developers to switch gateways without the need for relearning of new implementation and documentation.
Initialization involves specifying the preferred merchant, ensuring a smooth and consistent experience.
Currently, PayStack is the starting point for this library.


Installation Guide via Composer:

```bash
composer require nanoblocktech/payment-gateways
```

## Available Merchant Payment

1. PayStack - Here you can find about PayStack API documentation to get started https://paystack.com/docs/api/

#### Initialize payment gateway
To initialize the payment gateway you will need to pass the merchant interface you want to use 

First, initialize your payment merchant gateway 

```php 
$gateway = new PayStack("PAYMENT_PRIVATE_KEY");
```

Calling `Merchant::getInstance` will return instances of `Bank`, `Customers`, and `Processor as Payment`
which can then be used to access individual class instances `$merchant->bank->foo()`

```php
$merchant = Merchant::getInstance($gateway);
```

Initialize with payment instance 

```php
$payment = Merchant::getPaymentInstance($gateway);
```

Initialize with customer instance 
```php
$customer = Merchant::getCustomerInstance($gateway);
```

Initialize with a bank instance 
```php
$bank = Merchant::getBankInstance($gateway);
```


### CUSTOMERS

If you are using `getInstance` then you must access the customer instance by using `$merchant->customer->` else if you are working with `getCustomerInstance` you can directly call the customer class method.

Create customer account 

```php
$result = $customer->create([
    'metadata' => [=
        'key' => "Value"
    ],
    'first_name' => "Peter",
    'last_name' => "Foo",
    'phone' => "000000",
    'email' => "peter@example.com",
]);
if($result->isSuccess()){
//Do something
}
```

Update customer account
```php
$result = $customer->update($customerCode, [
    'first_name' => "Peter",
    'last_name' => "Bar",
    'phone' => "1111111",
    'email' => "peter@example.com",
]);
if($result->isSuccess()){
//Do something
}
```


Flag customer accounts based on risk level
Allowed flags `PayStack::FLAG_DEFAULT`, `PayStack::FLAG_ALLOW`, or `PayStack::FLAG_DENY`

```php
$result = $customer->flag($customerCode, PayStack::FLAG_DENY);
if($result->isSuccess()){
//Do something
}
```

Verify customer account

```php
$result = $customer->verify($customerCode, ['array']);
if($result->isSuccess()){
//Do something
}
```

List customers 

```php
$result = $customer->list($limit);
```

Find customer by email address or customer code 

```php
$result = $customer->find('CUS_jbf8bq0kbkrk3sc');
```

### CUSTOMER ACCOUNT 

Initialize customer account with an email address or customer code

```php
$account = $customer->withAccount('CUS_jbf8bq0kbkrk3sc');
```

Create a new customer account and assign it to the current session

```php
$result = $account->create(['array']);
```
Update the current session customer account 

```php
$result = $account->update(['array']);
```

Verify current session customer account 
```php
$result = $account->verify(['array']);
```

Flag current session customer account 
```php
$result = $account->flag(PayStack::FLAG_DENY);
```

Refresh the current session customer account 
```php
$result = $account->refresh();
```

Get property by key name using get method or directly access the object property by name 
```php
$email = $account->get('email');
// OR
$email = $account->email;
```

### PAYMENTS 

Initialize payment

```php
$result = $payment->initialize(['array']);
if($result->isSuccess()){
//Do something
}
```

Charge customer card

```php
$result = $payment->charge(['array']);
if($result->isSuccess()){
//Do something
}
```

Charge customer card authorization code

```php
$result = $payment->chargeAuthorization(['array']);
if($result->isSuccess()){
//Do something
}
```

verify payment with transaction reference number 

```php
$result = $payment->verify($reference);
if($result->isSuccess()){
//Do something
}
```

### CHARGE

You can use charge class to build your transaction fee

Initialize  charge class instance 
```php
$charge = new Charge();
```
Set total transaction amount (Required)
```php
$charge->setAmount($amount);
```
Set transaction fee or merchant fee (Optional)
```php
$charge->setFee($amount);
```
Or Set transaction fee by rate `1.7%` (Optional)
```php
$charge->setFeeRate($rate);
```

Set shipping fee (Optional)
```php
$charge->setShipping($amount);
```

Build your charges 
```php
$builder = $charge->build();
```

**Get charges after building**

Get total amount 
```php
$totalFloat = $builder->getTotal();
```

Get total as integer
```php
$totalInt = $builder->toInt();
```

Convert total to cent integer
```php
$centInt = $builder->toCent();
```

Convert total to cent float
```php
$centFloat = $builder->toCentAsFloat();
```

Get Fee
```php
$fee = $builder->getFee();
```


### BANK

Create dedicated virtual bank account

```php
$result = $bank->createVirtualAccount($customerCode, $bankId, [fields]);
if($result->isSuccess()){
//Do something
}
```

Assign dedicated virtual bank account to customer

```php
$result = $bank->assignVirtualAccount([fields]);
if($result->isSuccess()){
//Do something
}
```
List dedicated virtual accounts

```php
$result = $bank->listVirtualAccount($active, $currency, array $fields = []);
if($result->isSuccess()){
//Do something
}
```

Find dedicated virtual accounts

```php
$result = $bank->findVirtualAccount($account_id);
if($result->isSuccess()){
//Do something
}
```

Re-query dedicated virtual accounts

```php
$result = $bank->queryVirtualAccount(String $account, string $slug, string $date = '');
if($result->isSuccess()){
//Do something
}
```

List dedicated virtual account bank providers

```php
$result = $bank->virtualAccountProviders();
if($result->isSuccess()){
//Do something
}
```

Resolve customer account number and bank verification number

```php
$result = $bank->resolveAccount(string|int $account, string|int $bic);
if($result->isSuccess()){
//Do something
}
```

Resolve customer bank verification number (BVN)

```php
$result = $bank->resolveBvn(string|int $bvn);
if($result->isSuccess()){
//Do something
}
```

List bank in a specific country  

```php
$result = $bank->list(string $country = 'nigeria', int $limit = 50, bool $cursor = false);
if($result->isSuccess()){
//Do something
}
```

Get bank by bank code or name in a specific country 
```php
$result = $bank->get(string|int $identification, string $country = 'nigeria');
if($result->isSuccess()){
//Do something
}
```


### RESPONSE


Get the original response object.
return the original response object or null if not available.

```php 
$result->getResponse(): mixed
```

Get the response headers.
return array of request response headers.

```php 
$result->getHeaders(): array
```

Get a specific header by key.
return mixed value of header or null if not found.

```php 
$result->getHeader(string $key): mixed;
```

Get the HTTP status code.
return int, the request response HTTP status code or 0 if not failed.
```php 
$result->getStatus(): int
```

Check if the response is successful from gateway.
```php
$result->isSuccess(): bool
```

Get a response object body returned from gateway
```php 
$result->getBody(): object
```

Get the response data portion of the gateway response.
```php 
$result->getData(): ?object
```

Get the message from gateway response body.
```php 
$result->getMessage(): string
```

Get the error object from gateway response.
```php 
$result->getErrors(): ?object
```

Get the error message from gateway response.
```php 
$result->getError(): string
```

Get the error code from the response.

```php 
$result->getErrorCode(): int
```
