<?php

namespace Three\LaravelResponse\Contract;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;

interface ResponseFormat
{
    public function response(): JsonResponse;

    /**
     * Get formatted data.
     */
    public function get(): ?array;

    /**
     * Format data structures.
     *
     * @return $this
     */
    public function data(mixed $data = null, string $message = '', int|\BackedEnum $code = 200, $error = null): static;

    /**
     * Format paginator data.
     */
    public function paginator(AbstractPaginator|AbstractCursorPaginator|Paginator $resource): array;

    /**
     * Format collection resource data.
     */
    public function resourceCollection(ResourceCollection $collection): array;

    /**
     * Format JsonResource Data.
     */
    public function jsonResource(JsonResource $resource): array;
}
