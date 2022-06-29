<?php
namespace App\Repositories;

use App\Models\Binary;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface BinaryRepositoryInterface
 * @package App\Repositories
 */
interface BinaryRepositoryInterface {

    /**
     * Create New Binary
     *
     * @param array $binaryData
     * @return Binary
     */
    public function create(array $binaryData);

    /**
     * Binary Pagination
     *
     * @param array $filter
     * @return collection
     */
    public function paginate(array $filter);

    /**
     * Get Binary by ID
     * @param $binary_id
     * @return Binary
     */
    public function find($binary_id);
    
    /**
     * Get Binary with filter
     * @param array $filter
     * @return Binary
     */
    public function filter(array $filter);

    public function orderBy($column, $direction);
}
