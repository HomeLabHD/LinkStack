<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirectToProvider(String $provider)
    {
        return \Socialite::driver($provider)->redirect();
    }

    public function providerCallback(String $provider)
    {
        try {
            $social_user = \Socialite::driver($provider)->user();

            // 1) Primary identity match is the immutable provider subject id (OIDC `sub`),
            //    never the email — an email can be reassigned upstream and must not be a
            //    login key on its own.
            $account = SocialAccount::where([
                'provider_name' => $provider,
                'provider_id'   => $social_user->getId(),
            ])->first();

            if ($account) {
                auth()->login($account->user);
                $this->rememberSsoSession($provider, $social_user);

                return redirect('/studio/index');
            }

            // Beyond this point we fall back to the email to bridge/provision an account,
            // so an email is required and — to avoid account takeover via an unverified,
            // attacker-controlled address — it must be verified by the provider.
            $email = $social_user->getEmail();

            if (empty($email)) {
                return redirect()->route('login')->withErrors(
                    __('messages.Your identity provider did not release an email address.')
                );
            }

            if ($this->requireVerifiedEmail() && $this->emailIsUnverified($social_user)) {
                return redirect()->route('login')->withErrors(
                    __('messages.Your email address is not verified with the identity provider.')
                );
            }

            // 2) Bridge to an existing local user by (verified) email, else 3) provision a
            //    new user mirroring the shape of a locally-registered account (role, block,
            //    a hashed random password, a valid unique slug).
            $user = User::where('email', $email)->first();

            if (! $user) {
                $user = User::create([
                    'name'              => $social_user->getName() ?: Str::before($email, '@'),
                    'email'             => $email,
                    'image'             => $social_user->getAvatar(),
                    'littlelink_name'   => $this->uniqueLittlelinkName(
                        $social_user->getNickname() ?: Str::before($email, '@')
                    ),
                    'password'          => Hash::make(Str::random(64)),
                    'email_verified_at' => now(),
                ]);

                $user->role  = 'user';
                $user->block = env('MANUAL_USER_VERIFICATION') == true ? 'yes' : 'no';
                $user->save();
            }

            // Link the social identity so subsequent logins match on the immutable sub.
            $user->socialAccounts()->create([
                'provider_id'   => $social_user->getId(),
                'provider_name' => $provider,
            ]);

            auth()->login($user);
            $this->rememberSsoSession($provider, $social_user);

            return redirect('/studio/index');
        } catch (\Throwable $e) {
            return redirect()->route('login')->withErrors($e->getMessage());
        }
    }

    /**
     * Post-logout landing for RP-initiated logout. The IdP redirects here after ending its
     * session; validate the state logout() minted (consumed on first use, so replays fail),
     * then complete the local logout. Fails closed if the state can't be validated.
     */
    public function logoutCallback(Request $request)
    {
        $oidc = $request->session()->get('oidc_logout');
        $provider = is_array($oidc) && ! empty($oidc['provider']) ? $oidc['provider'] : 'openidconnect';

        try {
            if (! \Socialite::driver($provider)->validateLogoutState($request)) {
                abort(403);
            }
        } catch (\Throwable $e) {
            abort(403);
        }

        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Whether a provider-verified email is required before an email is trusted for
     * bridging or provisioning. On by default; opt out with OIDC_REQUIRE_VERIFIED_EMAIL=false.
     */
    protected function requireVerifiedEmail(): bool
    {
        return filter_var(env('OIDC_REQUIRE_VERIFIED_EMAIL', true), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * True only when the provider explicitly asserts the email is NOT verified. A missing
     * claim is treated as verified so providers that never send `email_verified` still work.
     */
    protected function emailIsUnverified($social_user): bool
    {
        $claims = method_exists($social_user, 'getRaw') ? (array) $social_user->getRaw() : [];

        if (! array_key_exists('email_verified', $claims)) {
            return false;
        }

        return ! filter_var($claims['email_verified'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Derive a slug that satisfies the same rules registration enforces
     * (max 50, unique, /^[\p{L}0-9-_]+$/u), disambiguating on collision.
     */
    protected function uniqueLittlelinkName(string $seed): string
    {
        $base = preg_replace('/[^\p{L}0-9\-_]/u', '', $seed);
        $base = $base !== '' ? mb_substr($base, 0, 50) : 'user';

        $name = $base;
        $suffix = 1;

        while (User::where('littlelink_name', $name)->exists()) {
            $tail = '-'.$suffix++;
            $name = mb_substr($base, 0, 50 - mb_strlen($tail)).$tail;
        }

        return $name;
    }

    /**
     * Stash the OIDC id_token so logout can perform an RP-initiated end-session round-trip.
     */
    protected function rememberSsoSession(string $provider, $social_user): void
    {
        if (! Str::startsWith($provider, ['oidc', 'openidconnect'])) {
            return;
        }

        session()->put('oidc_logout', [
            'provider' => $provider,
            'id_token' => $social_user->accessTokenResponseBody['id_token'] ?? null,
        ]);
    }
}
