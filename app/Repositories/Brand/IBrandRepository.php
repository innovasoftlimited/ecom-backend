<?php

namespace App\Repositories\Brand;

use App\Repositories\IBaseRepository;

interface IBrandRepository extends IBaseRepository
{
    /**
     * @param string $keyword
     * @return array
     */
    public function brandListWithFilter(string $keyword): array;

}
