<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class TotalEquals extends Constraint
{
    public string $message = 'Vous devez répartir {{ expected }} points. Actuellement : {{ total }}.';
    public int $expected;

    public function __construct(int $expected, array $options = [], mixed $payload = null)
    {
        parent::__construct($options, $payload);
        $this->expected = $expected;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}