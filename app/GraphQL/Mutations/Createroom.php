<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Http;

use App\Models\Appointment;

final readonly class Createroom
{

    public function __construct(
    ) {
    }

    /**
     * @param  array<string, string>  $args
     * @return array<string, string|null>
     *
     * @throws Exception
     */
    public function __invoke(mixed $_, array $args): String
    {
        $appointment_id = $args['appointment_id'];
        $appointment = Appointment::whereId($appointment_id);
        // вытащить длину сессии закинуть в переменную $session_length

        $token = '76b9a8244e1657186429a60d2a9007e81956c1c1e3927eff228ee058b8040160'; 
        $roomUrl = 'https://api.daily.co/v1/rooms/';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $token",
        ])->post($roomUrl, [
            'properties' => [
                'exp' => time() + 3600, // Current timestamp plus 1 hour в итоге тут поставить $session_length
            ],
        ]);


        $appointment->update([
            'room_data' => json_encode($response->json()),
        ]);

        return json_encode($response->json());

        // {

        //     "id": "987b5eb5-d116-4a4e-8e2c-14fcb5710966",
          
        //     "name": "ePR84NQ1bPigp79dDezz",
          
        //     "api_created": true,
          
        //     "privacy": "public",
          
        //     "url": "https://api-demo.daily.co/ePR84NQ1bPigp79dDezz",
          
        //     "created_at": "2019-01-28T20:08:15.000Z",
          
        //     "config": {
          
        //       "exp": 1548709695
          
        //     }
          
        //   }
    }


}
