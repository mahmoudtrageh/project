<?php

namespace Modules\Cms\Http\Controllers\Faq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Cms\Http\Requests\Faq\StoreFaqRequest;
use Modules\Cms\Http\Requests\Faq\UpdateFaqRequest;
use Modules\Cms\Services\FaqService;

class FaqController extends Controller
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function index()
    {
        $faqs = $this->faqService->getFaqsPaginated();
        return view('cms::faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('cms::faqs.create');
    }

    public function store(StoreFaqRequest $request)
    {
        $faq = $this->faqService->createFaq($request->validated());
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function show($id)
    {
        $faq = $this->faqService->getFaq($id);
        return view('cms::faqs.show', compact('faq'));
    }

    public function edit($id)
    {
        $faq = $this->faqService->getFaq($id);
        return view('cms::faqs.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, $id)
    {
        $faq = $this->faqService->updateFaq($id, $request->validated());
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy($id)
    {
        $this->faqService->deleteFaq($id);
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $faqs = $this->faqService->searchFaqs($query);
        return view('admin.faqs.search', compact('faqs', 'query'));
    }
}
