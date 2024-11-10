<?php

namespace App\Base\Controllers;

use App\Base\Resources\SimpleResource;
use App\Base\Services\BaseService;
use App\Base\Traits\Response\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseApiController
{
    use ApiResponseTrait;

    protected $request;
    protected $model;
    protected $resource;
    protected $hasDelete;
    protected BaseService $service;

    protected $indexRelations = [];
    protected $oneItemRelations = [];
    protected $customWhen = [];

    public function __construct(
        FormRequest $request,
        Model $model,
        JsonResource $resource,
        BaseService $service,
        $hasDelete = true
    ) {
        $this->request = $request;
        $this->model = $model;
        $this->service = $service;
        $this->resource = $resource;
        $this->hasDelete = $hasDelete;
    }

    public function customWhen()
    {
        return [
            'condition' => false,
            'callback' => function ($q) {
            },
        ];
    }

    public function defaultCustomWhenArray()
    {
        return [
            'condition' => false,
            'callback' => function ($q) {
            },
        ];
    }

    public function index()
    {
        $results = $this->service->index();
        return $this->respondWithCollection($this->resource::collection($results));
    }

    public function listName()
    {
        return $this->respondWithArray(SimpleResource::collection($this->service->list('name')));
    }

    public function show($id)
    {
        $record = $this->service->show($id);
        return $this->respondWithModelData($this->resource::make($record));
    }

    public function store()
    {
        $record = $this->service->store($this->request->validated());
        return $this->respondWithSuccess(__('main.successfully_added'), ['record' => new $this->resource($record)]);
    }

    public function update($id)
    {
        $record = $this->service->update($id, $this->request->validated());
        return $this->respondWithSuccess(__('main.successfully_updated'), ['record' => new $this->resource($record)]);
    }

    public function destroy($id)
    {
        if (!$this->hasDelete)
            return $this->setStatusCode(422)->respondWithError(__('main.the_model_cannot_be_deleted'));

        $this->service->destroy($id);

        return $this->respondWithSuccess(__('main.successfully_deleted'));
    }
}
