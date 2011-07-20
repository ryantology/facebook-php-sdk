Facebook PHP SDK (v.3.0.0) + CakePHP HttpSocket
==========================

The [Facebook Platform](http://developers.facebook.com/) is
a set of APIs that make your application more social. Read more about
[integrating Facebook with your web site](http://developers.facebook.com/docs/guides/web)
on the Facebook developer site.

The original Facebook SDK requires cURL. When using CakePHP there is no need for curl to perform basic http calls. The cURL calls have been replaced with [HttpSocket](http://book.cakephp.org/view/1517/HttpSocket)
	Requires CakePHP 1.3
	
NOTE: This may be done by someone else. I was unable to find this and after repeatedly using this bit of code I figured it would be good to publish.

This repository contains the open source PHP SDK that allows you to utilize the
above on your website. Except as otherwise noted, the Facebook PHP SDK
is licensed under the Apache Licence, Version 2.0
(http://www.apache.org/licenses/LICENSE-2.0.html)


CakePHP Usage
-----

Copy the files from

    src/
    src/base_facebook.php
    src/facebook.php
    src/facebook_cakephp.php

to

    app/vendors/facebook-cakephp

Import them into your controller as needed.

    App::import('Vendor','facebook-cakephp/facebook_cakephp');
	
	// Create our Application instance (replace this with your appId and secret).
	$facebook = new CakeFacebook(array(
	  'appId'  => '################',
	  'secret' => '$$$$$$$$$$$$$$$$$$$$$$$$$$$$$',
	));


General SDK Usage
-----

The [examples][examples] are a good place to start. The minimal you'll need to
have is:

    require './facebook.php';

    $facebook = new Facebook(array(
      'appId'  => 'YOUR_APP_ID',
      'secret' => 'YOUR_APP_SECRET',
    ));

    // Get User ID
    $user = $facebook->getUser();

To make [API][API] calls:

    if ($user) {
      try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
      }
    }

Login or logout url will be needed depending on current user state.

    if ($user) {
      $logoutUrl = $facebook->getLogoutUrl();
    } else {
      $loginUrl = $facebook->getLoginUrl();
    }

[examples]: http://github.com/facebook/php-sdk/blob/master/examples/example.php
[API]: http://developers.facebook.com/docs/api


Feedback
--------

File bugs or other issues [here].

[here]: http://bugs.developers.facebook.net/



Tests
-----

In order to keep us nimble and allow us to bring you new functionality, without
compromising on stability, we have ensured full test coverage of the new SDK.
We are including this in the open source repository to assure you of our
commitment to quality, but also with the hopes that you will contribute back to
help keep it stable. The easiest way to do so is to file bugs and include a
test case.
