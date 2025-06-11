<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;


use App\Models\DoctorSession;
use App\Models\WeekDay;

final readonly class UpsertSessionsCustom
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $week_days = \Illuminate\Support\Arr::pull($args, 'sessionWeekDays');

        $sessions = DoctorSession::where('doctor_id',$args['doctor_id'])
        ->get();

        $is_exist = !$sessions->isEmpty();



        $sessions = DoctorSession::upsert(
            [
                [
                    'doctor_id' => $args['doctor_id'],
                    'session_meeting_time' => $args['session_meeting_time'],
                    'session_gap' => $args['session_gap']
                ]
            ],
            uniqueBy: ['id', 'doctor_id'],
            update: ['session_meeting_time','session_gap']
        );

        $sessions = DoctorSession::where('doctor_id',$args['doctor_id'])
        ->get();

        Log::debug('An $sessions.');
        Log::debug($sessions[0]->id);

        $week_days_upsert_array = [];
        foreach ($week_days as $week_day) {
            $week_day['doctor_session_id'] = $sessions[0]->id;
            $week_day['doctor_id'] = $args['doctor_id'];
            $week_days_upsert_array[] = $week_day;
        }

        Log::debug('An $week_days_upsert_array.');
        Log::debug($week_days_upsert_array);

        WeekDay::where('doctor_id',$args['doctor_id'])->delete();

        $week_days = WeekDay::upsert(
            
                $week_days_upsert_array
            ,
            uniqueBy: ['doctor_id','day_of_week'],
            update: [
                'start_time',
                'start_time_type',
                'end_time',
                'end_time_type'
                ]
        );

        return $sessions[0];




    }
}