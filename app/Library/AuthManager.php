<?php

namespace WjCrypto\Library;

use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use WjCrypto\Model\UserModel;

class AuthManager
{
    private const KEY_AUTH = 'HTTP_AUTHORIZATION';
    private const KEY_USER = 0;
    private const KEY_PASSWD = 1;

    private static $loggedUser = null;

    /**
     * @param ServerRequestInterface $request
     * @throws UnauthorizedException
     * @throws BadRequestException
     */
    public static function validateRequest(ServerRequestInterface $request): void
    {
        $serverParams = $request->getServerParams();

        if (
            empty($serverParams) ||
            !array_key_exists(self::KEY_AUTH, $serverParams) ||
            empty($serverParams[self::KEY_AUTH])
        ) {
            throw new BadRequestException('Bad Request: Missing authentication key');
        }

        $token = $serverParams[self::KEY_AUTH];

        if (strpos($token, 'Basic ') < 0) {
            throw new BadRequestException('Bad Request: Only basic auth supported');
        }

        $decodedToken = base64_decode(substr($token, 6));
        $loginInfo = explode(':', $decodedToken);

        if (count($loginInfo) !== 2) {
            throw new BadRequestException('Bad Request: Failed to decode token');
        }

        $user = new UserModel();

        if (!$user->fetchInfo($loginInfo[self::KEY_USER])) {
            throw new UnauthorizedException('Unauthorized: Usuario inixistente');
        }

        if (!password_verify($loginInfo[self::KEY_PASSWD], $user->getPassword())) {
            throw new UnauthorizedException('Unauthorized: Senha incorreta');
        }

        $user->fetchInfo($loginInfo[self::KEY_USER]);

        self::$loggedUser = $user;
    }

    public static function getLoggedUser(): UserModel
    {
        return self::$loggedUser;
    }
}