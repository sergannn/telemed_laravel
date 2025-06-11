<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FaqController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $faqs = Faq::dynamicPaginate();

        return $faqs;
    }

    public function store(Request $request): JsonResponse
    {
        $faq = Faq::create($request->validated());

        return $this->responseCreated('Faq created successfully', $faq);
    }

    public function show(Faq $faq): JsonResponse
    {
        return $this->responseSuccess(null, $faq);
    }

    public function update(Request $request, Faq $faq): JsonResponse
    {
        $faq->update($request->validated());

        return $this->responseSuccess('Faq updated Successfully', $faq);
    }

    public function destroy(Faq $faq): JsonResponse
    {
        $faq->delete();

        return $this->responseDeleted();
    }

   
}
