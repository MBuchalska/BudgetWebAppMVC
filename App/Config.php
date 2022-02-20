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
    const DB_HOST = '';  

    /**
     * Database name
     * @var string
     */
    const DB_NAME = ''; 

    /**
     * Database user
     * @var string
     */
    const DB_USER = '';

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
	const SECRET_KEY ='';
	
	// your mailing domain data
	
	const MAIL_PASS = '';
	
	const MAIL_DOMAIN = '';
	
	const MAIL_HOST = '';
	
	const MAIL_SENDER = '';
	
	
}
