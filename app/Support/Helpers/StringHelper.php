<?php

namespace Support\Helpers;

class StringHelper {

    public function retrieveId($data, $char)
    {
        return substr($data, strpos($data, $char) + 1);
    }
}
