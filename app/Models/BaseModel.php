<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

	/**
	 * set fillable fields
	 */
	protected $fillable = [];
	
	/**
	 * set string fields for filtering
	 * @var array
	 */
	protected $likeFilterFields = [];

    /**
     * set boolean fields for filtering
     * @var array
     */
    protected $boolFilterFields = ['status'];

    protected $sortable = [];

    /**
     * add filtering.
     *
     * @param  $builder: query builder.
     * @param  $filters: array of filters.
     * @return query builder.
     */
    public function scopeFilter($builder, $filters = [])
    {
        if(!$filters) {
            return $builder;
        }
        $tableName = $this->getTable();
        $defaultFillableFields = $this->fillable;
        foreach ($filters as $field => $value) {
            if(in_array($field, $this->boolFilterFields) && $value != null) {
                $builder->where($field, (bool)$value);
                continue;
            }

            // if(!in_array($field, $defaultFillableFields) || !$value) {
            //     continue;
            // }

            if(in_array($field, $this->likeFilterFields)) {
                $builder->where($tableName.'.'.$field, 'LIKE', "%$value%");
            } else if(is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }

        return $builder;
    }

    /**
     * Scope to get by repo_name
     *
     * @param object $query
     * @param string $repo_name
     * @return Builder
     */
    public function scopeRepoName($builder, $repo_name)
    {
        return $builder->where("repo_name", $repo_name);
    }

    /**
     * Return only active models
     *
     * @param object $query
     * @return Builder
     */
    public function scopeActive($builder, $state = 1)
    {
        return $builder->where("status", $state);
    }

    public function scopeType($builder, $type) {
        return $builder->where('type', $type);
    }
}