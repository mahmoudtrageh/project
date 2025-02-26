<?php

namespace Modules\Cms\Repositories;

use App\Repositories\BaseRepository;
use Modules\Cms\Models\Faq;

class FaqRepository extends BaseRepository implements FaqRepositoryInterface
{
    public function __construct(Faq $model)
    {
        parent::__construct($model);
    }

    public function getPublished()
    {
        return $this->model->where('is_published', true)->get();
    }

    public function search($query)
    {
        return $this->model->where('question', 'like', "%{$query}%")
            ->orWhere('answer', 'like', "%{$query}%")
            ->get();
    }
}