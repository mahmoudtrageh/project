<?php

namespace Modules\Cms\Services;

use Modules\Cms\Repositories\FaqRepositoryInterface;

class FaqService
{
    protected $faqRepository;

    public function __construct(FaqRepositoryInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    public function getAllFaqs()
    {
        return $this->faqRepository->all();
    }

    public function getFaqsPaginated($perPage = 15)
    {
        return $this->faqRepository->paginate($perPage);
    }

    public function getFaq($id)
    {
        return $this->faqRepository->find($id);
    }

    public function createFaq(array $data)
    {
        return $this->faqRepository->create($data);
    }

    public function updateFaq($id, array $data)
    {
        return $this->faqRepository->update($id, $data);
    }

    public function deleteFaq($id)
    {
        return $this->faqRepository->delete($id);
    }

    public function getPublishedFaqs()
    {
        return $this->faqRepository->getPublished();
    }

    public function searchFaqs($query)
    {
        return $this->faqRepository->search($query);
    }
}