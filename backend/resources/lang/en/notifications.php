<?php

return [
  /*
  |--------------------------------------------------------------------------
  | Notifications translation file
  |--------------------------------------------------------------------------
  |
  | The following language lines are used during notifications for various
  | messages that we need to display to the user. You are free to modify
  | these language lines according to your application's requirements.
  |
  */

  'dear' => 'Dear, :name',
  'thanks' => 'Thanks',

  'item' => 'Item: :item',
  'amount' => 'Amount: :amount',

  'email' => [
    'reset' => [
      'subject' => 'Email Reset',
      'message' => "You've requested an email reset in our website, please click in the following link to finish the process, if haven't requested, please reset your password.",
      'button' => 'Reset email'
    ],

    'verify' => [
      'subject' => 'Email Verification',
      'message' => "You've registered in our app, so please verify your email",
      'button' => 'Verify email'
    ],
  ],

  'password' => [
    'reset' => [
      'subject' => 'Password Reset',
      'message' => "You've requested an password reset in our website, please click in the following link to finish the process, if haven't requested, please so ignore this.",
      'button' => 'Reset password'
    ],
    'reseted' => [
      'subject' => 'Password was reset',
      'message' => "You've updated your password now at :now."
    ]
  ],


  'product' => [
    'purchased' => [
      'message' => 'The following products was purchased, so wait the payment work, and checkout in the server.',
      'subject' => 'Products purchased',
    ],
    'paid' => [
      'message' => 'Congratulations for your purchase! You have purchased the following items:',
      'subject' => 'Products paid',
    ]
  ]
];
