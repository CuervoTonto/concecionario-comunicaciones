<?php 

namespace Src\App\Controllers;

use Src\Validator\Validator;

abstract class Controller
{
    /**
     * validate data and redirect on case of fail
     * 
     * @param array<string, mixed> $data data to validate
     * @param array<string, string|array> $validations validations to improve on data
     * @param array<string, string> $messages messages to fail validations
     * @param string $url url on case of no have a referer
     * 
     * @return \Src\Validator\Validator 
     */
    protected function validate(
        array $data,
        array $validations = [],
        array $messages = [],
        string $url = '/'
    ): Validator {
        $validator = new Validator($data, $validations, $messages);

        $validator->validateRedirect(
            request()->referer() ?? $url,
            session()->repository(),
        );

        return $validator;
    }
}