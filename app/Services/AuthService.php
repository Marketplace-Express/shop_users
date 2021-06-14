<?php
/**
 * User: Wajdi Jurry
 * Date: 2020/08/26
 * Time: 22:38
 */

namespace App\Services;


use App\Exceptions\NotFound;
use App\Exceptions\OperationFailed;
use App\Exceptions\OperationNotPermitted;
use App\Http\Controllers\Annotations\Permissions;
use App\Models\Token;
use App\Models\User;
use App\Policies\Policy;
use App\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Illuminate\Contracts\Auth\Access\Gate;

/**
 * Class AuthService
 * @package App\Services
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
     * @var object
     */
    private $decodedToken;

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
     * @param string|null $identifier
     * @param string|null $password
     * @param string|null $refreshToken
     * @return Token
     * @throws NotFound
     * @throws OperationFailed
     * @throws OperationNotPermitted
     * @throws \Throwable
     */
    public function authenticate(?string $identifier, ?string $password, ?string $refreshToken = null): Token
    {
        if (empty($identifier) && empty($password) && empty($refreshToken)) {
            throw new OperationNotPermitted('empty credentials');
        }

        if (!empty($identifier) && !empty($password)) {
            $user = $this->userRepository->getByIdentifierAndPassword($identifier, $password);
        } elseif (!empty($refreshToken)) {
            $this->jwt::decode($refreshToken, $this->getPublicKey(), [env('JWT_ALG')]);
            $user = $this->userRepository->getByRefreshToken($refreshToken);
        }

        $token = $this->getUserToken($user);

        if (!empty($token)) {
            if ($this->isAuthenticated($token->access_token)) {
                return $token;
            } else {
                $token->delete();
            }
        }

        // Generate new CSRF Token
        $csrfToken = $this->generateCsrfToken();

        $accessToken = $this->generateToken([
            'sub' => $user->email,
            'iss' => env('APP_NAME'),
            'exp' => time() + env('JWT_TTL') * 3600, // hours to seconds,
            'user' => $user->toTokenArrayData(),
            'csrf_token' => $csrfToken
        ]);

        $refreshToken = $this->generateToken([
            'sub' => $user->email,
            'iss' => env('APP_NAME'),
            'exp' => time() + env('JWT_REFRESH_TTL') * 3600, // hours to seconds
        ]);

        return $this->saveGeneratedToken($user->getAuthIdentifier(), $accessToken, $refreshToken, $csrfToken);
    }

    /**
     * @param array $user
     * @param array $permissions
     * @param string $policyModelName
     * @param string $operator
     * @param array $authorizeData
     * @return bool
     */
    public function isAuthorized(
        array $user,
        array $permissions,
        string $policyModelName,
        string $operator = Permissions::OPERATOR_AND,
        array $authorizeData = []
    ): bool {

        if (empty($policyModelName)) {
            throw new \InvalidArgumentException('policy model is not provided', 400);
        }

        if (empty($user) || empty($user['user_id'])) {
            return false;
        }

        $user = User::withoutBanned()->firstWhere('user_id', $user['user_id']);

        if (!$user) {
            return false;
        }

        $policyModel = Policy::getPolicyModel($policyModelName, $authorizeData);

        if ($operator == Permissions::OPERATOR_AND) {
            if (!app(Gate::class)->forUser($user)->check($permissions, [$policyModel])) {
                return false;
            }
        } elseif ($operator == Permissions::OPERATOR_OR) {
            if (!app(Gate::class)->forUser($user)->any($permissions, [$policyModel])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string|null $token
     * @return bool
     */
    public function isAuthenticated(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        try {
            $token = $this->decodedToken = $this->decodeToken($token);
        } catch (\Throwable $exception) {
            return false;
        }

        if ($token->csrf_token !== request()->header('csrf-token')) {
            return false;
        }

        return true;
    }

    /**
     * @param string $token
     * @return object
     */
    public function decodeToken(string $token)
    {
        return $this->jwt::decode($token, $this->getPublicKey(), [env('JWT_ALG')]);
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
     * @param string $refreshToken
     * @param string $csrfToken
     * @return Token
     * @throws OperationFailed
     */
    private function saveGeneratedToken(string $userId, string $accessToken, string $refreshToken, string $csrfToken): Token
    {
        $token = new Token([
            'user_id' => $userId,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'csrf_token' => $csrfToken,
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
        return $token::firstWhere(
            $token->getAuthIdentifierName(), $user->getAuthIdentifier()
        );
    }

    /**
     * @return object
     */
    public function getDecodedToken(): object
    {
        return $this->decodedToken ?? new \stdClass();
    }

    /**
     * @return string
     */
    private function generateCsrfToken(): string
    {
        return str_shuffle('0a1b2c3d4e5f6g7h8i9j');
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
    public function getPublicKey()
    {
        return file_get_contents(app()->basePath() . DIRECTORY_SEPARATOR . env('JWT_PUBLIC_KEY'));
    }
}