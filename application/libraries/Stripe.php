<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stripe
{
    public function __construct()
    {
        require_once APPPATH.'third_party/Stripe/lib/Strip.php';
    }
}