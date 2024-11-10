<?php

namespace App\Base\Services;

use App\Base\Repositories\BaseRepository;
use App\Base\Traits\Custom\AttachmentAttribute;
use App\Base\Traits\Custom\HttpExceptionTrait;
use App\Base\Traits\Custom\ResizableImageTrait;
use App\Base\Traits\Response\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    use ApiResponseTrait, ResizableImageTrait, AttachmentAttribute, HttpExceptionTrait;

    /**
     * @var BaseRepository
     */
    protected BaseRepository $repository;

    protected $indexRelations = [];
    protected $oneItemRelations = [];
    protected $customWhen = [];

    /**
     * BaseService constructor.
     * @param BaseRepository $repository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $this->repository->initFilters();
        $this->repository->setRelations($this->getIndexRelations());
        $this->repository->setCustomWhen($this->getCustomWhen());
        return $this->repository->getAllDataPaginated();
    }

    public function list($name)
    {
        $this->repository->setCustomWhen($this->getCustomWhen());
        return $this->repository->getSelected($name);
    }

    public function listSelected($columns = [])
    {
        $this->repository->setCustomWhen($this->getCustomWhen());
        return $this->repository->getManySelected($columns);
    }

    public function listNameWhereCondition($name, $column, $value)
    {
        return $this->repository->listNameWhereCondition($name, $column, $value);
    }

    public function listWhereTableName($table_name)
    {
        return $this->repository->listWhereTableName($table_name);
    }

    public function show($id)
    {
        $this->repository->setRelations($this->getOneItemRelations());
        return $this->repository->findOrFail($id);
    }

    public function findOrFail($id, $relations = [])
    {
        return $this->repository->findOrFail($id, $relations);
    }

    /**
     * Store a newly created resource in storage.
     * Hint :: You can override this method in the child class BaseService add more functionality
     * Hint :: Don't forget to call parent::store($data) in the child class
     * Hint :: It will Upload any file in the request that its name has "image" ex. "profile_image, image, ..."
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     */
    public function store($data)
    {
        $record = $this->repository->create($data);
        !empty($this->getOneItemRelations()) ? $record->load(...$this->getOneItemRelations()) : null;

        $this->uploadRequestImages($data, $record);

        $record->refresh();
        return $record;
    }

    /**
     * Update the specified resource in storage.
     * Hint :: You can override this method in the child class BaseService add more functionality
     * Hint :: Don't forget to call parent::update($id, $data) in the child class
     * Hint :: It will delete old image if it is in the request and not nul and Upload any file in the request that its name has "image" ex. "profile_image, image, ..."
     *
     * @param array $data
     * @param array $relations
     * @return mixed
     */
    public function update($id, $data)
    {
        $this->repository->setRelations($this->getOneItemRelations());
        $record = $this->repository->update($id, $data);

        $this->updateRequestImages($data, $record);

        if ($record instanceof Model) {
            $record->refresh();
        }

        return $record;
    }

    public function destroy($id)
    {
        $this->repository->setCustomWhen($this->getCustomWhen());
        return $this->repository->remove($id);
    }

    // Setters and Getters
    public function setIndexRelations($relations)
    {
        $this->indexRelations = $relations;
    }

    public function getIndexRelations()
    {
        return $this->indexRelations;
    }

    public function setOneItemRelations($relations)
    {
        $this->oneItemRelations = $relations;
    }

    public function getOneItemRelations()
    {
        return $this->oneItemRelations;
    }

    public function getCustomWhen()
    {
        return empty($this->customWhen) ? $this->defaultCustomWhenArray() : $this->customWhen;
    }

    public function setCustomWhen($customWhen)
    {
        $this->customWhen = $customWhen;
    }

    public function defaultCustomWhenArray()
    {
        return [
            'condition' => false,
            'callback' => function ($q) {},
        ];
    }

    public function storeMany($data)
    {
        return $this->repository->createMany($data);
    }

    public function getMoreThanOneSelected(array $data)
    {
        return $this->repository->getMoreThanOneSelected($data);
    }

    public function uploadRequestImages($attributes, $record)
    {
        $keys = array_keys($attributes);
        $image_attributes = array_filter($keys, function ($key) {
            return strpos($key, 'image') !== false;
        });

        foreach ($image_attributes as $image_attribute) {
            if (isset($attributes[$image_attribute]) && !is_null($attributes[$image_attribute])) {
                $record->$image_attribute = $this->uploadImage($attributes[$image_attribute], 'uploads/' . ((new \ReflectionClass($record))->getShortName()), []);
                $record->save();
            }
        }

        if (!empty(request()->media)) {
            foreach (request()->media as $image) {
                if ($image) {
                    Attachment::addAttachment($image, $record, 'uploads/' . ((new \ReflectionClass($record))->getShortName()), []);
                }
            }
        }
    }
    public function updateRequestImages($attributes, $record)
    {
        $keys = array_keys($attributes);
        $image_attributes = array_filter($keys, function ($key) {
            return strpos($key, 'image') !== false;
        });

        foreach ($image_attributes as $image_attribute) {
            if (isset($attributes[$image_attribute]) && !is_null($attributes[$image_attribute])) {
                $record->$image_attribute = $this->updateImage($record->$attributes[$image_attribute], $attributes[$image_attribute], 'uploads/' . ((new \ReflectionClass($record))->getShortName()), []);
                $record->save();
            }
        }

        if (!empty(request()->media)) {
            $record->attachmentRelation()->delete();
            foreach (request()->media as $image) {
                if ($image) {
                    Attachment::addAttachment($image, $record, 'upload/' . ((new \ReflectionClass($record))->getShortName()), []);
                }
            }
        }
    }
}
