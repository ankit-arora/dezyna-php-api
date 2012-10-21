## Dezyna PHP API Library

This repository contains a helpful set of classes to connect to and interact with the https://www.dezyna.com API. For full details on the API and available methods please check out https://www.dezyna.com/api.

Source code: https://github.com/dezyna/dezyna-php-api

## Getting Started

To use the Dezyna class in your project, you need to include the lib/bootstrap.php file. Make sure to pass in the correct path to the lib folder, this will vary depending on where you place our API library within your project.

    require_once('lib/bootstrap.php');

Then setup an instance of the Dezyna class with your app api key and secret.

    $dezyna_client = Dezyna::instance("key", "secret");

## Grant a Reward

To grant a reward, do the following:
    
    $code = 'c'; // visit code that got converted
    $value = 100.50; //reward value in INR
    $payable_on = "2012-12-18"; //date [yyyy-mm-dd] when reward should be paid
    $reward_string = "Order no 007"; //unique string to track the reward

    $response = $dezyna_client->grant_reward($code, $value, $payable_on, $reward_string);
    if (!$response['data']->errorExists) { // request was successful since $response['data']->errorExists == false.
        print $response['data']->rewardString; 
    }
    else {
        print $response['data']->errorInfo; // print the error info for why the request failed.
    }

