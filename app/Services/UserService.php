<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    use ResponseTrait;

    public function createUser(array $userData, $userDetails = [], $attachments = [])
    {
        DB::beginTransaction();
        try {
            $user = User::create($userData);
            if ($attachments) {
                $user->assignAttachment($attachments);
            }
            if ($userDetails) {
                $user->userAddresses()->create($userDetails);
            }
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('User Service creation failed: ' . $e->getMessage(), ['exception' => $e]);
            return null; // or throw an exception if you prefer
        }
    }

    public function updateUser(array $userData, $user, $userDetails = [], $attachments = []): ?User
    {
        DB::beginTransaction();
        try {
            $user->update($userData);

            if ($attachments) {
                $user->assignAttachment($attachments);
            }
            if ($userDetails) {
                $user->userDetail()->update($userDetails);
            }

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('User Service update failed: ' . $e->getMessage(), ['exception' => $e]);

            return null; // or throw an exception if you prefer
        }
    }

    public function validateUser($user, $password): ?JsonResponse
    {
        $password = !Hash::check($password, $user->password);
        return match (true) {
            !$user, $password, $user->is_deleted => self::failResponse(422, 'Invalid Email or Password'),
            !$user->is_active => self::failResponse(
                420,
                'Your account is blocked. Please contact with customer service'
            ),
            default => null,
        };
    }
}
