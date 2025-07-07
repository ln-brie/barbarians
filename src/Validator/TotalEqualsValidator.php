<?php

namespace App\Validator;

use App\Form\Model\Attributs;
use App\Form\Model\Combat;
use App\Form\Model\CustomFormData;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TotalEqualsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TotalEquals || (!$value instanceof Attributs && !$value instanceof Combat)) {
            return;
        }

        $total = 0;
        if ($value instanceof Attributs) {
            $total = ($value->vigueur ?? 0) + ($value->agilite ?? 0) + ($value->esprit ?? 0) + ($value->aura ?? 0);
        } else if ($value instanceof Combat) {
            $total = ($value->initiative ?? 0) + ($value->melee ?? 0) + ($value->tir ?? 0) + ($value->defense ?? 0);
        }

        if ($total !== $constraint->expected) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ expected }}', $constraint->expected)
                ->setParameter('{{ total }}', $total)
                ->addViolation();
        }
    }
}
