<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConsultantResource;
use App\Models\Consultant;
use Illuminate\Http\Request;

class ConsultantController extends Controller
{
    public function index()
    {
        $consultants = Consultant::orderBy('name')->get();
        return ConsultantResource::collection($consultants);
    }

    public function show($id)
    {
        $consultant = Consultant::findOrFail($id);
        return new ConsultantResource($consultant);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|between:0,5',
            'price_individual_service' => 'nullable|integer|min:0',
            'price_individual_jasa' => 'nullable|integer|min:0',
            'price_company_service' => 'nullable|integer|min:0',
        ]);

        $consultant = Consultant::create($validated);
        return new ConsultantResource($consultant);
    }

    public function update(Request $request, $id)
    {
        $consultant = Consultant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'specialty' => 'nullable|string|max:100',
            'experience_years' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|between:0,5',
            'price_individual_service' => 'nullable|integer|min:0',
            'price_individual_jasa' => 'nullable|integer|min:0',
            'price_company_service' => 'nullable|integer|min:0',
        ]);

        $consultant->update($validated);
        return new ConsultantResource($consultant);
    }

    public function destroy($id)
    {
        $consultant = Consultant::findOrFail($id);
        $consultant->delete();

        return response()->json(['message' => 'Consultant deleted']);
    }

}
