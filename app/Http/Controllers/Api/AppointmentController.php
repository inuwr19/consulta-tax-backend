<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return AppointmentResource::collection(
            Appointment::with(['user', 'payment', 'consultant'])
                ->where('user_id', auth()->id())
                ->whereHas('payment', function ($query) {
                    $query->where('status', 'paid'); // hanya yang sudah dibayar
                })
                ->where('date', '>=', now()->toDateString()) // hanya yang akan datang
                ->orderBy('date')
                ->orderBy('time')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'method' => 'required|in:online,offline',
            'service_type' => 'required|in:individual-service,individual-jasa,company-service',
            'individual_service_type' => 'nullable|string',
            'consultant_id' => 'required|exists:consultants,id',
            'nama' => 'required|string',
            'nik' => 'required|string|max:20',
            'npwp' => 'nullable|string|max:25',
            'efin' => 'nullable|string|max:25',
            'notes' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        $appointment = Appointment::create($data);
        return new AppointmentResource($appointment->load(['user', 'payment', 'consultant']));
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return new AppointmentResource($appointment->load(['user', 'payment']));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $appointment->update($request->only(['status', 'gmeet_link']));
        return new AppointmentResource($appointment);
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted.']);
    }

    public function updateGmeet(Request $request, $id)
    {
        $request->validate([
            'gmeet_link' => 'nullable|url',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->gmeet_link = $request->gmeet_link;
        $appointment->save();

        return response()->json(['message' => 'Gmeet link updated successfully']);
    }

    public function getAllAppointments()
    {
        $appointments = Appointment::with(['user', 'consultant', 'payment']) // relasi sesuai kebutuhanmu
            ->where('date', '>=', now()->toDateString())
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $appointments
        ]);
    }


}

