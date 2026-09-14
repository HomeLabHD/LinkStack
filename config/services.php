<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect'      => env('FACEBOOK_CALLBACK_URL'),
    ],
    'twitter' => [
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('TWITTER_CALLBACK_URL'),
    ],
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_CALLBACK_URL'),
    ],
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => 'http://example.com/callback-url',
    ],

    // Generic OpenID Connect (socialiteproviders/openidconnect), driver "openidconnect".
    // Points at any OIDC issuer via discovery ({OIDC_ISSUER}/.well-known/openid-configuration).
    // Every id_token is validated (signature/iss/aud/azp/exp/nonce/at_hash) with PKCE and
    // automatic JWKS key-rotation. The redirect below is only a config-load fallback:
    // SocialLoginController sets it per request from the current host so one instance works
    // across every apex it serves. Set OIDC_REDIRECT_URL to pin a single redirect instead.
    'openidconnect' => [
        'base_url'                 => env('OIDC_ISSUER'),
        'client_id'                => env('OIDC_CLIENT_ID'),
        'client_secret'            => env('OIDC_CLIENT_SECRET'),
        // Deliberately no fallback: SocialLoginController derives the callback from
        // the request host when this is empty, which is what lets one instance serve
        // several apexes. Defaulting it to APP_URL makes that derivation unreachable
        // and sends every visitor back to a single domain.
        'redirect'                 => env('OIDC_REDIRECT_URL'),
        'scopes'                   => env('OIDC_SCOPES', 'openid profile email'),
        'require_email'            => filter_var(env('OIDC_REQUIRE_EMAIL', false), FILTER_VALIDATE_BOOLEAN),
        'post_logout_redirect_uri' => env('OIDC_POST_LOGOUT_REDIRECT_URL', env('APP_URL')),
    ],

];
