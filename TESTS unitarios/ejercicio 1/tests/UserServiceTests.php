<?php

use MyApp\IUserRepository;
use MyApp\User;
use MyApp\UserService;
use MyTests\TestRunner;

// ── Mock manual del repositorio (sin Moq, sin frameworks) ──────────────────
class MockUserRepository implements IUserRepository
{
    private ?User $stubbedUser = null;
    public int $saveCallCount  = 0;

    /** Configura qué devuelve findByEmail */
    public function willReturnUser(?User $user): void
    {
        $this->stubbedUser = $user;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->stubbedUser;
    }

    public function save(User $user): void
    {
        $this->saveCallCount++;
    }
}

return function (TestRunner $t): void {

    $t->suite('UserServiceTests', function (TestRunner $t): void {

        $t->test('Register_NewEmail_SavesUserAndReturnsIt', function (TestRunner $t): void {
            // Arrange
            $repo = new MockUserRepository();
            $repo->willReturnUser(null);          // email no existe
            $service = new UserService($repo);

            // Act
            $user = $service->register('Ana', 'nuevo@ejemplo.com', 'Secret123');

            // Assert
            $t->assertEquals('nuevo@ejemplo.com', $user->email);
            $t->assertEquals('user', $user->role);
            $t->assertEquals(1, $repo->saveCallCount);  // save llamado exactamente 1 vez
        });

        $t->test('Register_DuplicateEmail_ThrowsInvalidArgumentException', function (TestRunner $t): void {
            // Arrange
            $repo = new MockUserRepository();
            $repo->willReturnUser(new User(1, 'Otro', 'existente@ejemplo.com', 'user'));
            $service = new UserService($repo);

            // Act + Assert
            $t->assertThrows(
                \InvalidArgumentException::class,
                fn() => $service->register('Nuevo', 'existente@ejemplo.com', 'pass')
            );

            // save NUNCA debe ser llamado
            $t->assertEquals(0, $repo->saveCallCount);
        });

    });

};
