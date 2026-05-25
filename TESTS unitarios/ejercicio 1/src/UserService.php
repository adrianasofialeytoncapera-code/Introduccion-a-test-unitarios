<?php

namespace MyApp;

class User
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $role
    ) {}
}

interface IUserRepository
{
    public function findByEmail(string $email): ?User;
    public function save(User $user): void;
}

class UserService
{
    public function __construct(private IUserRepository $repo) {}

    public function register(string $name, string $email, string $password): User
    {
        $existing = $this->repo->findByEmail($email);
        if ($existing !== null) {
            throw new \InvalidArgumentException("El email '{$email}' ya está registrado.");
        }

        $user = new User(0, $name, $email, 'user');
        $this->repo->save($user);
        return $user;
    }
}
