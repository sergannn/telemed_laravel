<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use DanielDeWit\LighthouseSanctum\Exceptions\HasApiTokensException;
use DanielDeWit\LighthouseSanctum\Traits\CreatesUserProvider;
use Exception;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Config\Repository as Config;
use Laravel\Sanctum\Contracts\HasApiTokens;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;

use App\Models\User;

final readonly class Loginwithuserresult
{
    use CreatesUserProvider;

    public function __construct(
        protected AuthManager $authManager,
        protected Config $config,
    ) {
    }

    /**
     * @param  array<string, string>  $args
     * @return array<string, string|null>
     *
     * @throws Exception
     */
    public function __invoke(mixed $_, array $args): array
    {
        $userProvider = $this->createUserProvider();

        $identificationKey = $this->getConfig()
            ->get('lighthouse-sanctum.user_identifier_field_name', 'email');

        $user = $userProvider->retrieveByCredentials([
            $identificationKey => $args[$identificationKey],
            'password'         => $args['password'],
        ]);

        if (! $user || ! $userProvider->validateCredentials($user, $args)) {
            throw new AuthenticationException('The provided credentials are incorrect.');
        }

    /*    if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            throw new AuthenticationException('Your email address is not verified.');
        }
    */
        if (! $user instanceof HasApiTokens) {
            throw new HasApiTokensException($user);
        }
        $user_data = User::find($user->id);
        // $user_data->profile_image = $user->patient->profile;
        $userAttributes = $user->toArray();
       // print_r($userAttributes);
        if($userAttributes['role_name']=="Patient") {
       //     user->media()
        $userAttributes['profile_image'] =  $user->getProfileImageAttribute();
        $userAttributes['patient_id'] = $user->patient->id;
        $userAttributes['full_name'] = $userAttributes['first_name'] . ' ' . $userAttributes['last_name'] ;

        }
        if($userAttributes['role_name']=="Doctor") {
            
            $userAttributes['profile_image'] = $user->getProfileImageAttribute();
            //'https://preview.redd.it/sgau39u569o91.jpg?width=2048&format=pjpg&auto=webp&s=d941aaf1694b21883dd9d42bea9cb7fa12550063';
            //$user->doctor->profile;
           // $userAttributes['doctor_id'] = $user->doctor->id;
            $userAttributes['doctor_id'] = $user->doctor->id;
            $userAttributes['full_name'] = $userAttributes['first_name'] . ' ' . $userAttributes['last_name'] ;
            $userAttributes['ab']='doctor';
        }
        // dd($userAttributes);
        return [
            'token' => $user->createToken('default')->plainTextToken,
            'user' => $userAttributes,
            'json_user'=>print_r($userAttributes,true)
        ];
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
