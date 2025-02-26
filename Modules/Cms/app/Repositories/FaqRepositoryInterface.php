<?php

namespace Modules\Cms\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;

interface FaqRepositoryInterface extends RepositoryInterface
{
    public function getPublished();
    public function search($query);
}