<?php

namespace App\Repositories\Eloquent;

use App\Models\Binary;
use App\Repositories\BinaryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BinaryRepository
 * @package App\Repositories
 */
class BinaryRepository implements BinaryRepositoryInterface {

    /**
     * @var Binary
     */
    protected $binary;
    /**
     * @param Binary $binary
     */
    public function __construct(Binary $binary)
    {
        $this->binary = $binary;
    }

    /**
     * Create New Binary
     *
     * @param array $postData
     * @return Binary|null
     */
    public function create(array $postData)
    {
        return $this->binary->create($postData);
    }

    /**
     * Binary Pagination
     *
     * @param array $filter
     * @return collection
     */
    public function paginate(array $filter)
    {
        return $this->binary->paginate($filter['limit']);
    }

    /**
     * Get Binary by ID
     *
     * @param $post_id
     * @return Binary
     */
    public function find($post_id)
    {
        return $this->binary->findOrFail($post_id);
    }

    /**
     * Get Binary with filter
     * @param array $filter
     * @return Binary
     */
    public function filter($filter) {
        return $this->binary->filter($filter)->orderBy('parent_id', 'asc')->get();
    }

    public function orderBy($column, $direction) {
        return $this->binary->orderBy($column, $direction);
    }

}