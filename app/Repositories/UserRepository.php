<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 13:08
 */

namespace App\Repositories;


use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Exceptions\OperationFailed;
use App\Exceptions\OperationNotPermitted;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserRepository
{
    /**
     * @param array $data
     * @return User
     * @throws DuplicationExist
     * @throws OperationFailed
     */
    public function create(array $data): User
    {
        $user = new User();
        $user->user_id = Uuid::uuid4()->toString();
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        $user->age = $data['age'];
        $user->gender = $data['gender'];
        $user->birthdate = $data['birthdate'];
        $user->password = Hash::make($data['password']);

        try {
            if (!$user->save()) {
                throw new OperationFailed('failed to save user');
            }
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new DuplicationExist('user already exists');
            }
        }

        return $user;
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return mixed
     * @throws NotFound
     * @throws OperationNotPermitted
     */
    public function getByIdentifierAndPassword(string $identifier, string $password): User
    {
        $qBuilder = User::where(function ($query) use ($identifier) {
            $query->where('user_name', '=', $identifier)
                ->orWhere('email', '=', $identifier);
        });

        $user = $qBuilder->first();

        if (!$user) {
            throw new NotFound('user not found');
        }

        if (!Hash::check($password, $user->password)) {
            throw new OperationNotPermitted('wrong password');
        }

        return $user;
    }

    /**
     * @param string $userId
     * @throws NotFound
     */
    public function delete(string $userId)
    {
        $user = User::where('user_id', '=', $userId)->first();

        if (!$user) {
            throw new NotFound('user not found');
        }

        $user->delete();
    }

    /**
     * @param string $userId
     * @return User
     * @throws DuplicationExist
     * @throws NotFound
     */
    public function restoreDeleted(string $userId): User
    {
        $user = User::withTrashed()->where('user_id', $userId)->first();

        if (!$user) {
            throw new NotFound('user not found or maybe deleted');
        }

        try {
            $user->deletion_token = 'N/A'; // reset deletion_token
            $user->restore();
        } catch (QueryException $exception) {
            if ($exception->getCode() == "23000") {
                throw new DuplicationExist('user cannot be restored');
            }
        }

        return $user;
    }

    /**
     * @param string $userId
     * @param int $reason
     * @param string|null $description
     * @throws NotFound
     * @throws DuplicationExist
     */
    public function ban(string $userId, int $reason, ?string $description = null)
    {
        $user = User::firstWhere('user_id', $userId);

        if (!$user) {
            throw new NotFound();
        }

        $user->ban($reason, $description);
    }

    /**
     * @param string $userId
     * @throws NotFound
     */
    public function unBan(string $userId)
    {
        $user = User::firstWhere('user_id', $userId);

        if (!$user) {
            throw new NotFound();
        }

        $user->unBan();
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getBanned(int $page = 1, int $limit = 10): array
    {
        return User::onlyBanned()->paginate($limit, '*', 'page', $page)->items();
    }
}