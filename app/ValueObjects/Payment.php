<?php

namespace App\ValueObjects;

class Payment
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param  string  $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id() : string
    {
        return $this->id;
    }
}
