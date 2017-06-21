<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_auth",
 *   type="oauth2",
 *   authorizationUrl="http://sso.tld/authorize",
 *   tokenUrl="http://sso.tld/token",
 *   flow="accessCode",
 *   scopes={
 *     "openid offline_access api read:holds": "General API access",
 *     "openid offline_access api patron:read": "Patron specific API access",
 *     "openid offline_access api staff:read": "Staff specific API access"
 *   }
 * )
 */
