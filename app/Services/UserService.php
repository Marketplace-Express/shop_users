<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/22
 * Time: 12:04
 */

namespace App\Services;


use App\Exceptions\DuplicationExist;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;

class UserService
{
    /** @var UserRepository */
    private $repository;

    /** @var AuthService */
    private $authService;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     * @param AuthService $authService
     */
    public function __construct(UserRepository $repository, AuthService $authService)
    {
        $this->repository = $repository;
        $this->authService = $authService;
    }

    /**
     * @param array $data
     * @return \App\Models\User
     * @throws \App\Exceptions\DuplicationExist
     * @throws \App\Exceptions\OperationFailed
     */
    public function create(array $data = [])
    {
        return $this->repository->create($data);
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return \App\Models\Token|mixed
     * @throws \App\Exceptions\DuplicationExist
     * @throws \App\Exceptions\NotFound
     * @throws \App\Exceptions\OperationFailed
     * @throws \App\Exceptions\OperationNotPermitted
     */
    public function loginByUsernameOrEmail(string $identifier, string $password)
    {
        return $this->authService->authenticate($identifier, $password);
    }

    /**
     * @param string $userId
     * @throws \App\Exceptions\NotFound
     */
    public function delete(string $userId)
    {
        $this->repository->delete($userId);
    }

    /**
     * @param string $userId
     * @return \App\Models\User
     * @throws \App\Exceptions\DuplicationExist
     * @throws \App\Exceptions\NotFound
     */
    public function restore(string $userId)
    {
        return $this->repository->restoreDeleted($userId);
    }

    /**
     * @param array $data
     * @throws \App\Exceptions\NotFound
     * @throws \App\Exceptions\DuplicationExist
     */
    public function ban(array $data = [])
    {
        try {
            $this->repository->ban($data['userId'], $data['reason'], $data['description']);
        } catch (QueryException $exception) {
            if ($exception->getCode() == "23000") {
                throw new DuplicationExist('user is already banned', 400);
            }
        }
    }

    /**
     * @param string $userId
     * @throws \App\Exceptions\NotFound
     */
    public function unBan(string $userId)
    {
        $this->repository->unBan($userId);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getBanned(int $page, int $limit)
    {
        return $this->repository->getBanned($page, $limit);
    }

    /**
     * @param array $usersIds
     * @return array
     */
    public function getByIds(array $usersIds): array
    {
        return $this->repository->getByIds($usersIds);
    }
}