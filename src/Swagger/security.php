<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_auth",
 *   type="oauth2",
 *   authorizationUrl="https://isso.nypl.org/oauth/authorize",
 *   tokenUrl="https://isso.nypl.org/oauth/token",
 *   flow="accessCode",
 *   scopes={
 *     "openid offline_access api read:holds": "General API access",
 *     "openid offline_access api patron:read": "Patron specific API access",
 *     "openid offline_access api staff:read": "Staff specific API access",
 *     "openid offline_access api read:hold_requests": "Holds specific API access"
 *   }
 * )
 */
