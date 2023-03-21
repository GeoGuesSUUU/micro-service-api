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
                $response['@actions'] = array_map(fn($e) => explode('?', $_SERVER["REQUEST_URI"])[0] . $e , $options['actions']);
            }
            if (isset($options['items-actions']) && is_array($value)) {
                $response['response'] = array_map(fn($i) => [
                    'item' => $i,
                    '@actions' => array_map(fn($e) => explode('?', $_SERVER["REQUEST_URI"])[0] . (is_null($i->getId() ?? null) ? '' : '/' . $i->getId() ) . $e , $options['items-actions'])
                ], $response['response']);
            }
        }
        return $response;
    }
}
