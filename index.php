<?php

require_once __DIR__ ."/vendor/autoload.php";

use \LifenPag\ApiAsaas\V3\Asaas;
use \LifenPag\ApiAsaas\V3\Models\Customer;
use \LifenPag\ApiAsaas\V3\Models\Payment;

Asaas::connect('68d949b1a8996a3f83aaaa7d132881f62fab2101beaba3a13ecd7fe66715bca2', 'sandbox');


$created = Customer::create(['teste' => 'pow']);
var_dump('created',$created);

// $updated = Customer::find('6565')->update(['name' => 'Breno Vieira atualizado']);
// var_dump('updated',$updated);

// $deleted = Customer::delete($created->id);
// var_dump('deleted',$deleted->id,$deleted->name, $deleted->deleted);

// $restored = Customer::restore($created->id);
// var_dump('restored',$restored->id,$restored->name, $restored->deleted);

// $customerOnlyFind = Customer::find(3250118);

// $deletedAfterFind = Customer::find($created->id)->delete();
// var_dump('deletedAfterFind',$deletedAfterFind->id,$deletedAfterFind->name, $deletedAfterFind->deleted);

// $restoreAfterDeleteAfterFind = $deletedAfterFind::restore();
// var_dump('restoreAfterDeleteAfterFind',$restoreAfterDeleteAfterFind->id,$restoreAfterDeleteAfterFind->name, $restoreAfterDeleteAfterFind->deleted);

// $paymentCreated = Payment::create(['value' => 50, 'billingType' => 'BOLETO', 'dueDate' => '2020-10-05', 'customer' => $customerOnlyFind->id]);
// var_dump($paymentCreated->id);

// $paymentupdated = Payment::update(['value' => 55], 'pay_720296800945');
// var_dump($paymentupdated);

// $paymentDeleted = Payment::delete('pay_720296800945');
// var_dump($paymentDeleted);

// $paymentRestored = Payment::restore('pay_720296800945');
// var_dump($paymentRestored);

// $findPayment = Payment::find('pay_720296800945');
// var_dump($findPayment);

// $allPayments = Payment::all();
// var_dump($allPayments);

// $filteredPayments = Payment::where(['customer' => '3250118']);
// var_dump($filteredPayments);

die;
