# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_SANCTUM_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Authenticate via <b>POST /api/login</b> to receive a Sanctum Bearer token. Pass it as <code>Authorization: Bearer {token}</code> on all protected endpoints.
