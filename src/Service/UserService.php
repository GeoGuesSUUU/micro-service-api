<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistApiException;
use App\Exception\UserNotFoundApiException;
use App\Exception\UserNotValidApiException;
use App\Repository\UserRepository;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function auth(User $body): array
    {
        if (
            is_null($body->getEmail()) ||
            is_null($body->getPlainPassword())
        ) throw new UserNotValidApiException();

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $body->getEmail()]);
        if (is_null($user)) throw new UserNotFoundApiException();

        $isValid = $this->passwordHasher->isPasswordValid($user, $body->getPlainPassword());
        if (!$isValid) throw new UserNotValidApiException();

        $token = $this->jwtManager->create($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function register(User $data): array
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['email' => $data->getEmail()]);
        if (!is_null($user ?? null)) throw new UserAlreadyExistApiException();

        $data->setPassword($this->passwordHasher->hashPassword($data, $data->getPlainPassword()));

        $this->userRepository->save($data, true);

        $token = $this->jwtManager->create($data);
        return [
            'user' => $data,
            'token' => $token
        ];
    }

    public function getUserByToken(string $token): User | null
    {
        try {
            $tokenData = $this->jwtManager->parse($token);
        } catch (Exception $ex) {
            return null;
        }
        if (!is_null($tokenData) && !is_null($tokenData['username'] ?? null)) {
            return $this->userRepository->findOneBy([ 'email' => $tokenData['username']]);
        }
        return null;
    }

    public function update(array $data): array
    {

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => $data['user']['id']]);
        if (is_null($user)) throw new UserNotFoundApiException();

        if (!is_null($data['user']['name'] ?? null)) $user->setName($data['user']['name']);
        if (!is_null($data['user']['password'] ?? null)) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['user']['password']));
        }
        $this->userRepository->save($user, true);

        $token = $this->jwtManager->create($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function get(int $id): User|null
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }
}
