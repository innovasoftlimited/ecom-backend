<?php

namespace App\Repositories\Category;

use App\Repositories\IBaseRepository;

interface ICategoryRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @return array
     */
    public function categoryListWithFilter(string $keyword): array;

    /**
     * @return array
     */
    public function categoryList(): array;

}
