<?php

namespace Adamsafr\FormRequestBundle\Helper;

use Adamsafr\FormRequestBundle\Exception\JsonDecodeException;

class Json
{
    /**
     * Decode json string.
     *
     * @param string $content
     * @param bool $assoc
     * @return array
     * @throws JsonDecodeException
     */
    public static function decode(string $content, bool $assoc = true): array
    {
        if ('' === trim($content)) {
            return [];
        }

        $data = json_decode($content, $assoc);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new JsonDecodeException();
        }

        return $data;
    }

    /**
     * Encode array to json string.
     *
     * @param mixed $value
     * @return string
     */
    public static function encode($value): string
    {
        return json_encode($value);
    }
}
