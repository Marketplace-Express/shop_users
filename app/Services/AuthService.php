<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 22:38
 */

namespace App\Services;


use App\Exceptions\DuplicationExist;
use App\Exceptions\OperationFailed;
use App\Exceptions\OperationNotPermitted;
use App\Models\Interfaces\TokenArrayDataInterface;
use App\Models\Token;
use App\Models\User;
use App\Repositories\UserRepository;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

/**
 * Class AuthServiceProvider
 * @package App\Providers\Auth
 */
class AuthService implements AuthInterface
{
    /**
     * @var JWT
     */
    private $jwt;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var Token
     */
    private $userToken;

    /**
     * AuthServiceProvider constructor.
     * @param UserRepository $repository
     * @param JWT $auth
     */
    public function __construct(UserRepository $repository, JWT $auth)
    {
        $this->userRepository = $repository;
        $this->jwt = $auth;
    }

    /**
     * @param string $identifier
     * @param string $password
     * @return Token
     * @throws OperationFailed
     * @throws OperationNotPermitted
     * @throws \App\Exceptions\NotFound
     */
    public function authenticate(string $identifier, string $password): Token
    {
        if (empty($identifier) || empty($password)) {
            throw new OperationNotPermitted('empty credentials');
        }

        $user = $this->userRepository->getByIdentifierAndPassword($identifier, $password);

        if ($this->isAuthenticated($user, $this->getPublicKey())) {
            return $this->userToken ?? $this->getUserToken($user);
        }

        $generatedToken = $this->generateToken([
            'sub' => $user->email,
            'iss' => env('APP_NAME'),
            'exp' => time() + env('JWT_TTL') * 3600, // hours to seconds,
            'user' => ($user instanceof TokenArrayDataInterface) ? $user->toTokenArrayData() : $user->toArray()
        ]);

        return $this->saveGeneratedToken($user->getAuthIdentifier(), $generatedToken);
    }

    /**
     * @param User $user
     * @param $permission
     * @return bool
     */
    public function isAuthorized(User $user, $permission): bool
    {
        return $user->can($permission);
    }

    /**
     * @param User $user
     * @param $publicKey
     * @return bool
     */
    public function isAuthenticated(User $user, $publicKey): bool
    {
        if (empty($publicKey)) {
            return false;
        }

        $token = $this->userToken = $this->getUserToken($user);

        if (!$token) {
            return false;
        }

        try {
            $this->jwt::decode($token->token, $this->getPublicKey(), [env('JWT_ALG')]);
        } catch (ExpiredException $exception) {
            $token->delete(); // delete token if exists
            return false;
        }

        return true;
    }

    /**
     * @param $parameters
     * @return string
     */
    private function generateToken($parameters): string
    {
        return $this->jwt::encode($parameters, $this->getPrivateKey(), env('JWT_ALG'));
    }

    /**
     * @param string $userId
     * @param string $token
     * @return Token
     * @throws OperationFailed
     */
    private function saveGeneratedToken(string $userId, string $token): Token
    {
        $token = new Token([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => time() + env('JWT_TTL') * 3600 //seconds
        ]);

        try {
            $token->saveOrFail();
        } catch (\Throwable $exception) {
            if ($exception->getCode() == "23000") { // duplicate exists
                return Token::where($token->getAuthIdentifier(), $userId)->first();
            }
            throw new OperationFailed('unable to authenticate user');
        }

        return $token;
    }

    /**
     * @param User $user
     * @return Token|null
     */
    private function getUserToken(User $user): ?Token
    {
        $token = new Token();
        return $this->userToken = $token::where(
            $token->getAuthIdentifierName(), $user->getAuthIdentifier()
        )->first();
    }

    /**
     * @return false|string
     */
    private function getPrivateKey()
    {
        return file_get_contents(app()->basePath() . DIRECTORY_SEPARATOR . env('JWT_PRIVATE_KEY'));
    }

    /**
     * @return false|string
     */
    private function getPublicKey()
    {
        return file_get_contents(app()->basePath() . DIRECTORY_SEPARATOR . env('JWT_PUBLIC_KEY'));
    }
}