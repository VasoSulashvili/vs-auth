<?php

namespace VS\Auth\Services;

use Illuminate\Database\Eloquent\Model;
use PragmaRX\Google2FA\Google2FA;

class TOTPService
{
    public function __construct(protected Google2FA $google2FA)
    {
    }
    public function enable(Model $model, string $format = 'svg'): array
    {
        $secret = $this->google2FA->generateSecretKey();
        $model->update(['totp_secret' => $secret]);

//        $code = $this->google2FA->setQRCodeBackend($format);

        $qrCodeUrl = $this->google2FA->getQRCodeUrl(
            config('app.name'),
            $model->email,
            $secret,
        );

        return ['secret' => $secret, 'qr_code_url' => $qrCodeUrl];
    }

}
