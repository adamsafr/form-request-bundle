<?php

namespace Adamsafr\FormRequestBundle\Helper;

use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;

class Json
{
    /**
     * @param string $content
     * @return array
     * @throws JsonDecodeException
     */
    public static function decode(string $content): array
    {
        if ('' === trim($content)) {
            return [];
        }

        $data = json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDecodeException();
        }

        return $data;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public static function encode($value): string
    {
        return json_encode($value);
    }
}
