<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Verifies Firebase Authentication ID tokens on the server.
 * Checks the RS256 signature against Google's public certificates, plus aud, iss, exp, iat, sub and auth_time.
 */
final class FirebaseVerifier
{
    public const CERT_URL = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com';

    /** @param (callable():array<string,string>)|null $keyLoader custom certificate source (used by the test suite) */
    public function __construct(private string $projectId, private $keyLoader = null)
    {
    }

    /**
     * @return array{uid:string,email:string,name:string,picture:string,email_verified:bool,provider:string}
     * @throws \RuntimeException when the token is invalid
     */
    public function verify(string $idToken): array
    {
        if ($this->projectId === '') {
            throw new \RuntimeException('Firebase project ID is not configured.');
        }
        if (substr_count($idToken, '.') !== 2 || strlen($idToken) > 8192) {
            throw new \RuntimeException('Malformed token.');
        }
        $header = json_decode((string)base64_decode(strtr(explode('.', $idToken)[0], '-_', '+/')), true);
        if (!is_array($header) || ($header['alg'] ?? '') !== 'RS256' || empty($header['kid'])) {
            throw new \RuntimeException('Unexpected token header.');
        }

        $keys = [];
        foreach ($this->certificates() as $kid => $pem) {
            $keys[(string)$kid] = new Key($pem, 'RS256');
        }
        if (!isset($keys[(string)$header['kid']])) {
            // Google rotates keys: refresh once before giving up.
            Cache::forget('firebase_certs');
            foreach ($this->certificates() as $kid => $pem) {
                $keys[(string)$kid] = new Key($pem, 'RS256');
            }
        }

        JWT::$leeway = 30;
        try {
            $claims = (array)JWT::decode($idToken, $keys); // verifies signature, exp, nbf and iat
        } catch (\Throwable $e) {
            throw new \RuntimeException('Token verification failed: ' . $e->getMessage());
        }

        $now = time();
        if (($claims['aud'] ?? null) !== $this->projectId) {
            throw new \RuntimeException('Invalid audience.');
        }
        if (($claims['iss'] ?? null) !== 'https://securetoken.google.com/' . $this->projectId) {
            throw new \RuntimeException('Invalid issuer.');
        }
        if (!isset($claims['exp']) || (int)$claims['exp'] <= $now - JWT::$leeway) {
            throw new \RuntimeException('Token expired.');
        }
        if (!isset($claims['iat']) || (int)$claims['iat'] > $now + JWT::$leeway) {
            throw new \RuntimeException('Invalid issue time.');
        }
        if (!isset($claims['auth_time']) || (int)$claims['auth_time'] > $now + JWT::$leeway) {
            throw new \RuntimeException('Invalid authentication time.');
        }
        $sub = $claims['sub'] ?? '';
        if (!is_string($sub) || $sub === '' || strlen($sub) > 128) {
            throw new \RuntimeException('Invalid subject.');
        }
        $email = (string)($claims['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('The account has no e-mail address.');
        }
        $firebase = (array)($claims['firebase'] ?? []);

        return [
            'uid' => $sub,
            'email' => mb_strtolower($email),
            'name' => mb_substr(trim(strip_tags((string)($claims['name'] ?? ''))), 0, 120),
            'picture' => (string)($claims['picture'] ?? ''),
            'email_verified' => ($claims['email_verified'] ?? false) === true,
            'provider' => (string)($firebase['sign_in_provider'] ?? ''),
        ];
    }

    /** @return array<string,string> kid => PEM certificate */
    private function certificates(): array
    {
        if ($this->keyLoader !== null) {
            return ($this->keyLoader)();
        }
        $cached = Cache::get('firebase_certs');
        if (is_array($cached) && $cached) {
            return $cached;
        }
        $ch = curl_init(self::CERT_URL);
        $maxAge = 3600;
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 8,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTPS,
            CURLOPT_HEADERFUNCTION => static function ($ch, string $h) use (&$maxAge): int {
                if (stripos($h, 'cache-control:') === 0 && preg_match('/max-age=(\d+)/', $h, $m)) {
                    $maxAge = (int)$m[1];
                }
                return strlen($h);
            },
        ]);
        $body = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        $certs = is_string($body) ? json_decode($body, true) : null;
        if ($code !== 200 || !is_array($certs) || !$certs) {
            throw new \RuntimeException('Could not download Google public keys.');
        }
        Cache::set('firebase_certs', $certs, max(300, min($maxAge, 86400)));
        return $certs;
    }
}
