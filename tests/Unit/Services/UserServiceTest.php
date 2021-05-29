<?php

namespace Tests\Unit\Services;

use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\UserService;
use Laravel\Lumen\Testing\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @param ...$methods
     * @return UserService|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getServiceMock(array $constructorArgs, ...$methods)
    {
        return $this->getMockBuilder(UserService::class)
            ->setConstructorArgs($constructorArgs)
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @param mixed ...$methods
     * @return UserRepository|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getUserRepositoryMock(...$methods)
    {
        return $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * @param mixed ...$methods
     * @return AuthService|\PHPUnit\Framework\MockObject\MockObject
     */
    public function getAuthServiceMock(...$methods)
    {
        return $this->getMockBuilder(AuthService::class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreate()
    {
        $data = [
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'my@email.com',
            'gender' => 'male',
            'birthdate' => '1990-05-06',
            'password' => '123456'
        ];

        $repositoryMock = $this->getUserRepositoryMock();
        $repositoryMock->expects(self::once())->method('create')->with($data);

        $authServiceMock = $this->getAuthServiceMock();

        (new UserService($repositoryMock, $authServiceMock))->create($data);
    }

    public function testLoginByUsernameOrEmail()
    {
        $email = 'my@email.com';
        $password = '123456';

        $authServiceMock = $this->getAuthServiceMock('authenticate');
        $authServiceMock
            ->expects(self::once())
            ->method('authenticate')
            ->with($email, $password);

        $repositoryMock = $this->getUserRepositoryMock();

        (new UserService($repositoryMock, $authServiceMock))
            ->loginByUsernameOrEmail($email, $password);
    }

    public function testDelete()
    {
        $userId = '12345';

        $repositoryMock = $this->getUserRepositoryMock('delete');
        $repositoryMock->expects(self::once())->method('delete')->with($userId);

        $authServiceMock = $this->getAuthServiceMock();

        (new UserService($repositoryMock, $authServiceMock))->delete($userId);
    }
}
