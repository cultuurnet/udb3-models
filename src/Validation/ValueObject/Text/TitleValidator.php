<?php

namespace CultuurNet\UDB3\Model\Validation\ValueObject\Text;

use Respect\Validation\Rules\NotEmpty;
use Respect\Validation\Validator;

class TitleValidator extends Validator
{
    public function __construct()
    {
        $rules = [
            new NotEmpty(),
        ];

        parent::__construct($rules);
    }
}
