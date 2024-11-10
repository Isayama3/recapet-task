<?php

namespace App\Services;

use App\Base\Traits\Custom\HttpExceptionTrait;
use App\Base\Traits\Response\ApiResponseTrait;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserAuthService
{
    use ApiResponseTrait, HttpExceptionTrait;

    private $model;
    private $UserService;

    public function __construct(User $model, UserService $userService)
    {
        $this->model = $model;
        $this->UserService = $userService;
    }

    public function login(array $data)
    {
        $user = $this->model::where('email', $data['email'])->first();
        if (!$user) {
            return $this->throwHttpExceptionForWebAndApi(__('main.credentials_are_wrong'), 422);
        }

        if (Hash::check($data['password'], $user->password)) {
            $this->createToken($user);
            return $user;
        }

        return $this->throwHttpExceptionForWebAndApi(__('main.credentials_are_wrong'), 422);
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $user = $this->UserService->store($data);
            $user->wallet()->create();
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->throwHttpExceptionForWebAndApi(__('main.registration_failed'), 422);
        }
    }

    /**
     * Create sanctum token for user
     * @param User $user
     * @param array|null $abilities
     * @return string
     */
    public function createToken($user, $abilities = null): string
    {
        $accessToken = $user->createToken('snctumToken', $abilities ?? [])->plainTextToken;
        $this->addTokenExpiration($accessToken);
        return $accessToken;
    }

    public function logout(Request $request)
    {
        auth(activeGuard())?->user()->update([
            'fcm_token' => null,
        ]);
        PersonalAccessToken::findToken($request->bearerToken())->delete();
    }

    protected function addTokenExpiration($accessToken): void
    {
        $expirationTime = Carbon::now()->addDays(90);
        $personalAccessToken = PersonalAccessToken::findToken($accessToken);
        $personalAccessToken->expires_at = $expirationTime;
        $personalAccessToken->save();
    }
}
