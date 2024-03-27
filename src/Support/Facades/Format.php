<?php

namespace Three\LaravelResponse\Support\Facades;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Facades\Facade as IlluminateFacade;
use Three\LaravelResponse\Contract\ResponseFormat;

/**
 * @method static \Three\LaravelResponse\Support\Format data(mixed $data = null, string $message = '', int|\BackedEnum $code = 200, $error = null)
 * @method static array|null                               get()
 * @method static array                                    paginator(AbstractPaginator|AbstractCursorPaginator|Paginator $resource)
 * @method static array                                    resourceCollection(ResourceCollection $collection)
 * @method static array                                    jsonResource(JsonResource $resource)
 * @method static JsonResponse                             response()
 *
 * @see \Three\LaravelResponse\Laravel\Support\Format
 */
class Format extends IlluminateFacade
{
    protected static function getFacadeAccessor()
    {
        return ResponseFormat::class;
    }
}
