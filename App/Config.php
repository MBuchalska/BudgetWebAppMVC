<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'budgetmvc';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'root';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
	
	// secret key for hashing
	const SECRET_KEY ='YE3WGofMvvaSLGej5kCTi51OXciBv2yw';
	
	// sending domain data
	
	const MAIL_PASS = 'Maly_kot3k';
	
	const MAIL_DOMAIN = 'mbuchalska@martabuchalska.pl';
	
	const MAIL_HOST = 'mail.martabuchalska.pl';
	
	const MAIL_SENDER = 'Your Admin';
	
	
}
