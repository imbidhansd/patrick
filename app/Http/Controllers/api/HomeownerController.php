<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\CustomErrorCodes;
use App\Models\Homeowner;
use DB;
use Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Homeowner\EmailVerificationOTP;
use App\Mail\Homeowner\PhoneVerificationOTP;
use App\Mail\Homeowner\PasswordResetOTP;
use App\Services\TwilioService;
use App\Models\ServiceCategory;

class HomeownerController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Check if email already exists
            if ($request->has('email')) {
                $existingEmail = Homeowner::where('email', $request->email)->first();
                if ($existingEmail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email already exists',
                        'error_code' => CustomErrorCodes::UserExists
                    ], 400);
                }
            }

            // Check if phone already exists
            if ($request->has('phone')) {
                $existingPhone = Homeowner::where('phone', $request->phone)->first();
                if ($existingPhone) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Phone number already exists',
                        'error_code' => CustomErrorCodes::UserExists
                    ], 400);
                }
            }

            // Validate the request data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:25',
                'password' => 'required|string|confirmed',
                'address_line_1' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:50',
                'zip' => 'nullable|string|max:25',
            ]);

            // Create the homeowner
            $homeowner = Homeowner::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'password' => $validatedData['password'], // Will be hashed by model mutator
                'address_line_1' => $validatedData['address_line_1'] ?? null,
                'city' => $validatedData['city'] ?? null,
                'state' => $validatedData['state'] ?? null,
                'zip' => $validatedData['zip'] ?? null,
                'email_verified' => false,
                'phone_verified' => false,
                'status' => 'active',
            ]);

            $homeowner->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'error_code' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
                'error_code' => CustomErrorCodes::UnhandledException
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            // Find the user by email
            $homeowner = Homeowner::where('email', $validatedData['email'])->first();

            if (!$homeowner || !Hash::check($validatedData['password'], $homeowner->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                    'error_code' => CustomErrorCodes::AuthenticationFailure
                ], 401);
            }

            if ($homeowner->status === 'suspended') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been suspended. Please contact support.'
                ], 403);
            }

            // Update last login timestamp
            $homeowner->update(['last_login_at' => now()]);
            $homeowner->refresh();

            // Generate token (simple token for now)
            $token = base64_encode($homeowner->user_id . '|' . \Illuminate\Support\Str::random(40) . '|' . time());

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                    'token' => $token,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
                'error_code' => CustomErrorCodes::UnhandledException
            ], 500);
        }
    }

    public function sendEmailOTP(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:homeowners,email',
            ]);

            $homeowner = Homeowner::where('email', $validatedData['email'])->first();

            if ($homeowner->email_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is already verified'
                ], 400);
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $homeowner->update([
                'email_otp' => $otp,
                'email_otp_expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP email
            Mail::to($homeowner->email)->send(new EmailVerificationOTP($otp, $homeowner->first_name . ' ' . $homeowner->last_name, 10));

            $responseData = [
                'expires_at' => $homeowner->email_otp_expires_at,
            ];

            // Include OTP in response only in local environment
            if (env('APP_ENV') === 'local') {
                $responseData['otp'] = $otp;
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmailOTP(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:homeowners,email',
                'otp' => 'required|string|size:6',
            ]);

            $homeowner = Homeowner::where('email', $validatedData['email'])->first();

            if ($homeowner->email_verified) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email is already verified',
                    'data' => [
                        'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                    ]
                ], 200);
            }

            if (!$homeowner->email_otp || $homeowner->email_otp !== $validatedData['otp']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            if (now()->greaterThan($homeowner->email_otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired'
                ], 400);
            }

            $homeowner->markEmailAsVerified();
            $homeowner->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully',
                'data' => [
                    'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function sendPhoneOTP(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phone' => 'required|string|exists:homeowners,phone',
            ]);

            $homeowner = Homeowner::where('phone', $validatedData['phone'])->first();

            if ($homeowner->phone_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Phone is already verified'
                ], 400);
            }

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $homeowner->update([
                'phone_otp' => $otp,
                'phone_otp_expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP via Twilio SMS
            $twilioService = new TwilioService();
            $smsSent = $twilioService->sendOTP($homeowner->phone, $otp, 10);

            if (!$smsSent) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send SMS. Please check your Twilio configuration.'
                ], 500);
            }

            $responseData = [
                'expires_at' => $homeowner->phone_otp_expires_at,
            ];

            // Include OTP in response only in local environment
            if (env('APP_ENV') === 'local') {
                $responseData['otp'] = $otp;
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your phone',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyPhoneOTP(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'phone' => 'required|string|exists:homeowners,phone',
                'otp' => 'required|string|size:6',
            ]);

            $homeowner = Homeowner::where('phone', $validatedData['phone'])->first();

            if ($homeowner->phone_verified) {
                return response()->json([
                    'success' => true,
                    'message' => 'Phone is already verified',
                    'data' => [
                        'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                    ]
                ], 200);
            }

            if (!$homeowner->phone_otp || $homeowner->phone_otp !== $validatedData['otp']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            if (now()->greaterThan($homeowner->phone_otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired'
                ], 400);
            }

            $homeowner->markPhoneAsVerified();
            $homeowner->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Phone verified successfully',
                'data' => [
                    'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phone verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:homeowners,user_id',
                'first_name' => 'nullable|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100',
                'phone' => 'nullable|string|max:25',
                'address_line_1' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'state' => 'nullable|string|max:50',
                'zip' => 'nullable|string|max:25',
            ]);

            $homeowner = Homeowner::find($validatedData['user_id']);

            // Check if email is being changed and if it's unique
            if (isset($validatedData['email']) && $validatedData['email'] !== $homeowner->email) {
                $existingEmail = Homeowner::where('email', $validatedData['email'])
                    ->where('user_id', '!=', $homeowner->user_id)
                    ->first();

                if ($existingEmail) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Email is already taken by another user'
                    ], 400);
                }

                // If email changed, mark as unverified
                $homeowner->email_verified = false;
                $homeowner->email_otp = null;
                $homeowner->email_otp_expires_at = null;
            }

            // Check if phone is being changed and if it's unique
            if (isset($validatedData['phone']) && $validatedData['phone'] !== $homeowner->phone) {
                $existingPhone = Homeowner::where('phone', $validatedData['phone'])
                    ->where('user_id', '!=', $homeowner->user_id)
                    ->first();

                if ($existingPhone) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Phone number is already taken by another user'
                    ], 400);
                }

                // If phone changed, mark as unverified
                $homeowner->phone_verified = false;
                $homeowner->phone_otp = null;
                $homeowner->phone_otp_expires_at = null;
            }

            // Update only the fields that are provided
            $homeowner->fill(array_filter($validatedData, function($key) {
                return $key !== 'user_id';
            }, ARRAY_FILTER_USE_KEY));

            $homeowner->save();
            $homeowner->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $homeowner->makeHidden(['password', 'remember_token'])->toArray(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|integer|exists:homeowners,user_id',
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $homeowner = Homeowner::find($validatedData['user_id']);

            // Verify old password
            if (!Hash::check($validatedData['old_password'], $homeowner->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect'
                ], 400);
            }

            // Update password
            $homeowner->password = $validatedData['new_password'];
            $homeowner->save();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'error_code' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password change failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:homeowners,email',
            ]);

            $homeowner = Homeowner::where('email', $validatedData['email'])->first();

            // Generate 6-digit OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $homeowner->update([
                'password_reset_otp' => $otp,
                'password_reset_otp_expires_at' => now()->addMinutes(15),
            ]);

            // Send OTP email
            Mail::to($homeowner->email)->send(new PasswordResetOTP($otp, $homeowner->first_name . ' ' . $homeowner->last_name, 15));

            $responseData = [
                'message' => 'Password reset OTP sent to your email',
                'expires_at' => $homeowner->password_reset_otp_expires_at,
            ];

            // Include OTP in response only in local environment
            if (env('APP_ENV') === 'local') {
                $responseData['otp'] = $otp;
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset OTP sent to your email',
                'data' => $responseData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset OTP',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyResetOTP(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|exists:homeowners,email',
                'otp' => 'required|string|size:6',
            ]);

            $homeowner = Homeowner::where('email', $validatedData['email'])->first();

            if (!$homeowner->password_reset_otp || $homeowner->password_reset_otp !== $validatedData['otp']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP'
                ], 400);
            }

            if (now()->greaterThan($homeowner->password_reset_otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired'
                ], 400);
            }

            // Generate reset token (valid for 15 minutes)
            $resetToken = base64_encode($homeowner->user_id . '|' . \Illuminate\Support\Str::random(60) . '|' . time());

            $homeowner->update([
                'password_reset_token' => $resetToken,
                'password_reset_token_expires_at' => now()->addMinutes(15),
                'password_reset_otp' => null, // Clear OTP after successful verification
                'password_reset_otp_expires_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully',
                'data' => [
                    'reset_token' => $resetToken,
                    'expires_at' => $homeowner->password_reset_token_expires_at,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'reset_token' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $homeowner = Homeowner::where('password_reset_token', $validatedData['reset_token'])->first();

            if (!$homeowner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid reset token'
                ], 400);
            }

            if (now()->greaterThan($homeowner->password_reset_token_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reset token has expired'
                ], 400);
            }

            // Update password and clear reset token
            $homeowner->update([
                'password' => $validatedData['new_password'],
                'password_reset_token' => null,
                'password_reset_token_expires_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search services by keyword
     * Searches across service titles and tags
     */
    public function searchServices(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'search' => 'required|string|min:2',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $searchKeyword = $validatedData['search'];
            $limit = $validatedData['limit'] ?? 50;

            $services = ServiceCategory::select(
                'service_categories.id',
                'service_categories.title',
                'service_categories.sc_code',
                'service_categories.abbr',
                'service_categories.service_category_type_id',
                'sct.title as service_category_type',
                'service_categories.main_category_id',
                'mc.title as main_category',
                'tlc.id as top_level_category_id',
                'tlc.title as top_level_category'
            )
            ->join('service_category_types as sct', 'sct.id', 'service_categories.service_category_type_id')
            ->join('main_categories as mc', 'mc.id', 'service_categories.main_category_id')
            ->leftJoin('top_level_categories as tlc', 'tlc.id', 'service_categories.top_level_category_id')
            ->where('service_categories.status', 'active')
            ->where(function($query) use ($searchKeyword) {
                $query->where('service_categories.title', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('service_categories.tags', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('mc.title', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('mc.tags', 'like', '%' . $searchKeyword . '%')
                      ->orWhere('tlc.title', 'like', '%' . $searchKeyword . '%');
            })
            ->orderBy('service_categories.title', 'asc')
            ->limit($limit)
            ->get();

            if ($services->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'No services found',
                    'data' => [
                        'services' => [],
                        'total_count' => 0
                    ]
                ], 200);
            }

            $servicesData = $services->map(function($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'sc_code' => $service->sc_code,
                    'abbr' => $service->abbr,
                    'service_category_type' => [
                        'id' => $service->service_category_type_id,
                        'title' => $service->service_category_type
                    ],
                    'main_category' => [
                        'id' => $service->main_category_id,
                        'title' => $service->main_category
                    ],
                    'top_level_category' => [
                        'id' => $service->top_level_category_id,
                        'title' => $service->top_level_category
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Services retrieved successfully',
                'data' => [
                    'services' => $servicesData,
                    'total_count' => $services->count()
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search services',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
