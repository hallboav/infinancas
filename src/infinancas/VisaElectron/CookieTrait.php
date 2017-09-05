<?php
namespace InFinancas\VisaElectron;

use Symfony\Component\BrowserKit\Cookie;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
trait CookieTrait
{
    /**
     * Creates a new instance of Cookie.
     *
     * @param string $sessionId 24 characters length.
     *
     * @return Cookie
     */
    public function createCookieFromSessionId($sessionId)
    {
        $string = sprintf('ASP.NET_SessionId=%s; domain=portal.infinancasservicos.com.br; path=/; httponly', $sessionId);
        return Cookie::fromString($string);
    }
}
