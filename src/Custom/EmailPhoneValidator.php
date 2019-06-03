<?php
/*
 * This file is part of the "Smarttechno" company CRM system.
 *
 * (c) Pavel Evseenko <e.pavel@tut.by>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Custom;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class EmailPhoneValidator
 */
class EmailPhoneValidator
{
    public static function removeElementsFromArray(array $elements)
    {
        $clearedArray = array_filter(
            $elements,
            function ($value) {
                return $value !== 'toBe@Deleted';
            }
        );

        if (is_null($clearedArray)) {
            return [];
        }

        return $clearedArray;
    }

    public static function validateEmails(ExecutionContextInterface $context, array $emailArray, $formName = 'emails')
    {
        $counter = 0;
        if (!empty($emailArray)) {
            foreach ($emailArray as $key => $value) {
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $context->buildViolation('Похоже, имейл введен неправильно...')
                        ->atPath($formName.'['.$counter.']')
                        ->addViolation();
                }
                $counter++;
            }
        }
    }

    public static function validatePhones(ExecutionContextInterface $context, array $phoneArray, $formName = 'phones')
    {
        $counter = 0;
        if (!empty($phoneArray)) {
            foreach ($phoneArray as $key => $value) {
                if (!preg_match("/^\+{0,1}\d{5,15}$/", $value)) {
                    $context->buildViolation('Похоже, телефон введен неправильно... (допустимы только цифры и знак +)')
                        ->atPath($formName.'['.$counter.']')
                        ->addViolation();
                }
                $counter++;
            }
        }
    }
}
