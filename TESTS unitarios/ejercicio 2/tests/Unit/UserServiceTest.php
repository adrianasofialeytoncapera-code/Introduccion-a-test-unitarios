<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class UserServiceTest extends TestCase
{
    private MockObject $repositoryMock;
    private UserService $service;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(UserRepositoryInterface::class);
        $this->service = new UserService($this->repositoryMock);
    }

    public function test_register_saves_new_user(): void
    {
        $this->repositoryMock
            ->expects($this->once())
            ->method('findByEmail')
            ->willReturn(null);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $user = $this->service->register('nuevo@ejemplo.com', 'Secret123');

        $this->assertEquals('nuevo@ejemplo.com', $user['email']);
        $this->assertEquals('user', $user['role']);
        $this->assertTrue(password_verify('Secret123', $user['password']));
    }

    public function test_register_throws_when_email_already_exists(): void
    {
        $this->repositoryMock
            ->method('findByEmail')
            ->willReturn(['id' => 1, 'email' => 'existente@ejemplo.com']);

        $this->expectException(\RuntimeException::class);

        $this->service->register('existente@ejemplo.com', 'Secret123');
    }
}
