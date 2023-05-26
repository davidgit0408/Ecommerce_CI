<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2022-10-20 05:47:37 --> Stripe Webhook --> (object) array(
   'id' => 'evt_1LumS4AlIMTFVRVSKf6wwYy4',
   'object' => 'event',
   'api_version' => '2020-08-27',
   'created' => 1666225056,
   'data' => 
  (object) array(
     'object' => 
    (object) array(
       'object' => 'balance',
       'available' => 
      array (
        0 => 
        (object) array(
           'amount' => 1341521,
           'currency' => 'usd',
           'source_types' => 
          (object) array(
             'card' => 1341521,
          ),
        ),
      ),
       'livemode' => false,
       'pending' => 
      array (
        0 => 
        (object) array(
           'amount' => 0,
           'currency' => 'usd',
           'source_types' => 
          (object) array(
             'card' => 0,
          ),
        ),
      ),
    ),
  ),
   'livemode' => false,
   'pending_webhooks' => 3,
   'request' => 
  (object) array(
     'id' => NULL,
     'idempotency_key' => NULL,
  ),
   'type' => 'balance.available',
)
ERROR - 2022-10-20 05:47:37 --> Stripe Webhook | Transaction could not be detected --> (object) array(
   'id' => 'evt_1LumS4AlIMTFVRVSKf6wwYy4',
   'object' => 'event',
   'api_version' => '2020-08-27',
   'created' => 1666225056,
   'data' => 
  (object) array(
     'object' => 
    (object) array(
       'object' => 'balance',
       'available' => 
      array (
        0 => 
        (object) array(
           'amount' => 1341521,
           'currency' => 'usd',
           'source_types' => 
          (object) array(
             'card' => 1341521,
          ),
        ),
      ),
       'livemode' => false,
       'pending' => 
      array (
        0 => 
        (object) array(
           'amount' => 0,
           'currency' => 'usd',
           'source_types' => 
          (object) array(
             'card' => 0,
          ),
        ),
      ),
    ),
  ),
   'livemode' => false,
   'pending_webhooks' => 3,
   'request' => 
  (object) array(
     'id' => NULL,
     'idempotency_key' => NULL,
  ),
   'type' => 'balance.available',
)
ERROR - 2022-10-20 09:15:01 --> {"EventType":1,"Event":"TransactionsStatusChanged","DateTime":"20102022064501","CountryIsoCode":"KWT","Data":{"InvoiceId":1764414,"InvoiceReference":"2022000048","CreatedDate":"20102022064228","CustomerReference":null,"CustomerName":"Anonymous","CustomerMobile":"+965","CustomerEmail":null,"TransactionStatus":"SUCCESS","PaymentMethod":"KNET","UserDefinedField":"wallet-refill-user-20-1666237341570-142","ReferenceId":"229310000034","TrackId":"20-10-2022_1290882","PaymentId":"100202229318677381","AuthorizationId":"B64507","InvoiceValueInBaseCurrency":"5000","BaseCurrency":"KWD","InvoiceValueInDisplayCurreny":"5000","DisplayCurrency":"KWD","InvoiceValueInPayCurrency":"5000","PayCurrency":"KWD"}}
ERROR - 2022-10-20 09:15:01 --> SIGNATURE MATCHED
ERROR - 2022-10-20 09:15:01 --> 100202229318677381
ERROR - 2022-10-20 09:15:01 --> My fatoorah user ID -  transaction data--> array (
  'EventType' => 1,
  'Event' => 'TransactionsStatusChanged',
  'DateTime' => '20102022064501',
  'CountryIsoCode' => 'KWT',
  'Data' => 
  array (
    'InvoiceId' => 1764414,
    'InvoiceReference' => '2022000048',
    'CreatedDate' => '20102022064228',
    'CustomerReference' => NULL,
    'CustomerName' => 'Anonymous',
    'CustomerMobile' => '+965',
    'CustomerEmail' => NULL,
    'TransactionStatus' => 'SUCCESS',
    'PaymentMethod' => 'KNET',
    'UserDefinedField' => 'wallet-refill-user-20-1666237341570-142',
    'ReferenceId' => '229310000034',
    'TrackId' => '20-10-2022_1290882',
    'PaymentId' => '100202229318677381',
    'AuthorizationId' => 'B64507',
    'InvoiceValueInBaseCurrency' => '5000',
    'BaseCurrency' => 'KWD',
    'InvoiceValueInDisplayCurreny' => '5000',
    'DisplayCurrency' => 'KWD',
    'InvoiceValueInPayCurrency' => '5000',
    'PayCurrency' => 'KWD',
  ),
  'transaction_type' => 'wallet',
  'user_id' => '20',
  'order_id' => 'wallet-refill-user-20-1666237341570-142',
  'type' => 'credit',
  'txn_id' => '100202229318677381',
  'amount' => '5000',
  'status' => 'success',
  'message' => 'Wallet refill successful',
)
ERROR - 2022-10-20 09:15:01 --> My Fatoorah user ID - Add transaction --> '100202229318677381'
ERROR - 2022-10-20 09:15:01 --> My fatoorah user ID - Wallet recharged successfully --> 'wallet-refill-user-20-1666237341570-142'
ERROR - 2022-10-20 09:16:20 --> {"EventType":1,"Event":"TransactionsStatusChanged","DateTime":"20102022064620","CountryIsoCode":"KWT","Data":{"InvoiceId":1764418,"InvoiceReference":"2022000049","CreatedDate":"20102022064530","CustomerReference":null,"CustomerName":"Anonymous","CustomerMobile":"+965","CustomerEmail":null,"TransactionStatus":"SUCCESS","PaymentMethod":"KNET","UserDefinedField":"wallet-refill-user-20-1666237525345-299","ReferenceId":"229310000035","TrackId":"20-10-2022_1290884","PaymentId":"100202229318766826","AuthorizationId":"B64666","InvoiceValueInBaseCurrency":"5000","BaseCurrency":"KWD","InvoiceValueInDisplayCurreny":"5000","DisplayCurrency":"KWD","InvoiceValueInPayCurrency":"5000","PayCurrency":"KWD"}}
ERROR - 2022-10-20 09:16:20 --> SIGNATURE MATCHED
ERROR - 2022-10-20 09:16:20 --> 100202229318766826
ERROR - 2022-10-20 09:16:20 --> My fatoorah user ID -  transaction data--> array (
  'EventType' => 1,
  'Event' => 'TransactionsStatusChanged',
  'DateTime' => '20102022064620',
  'CountryIsoCode' => 'KWT',
  'Data' => 
  array (
    'InvoiceId' => 1764418,
    'InvoiceReference' => '2022000049',
    'CreatedDate' => '20102022064530',
    'CustomerReference' => NULL,
    'CustomerName' => 'Anonymous',
    'CustomerMobile' => '+965',
    'CustomerEmail' => NULL,
    'TransactionStatus' => 'SUCCESS',
    'PaymentMethod' => 'KNET',
    'UserDefinedField' => 'wallet-refill-user-20-1666237525345-299',
    'ReferenceId' => '229310000035',
    'TrackId' => '20-10-2022_1290884',
    'PaymentId' => '100202229318766826',
    'AuthorizationId' => 'B64666',
    'InvoiceValueInBaseCurrency' => '5000',
    'BaseCurrency' => 'KWD',
    'InvoiceValueInDisplayCurreny' => '5000',
    'DisplayCurrency' => 'KWD',
    'InvoiceValueInPayCurrency' => '5000',
    'PayCurrency' => 'KWD',
  ),
  'transaction_type' => 'wallet',
  'user_id' => '20',
  'order_id' => 'wallet-refill-user-20-1666237525345-299',
  'type' => 'credit',
  'txn_id' => '100202229318766826',
  'amount' => '5000',
  'status' => 'success',
  'message' => 'Wallet refill successful',
)
ERROR - 2022-10-20 09:16:20 --> My Fatoorah user ID - Add transaction --> '100202229318766826'
ERROR - 2022-10-20 09:16:20 --> My fatoorah user ID - Wallet recharged successfully --> 'wallet-refill-user-20-1666237525345-299'
ERROR - 2022-10-20 09:17:40 --> {"EventType":1,"Event":"TransactionsStatusChanged","DateTime":"20102022064739","CountryIsoCode":"KWT","Data":{"InvoiceId":1764419,"InvoiceReference":"2022000050","CreatedDate":"20102022064656","CustomerReference":null,"CustomerName":"Anonymous","CustomerMobile":"+965","CustomerEmail":null,"TransactionStatus":"SUCCESS","PaymentMethod":"KNET","UserDefinedField":"wallet-refill-user-20-1666237612156-250","ReferenceId":"229310000036","TrackId":"20-10-2022_1290885","PaymentId":"100202229318808907","AuthorizationId":"B64786","InvoiceValueInBaseCurrency":"10000","BaseCurrency":"KWD","InvoiceValueInDisplayCurreny":"10000","DisplayCurrency":"KWD","InvoiceValueInPayCurrency":"10000","PayCurrency":"KWD"}}
ERROR - 2022-10-20 09:17:40 --> SIGNATURE MATCHED
ERROR - 2022-10-20 09:17:40 --> 100202229318808907
ERROR - 2022-10-20 09:17:40 --> My fatoorah user ID -  transaction data--> array (
  'EventType' => 1,
  'Event' => 'TransactionsStatusChanged',
  'DateTime' => '20102022064739',
  'CountryIsoCode' => 'KWT',
  'Data' => 
  array (
    'InvoiceId' => 1764419,
    'InvoiceReference' => '2022000050',
    'CreatedDate' => '20102022064656',
    'CustomerReference' => NULL,
    'CustomerName' => 'Anonymous',
    'CustomerMobile' => '+965',
    'CustomerEmail' => NULL,
    'TransactionStatus' => 'SUCCESS',
    'PaymentMethod' => 'KNET',
    'UserDefinedField' => 'wallet-refill-user-20-1666237612156-250',
    'ReferenceId' => '229310000036',
    'TrackId' => '20-10-2022_1290885',
    'PaymentId' => '100202229318808907',
    'AuthorizationId' => 'B64786',
    'InvoiceValueInBaseCurrency' => '10000',
    'BaseCurrency' => 'KWD',
    'InvoiceValueInDisplayCurreny' => '10000',
    'DisplayCurrency' => 'KWD',
    'InvoiceValueInPayCurrency' => '10000',
    'PayCurrency' => 'KWD',
  ),
  'transaction_type' => 'wallet',
  'user_id' => '20',
  'order_id' => 'wallet-refill-user-20-1666237612156-250',
  'type' => 'credit',
  'txn_id' => '100202229318808907',
  'amount' => '10000',
  'status' => 'success',
  'message' => 'Wallet refill successful',
)
ERROR - 2022-10-20 09:17:40 --> My Fatoorah user ID - Add transaction --> '100202229318808907'
ERROR - 2022-10-20 09:17:40 --> My fatoorah user ID - Wallet recharged successfully --> 'wallet-refill-user-20-1666237612156-250'
ERROR - 2022-10-20 09:19:27 --> {"EventType":1,"Event":"TransactionsStatusChanged","DateTime":"20102022064926","CountryIsoCode":"KWT","Data":{"InvoiceId":1764423,"InvoiceReference":"2022000051","CreatedDate":"20102022064839","CustomerReference":null,"CustomerName":"Anonymous","CustomerMobile":"+965","CustomerEmail":null,"TransactionStatus":"SUCCESS","PaymentMethod":"KNET","UserDefinedField":"wallet-refill-user-20-1666237715171-115","ReferenceId":"229310000037","TrackId":"20-10-2022_1290887","PaymentId":"100202229381139297","AuthorizationId":"B64973","InvoiceValueInBaseCurrency":"10000","BaseCurrency":"KWD","InvoiceValueInDisplayCurreny":"10000","DisplayCurrency":"KWD","InvoiceValueInPayCurrency":"10000","PayCurrency":"KWD"}}
ERROR - 2022-10-20 09:19:27 --> SIGNATURE MATCHED
ERROR - 2022-10-20 09:19:27 --> 100202229381139297
ERROR - 2022-10-20 09:19:27 --> My fatoorah user ID -  transaction data--> array (
  'EventType' => 1,
  'Event' => 'TransactionsStatusChanged',
  'DateTime' => '20102022064926',
  'CountryIsoCode' => 'KWT',
  'Data' => 
  array (
    'InvoiceId' => 1764423,
    'InvoiceReference' => '2022000051',
    'CreatedDate' => '20102022064839',
    'CustomerReference' => NULL,
    'CustomerName' => 'Anonymous',
    'CustomerMobile' => '+965',
    'CustomerEmail' => NULL,
    'TransactionStatus' => 'SUCCESS',
    'PaymentMethod' => 'KNET',
    'UserDefinedField' => 'wallet-refill-user-20-1666237715171-115',
    'ReferenceId' => '229310000037',
    'TrackId' => '20-10-2022_1290887',
    'PaymentId' => '100202229381139297',
    'AuthorizationId' => 'B64973',
    'InvoiceValueInBaseCurrency' => '10000',
    'BaseCurrency' => 'KWD',
    'InvoiceValueInDisplayCurreny' => '10000',
    'DisplayCurrency' => 'KWD',
    'InvoiceValueInPayCurrency' => '10000',
    'PayCurrency' => 'KWD',
  ),
  'transaction_type' => 'wallet',
  'user_id' => '20',
  'order_id' => 'wallet-refill-user-20-1666237715171-115',
  'type' => 'credit',
  'txn_id' => '100202229381139297',
  'amount' => '10000',
  'status' => 'success',
  'message' => 'Wallet refill successful',
)
ERROR - 2022-10-20 09:19:27 --> My Fatoorah user ID - Add transaction --> '100202229381139297'
ERROR - 2022-10-20 09:19:27 --> My fatoorah user ID - Wallet recharged successfully --> 'wallet-refill-user-20-1666237715171-115'
ERROR - 2022-10-20 09:37:12 --> Could not find the language line "open_nav"
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 401
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 406
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 438
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Undefined variable: product_row /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 09:37:12 --> Severity: Notice --> Trying to access array offset on value of type null /home/u535396765/domains/wrteam.co.in/public_html/vendoreshop/application/views/front-end/classic/pages/home.php 443
ERROR - 2022-10-20 11:45:30 --> {
    "EventType": 1,
    "Event": "TransactionsStatusChanged",
    "DateTime": "19102022101313",
    "CountryIsoCode": "KWT",
    "Data": {
        "InvoiceId": 1762784,
        "InvoiceReference": "2022000029",
        "CreatedDate": "19102022101231",
        "CustomerReference": null,
        "CustomerName": "Anonymous",
        "CustomerMobile": "+965",
        "CustomerEmail": null,
        "TransactionStatus": "FAILED",
        "PaymentMethod": "VISA/MASTER",
        "UserDefinedField": "690",
        "ReferenceId": "07071762784129012172",
        "TrackId": "19-10-2022_1290121",
        "PaymentId": "07071762784129012172",
        "AuthorizationId": "07071762784129012172",
        "InvoiceValueInBaseCurrency": "825",
        "BaseCurrency": "KWD",
        "InvoiceValueInDisplayCurreny": "825",
        "DisplayCurrency": "KWD",
        "InvoiceValueInPayCurrency": "825",
        "PayCurrency": "KWD"
    }
}
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.id"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.id"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.user_id"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.user_id"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.name"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.name"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.product_name"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.product_name"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.mobile"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.mobile"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.address"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.address"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.final_total"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.final_total"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.date_added"
ERROR - 2022-10-20 11:47:45 --> Could not find the language line "Text.date_added"
ERROR - 2022-10-20 11:48:23 --> {
    "EventType": 1,
    "Event": "TransactionsStatusChanged",
    "DateTime": "19102022101313",
    "CountryIsoCode": "KWT",
    "Data": {
        "InvoiceId": 1762784,
        "InvoiceReference": "2022000029",
        "CreatedDate": "19102022101231",
        "CustomerReference": null,
        "CustomerName": "Anonymous",
        "CustomerMobile": "+965",
        "CustomerEmail": null,
        "TransactionStatus": "FAILED",
        "PaymentMethod": "VISA/MASTER",
        "UserDefinedField": "658",
        "ReferenceId": "07071762784129012172",
        "TrackId": "19-10-2022_1290121",
        "PaymentId": "07071762784129012172",
        "AuthorizationId": "07071762784129012172",
        "InvoiceValueInBaseCurrency": "825",
        "BaseCurrency": "KWD",
        "InvoiceValueInDisplayCurreny": "825",
        "DisplayCurrency": "KWD",
        "InvoiceValueInPayCurrency": "825",
        "PayCurrency": "KWD"
    }
}
ERROR - 2022-10-20 11:50:02 --> {"EventType":1,"Event":"TransactionsStatusChanged","DateTime":"19102022101409","CountryIsoCode":"KWT","Data":{"InvoiceId":1762788,"InvoiceReference":"2022000030","CreatedDate":"19102022101331","CustomerReference":null,"CustomerName":"Anonymous","CustomerMobile":"+965","CustomerEmail":null,"TransactionStatus":"SUCCESS","PaymentMethod":"KNET","UserDefinedField":"692","ReferenceId":"229210000381","TrackId":"19-10-2022_1290123","PaymentId":"100202229231806767","AuthorizationId":"B26457","InvoiceValueInBaseCurrency":"325","BaseCurrency":"KWD","InvoiceValueInDisplayCurreny":"325","DisplayCurrency":"KWD","InvoiceValueInPayCurrency":"325","PayCurrency":"KWD"}}
