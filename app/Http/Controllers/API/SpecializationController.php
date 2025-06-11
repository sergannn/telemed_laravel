<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Specialization;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SpecializationController extends Controller
{
    public function __construct()
    {

    }
    public function showActive()
    {
        $specializations = Doctor::has('specializations')
        ->get()
        ->pluck('specializations')
        ->flatten()
        ->mapWithKeys(function ($specialization) {
            return [$specialization->name => $specialization->doctors->count()];
        })
        ->all();
        return $specializations;
    }
    public function index()
    {
        $specializations = Specialization::dynamicPaginate();

        return $specializations;
    }

    public function store(Request $request): JsonResponse
    {
        $specialization = Specialization::create($request->validated());

        return $this->responseCreated('Specialization created successfully', $specialization);
    }

    public function show(Specialization $specialization): JsonResponse
    {
        return $this->responseSuccess(null, $specialization);
    }

    public function update(Request $request, Specialization $specialization): JsonResponse
    {
        $specialization->update($request->validated());

        return $this->responseSuccess('Specialization updated Successfully', $specialization);
    }

    public function destroy(Specialization $specialization): JsonResponse
    {
        $specialization->delete();

        return $this->responseDeleted();
    }

   
}
