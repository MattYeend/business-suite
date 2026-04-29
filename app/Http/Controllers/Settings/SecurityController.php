<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\PasswordUpdateRequest;
use App\Http\Requests\Settings\TwoFactorAuthenticationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class SecurityController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        $canManage = Features::canManageTwoFactorAuthentication();
        $requiresConfirm = Features::optionEnabled(
            Features::twoFactorAuthentication(),
            'confirmPassword'
        );

        return $canManage && $requiresConfirm
            ? [new Middleware('password.confirm', only: ['edit'])]
            : [];
    }

    /**
     * Show the user's security settings page.
     */
    public function edit(
        TwoFactorAuthenticationRequest $request
    ): Response {
        $canManageTwoFactor = Features::canManageTwoFactorAuthentication();
        $props = ['canManageTwoFactor' => $canManageTwoFactor];

        if (Features::canManageTwoFactorAuthentication()) {
            $request->ensureStateIsValid();

            $user = $request->user();
            $props['twoFactorEnabled'] =
                $user->hasEnabledTwoFactorAuthentication();
            $props['requiresConfirmation'] = Features::optionEnabled(
                Features::twoFactorAuthentication(),
                'confirm'
            );
        }

        return Inertia::render('settings/Security', $props);
    }

    /**
     * Update the user's password.
     */
    public function update(
        PasswordUpdateRequest $request
    ): RedirectResponse {
        $request->user()->update([
            'password' => $request->password,
        ]);

        Inertia::flash(
            'toast',
            [
                'type' => 'success',
                'message' => __('Password updated.'),
            ]
        );

        return back();
    }
}
