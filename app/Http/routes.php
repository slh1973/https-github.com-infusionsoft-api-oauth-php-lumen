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
		// NOTE: this can be saved in your database - make sure to serialize the entire token for easy future access
		Session::put('token', serialize($infusionsoft->getToken()));

		// Now redirect the user to a page that performs some infusionsoft actions
		return redirect()->to('/contacts');
	}
	else {
		echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to connect to Infusionsoft</a>';
	}

});

$app->get('/contacts', function() use ($app) {

	// Setup a new Infusionsoft SDK object
	$infusionsoft = new \Infusionsoft\Infusionsoft(array(
		'clientId'     => getenv('INFUSIONSOFT_CLIENT_ID'),
		'clientSecret' => getenv('INFUSIONSOFT_CLIENT_SECRET'),
		'redirectUri'  => 'http://local.infusionsoftexample.com/',
	));

	// set the token if we have it in storage (in this case, a session)
	$infusionsoft->setToken(unserialize(Session::get('token')));

	try {
		// retrieve a list of contacts by querying the data service
		$contacts = $infusionsoft->data->query('Contact', 10, 0, ['FirstName' => 'John'], ['FirstName', 'LastName', 'Email', 'ID'], 'FirstName', true);
	} catch (\Infusionsoft\TokenExpiredException $e) {
		// refresh our access token just in case
		$infusionsoft->refreshAccessToken();
		// we also have to save the new token, since it's now been refreshed
		Session::put( 'token', serialize( $infusionsoft->getToken() ) );

		// retrieve the list of contacts again now that we have a new token
		$contacts = $infusionsoft->data->query('Contact', 10, 0, ['FirstName' => 'John'], ['FirstName', 'LastName', 'Email', 'ID'], 'FirstName', true);
	}

	return $contacts;

});