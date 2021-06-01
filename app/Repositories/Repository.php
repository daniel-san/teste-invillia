<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class Repository
{
    /**
     * Type of the resource being managed.
     *
     * @var string
     */
    protected $resourceType = Model::class;

    /**
     * Get a new resource instance.
     *
     * @return Model
     */
    public function getInstance()
    {
        return new $this->resourceType;
    }

    /**
     * Find the specified resource by id.
     *
     * @param int $id
     * @return Model|null
     */
    public function find($id)
    {
        return $this->getInstance()->find($id);
    }

    /**
     * Fills data to the resource.
     *
     * @param Model $resource
     * @param array $attributes
     * @param bool $force
     * @return Model
     */
    public function fill($resource, $attributes, $force = false)
    {
        if ($force) {
            return $resource->forceFill($attributes);
        }

        return $resource->fill($attributes);
    }

    /**
     * Build a new object without saving.
     *
     * @param array $attributes
     * @param bool $force
     * @return Model
     */
    public function build($attributes, $force = false)
    {
        return $this->fill(
            $this->getInstance(),
            $attributes,
            $force
        );
    }

    /**
     * Store a newly created resource in the database.
     *
     * @param array $attributes
     * @return Model
     */
    public function create($attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $attributes = $this->attributesForCreate($attributes);

            /** @var Model $resource */
            $resource = $this->build($attributes);
            $resource->save();

            return $this->afterCreate($resource, $attributes);
        });
    }

    /**
     * Update the specified resource in the database.
     *
     * @param Model $resource
     * @param array $attributes
     * @return Model
     */
    public function update($resource, $attributes)
    {
        return DB::transaction(function () use ($resource, $attributes) {
            $attributes = $this->attributesForUpdate($attributes);

            /** @var Model $resource */
            $resource = $this->fill($resource, $attributes);
            $resource->save();

            return $this->afterUpdate($resource, $attributes);
        });
    }

    /**
     * Remove the specified resource from the database.
     *
     * @param Model $resource
     * @return Model
     */
    public function delete($resource)
    {
        return DB::transaction(function () use ($resource) {
            return tap($resource)->delete();
        });
    }

    /**
     * Handles attributes for resource creation.
     *
     * @param array $attributes
     * @return array
     */
    public function attributesForCreate($attributes)
    {
        return $this->filterAttributes($attributes);
    }

    /**
     * Handles attributes for resource update.
     *
     * @param array $attributes
     * @return array
     */
    public function attributesForUpdate($attributes)
    {
        return $this->filterAttributes($attributes);
    }

    /**
     * Filter the given attributes.
     *
     * @param array $attributes
     * @return array
     */
    public function filterAttributes($attributes)
    {
        return $attributes;
    }

    /**
     * Handles resource after save.
     *
     * @param Model $resource
     * @param array $attributes
     * @return Model
     */
    public function afterSave($resource, $attributes)
    {
        return $resource;
    }

    /**
     * Handles resource after create.
     *
     * @param Model $resource
     * @param array $attributes
     * @return Model
     */
    public function afterCreate($resource, $attributes)
    {
        return $this->afterSave($resource, $attributes);
    }

    /**
     * Handles resource after update.
     *
     * @param Model $resource
     * @param array $attributes
     * @return Model
     */
    public function afterUpdate($resource, $attributes)
    {
        return $this->afterSave($resource, $attributes);
    }
}
