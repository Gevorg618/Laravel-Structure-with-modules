<?php

namespace App\Providers;

use Input;
use Request;
use Validator;
use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;
use Illuminate\Validation\ValidationServiceProvider as BaseProvider;

class ValidationServiceProvider extends BaseProvider
{
    public function register()
    {
        parent::register();

        $this->registerCustomValidationRules();
    }

    protected function registerCustomValidationRules()
    {
        Validator::extend('alpha_dash_period', function ($attribute, $value, $parameters, $validator) {
            return (bool) !preg_match('~[^a-z0-9-_.\s\&\@\,\#]~iu', $value);
        });

        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            $input = Input::get('captcha');
            $instance = new ReCaptcha(config('services.recaptcha.secret'), (new CurlPost));
            $response = $instance->verify($input, realIp());

            return $response->isSuccess();
        });
    }
}
