<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use DanielDeWit\LighthouseSanctum\Contracts\Services\EmailVerificationServiceInterface;
use DanielDeWit\LighthouseSanctum\Exceptions\HasApiTokensException;
use DanielDeWit\LighthouseSanctum\Traits\CreatesUserProvider;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Contracts\HasApiTokens;
use App\Models\Patient;
use App\Models\User;

use Illuminate\Support\Facades\Log;

use Nuwave\Lighthouse\Exceptions\AuthenticationException;

final readonly class RegisterUser
{
    use CreatesUserProvider;

    public function __construct(
        protected AuthManager $authManager,
        protected Config $config,
        protected Hasher $hash,
        protected EmailVerificationServiceInterface $emailVerificationService,
    ) {
    }    
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $is_doctor = $args['role'] == 'doctor';
        $is_patient = $args['role'] == 'patient';
        Log::debug("DOCTOR DOCTOR DOCTOR");
        Log::debug($is_doctor);
        Log::debug($is_patient);
        Log::debug("DOCTOR DOCTOR DOCTOR");

        if(!$is_doctor && !$is_patient) {
            throw new AuthenticationException('Prohibited role for registration.');
        }
        /** @var EloquentUserProvider $userProvider */
        $userProvider = $this->createUserProvider();
        
        if($is_doctor) $user['type'] = User::DOCTOR;
        if($is_patient) $user['type'] = User::PATIENT;

        $user = $this->saveUser(
            $userProvider->createModel(),
            $this->getPropertiesFromArgs($args),
        );

        
        if($is_doctor) {
            $user->assignRole('doctor');
            $createDoctor = $user->doctor()->create([]);
            Log::debug("DOCTOR ASSIGEND");
        }

        if($is_patient) {
            $user->assignRole('patient');
            $createPatient = $user->patient()->create([

                'patient_unique_id' => mb_strtoupper(Patient::generatePatientUniqueId()),
            ]);
       
            Log::debug("PATIENT ASSIGEND");
        }

        if ($user instanceof MustVerifyEmail) {
            if (isset($args['verification_url'])) {
                /** @var array<string, string> $verificationUrl */
                $verificationUrl = $args['verification_url'];

                $this->emailVerificationService->setVerificationUrl($verificationUrl['url']);
            }

            $user->sendEmailVerificationNotification();

            return [
                'token'  => null,
                'status' => 'MUST_VERIFY_EMAIL',
            ];
        }

        if (! $user instanceof HasApiTokens) {
            throw new HasApiTokensException($user);
        }

       

        return [
            'token'  => $user->createToken('default')->plainTextToken,
            'status' => 'SUCCESS',
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function saveUser(Model $user, array $attributes): Model
    {
        $user
            ->fill($attributes)
            ->save();

        return $user;
    }

    /**
     * @param  array<string, mixed>  $args
     * @return array<string, string>
     */
    protected function getPropertiesFromArgs(array $args): array
    {
        $properties = Arr::except($args, [
            'directive',
            'password_confirmation',
            'verification_url',
        ]);

        $properties['password'] = $this->hash->make($properties['password']);

        return $properties;
    }

    protected function getAuthManager(): AuthManager
    {
        return $this->authManager;
    }

    protected function getConfig(): Config
    {
        return $this->config;
    }
}
