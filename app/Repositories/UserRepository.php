<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 13:08
 */

namespace App\Repositories;


use App\Enums\BanUserReasonsEnum;
use App\Exceptions\DuplicationExist;
use App\Exceptions\NotFound;
use App\Exceptions\OperationFailed;
use App\Exceptions\OperationNotPermitted;
use App\Models\BannedUser;
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
        $user->gender = $data['gender'];
        $user->birthdate = $data['birthdate'];
        $user->password = Hash::make($data['password']);
        $this->generateUserName($user);
        $this->calculateAge($user);

        try {
            if (!$user->save()) {
                throw new OperationFailed('failed to save user');
            }
        } catch (QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new DuplicationExist('user already exists');
            } else {
                throw $exception;
            }
        }

        return $user;
    }

    /**
     * @param User $user
     */
    protected function generateUserName(User $user)
    {
        $user->user_name = str_replace(' ', '.', $user->first_name . ' ' . $user->last_name);

        if ($user->isUserNameExists()) {
            $user->user_name .= '.' . substr($user->user_id, 9, 4);
        }
    }

    /**
     * @param User $user
     */
    public function calculateAge(User $user)
    {
        $user->age = date_diff(date_create($user->birthdate), date_create('now'))->y;
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
        $user = User::withoutBanned()->where(function ($query) use ($identifier) {
            $query->where('user_name', '=', $identifier)
                ->orWhere('email', '=', $identifier);
        })->first();

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
     * @return User
     * @throws NotFound
     */
    public function getById(string $userId): User
    {
        $user = User::firstWhere('user_id', $userId);

        if (!$user) {
            throw new NotFound('user not found or maybe deleted');
        }

        return $user;
    }

    /**
     * @param array $usersIds
     * @return array
     */
    public function getByIds(array $usersIds): array
    {
        return User::query()->findMany($usersIds)->all();
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
        $bannedUsers = User::onlyBanned()->paginate($limit, '*', 'page', $page)->items();

        return array_map(function ($user) {
            $banInfo = BannedUser::firstWhere('user_id', $user->user_id);
            return [
                'reason' => BanUserReasonsEnum::getKey($banInfo->reason),
                'description' => $banInfo->description,
                'user' => $user
            ];
        }, $bannedUsers);
    }
}