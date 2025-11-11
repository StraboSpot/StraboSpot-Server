<?php
/**
 * File: quick-jwt.php
 * Description: QuickJWT class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

class QuickJWT {
	/**
	 * Sign a payload to create a JWT token.
	 *
	 * @param array $payload The payload as an associative array.
	 * @param string $secret The secret used to sign the JWT.
	 * @return string The signed JWT token.
	 */
	public static function sign($payload, $secret) {
		// Define the header
		$header = ['alg' => 'HS256', 'typ' => 'JWT'];

		// Encode the header and payload as base64url
		$base64UrlHeader = self::base64url_encode(json_encode($header));
		$base64UrlPayload = self::base64url_encode(json_encode($payload));

		// Create the signature
		$data = "$base64UrlHeader.$base64UrlPayload";
		$signature = hash_hmac('sha256', $data, $secret, true);
		$base64UrlSignature = self::base64url_encode($signature);

		// Return the full JWT token
		return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
	}

	/**
	 * Decode a JWT payload.
	 *
	 * @param string $jwt The JWT token string.
	 * @return array|null Associative array of the payload, or null if invalid.
	 */
	public static function decode($jwt) {
		$parts = explode('.', $jwt);
		if (count($parts) !== 3) {
			return null; // Invalid JWT structure
		}

		$payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1]));
		$decodedPayload = json_decode($payload, true);

		if (json_last_error() === JSON_ERROR_NONE) {
			return $decodedPayload;
		}

		return null;
	}

	/**
	 * Validate a JWT token's signature.
	 *
	 * @param string $secret The secret used to sign the JWT.
	 * @param string $jwt The JWT token string.
	 * @return bool True if the JWT signature is valid, false otherwise.
	 */
	public static function validate($secret, $jwt) {
		$parts = explode('.', $jwt);
		if (count($parts) !== 3) {
			return false;
		}

		$base64UrlHeader = $parts[0];
		$base64UrlPayload = $parts[1];
		$signature = $parts[2];

		$data = "$base64UrlHeader.$base64UrlPayload";
		$expectedSignature = hash_hmac('sha256', $data, $secret, true);
		$expectedBase64UrlSignature = self::base64url_encode($expectedSignature);

		return hash_equals($signature, $expectedBase64UrlSignature);
	}

	/**
	 * Helper function to encode data in base64url format.
	 *
	 * @param string $data The data to encode.
	 * @return string The base64url encoded string.
	 */
	private static function base64url_encode($data) {
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}
