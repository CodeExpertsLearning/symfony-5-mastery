<?php
namespace App\Service\Api\Token;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class Jwt
{
	private $key;
	private $host;

	public function __construct(string $key, string $host)
	{
		$this->key = $key;
		$this->host = $host;
	}

	public function generate(array $claimsData)
	{
		$signer = new Sha256();
		$time = time();

		$token = (new Builder())->issuedBy($this->host)
		                        ->permittedFor($this->host)
		                        ->identifiedBy($claimsData['uid'], true)
		                        ->issuedAt($time)
		                        ->canOnlyBeUsedAfter($time + 60)
		                        ->expiresAt($time + 3600);

		foreach ($claimsData as $key => $data) {
			$token->withClaim($key, $data);
		}

		return $token->getToken($signer, new Key($this->key));
	}
}