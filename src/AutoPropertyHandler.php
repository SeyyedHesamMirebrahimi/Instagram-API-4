<?php

namespace InstagramAPI;

/**
 * Automatically creates virtual "getX", "setX" and "isX" functions for all
 * object properties.
 */
class AutoPropertyHandler
{
    // CALL is invoked when attempting to access missing functions.
    // This allows us to auto-map setters and getters for properties.
    public function __call(
        $function,
        $args)
    {
        // Parse the name of the function they tried to call.
        $underScoreNames = $this->camelCaseToUnderScore($function);
        if (strpos($underScoreNames, '_') === false) {
            throw new \Exception("Unknown function {$function}.");
        }
        list($functionType, $propName) = explode('_', $underScoreNames, 2);

        // Make sure the requested function corresponds to an object property.
        if (!property_exists($this, $propName)) {
            throw new \Exception("Unknown function {$function}.");
        }

        // Return the kind of response expected by their function.
        switch ($functionType) {
        case 'get':
            return $this->$propName;
            break;
        case 'set':
            $this->$propName = $args[0];
            break;
        case 'is':
            return $this->$propName ? true : false;
            break;
        default:
            // Unknown camelcased function call...
            throw new \Exception("Unknown function {$function}.");
        }
    }

    public function camelCaseToUnderScore(
        $input)
    {
        // This is a highly optimized regexp which achieves the matching in very
        // few regex engine steps and with very high performance. Do not touch!
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }
}
