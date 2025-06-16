<?php

namespace App\GraphQL\Mutations;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class CancelAppointment
{
    public function __invoke($root, array $args)
    {
        try {
            DB::beginTransaction();
            
            $appointment = Appointment::findOrFail($args['id']);
            
            // Add any business logic here (e.g., check if cancellation is allowed)
            if ($appointment->status === 'CANCELLED') {
                throw new \Exception('Appointment is already cancelled');
            }
            
            // Soft delete or update status
            $appointment->update(['status' => 'CANCELLED']);
            // OR for hard delete: $appointment->delete();
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Appointment cancelled successfully',
                'appointment' => $appointment
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'appointment' => null
            ];
        }
    }
}