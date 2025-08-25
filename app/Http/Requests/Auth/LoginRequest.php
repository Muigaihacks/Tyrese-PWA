<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Log authentication attempt
        Log::info('Laravel authentication attempt started', [
            'email' => $this->input('email'),
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $this->ensureIsNotRateLimited();

        // Check if user exists before attempting authentication
        $user = \App\Models\User::where('email', $this->input('email'))->first();
        
        if (!$user) {
            Log::warning('Laravel authentication failed - User not found', [
                'email' => $this->input('email'),
                'ip' => $this->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Log user found
        Log::info('Laravel authentication - User found', [
            'user_id' => $user->id,
            'email' => $user->email,
            'status' => $user->status ?? 'not_set',
            'email_verified_at' => $user->email_verified_at,
            'timestamp' => now()->toDateTimeString()
        ]);

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            Log::warning('Laravel authentication failed - Invalid credentials', [
                'user_id' => $user->id,
                'email' => $user->email,
                'password_provided' => !empty($this->input('password')),
                'password_hash_exists' => !empty($user->password),
                'ip' => $this->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);
            
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Log successful authentication
        Log::info('Laravel authentication successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $this->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        Log::warning('Laravel authentication rate limited', [
            'email' => $this->input('email'),
            'ip' => $this->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
