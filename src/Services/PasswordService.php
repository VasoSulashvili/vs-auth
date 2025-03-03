<?php

namespace VS\Auth\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use VS\Admin\Models\Admin;
use VS\Auth\Repositories\PasswordRepository;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use VS\Base\Exceptions\APIException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PasswordService
{
    protected $repository;

//    protected $model;

    public function __construct(Authenticatable $model)
    {
        $this->repository = new PasswordRepository($model);
    }



    /**
     * @param Authenticatable $user
     * @param string $oldPassword
     * @param string $password
     * @param string $passwordConfirmation
     * @return Authenticatable
     * @throws APIException
     */
    public function update(Authenticatable $user, string $oldPassword, string $password, string $passwordConfirmation): Authenticatable
    {
        if(!Hash::check($oldPassword, $user->password)) {
            throw new APIException('Old password is wrong', 403);
        }

        if($password !== $passwordConfirmation) {
            throw new APIException('New password and repeat password are not same', 403);
        }

        return $this->repository->update($user, $password);
    }



    /**
     * @param string $email
     * @param string $broker
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(string $email, string $broker)
    {
        $status = Password::broker($broker)->sendResetLink(
            ['email' => $email],
        );

        return $status === Password::ResetLinkSent
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

    }



    /**
     * @param string $broker
     * @param array $credentials
     * @return bool
     * @throws APIException
     */
    public function reset(string $broker, array $credentials): bool
    {
        if (!$this->checkBroker($broker)) {
            throw new APIException('Broker not found', 403);
        }

        $status = Password::broker($broker)->reset(
            $credentials,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET;
    }



    /**
     * @param string $broker
     * @return bool
     */
    protected function checkBroker(string $broker) : bool
    {
        if (!config('auth.passwords.' . $broker)) {
            return false;
        }
        return true;
    }

}
