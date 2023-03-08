<?php

namespace App\Utils;


use App\Exception\BadRequestApiException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiResponse
{

    /**
     * @throws HttpException
     */
    public static function get(mixed $value, int $httpStatus = 200, array $options = []): array
    {
        if (!isset($value) && $httpStatus !== 204) {
            throw new BadRequestApiException();
        }
        $response = [
            'meta-data' => [
                'status' => $httpStatus,
                'request' => $_SERVER["REQUEST_URI"],
                'method' => $_SERVER["REQUEST_METHOD"],
            ],
            'response' => $value ?? "",
        ];
        if (is_array($value)) {
            $response['meta-data']['total'] = count($value);
        }
        if (!empty($options)) {
            if (isset($options['pagination'])) {
                $response['meta-data']['page'] = $options['pagination']['page'];
                $response['meta-data']['limit'] = $options['pagination']['limit'];
                $response['@actions'] = [];
                if ($options['pagination']['page'] > 1) $response['@actions']['@previous'] = explode('?', $_SERVER["REQUEST_URI"])[0] . '?page=' . $options['pagination']['page'] - 1;
                $response['@actions']['@next'] = explode('?', $_SERVER["REQUEST_URI"])[0] . '?page=' . $options['pagination']['page'] + 1;
            }
            if (isset($options['actions'])) {
                //TODO: actions
                $response['@actions'] = $options['actions'];
            }
        }
        return $response;
    }
}
