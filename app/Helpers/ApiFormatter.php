<?php

namespace App\Helpers;

class ApiFormatter
{
    protected static array $response = [
        'code'    => null,
        'message' => null,
        'data'    => null,
    ];

    public static function createJson(int $code, string $message, $data = null)
    {
        self::$response['code'] = $code;
        self::$response['message'] = $message;

        if (is_array($data)) {
            self::$response['data'] = self::filterSensitiveData($data);
        } elseif (is_object($data)) {
            self::$response['data'] = self::filterSensitiveData((array) $data);
        } else {
            self::$response['data'] = $data;
        }

        return response()->json(self::$response, $code);
    }

    public static function filterSensitiveData(array $data = []): array
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'token',
            'api_key',
            'secret',
            'jwt_secret',
        ];

        array_walk_recursive($data, function (&$value, $key) use ($sensitiveFields) {
            if (in_array(strtolower($key), array_map('strtolower', $sensitiveFields), true)) {
                $value = '[FILTERED]';
            }
        });

        return $data;
    }
}
