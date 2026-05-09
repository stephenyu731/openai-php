<?php

namespace SH\OpenAI\Model\Response;

class ModerationCategory
{
    public $sexual;
    public $hate;
    public $harassment;
    public $selfHarm;
    public $sexualMinors;
    public $hateThreatening;
    public $violenceGraphic;
    public $selfHarmIntent;
    public $selfHarmInstructions;
    public $harassmentThreatening;
    public $violence;
    public $illicit;
    public $illicitViolent;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $prop = lcfirst(str_replace('_', '', ucwords($key, '_')));
            if (property_exists($this, $prop)) {
                $this->{$prop} = $value;
            }
        }
    }
}
