<?php declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Models\Customer;

class CrAppUid
{
    public function appointment_unique_id(String $appointment_unique_id)
    {
        return 'good';
    }

}