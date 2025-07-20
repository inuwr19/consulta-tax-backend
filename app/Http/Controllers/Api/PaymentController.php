<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PaymentController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');

    }

    // List pembayaran user yang login
    public function index()
    {
        return PaymentResource::collection(
            Payment::with(['user', 'appointment'])
                ->where('user_id', auth()->id())
                ->get()
        );
    }

    // Store manual payment (misalnya transfer bank)
    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id'    => 'required|exists:appointments,id',
            'method'            => 'required|string',
            'reference_number'  => 'required|string|unique:payments',
            'proof_url'         => 'nullable|url',
        ]);

        $data['user_id'] = auth()->id();
        $data['status']  = 'pending';

        $payment = Payment::create($data);

        return (new PaymentResource($payment->load(['user', 'appointment'])))
        ->response()->setStatusCode(201);
    }

    // Tampilkan detail payment tertentu
    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        return new PaymentResource($payment->load(['user', 'appointment']));
    }

    // Generate Snap Token dari Midtrans dan simpan payment
    public function createSnapToken(Request $request): JsonResponse
    {
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
        ]);

        $appointment = Appointment::with('consultant')->findOrFail($data['appointment_id']);

        $price = match ($appointment->service_type) {
            'individual-service' => $appointment->consultant->price_individual_service,
            'individual-jasa'    => $appointment->consultant->price_individual_jasa,
            'company-service'    => $appointment->consultant->price_company_service,
            default              => 0,
        };

        $user    = auth()->user();
        $orderId = 'ORDER-' . uniqid();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $price,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email ?? 'noemail@example.com',
            ],
        ];

        try {
            \Log::info('CreateSnapToken started', $request->all());
            $snapToken = Snap::getSnapToken($params);

            $payment = Payment::create([
                'appointment_id'   => $appointment->id,
                'user_id'          => $user->id,
                'method'           => 'midtrans',
                'reference_number' => $orderId,
                'status'           => 'pending',
                'snap_token'       => $snapToken,
            ]);

            return response()->json([
                'snapToken' => $snapToken,
                'payment'   => new PaymentResource($payment->load(['user', 'appointment'])),
            ], 201);
        } catch (\Exception $e) {
            \Log::error('CreateSnapToken error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'reference_number' => 'required|string',
            'status'           => 'required|in:paid,pending,failed',
        ]);

        $payment = Payment::where('reference_number', $request->reference_number)->first();

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Update status payment
        $payment->status = $request->status;
        if ($request->status === 'paid') {
            $payment->paid_at = now();
        }
        $payment->save();

        // Update juga status appointment jika payment sukses
        if ($request->status === 'paid' && $payment->appointment) {
            $payment->appointment->status = 'confirmed'; // kamu bisa ganti 'confirmed' jadi 'paid' atau status lainnya
            $payment->appointment->save();
        }

        return response()->json(['message' => 'Payment and appointment updated successfully']);
    }

}
