<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function() use ($app) {
	// Setup a new Infusionsoft SDK object
	$infusionsoft = new \Infusionsoft\Infusionsoft(array(
		'clientId'     => getenv('INFUSIONSOFT_CLIENT_ID'),
		'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
		'redirectUri'  => 'http://local.infusionsoftexample.com/',
	));

	// If the serialized token is available in the session storage, we tell the SDK
	// to use that token for subsequent requests.
	if (Session::has('token')) {
		$infusionsoft->setToken(unserialize(Session::get('token')));
	}

	// If we are returning from Infusionsoft we need to exchange the code for an access token.
	if (Request::has('code') and !$infusionsoft->getToken()) {
		$infusionsoft->requestAccessToken(Request::get('code'));
	}

	if ($infusionsoft->getToken()) {
		// Save the serialized token to the current session for subsequent requests
		Session::put('token', serialize($infusionsoft->getToken()));

		$infusionsoft->contacts->add(array('FirstName' => 'John', 'LastName' => 'Doe'));
	}
	else {
		echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to connect to Infusionsoft</a>';
	}
});
