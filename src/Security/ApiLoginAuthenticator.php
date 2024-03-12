<?php 

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiLoginAuthenticator extends AbstractAuthenticator
{

    private ?User $user = null;

    public function __construct(private UserRepository $userRepository) {}

    public const AUTH_HEADER = 'X-AUTH-TOKEN';
    public const AUTH_HEADER_START = 'Bearer ';
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::AUTH_HEADER) && str_contains($request->headers->get(self::AUTH_HEADER), self::AUTH_HEADER_START);
    }

    public function authenticate(Request $request): Passport
    {
        $userIdentifier = ltrim(str_replace(self::AUTH_HEADER_START, '', $request->headers->get(self::AUTH_HEADER)));
        
        $this->user = $this->userRepository->findOneBy(['apiToken' => $userIdentifier]);

        return new SelfValidatingPassport(new UserBadge($userIdentifier));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if (!$request->headers->get(self::AUTH_HEADER)) {
            return new JsonResponse($this->message('No API Token provided.'), Response::HTTP_UNAUTHORIZED);
        }

        if ($this->user === null) {
            return new JsonResponse($this->message('Invalid credentials'), Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse($this->message('Access denied.'), Response::HTTP_UNAUTHORIZED);
    }

    private function message(string $message = '', bool $success = false) {
        return compact('message', 'success');
    }
}