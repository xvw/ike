<?php
declare(strict_types=1);

namespace ike\exception;

class Base extends \Exception
{
}

class InvalidPathVariable extends Base
{
}

class NeedSuggestion extends Base¨
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
        $message  = 'Undefined type : [' . $input;
        $message .= '], did you mean maybe [' . $suggestion . '] ?';
        parent::__construct($message, ...$params);
    }
}

class InvalidType extends NeedSuggestion
{
}
