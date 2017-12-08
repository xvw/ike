<?php
declare(strict_types=1);

namespace ike\exception;

class Base extends \Exception
{
}

class InvalidPathVariable extends Base
{
}

class MissingField extends Base
{
    private $missingField;
    /**
     * Build a Missing Field Exception
     * @param string $input the gived input
     */
    public function __construct(string $input, ...$params)
    {
        $this->missingField = $input;
        $message  = 'field ['.$input.'] is missing';
        parent::__construct($message, ...$params);
    }
}

class NeedSuggestion extends Base
{
    private $baseInput;
    private $suggestedOutput;
    /**
     * Build a Suggestion Exception
     * @param string $input the gived input
     * @param string $suggestion a type suggestion
     * @param array $params the other exception parameter
     */
    public function __construct(string $input, string $suggestion, ...$params)
    {
        $this->baseInput = $input;
        $this->suggestion = $suggestion;
        $message  = 'Invalid : [' . $input;
        $message .= '], did you mean maybe [' . $suggestion . '] ?';
        parent::__construct($message, ...$params);
    }
}

class InvalidType extends NeedSuggestion
{
}

class InvalidFunction extends Base
{
}

class ParameterDoesNotExistsInFunction extends NeedSuggestion
{
}
