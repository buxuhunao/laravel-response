<?php

namespace Three\LaravelResponse\Support\Facades;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * @method static JsonResponse accepted($data = null, string $message = '', string $location = '')
 * @method static JsonResponse created($data = null, string $message = '', string $location = '')
 * @method static JsonResponse noContent(string $message = '')
 * @method static JsonResponse localize(int|\BackedEnum $code = 200)
 * @method static JsonResponse ok(string $message = '', int|\BackedEnum $code = 200)
 * @method static JsonResponse success($data = null, string $message = '', int|\BackedEnum $code = 200)
 * @method static JsonResponse errorBadRequest(?string $message = '')
 * @method static JsonResponse errorUnauthorized(string $message = '')
 * @method static JsonResponse errorForbidden(string $message = '')
 * @method static JsonResponse errorNotFound(string $message = '')
 * @method static JsonResponse errorMethodNotAllowed(string $message = '')
 * @method static JsonResponse fail(string $message = '', int|\BackedEnum $code = 500, $errors = null)
 *
 * @see \Three\LaravelResponse\Response
 */
class Response extends IlluminateFacade
{
    protected static function getFacadeAccessor()
    {
        return \Three\LaravelResponse\Response::class;
    }
}
