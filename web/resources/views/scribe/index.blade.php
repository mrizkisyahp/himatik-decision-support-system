<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('images/Logo_HIMATIK-DIC1vDRy.png') }}" type="image/png">

    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Laravel API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('vendor/scribe/css/theme-default.style.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('vendor/scribe/css/theme-default.print.css') }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset('vendor/scribe/js/tryitout-5.10.0.js') }}"></script>

    <script src="{{ asset('vendor/scribe/js/theme-default-5.10.0.js') }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset('vendor/scribe/images/navbar.png') }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authentication" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authentication">
                    <a href="#authentication">Authentication</a>
                </li>
                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="authentication-POSTapi-login">
                                <a href="#authentication-POSTapi-login">Login</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-logout">
                                <a href="#authentication-POSTapi-logout">Logout</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-email-verify-otp">
                                <a href="#authentication-POSTapi-email-verify-otp">Verify Email OTP</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="authentication-POSTapi-email-resend-otp">
                                <a href="#authentication-POSTapi-email-resend-otp">Resend Email OTP</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-candidate" class="tocify-header">
                <li class="tocify-item level-1" data-unique="candidate">
                    <a href="#candidate">Candidate</a>
                </li>
                                    <ul id="tocify-subheader-candidate" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="candidate-GETapi-departments">
                                <a href="#candidate-GETapi-departments">Get Departments List</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="candidate-POSTapi-register">
                                <a href="#candidate-POSTapi-register">Register Candidate Account</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="candidate-GETapi-me">
                                <a href="#candidate-GETapi-me">Get Candidate Profile, Schedule & Results</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="candidate-POSTapi-candidate-profile">
                                <a href="#candidate-POSTapi-candidate-profile">Submit Candidate Profile</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="candidate-GETapi-schedules">
                                <a href="#candidate-GETapi-schedules">Get Available Interview Schedules</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="candidate-POSTapi-schedules-book">
                                <a href="#candidate-POSTapi-schedules-book">Book Interview Schedule</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-interviewer" class="tocify-header">
                <li class="tocify-item level-1" data-unique="interviewer">
                    <a href="#interviewer">Interviewer</a>
                </li>
                                    <ul id="tocify-subheader-interviewer" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="interviewer-GETapi-interviewer-schedules">
                                <a href="#interviewer-GETapi-interviewer-schedules">Get Interviewer's Schedules</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="interviewer-GETapi-interviewer-grade--candidate_id---department_id-">
                                <a href="#interviewer-GETapi-interviewer-grade--candidate_id---department_id-">Get Grading Details</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="interviewer-POSTapi-interviewer-grade--candidate_id---department_id-">
                                <a href="#interviewer-POSTapi-interviewer-grade--candidate_id---department_id-">Submit Evaluation Scores</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-admin" class="tocify-header">
                <li class="tocify-item level-1" data-unique="admin">
                    <a href="#admin">Admin</a>
                </li>
                                    <ul id="tocify-subheader-admin" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="admin-POSTapi-interviewer-decide--candidate_id-">
                                <a href="#admin-POSTapi-interviewer-decide--candidate_id-">Decide Candidate (Accept/Reject)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-stats">
                                <a href="#admin-GETapi-admin-stats">Get Dashboard Stats</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-rankings--department_id-">
                                <a href="#admin-GETapi-admin-rankings--department_id-">Get DSS Rankings for Department</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-decide--candidate_id-">
                                <a href="#admin-POSTapi-admin-decide--candidate_id-">Decide Candidate (Accept/Reject)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-publish">
                                <a href="#admin-POSTapi-admin-publish">Toggle Announcement Publish State</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-departments">
                                <a href="#admin-POSTapi-admin-departments">Create Department</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-PUTapi-admin-departments--department_id-">
                                <a href="#admin-PUTapi-admin-departments--department_id-">Update Department</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-DELETEapi-admin-departments--department_id-">
                                <a href="#admin-DELETEapi-admin-departments--department_id-">Delete Department</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-schedules">
                                <a href="#admin-GETapi-admin-schedules">Get All Schedules (Admin)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-schedules">
                                <a href="#admin-POSTapi-admin-schedules">Create Interview Schedule</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-PUTapi-admin-schedules--schedule_id-">
                                <a href="#admin-PUTapi-admin-schedules--schedule_id-">Update Interview Schedule</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-DELETEapi-admin-schedules--schedule_id-">
                                <a href="#admin-DELETEapi-admin-schedules--schedule_id-">Delete Interview Schedule</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-criteria--department_id-">
                                <a href="#admin-GETapi-admin-criteria--department_id-">Get Evaluation Criteria</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-criteria--department_id-">
                                <a href="#admin-POSTapi-admin-criteria--department_id-">Create Evaluation Criterion</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-PUTapi-admin-criteria--criterion_id-">
                                <a href="#admin-PUTapi-admin-criteria--criterion_id-">Update Evaluation Criterion</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-DELETEapi-admin-criteria--criterion_id-">
                                <a href="#admin-DELETEapi-admin-criteria--criterion_id-">Delete Evaluation Criterion</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-admin-interviewers">
                                <a href="#admin-GETapi-admin-interviewers">Get All Interviewers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-admin-interviewers">
                                <a href="#admin-POSTapi-admin-interviewers">Create Interviewer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-PUTapi-admin-interviewers--user_id-">
                                <a href="#admin-PUTapi-admin-interviewers--user_id-">Update Interviewer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-DELETEapi-admin-interviewers--user_id-">
                                <a href="#admin-DELETEapi-admin-interviewers--user_id-">Delete Interviewer</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-public" class="tocify-header">
                <li class="tocify-item level-1" data-unique="public">
                    <a href="#public">Public</a>
                </li>
                                    <ul id="tocify-subheader-public" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="public-GETapi-landing">
                                <a href="#public-GETapi-landing">Get Landing Page Departments</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="public-GETapi-announcements">
                                <a href="#public-GETapi-announcements">Get Accepted Candidates List</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-endpoints" class="tocify-header">
                <li class="tocify-item level-1" data-unique="endpoints">
                    <a href="#endpoints">Endpoints</a>
                </li>
                                    <ul id="tocify-subheader-endpoints" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="endpoints-GETapi-documentation">
                                <a href="#endpoints-GETapi-documentation">Handles the API request and renders the Swagger documentation view.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-GETapi-oauth2-callback">
                                <a href="#endpoints-GETapi-oauth2-callback">Handles the OAuth2 callback and retrieves the required file for the redirect.</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="endpoints-POSTapi-admin-criteria--department_id--reset">
                                <a href="#endpoints-POSTapi-admin-criteria--department_id--reset">POST api/admin/criteria/{department_id}/reset</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ url('/docs.postman') }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ url('/docs.openapi') }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: June 6, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>REST API for the HIMATIK Recruitment Decision Support System (DSS). Used by the Flutter mobile app to manage candidate registration, interview scheduling, evaluation scoring, and announcement publishing.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>This documentation aims to provide all the information you need to work with our API.

&lt;aside&gt;As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).&lt;/aside&gt;</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_SANCTUM_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Authenticate via <b>POST /api/login</b> to receive a Sanctum Bearer token. Pass it as <code>Authorization: Bearer {token}</code> on all protected endpoints.</p>

        <h1 id="authentication">Authentication</h1>

    

                                <h2 id="authentication-POSTapi-login">Login</h2>

<p>
</p>

<p>Authenticate a user and receive a Sanctum Bearer token for use on protected endpoints.
Works for all roles: <code>candidate</code>, <code>interviewer</code>, and <code>admin</code>.</p>

<span id="example-requests-POSTapi-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"candidate@himatik.ac.id\",
    \"password\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/login"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "candidate@himatik.ac.id",
    "password": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/login';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'email' =&gt; 'candidate@himatik.ac.id',
            'password' =&gt; 'password123',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-login">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Logged in successfully!&quot;,
    &quot;token&quot;: &quot;1|abc123def456...&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ahmad Rizki&quot;,
        &quot;email&quot;: &quot;candidate@himatik.ac.id&quot;,
        &quot;role&quot;: &quot;candidate&quot;,
        &quot;email_verified&quot;: true
    },
    &quot;candidate&quot;: null,
    &quot;next_step&quot;: &quot;candidate_registration&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-login" data-method="POST"
      data-path="api/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-login"
                    onclick="tryItOut('POSTapi-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-login"
                    onclick="cancelTryOut('POSTapi-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-login"
               value="candidate@himatik.ac.id"
               data-component="body">
    <br>
<p>The user's email address. Example: <code>candidate@himatik.ac.id</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-login"
               value="password123"
               data-component="body">
    <br>
<p>The user's password. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-logout">Logout</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Revoke the current Sanctum Bearer token, effectively logging the user out.</p>

<span id="example-requests-POSTapi-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/logout" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/logout"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/logout';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-logout">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Logged out successfully&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-logout" data-method="POST"
      data-path="api/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-logout"
                    onclick="tryItOut('POSTapi-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-logout"
                    onclick="cancelTryOut('POSTapi-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-logout"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="authentication-POSTapi-email-verify-otp">Verify Email OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Verify the candidate user's email OTP and mark the email as verified.</p>

<span id="example-requests-POSTapi-email-verify-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/email/verify-otp" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"otp\": \"123456\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/email/verify-otp"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "otp": "123456"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/email/verify-otp';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'otp' =&gt; '123456',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-email-verify-otp">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Email verified successfully.&quot;,
    &quot;redirect_to&quot;: &quot;landing&quot;,
    &quot;next_step&quot;: &quot;candidate_registration&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-email-verify-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-email-verify-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-email-verify-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-email-verify-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-email-verify-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-email-verify-otp" data-method="POST"
      data-path="api/email/verify-otp"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-email-verify-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-email-verify-otp"
                    onclick="tryItOut('POSTapi-email-verify-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-email-verify-otp"
                    onclick="cancelTryOut('POSTapi-email-verify-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-email-verify-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/email/verify-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-email-verify-otp"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-email-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-email-verify-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>otp</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="otp"                data-endpoint="POSTapi-email-verify-otp"
               value="123456"
               data-component="body">
    <br>
<p>Six digit OTP code. Example: <code>123456</code></p>
        </div>
        </form>

                    <h2 id="authentication-POSTapi-email-resend-otp">Resend Email OTP</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send a new OTP to the authenticated candidate user's email.</p>

<span id="example-requests-POSTapi-email-resend-otp">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/email/resend-otp" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/email/resend-otp"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/email/resend-otp';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-email-resend-otp">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;OTP sent successfully.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-email-resend-otp" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-email-resend-otp"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-email-resend-otp"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-email-resend-otp" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-email-resend-otp">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-email-resend-otp" data-method="POST"
      data-path="api/email/resend-otp"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-email-resend-otp', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-email-resend-otp"
                    onclick="tryItOut('POSTapi-email-resend-otp');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-email-resend-otp"
                    onclick="cancelTryOut('POSTapi-email-resend-otp');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-email-resend-otp"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/email/resend-otp</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-email-resend-otp"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-email-resend-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-email-resend-otp"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="candidate">Candidate</h1>

    

                                <h2 id="candidate-GETapi-departments">Get Departments List</h2>

<p>
</p>

<p>Returns all available departments/biros for candidates to select as their recruitment preferences.</p>

<span id="example-requests-GETapi-departments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/departments" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/departments"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/departments';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-departments">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Biro Humas&quot;,
            &quot;description&quot;: &quot;Biro Hubungan Masyarakat&quot;
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Biro Akademik&quot;,
            &quot;description&quot;: &quot;Mengelola kegiatan akademik&quot;
        }
    ]
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-departments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-departments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-departments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-departments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-departments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-departments" data-method="GET"
      data-path="api/departments"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-departments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-departments"
                    onclick="tryItOut('GETapi-departments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-departments"
                    onclick="cancelTryOut('GETapi-departments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-departments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/departments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-departments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-departments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="candidate-POSTapi-register">Register Candidate Account</h2>

<p>
</p>

<p>Register a candidate account and send an email OTP. This endpoint only creates the user account.
The candidate profile is submitted separately after OTP verification.</p>

<span id="example-requests-POSTapi-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"ahmad@student.pnj.ac.id\",
    \"nama\": \"Ahmad Rizki\",
    \"password\": \"password123\",
    \"password_confirmation\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/register"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ahmad@student.pnj.ac.id",
    "nama": "Ahmad Rizki",
    "password": "password123",
    "password_confirmation": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/register';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'email' =&gt; 'ahmad@student.pnj.ac.id',
            'nama' =&gt; 'Ahmad Rizki',
            'password' =&gt; 'password123',
            'password_confirmation' =&gt; 'password123',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-register">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Account created successfully. OTP sent to email.&quot;,
    &quot;token&quot;: &quot;2|xyz789...&quot;,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ahmad Rizki&quot;,
        &quot;email&quot;: &quot;ahmad@student.pnj.ac.id&quot;,
        &quot;role&quot;: &quot;candidate&quot;,
        &quot;email_verified&quot;: false
    },
    &quot;next_step&quot;: &quot;verify_email&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-register" data-method="POST"
      data-path="api/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-register"
                    onclick="tryItOut('POSTapi-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-register"
                    onclick="cancelTryOut('POSTapi-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-register"
               value="ahmad@student.pnj.ac.id"
               data-component="body">
    <br>
<p>Candidate email. Must be unique. Example: <code>ahmad@student.pnj.ac.id</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nama</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nama"                data-endpoint="POSTapi-register"
               value="Ahmad Rizki"
               data-component="body">
    <br>
<p>Full name. Example: <code>Ahmad Rizki</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-register"
               value="password123"
               data-component="body">
    <br>
<p>Min 8 chars. Example: <code>password123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password_confirmation</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password_confirmation"                data-endpoint="POSTapi-register"
               value="password123"
               data-component="body">
    <br>
<p>Must match password. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="candidate-GETapi-me">Get Candidate Profile, Schedule &amp; Results</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns the authenticated user's profile info, candidate profile if it exists,
booked interview schedule slot, and final announcement outcome &amp; DSS score breakdown if published.</p>

<span id="example-requests-GETapi-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/me" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/me"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/me';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-me">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;user&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ahmad Rizki&quot;,
        &quot;email&quot;: &quot;candidate@himatik.ac.id&quot;,
        &quot;role&quot;: &quot;candidate&quot;,
        &quot;email_verified&quot;: true
    },
    &quot;candidate&quot;: null,
    &quot;schedule&quot;: null,
    &quot;announcement&quot;: null,
    &quot;dss_results&quot;: null,
    &quot;next_step&quot;: &quot;candidate_registration&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-me" data-method="GET"
      data-path="api/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-me"
                    onclick="tryItOut('GETapi-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-me"
                    onclick="cancelTryOut('GETapi-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-me"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="candidate-POSTapi-candidate-profile">Submit Candidate Profile</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Submit the verified candidate's full registration profile. Accepts <code>multipart/form-data</code>.</p>

<span id="example-requests-POSTapi-candidate-profile">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/candidate/profile" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: multipart/form-data" \
    --header "Accept: application/json" \
    --form "candidate_type=staff"\
    --form "nickname=Ahmad"\
    --form "nim=2211501234"\
    --form "prodi=Teknik Informatika"\
    --form "kelas=TI-2A"\
    --form "phone=081234567890"\
    --form "address=architecto"\
    --form "first_choice_id=1"\
    --form "second_choice_id=2"\
    --form "department_choice_reason=architecto"\
    --form "weakness_description=architecto"\
    --form "contribution_plan=architecto"\
    --form "educations[]=architecto"\
    --form "organizations[]=architecto"\
    --form "committees[]=architecto"\
    --form "skills[]=architecto"\
    --form "facilities[]=architecto"\
    --form "photo=@C:\Users\Rizki\AppData\Local\Temp\php700A.tmp" \
    --form "instagram_proof=@C:\Users\Rizki\AppData\Local\Temp\php701B.tmp" \
    --form "youtube_proof=@C:\Users\Rizki\AppData\Local\Temp\php701C.tmp" \
    --form "political_statement=@C:\Users\Rizki\AppData\Local\Temp\php701D.tmp" \
    --form "candidate_signature=@C:\Users\Rizki\AppData\Local\Temp\php701E.tmp" \
    --form "parent_signature=@C:\Users\Rizki\AppData\Local\Temp\php701F.tmp" </code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/candidate/profile"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('candidate_type', 'staff');
body.append('nickname', 'Ahmad');
body.append('nim', '2211501234');
body.append('prodi', 'Teknik Informatika');
body.append('kelas', 'TI-2A');
body.append('phone', '081234567890');
body.append('address', 'architecto');
body.append('first_choice_id', '1');
body.append('second_choice_id', '2');
body.append('department_choice_reason', 'architecto');
body.append('weakness_description', 'architecto');
body.append('contribution_plan', 'architecto');
body.append('educations[]', 'architecto');
body.append('organizations[]', 'architecto');
body.append('committees[]', 'architecto');
body.append('skills[]', 'architecto');
body.append('facilities[]', 'architecto');
body.append('photo', document.querySelector('input[name="photo"]').files[0]);
body.append('instagram_proof', document.querySelector('input[name="instagram_proof"]').files[0]);
body.append('youtube_proof', document.querySelector('input[name="youtube_proof"]').files[0]);
body.append('political_statement', document.querySelector('input[name="political_statement"]').files[0]);
body.append('candidate_signature', document.querySelector('input[name="candidate_signature"]').files[0]);
body.append('parent_signature', document.querySelector('input[name="parent_signature"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/candidate/profile';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'multipart/form-data',
            'Accept' =&gt; 'application/json',
        ],
        'multipart' =&gt; [
            [
                'name' =&gt; 'candidate_type',
                'contents' =&gt; 'staff'
            ],
            [
                'name' =&gt; 'nickname',
                'contents' =&gt; 'Ahmad'
            ],
            [
                'name' =&gt; 'nim',
                'contents' =&gt; '2211501234'
            ],
            [
                'name' =&gt; 'prodi',
                'contents' =&gt; 'Teknik Informatika'
            ],
            [
                'name' =&gt; 'kelas',
                'contents' =&gt; 'TI-2A'
            ],
            [
                'name' =&gt; 'phone',
                'contents' =&gt; '081234567890'
            ],
            [
                'name' =&gt; 'address',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'first_choice_id',
                'contents' =&gt; '1'
            ],
            [
                'name' =&gt; 'second_choice_id',
                'contents' =&gt; '2'
            ],
            [
                'name' =&gt; 'department_choice_reason',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'weakness_description',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'contribution_plan',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'educations[]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'organizations[]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'committees[]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'skills[]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'facilities[]',
                'contents' =&gt; 'architecto'
            ],
            [
                'name' =&gt; 'photo',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php700A.tmp', 'r')
            ],
            [
                'name' =&gt; 'instagram_proof',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php701B.tmp', 'r')
            ],
            [
                'name' =&gt; 'youtube_proof',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php701C.tmp', 'r')
            ],
            [
                'name' =&gt; 'political_statement',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php701D.tmp', 'r')
            ],
            [
                'name' =&gt; 'candidate_signature',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php701E.tmp', 'r')
            ],
            [
                'name' =&gt; 'parent_signature',
                'contents' =&gt; fopen('C:\Users\Rizki\AppData\Local\Temp\php701F.tmp', 'r')
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-candidate-profile">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Candidate profile registered successfully!&quot;,
    &quot;candidate&quot;: {
        &quot;id&quot;: 1,
        &quot;nim&quot;: &quot;2211501234&quot;,
        &quot;status&quot;: &quot;registered&quot;
    },
    &quot;next_step&quot;: &quot;schedule_selection&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-candidate-profile" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-candidate-profile"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-candidate-profile"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-candidate-profile" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-candidate-profile">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-candidate-profile" data-method="POST"
      data-path="api/candidate/profile"
      data-authed="1"
      data-hasfiles="1"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-candidate-profile', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-candidate-profile"
                    onclick="tryItOut('POSTapi-candidate-profile');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-candidate-profile"
                    onclick="cancelTryOut('POSTapi-candidate-profile');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-candidate-profile"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/candidate/profile</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-candidate-profile"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-candidate-profile"
               value="multipart/form-data"
               data-component="header">
    <br>
<p>Example: <code>multipart/form-data</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-candidate-profile"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>candidate_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="candidate_type"                data-endpoint="POSTapi-candidate-profile"
               value="staff"
               data-component="body">
    <br>
<p>Registration type. One of: <code>staff</code>, <code>bph</code>. Example: <code>staff</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nickname</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nickname"                data-endpoint="POSTapi-candidate-profile"
               value="Ahmad"
               data-component="body">
    <br>
<p>Candidate nickname. Example: <code>Ahmad</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>nim</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="nim"                data-endpoint="POSTapi-candidate-profile"
               value="2211501234"
               data-component="body">
    <br>
<p>Exactly 10 digits and unique. Example: <code>2211501234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>prodi</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="prodi"                data-endpoint="POSTapi-candidate-profile"
               value="Teknik Informatika"
               data-component="body">
    <br>
<p>Study program. Example: <code>Teknik Informatika</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>kelas</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="kelas"                data-endpoint="POSTapi-candidate-profile"
               value="TI-2A"
               data-component="body">
    <br>
<p>Class name. Example: <code>TI-2A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-candidate-profile"
               value="081234567890"
               data-component="body">
    <br>
<p>Phone number. Example: <code>081234567890</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-candidate-profile"
               value="architecto"
               data-component="body">
    <br>
<p>Full address. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>first_choice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="first_choice_id"                data-endpoint="POSTapi-candidate-profile"
               value="1"
               data-component="body">
    <br>
<p>Department ID for first choice. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>second_choice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="second_choice_id"                data-endpoint="POSTapi-candidate-profile"
               value="2"
               data-component="body">
    <br>
<p>optional Department ID for second choice. Must differ from first choice. Example: <code>2</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>department_choice_reason</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="department_choice_reason"                data-endpoint="POSTapi-candidate-profile"
               value="architecto"
               data-component="body">
    <br>
<p>Combined reason for department choices. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>weakness_description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="weakness_description"                data-endpoint="POSTapi-candidate-profile"
               value="architecto"
               data-component="body">
    <br>
<p>Self-described weakness. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>contribution_plan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="contribution_plan"                data-endpoint="POSTapi-candidate-profile"
               value="architecto"
               data-component="body">
    <br>
<p>Concrete plan if selected. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>photo</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="photo"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>Candidate photo image. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php700A.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>instagram_proof</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="instagram_proof"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>Instagram follow proof image. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php701B.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>youtube_proof</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="youtube_proof"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>YouTube subscribe proof image. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php701C.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>political_statement</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="political_statement"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>Statement letter file. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php701D.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>candidate_signature</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="candidate_signature"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>Candidate signature image. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php701E.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>parent_signature</code></b>&nbsp;&nbsp;
<small>file</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="file" style="display: none"
                              name="parent_signature"                data-endpoint="POSTapi-candidate-profile"
               value=""
               data-component="body">
    <br>
<p>Parent signature image. Example: <code>C:\Users\Rizki\AppData\Local\Temp\php701F.tmp</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>educations</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="educations[0]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
        <input type="text" style="display: none"
               name="educations[1]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
    <br>
<p>optional Education rows.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>organizations</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="organizations[0]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
        <input type="text" style="display: none"
               name="organizations[1]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
    <br>
<p>optional External organization rows.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>committees</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="committees[0]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
        <input type="text" style="display: none"
               name="committees[1]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
    <br>
<p>optional Committee rows.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>skills</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="skills[0]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
        <input type="text" style="display: none"
               name="skills[1]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
    <br>
<p>optional Skill rows.</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>facilities</code></b>&nbsp;&nbsp;
<small>string[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="facilities[0]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
        <input type="text" style="display: none"
               name="facilities[1]"                data-endpoint="POSTapi-candidate-profile"
               data-component="body">
    <br>
<p>optional Facility rows.</p>
        </div>
        </form>

                    <h2 id="candidate-GETapi-schedules">Get Available Interview Schedules</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all available interview time slots, plus the currently booked slot (if any) for the authenticated candidate.</p>

<span id="example-requests-GETapi-schedules">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/schedules" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/schedules"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/schedules';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-schedules">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 3,
            &quot;session_name&quot;: &quot;Sesi Pagi A&quot;,
            &quot;scheduled_at&quot;: &quot;2025-08-15T09:00:00.000000Z&quot;,
            &quot;location&quot;: &quot;Ruang Rapat HIMATIK&quot;,
            &quot;candidate_id&quot;: null
        }
    ],
    &quot;current_booked_slot_id&quot;: null
}</code>
 </pre>
            <blockquote>
            <p>Example response (404):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Candidate profile not found.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-schedules" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-schedules"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-schedules"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-schedules" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-schedules">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-schedules" data-method="GET"
      data-path="api/schedules"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-schedules', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-schedules"
                    onclick="tryItOut('GETapi-schedules');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-schedules"
                    onclick="cancelTryOut('GETapi-schedules');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-schedules"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/schedules</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-schedules"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="candidate-POSTapi-schedules-book">Book Interview Schedule</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Book or change the candidate's interview time slot. Automatically releases any previously booked slot.
Updates candidate status to <code>scheduled</code>.</p>

<span id="example-requests-POSTapi-schedules-book">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/schedules/book" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"schedule_id\": 3
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/schedules/book"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "schedule_id": 3
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/schedules/book';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'schedule_id' =&gt; 3,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-schedules-book">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Schedule successfully booked!&quot;,
    &quot;booked_slot&quot;: {
        &quot;id&quot;: 3,
        &quot;session_name&quot;: &quot;Sesi Pagi A&quot;,
        &quot;scheduled_at&quot;: &quot;2025-08-15T09:00:00.000000Z&quot;,
        &quot;location&quot;: &quot;Ruang Rapat HIMATIK&quot;,
        &quot;candidate_id&quot;: 1
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-schedules-book" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-schedules-book"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-schedules-book"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-schedules-book" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-schedules-book">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-schedules-book" data-method="POST"
      data-path="api/schedules/book"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-schedules-book', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-schedules-book"
                    onclick="tryItOut('POSTapi-schedules-book');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-schedules-book"
                    onclick="cancelTryOut('POSTapi-schedules-book');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-schedules-book"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/schedules/book</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-schedules-book"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-schedules-book"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-schedules-book"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>schedule_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="schedule_id"                data-endpoint="POSTapi-schedules-book"
               value="3"
               data-component="body">
    <br>
<p>ID of the schedule slot to book. Example: <code>3</code></p>
        </div>
        </form>

                <h1 id="interviewer">Interviewer</h1>

    

                                <h2 id="interviewer-GETapi-interviewer-schedules">Get Interviewer&#039;s Schedules</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all interview slots assigned to the currently logged-in interviewer,
with candidate and department details. Accessible by both <code>interviewer</code> and <code>admin</code> roles.</p>

<span id="example-requests-GETapi-interviewer-schedules">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/interviewer/schedules" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/interviewer/schedules"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/interviewer/schedules';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-interviewer-schedules">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;session_name&quot;: &quot;Sesi Pagi A&quot;,
            &quot;scheduled_at&quot;: &quot;2025-08-15T09:00:00.000000Z&quot;,
            &quot;location&quot;: &quot;Ruang Rapat HIMATIK&quot;,
            &quot;candidate&quot;: {
                &quot;id&quot;: 1,
                &quot;nim&quot;: &quot;2211501234&quot;,
                &quot;user&quot;: {
                    &quot;name&quot;: &quot;Ahmad Rizki&quot;,
                    &quot;email&quot;: &quot;ahmad@student.pnj.ac.id&quot;
                }
            }
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized. Only interviewers/admins can access.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-interviewer-schedules" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-interviewer-schedules"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-interviewer-schedules"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-interviewer-schedules" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-interviewer-schedules">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-interviewer-schedules" data-method="GET"
      data-path="api/interviewer/schedules"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-interviewer-schedules', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-interviewer-schedules"
                    onclick="tryItOut('GETapi-interviewer-schedules');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-interviewer-schedules"
                    onclick="cancelTryOut('GETapi-interviewer-schedules');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-interviewer-schedules"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/interviewer/schedules</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-interviewer-schedules"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-interviewer-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-interviewer-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="interviewer-GETapi-interviewer-grade--candidate_id---department_id-">Get Grading Details</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns evaluation criteria and existing scores for a specific candidate and department pair.
Used by the Flutter app to pre-fill the grading form.</p>

<span id="example-requests-GETapi-interviewer-grade--candidate_id---department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/interviewer/grade/1/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/interviewer/grade/1/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/interviewer/grade/1/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-interviewer-grade--candidate_id---department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;candidate&quot;: {
        &quot;id&quot;: 1,
        &quot;nim&quot;: &quot;2211501234&quot;,
        &quot;user&quot;: {
            &quot;name&quot;: &quot;Ahmad Rizki&quot;
        }
    },
    &quot;department&quot;: {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Biro Akademik&quot;
    },
    &quot;criteria&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Motivasi&quot;,
            &quot;weight&quot;: 0.3
        },
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Kemampuan Komunikasi&quot;,
            &quot;weight&quot;: 0.3
        }
    ],
    &quot;existing_scores&quot;: {
        &quot;1&quot;: 4,
        &quot;2&quot;: 3
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (400):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Candidate did not choose this department.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-interviewer-grade--candidate_id---department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-interviewer-grade--candidate_id---department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-interviewer-grade--candidate_id---department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-interviewer-grade--candidate_id---department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-interviewer-grade--candidate_id---department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-interviewer-grade--candidate_id---department_id-" data-method="GET"
      data-path="api/interviewer/grade/{candidate_id}/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-interviewer-grade--candidate_id---department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-interviewer-grade--candidate_id---department_id-"
                    onclick="tryItOut('GETapi-interviewer-grade--candidate_id---department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-interviewer-grade--candidate_id---department_id-"
                    onclick="cancelTryOut('GETapi-interviewer-grade--candidate_id---department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-interviewer-grade--candidate_id---department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/interviewer/grade/{candidate_id}/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate_id"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the candidate. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The candidate ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="GETapi-interviewer-grade--candidate_id---department_id-"
               value="2"
               data-component="url">
    <br>
<p>The department/biro ID. Example: <code>2</code></p>
            </div>
                    </form>

                    <h2 id="interviewer-POSTapi-interviewer-grade--candidate_id---department_id-">Submit Evaluation Scores</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Save or update consensus scores for a candidate per department criteria.
Scores must be integers from 1–5. Updates candidate status to <code>evaluated</code>.</p>

<span id="example-requests-POSTapi-interviewer-grade--candidate_id---department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/interviewer/grade/1/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"scores\": {
        \"1\": 4,
        \"2\": 3,
        \"3\": 5
    }
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/interviewer/grade/1/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "scores": {
        "1": 4,
        "2": 3,
        "3": 5
    }
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/interviewer/grade/1/1';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'scores' =&gt; [
                1 =&gt; 4,
                3,
                5,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-interviewer-grade--candidate_id---department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Consensus scores saved successfully!&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The scores field is required.&quot;,
    &quot;errors&quot;: {
        &quot;scores&quot;: [
            &quot;The scores field is required.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Failed to save scores.&quot;,
    &quot;error&quot;: &quot;...&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-interviewer-grade--candidate_id---department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-interviewer-grade--candidate_id---department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-interviewer-grade--candidate_id---department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-interviewer-grade--candidate_id---department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-interviewer-grade--candidate_id---department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-interviewer-grade--candidate_id---department_id-" data-method="POST"
      data-path="api/interviewer/grade/{candidate_id}/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-interviewer-grade--candidate_id---department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-interviewer-grade--candidate_id---department_id-"
                    onclick="tryItOut('POSTapi-interviewer-grade--candidate_id---department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-interviewer-grade--candidate_id---department_id-"
                    onclick="cancelTryOut('POSTapi-interviewer-grade--candidate_id---department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-interviewer-grade--candidate_id---department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/interviewer/grade/{candidate_id}/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate_id"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the candidate. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="1"
               data-component="url">
    <br>
<p>The candidate ID. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value="2"
               data-component="url">
    <br>
<p>The department/biro ID. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>scores</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scores"                data-endpoint="POSTapi-interviewer-grade--candidate_id---department_id-"
               value=""
               data-component="body">
    <br>
<p>Key-value map of criteria_id → score (1–5).</p>
        </div>
        </form>

                <h1 id="admin">Admin</h1>

    

                                <h2 id="admin-POSTapi-interviewer-decide--candidate_id-">Decide Candidate (Accept/Reject)</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Save the admin's or interviewer's decision for a candidate.
Both <code>admin</code> and <code>interviewer</code> roles can call this endpoint.</p>

<span id="example-requests-POSTapi-interviewer-decide--candidate_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/interviewer/decide/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"status\": \"accepted\",
    \"assigned_department_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/interviewer/decide/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "accepted",
    "assigned_department_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/interviewer/decide/1';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'status' =&gt; 'accepted',
            'assigned_department_id' =&gt; 1,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-interviewer-decide--candidate_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Decision saved successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-interviewer-decide--candidate_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-interviewer-decide--candidate_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-interviewer-decide--candidate_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-interviewer-decide--candidate_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-interviewer-decide--candidate_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-interviewer-decide--candidate_id-" data-method="POST"
      data-path="api/interviewer/decide/{candidate_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-interviewer-decide--candidate_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-interviewer-decide--candidate_id-"
                    onclick="tryItOut('POSTapi-interviewer-decide--candidate_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-interviewer-decide--candidate_id-"
                    onclick="cancelTryOut('POSTapi-interviewer-decide--candidate_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-interviewer-decide--candidate_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/interviewer/decide/{candidate_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate_id"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the candidate. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="1"
               data-component="url">
    <br>
<p>The candidate ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="accepted"
               data-component="body">
    <br>
<p>One of: <code>accepted</code>, <code>rejected</code>. Example: <code>accepted</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>assigned_department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="assigned_department_id"                data-endpoint="POSTapi-interviewer-decide--candidate_id-"
               value="1"
               data-component="body">
    <br>
<p>if accepted. Department to assign. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="admin-GETapi-admin-stats">Get Dashboard Stats</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns an overview of recruitment statistics including candidate counts per status,
and department-level candidate application counts. Admin only.</p>

<span id="example-requests-GETapi-admin-stats">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/admin/stats" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/stats"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/stats';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-stats">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: {
        &quot;stats&quot;: {
            &quot;total_candidates&quot;: 42,
            &quot;total_registered&quot;: 15,
            &quot;total_scheduled&quot;: 20,
            &quot;total_evaluated&quot;: 7
        },
        &quot;departments&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Biro Humas&quot;,
                &quot;first_choice_candidates_count&quot;: 10
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-stats" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-stats"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-stats"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-stats" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-stats">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-stats" data-method="GET"
      data-path="api/admin/stats"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-stats', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-stats"
                    onclick="tryItOut('GETapi-admin-stats');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-stats"
                    onclick="cancelTryOut('GETapi-admin-stats');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-stats"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/stats</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-stats"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-stats"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-stats"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-GETapi-admin-rankings--department_id-">Get DSS Rankings for Department</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns Profile Matching DSS-calculated candidate rankings for a specific department. Admin only.</p>

<span id="example-requests-GETapi-admin-rankings--department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/admin/rankings/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/rankings/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/rankings/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-rankings--department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;department&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Biro Humas&quot;
    },
    &quot;rankings&quot;: [],
    &quot;announcements&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-rankings--department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-rankings--department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-rankings--department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-rankings--department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-rankings--department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-rankings--department_id-" data-method="GET"
      data-path="api/admin/rankings/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-rankings--department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-rankings--department_id-"
                    onclick="tryItOut('GETapi-admin-rankings--department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-rankings--department_id-"
                    onclick="cancelTryOut('GETapi-admin-rankings--department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-rankings--department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/rankings/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-rankings--department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-rankings--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-rankings--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="GETapi-admin-rankings--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="GETapi-admin-rankings--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The department/biro ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="admin-POSTapi-admin-decide--candidate_id-">Decide Candidate (Accept/Reject)</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Save the admin's or interviewer's decision for a candidate.
Both <code>admin</code> and <code>interviewer</code> roles can call this endpoint.</p>

<span id="example-requests-POSTapi-admin-decide--candidate_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/decide/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"status\": \"accepted\",
    \"assigned_department_id\": 1
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/decide/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "status": "accepted",
    "assigned_department_id": 1
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/decide/1';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'status' =&gt; 'accepted',
            'assigned_department_id' =&gt; 1,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-decide--candidate_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Decision saved successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-decide--candidate_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-decide--candidate_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-decide--candidate_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-decide--candidate_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-decide--candidate_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-decide--candidate_id-" data-method="POST"
      data-path="api/admin/decide/{candidate_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-decide--candidate_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-decide--candidate_id-"
                    onclick="tryItOut('POSTapi-admin-decide--candidate_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-decide--candidate_id-"
                    onclick="cancelTryOut('POSTapi-admin-decide--candidate_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-decide--candidate_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/decide/{candidate_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate_id"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the candidate. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>candidate</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="candidate"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="1"
               data-component="url">
    <br>
<p>The candidate ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="accepted"
               data-component="body">
    <br>
<p>One of: <code>accepted</code>, <code>rejected</code>. Example: <code>accepted</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>assigned_department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="assigned_department_id"                data-endpoint="POSTapi-admin-decide--candidate_id-"
               value="1"
               data-component="body">
    <br>
<p>if accepted. Department to assign. Example: <code>1</code></p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-publish">Toggle Announcement Publish State</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Publish or unpublish all candidate announcements at once. Admin only.</p>

<span id="example-requests-POSTapi-admin-publish">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/publish" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"is_published\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/publish"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "is_published": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/publish';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'is_published' =&gt; true,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-publish">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Announcement board visibility toggled successfully!&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-publish" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-publish"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-publish"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-publish" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-publish">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-publish" data-method="POST"
      data-path="api/admin/publish"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-publish', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-publish"
                    onclick="tryItOut('POSTapi-admin-publish');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-publish"
                    onclick="cancelTryOut('POSTapi-admin-publish');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-publish"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/publish</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-publish"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-publish"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-publish"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_published</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-publish" style="display: none">
            <input type="radio" name="is_published"
                   value="true"
                   data-endpoint="POSTapi-admin-publish"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-publish" style="display: none">
            <input type="radio" name="is_published"
                   value="false"
                   data-endpoint="POSTapi-admin-publish"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p><code>true</code> to publish, <code>false</code> to unpublish. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="admin-POSTapi-admin-departments">Create Department</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Create a new department/biro. Admin only.</p>

<span id="example-requests-POSTapi-admin-departments">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/departments" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Biro Humas\",
    \"description\": \"Biro Hubungan Masyarakat\",
    \"slug\": \"v\",
    \"personal_aspect_weight\": 1,
    \"organizational_aspect_weight\": 5,
    \"core_factor_weight\": 0.6,
    \"secondary_factor_weight\": 0.4,
    \"is_active\": true
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/departments"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Biro Humas",
    "description": "Biro Hubungan Masyarakat",
    "slug": "v",
    "personal_aspect_weight": 1,
    "organizational_aspect_weight": 5,
    "core_factor_weight": 0.6,
    "secondary_factor_weight": 0.4,
    "is_active": true
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/departments';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Biro Humas',
            'description' =&gt; 'Biro Hubungan Masyarakat',
            'slug' =&gt; 'v',
            'personal_aspect_weight' =&gt; 1,
            'organizational_aspect_weight' =&gt; 5,
            'core_factor_weight' =&gt; 0.6,
            'secondary_factor_weight' =&gt; 0.4,
            'is_active' =&gt; true,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-departments">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Department created successfully!&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 3,
        &quot;name&quot;: &quot;Biro Humas&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-departments" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-departments"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-departments"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-departments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-departments">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-departments" data-method="POST"
      data-path="api/admin/departments"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-departments', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-departments"
                    onclick="tryItOut('POSTapi-admin-departments');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-departments"
                    onclick="cancelTryOut('POSTapi-admin-departments');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-departments"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/departments</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-departments"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-departments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-departments"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-departments"
               value="Biro Humas"
               data-component="body">
    <br>
<p>Department name. Must be unique. Example: <code>Biro Humas</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-admin-departments"
               value="Biro Hubungan Masyarakat"
               data-component="body">
    <br>
<p>optional Description. Example: <code>Biro Hubungan Masyarakat</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="POSTapi-admin-departments"
               value="v"
               data-component="body">
    <br>
<p>Must not be greater than 255 characters. Example: <code>v</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>personal_aspect_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="personal_aspect_weight"                data-endpoint="POSTapi-admin-departments"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>organizational_aspect_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="organizational_aspect_weight"                data-endpoint="POSTapi-admin-departments"
               value="5"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>core_factor_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="core_factor_weight"                data-endpoint="POSTapi-admin-departments"
               value="0.6"
               data-component="body">
    <br>
<p>Core factor weight (0–1). Example: <code>0.6</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>secondary_factor_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="secondary_factor_weight"                data-endpoint="POSTapi-admin-departments"
               value="0.4"
               data-component="body">
    <br>
<p>Secondary factor weight (0–1). Example: <code>0.4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-departments" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-departments"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-departments" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-departments"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="admin-PUTapi-admin-departments--department_id-">Update Department</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update an existing department/biro. Admin only.</p>

<span id="example-requests-PUTapi-admin-departments--department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/admin/departments/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Biro Humas\",
    \"description\": \"Updated description\",
    \"personal_aspect_weight\": 1,
    \"organizational_aspect_weight\": 1,
    \"core_factor_weight\": 0.6,
    \"secondary_factor_weight\": 0.4,
    \"is_active\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/departments/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Biro Humas",
    "description": "Updated description",
    "personal_aspect_weight": 1,
    "organizational_aspect_weight": 1,
    "core_factor_weight": 0.6,
    "secondary_factor_weight": 0.4,
    "is_active": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/departments/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Biro Humas',
            'description' =&gt; 'Updated description',
            'personal_aspect_weight' =&gt; 1,
            'organizational_aspect_weight' =&gt; 1,
            'core_factor_weight' =&gt; 0.6,
            'secondary_factor_weight' =&gt; 0.4,
            'is_active' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-departments--department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Department updated successfully!&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Biro Humas&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-admin-departments--department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-departments--department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-departments--department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-departments--department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-departments--department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-departments--department_id-" data-method="PUT"
      data-path="api/admin/departments/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-departments--department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-departments--department_id-"
                    onclick="tryItOut('PUTapi-admin-departments--department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-departments--department_id-"
                    onclick="cancelTryOut('PUTapi-admin-departments--department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-departments--department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/departments/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-departments--department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The department ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="Biro Humas"
               data-component="body">
    <br>
<p>Department name. Example: <code>Biro Humas</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="Updated description"
               data-component="body">
    <br>
<p>optional Description. Example: <code>Updated description</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>slug</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="slug"                data-endpoint="PUTapi-admin-departments--department_id-"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>personal_aspect_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="personal_aspect_weight"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>organizational_aspect_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="organizational_aspect_weight"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>core_factor_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="core_factor_weight"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="0.6"
               data-component="body">
    <br>
<p>Core factor weight (0–1). Example: <code>0.6</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>secondary_factor_weight</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="secondary_factor_weight"                data-endpoint="PUTapi-admin-departments--department_id-"
               value="0.4"
               data-component="body">
    <br>
<p>Secondary factor weight (0–1). Example: <code>0.4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-departments--department_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-departments--department_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-departments--department_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-departments--department_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="admin-DELETEapi-admin-departments--department_id-">Delete Department</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Delete a department/biro. Admin only.</p>

<span id="example-requests-DELETEapi-admin-departments--department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/admin/departments/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/departments/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/departments/1';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-departments--department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Department deleted successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-admin-departments--department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-departments--department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-departments--department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-departments--department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-departments--department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-departments--department_id-" data-method="DELETE"
      data-path="api/admin/departments/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-departments--department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-departments--department_id-"
                    onclick="tryItOut('DELETEapi-admin-departments--department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-departments--department_id-"
                    onclick="cancelTryOut('DELETEapi-admin-departments--department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-departments--department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/departments/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-departments--department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-departments--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-departments--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="DELETEapi-admin-departments--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="DELETEapi-admin-departments--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The department ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-admin-schedules">Get All Schedules (Admin)</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all interview schedule slots with candidate and interviewer details. Admin only.</p>

<span id="example-requests-GETapi-admin-schedules">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/admin/schedules" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/schedules"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/schedules';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-schedules">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;session_name&quot;: &quot;Sesi Pagi A&quot;,
            &quot;scheduled_at&quot;: &quot;2025-08-15T09:00:00Z&quot;,
            &quot;location&quot;: &quot;Ruang Rapat&quot;,
            &quot;candidate&quot;: null,
            &quot;interviewers&quot;: []
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-schedules" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-schedules"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-schedules"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-schedules" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-schedules">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-schedules" data-method="GET"
      data-path="api/admin/schedules"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-schedules', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-schedules"
                    onclick="tryItOut('GETapi-admin-schedules');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-schedules"
                    onclick="cancelTryOut('GETapi-admin-schedules');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-schedules"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/schedules</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-schedules"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-POSTapi-admin-schedules">Create Interview Schedule</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Create a new interview time slot and optionally assign interviewers. Admin only.</p>

<span id="example-requests-POSTapi-admin-schedules">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/schedules" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"session_name\": \"Sesi Pagi A\",
    \"department_id\": \"architecto\",
    \"scheduled_at\": \"2025-08-15T09:00:00\",
    \"location\": \"Ruang Rapat HIMATIK\",
    \"is_active\": false,
    \"interviewer_ids\": [
        2,
        3
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/schedules"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "session_name": "Sesi Pagi A",
    "department_id": "architecto",
    "scheduled_at": "2025-08-15T09:00:00",
    "location": "Ruang Rapat HIMATIK",
    "is_active": false,
    "interviewer_ids": [
        2,
        3
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/schedules';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'session_name' =&gt; 'Sesi Pagi A',
            'department_id' =&gt; 'architecto',
            'scheduled_at' =&gt; '2025-08-15T09:00:00',
            'location' =&gt; 'Ruang Rapat HIMATIK',
            'is_active' =&gt; false,
            'interviewer_ids' =&gt; [
                2,
                3,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-schedules">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Schedule created successfully!&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 5,
        &quot;session_name&quot;: &quot;Sesi Pagi A&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-schedules" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-schedules"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-schedules"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-schedules" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-schedules">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-schedules" data-method="POST"
      data-path="api/admin/schedules"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-schedules', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-schedules"
                    onclick="tryItOut('POSTapi-admin-schedules');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-schedules"
                    onclick="cancelTryOut('POSTapi-admin-schedules');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-schedules"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/schedules</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-schedules"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-schedules"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>session_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="session_name"                data-endpoint="POSTapi-admin-schedules"
               value="Sesi Pagi A"
               data-component="body">
    <br>
<p>Session name. Example: <code>Sesi Pagi A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="department_id"                data-endpoint="POSTapi-admin-schedules"
               value="architecto"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the departmentsbiro table. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>scheduled_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scheduled_at"                data-endpoint="POSTapi-admin-schedules"
               value="2025-08-15T09:00:00"
               data-component="body">
    <br>
<p>Date and time (ISO 8601). Example: <code>2025-08-15T09:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>location</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="location"                data-endpoint="POSTapi-admin-schedules"
               value="Ruang Rapat HIMATIK"
               data-component="body">
    <br>
<p>Location. Example: <code>Ruang Rapat HIMATIK</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-schedules" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-schedules"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-schedules" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-schedules"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>interviewer_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="interviewer_ids[0]"                data-endpoint="POSTapi-admin-schedules"
               data-component="body">
        <input type="number" style="display: none"
               name="interviewer_ids[1]"                data-endpoint="POSTapi-admin-schedules"
               data-component="body">
    <br>
<p>optional Array of user IDs (interviewers) to assign.</p>
        </div>
        </form>

                    <h2 id="admin-PUTapi-admin-schedules--schedule_id-">Update Interview Schedule</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update an existing interview schedule slot. Admin only.</p>

<span id="example-requests-PUTapi-admin-schedules--schedule_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/admin/schedules/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"session_name\": \"Sesi Pagi A\",
    \"department_id\": \"architecto\",
    \"scheduled_at\": \"2025-08-15T09:00:00\",
    \"location\": \"Ruang Rapat HIMATIK\",
    \"is_active\": false,
    \"interviewer_ids\": [
        2,
        3
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/schedules/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "session_name": "Sesi Pagi A",
    "department_id": "architecto",
    "scheduled_at": "2025-08-15T09:00:00",
    "location": "Ruang Rapat HIMATIK",
    "is_active": false,
    "interviewer_ids": [
        2,
        3
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/schedules/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'session_name' =&gt; 'Sesi Pagi A',
            'department_id' =&gt; 'architecto',
            'scheduled_at' =&gt; '2025-08-15T09:00:00',
            'location' =&gt; 'Ruang Rapat HIMATIK',
            'is_active' =&gt; false,
            'interviewer_ids' =&gt; [
                2,
                3,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-schedules--schedule_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Schedule updated successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-admin-schedules--schedule_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-schedules--schedule_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-schedules--schedule_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-schedules--schedule_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-schedules--schedule_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-schedules--schedule_id-" data-method="PUT"
      data-path="api/admin/schedules/{schedule_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-schedules--schedule_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-schedules--schedule_id-"
                    onclick="tryItOut('PUTapi-admin-schedules--schedule_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-schedules--schedule_id-"
                    onclick="cancelTryOut('PUTapi-admin-schedules--schedule_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-schedules--schedule_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/schedules/{schedule_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>schedule_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="schedule_id"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the schedule. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>schedule</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="schedule"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="1"
               data-component="url">
    <br>
<p>The schedule ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>session_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="session_name"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="Sesi Pagi A"
               data-component="body">
    <br>
<p>Session name. Example: <code>Sesi Pagi A</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="department_id"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="architecto"
               data-component="body">
    <br>
<p>The <code>id</code> of an existing record in the departmentsbiro table. Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>scheduled_at</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="scheduled_at"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="2025-08-15T09:00:00"
               data-component="body">
    <br>
<p>Date and time (ISO 8601). Example: <code>2025-08-15T09:00:00</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>location</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="location"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               value="Ruang Rapat HIMATIK"
               data-component="body">
    <br>
<p>Location. Example: <code>Ruang Rapat HIMATIK</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-schedules--schedule_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-schedules--schedule_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-schedules--schedule_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-schedules--schedule_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>interviewer_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="interviewer_ids[0]"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               data-component="body">
        <input type="number" style="display: none"
               name="interviewer_ids[1]"                data-endpoint="PUTapi-admin-schedules--schedule_id-"
               data-component="body">
    <br>
<p>optional Array of user IDs to assign.</p>
        </div>
        </form>

                    <h2 id="admin-DELETEapi-admin-schedules--schedule_id-">Delete Interview Schedule</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Delete an interview schedule slot. Admin only.</p>

<span id="example-requests-DELETEapi-admin-schedules--schedule_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/admin/schedules/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/schedules/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/schedules/1';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-schedules--schedule_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Schedule deleted successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-admin-schedules--schedule_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-schedules--schedule_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-schedules--schedule_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-schedules--schedule_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-schedules--schedule_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-schedules--schedule_id-" data-method="DELETE"
      data-path="api/admin/schedules/{schedule_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-schedules--schedule_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-schedules--schedule_id-"
                    onclick="tryItOut('DELETEapi-admin-schedules--schedule_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-schedules--schedule_id-"
                    onclick="cancelTryOut('DELETEapi-admin-schedules--schedule_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-schedules--schedule_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/schedules/{schedule_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-schedules--schedule_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-schedules--schedule_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-schedules--schedule_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>schedule_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="schedule_id"                data-endpoint="DELETEapi-admin-schedules--schedule_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the schedule. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>schedule</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="schedule"                data-endpoint="DELETEapi-admin-schedules--schedule_id-"
               value="1"
               data-component="url">
    <br>
<p>The schedule ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-admin-criteria--department_id-">Get Evaluation Criteria</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns all evaluation criteria for a specific department. Admin only.</p>

<span id="example-requests-GETapi-admin-criteria--department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/admin/criteria/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/criteria/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/criteria/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-criteria--department_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Komunikasi&quot;,
            &quot;type&quot;: &quot;core&quot;,
            &quot;target_score&quot;: 4
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-criteria--department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-criteria--department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-criteria--department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-criteria--department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-criteria--department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-criteria--department_id-" data-method="GET"
      data-path="api/admin/criteria/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-criteria--department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-criteria--department_id-"
                    onclick="tryItOut('GETapi-admin-criteria--department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-criteria--department_id-"
                    onclick="cancelTryOut('GETapi-admin-criteria--department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-criteria--department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/criteria/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-criteria--department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-criteria--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-criteria--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="GETapi-admin-criteria--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="GETapi-admin-criteria--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The department ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="admin-POSTapi-admin-criteria--department_id-">Create Evaluation Criterion</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Add a new evaluation criterion for a specific department. Admin only.</p>

<span id="example-requests-POSTapi-admin-criteria--department_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/criteria/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Komunikasi\",
    \"type\": \"core\",
    \"aspect\": \"organizational\",
    \"target_score\": 4,
    \"description\": \"Kemampuan berbicara di depan umum\",
    \"code\": \"v\",
    \"catatan\": \"architecto\",
    \"is_active\": false,
    \"sort_order\": 39
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/criteria/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Komunikasi",
    "type": "core",
    "aspect": "organizational",
    "target_score": 4,
    "description": "Kemampuan berbicara di depan umum",
    "code": "v",
    "catatan": "architecto",
    "is_active": false,
    "sort_order": 39
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/criteria/1';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Komunikasi',
            'type' =&gt; 'core',
            'aspect' =&gt; 'organizational',
            'target_score' =&gt; 4,
            'description' =&gt; 'Kemampuan berbicara di depan umum',
            'code' =&gt; 'v',
            'catatan' =&gt; 'architecto',
            'is_active' =&gt; false,
            'sort_order' =&gt; 39,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-criteria--department_id-">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Criterion created successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-criteria--department_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-criteria--department_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-criteria--department_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-criteria--department_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-criteria--department_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-criteria--department_id-" data-method="POST"
      data-path="api/admin/criteria/{department_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-criteria--department_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-criteria--department_id-"
                    onclick="tryItOut('POSTapi-admin-criteria--department_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-criteria--department_id-"
                    onclick="cancelTryOut('POSTapi-admin-criteria--department_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-criteria--department_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/criteria/{department_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-criteria--department_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="1"
               data-component="url">
    <br>
<p>The department ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="Komunikasi"
               data-component="body">
    <br>
<p>Criterion name. Example: <code>Komunikasi</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="core"
               data-component="body">
    <br>
<p>Factor type. One of: <code>core</code>, <code>secondary</code>. Example: <code>core</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>aspect</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="aspect"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="organizational"
               data-component="body">
    <br>
<p>Example: <code>organizational</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>personal</code></li> <li><code>organizational</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>target_score</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="target_score"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="4"
               data-component="body">
    <br>
<p>Target score (1–5). Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="Kemampuan berbicara di depan umum"
               data-component="body">
    <br>
<p>optional Description. Example: <code>Kemampuan berbicara di depan umum</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="v"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>v</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>catatan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="catatan"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-admin-criteria--department_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-admin-criteria--department_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-admin-criteria--department_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-admin-criteria--department_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="POSTapi-admin-criteria--department_id-"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>39</code></p>
        </div>
        </form>

                    <h2 id="admin-PUTapi-admin-criteria--criterion_id-">Update Evaluation Criterion</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update an existing evaluation criterion for a department. Admin only.</p>

<span id="example-requests-PUTapi-admin-criteria--criterion_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/admin/criteria/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Komunikasi\",
    \"type\": \"core\",
    \"aspect\": \"personal\",
    \"target_score\": 4,
    \"description\": \"Updated description\",
    \"code\": \"v\",
    \"catatan\": \"architecto\",
    \"is_active\": false,
    \"sort_order\": 39
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/criteria/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Komunikasi",
    "type": "core",
    "aspect": "personal",
    "target_score": 4,
    "description": "Updated description",
    "code": "v",
    "catatan": "architecto",
    "is_active": false,
    "sort_order": 39
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/criteria/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Komunikasi',
            'type' =&gt; 'core',
            'aspect' =&gt; 'personal',
            'target_score' =&gt; 4,
            'description' =&gt; 'Updated description',
            'code' =&gt; 'v',
            'catatan' =&gt; 'architecto',
            'is_active' =&gt; false,
            'sort_order' =&gt; 39,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-criteria--criterion_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Criterion updated successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-admin-criteria--criterion_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-criteria--criterion_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-criteria--criterion_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-criteria--criterion_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-criteria--criterion_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-criteria--criterion_id-" data-method="PUT"
      data-path="api/admin/criteria/{criterion_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-criteria--criterion_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-criteria--criterion_id-"
                    onclick="tryItOut('PUTapi-admin-criteria--criterion_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-criteria--criterion_id-"
                    onclick="cancelTryOut('PUTapi-admin-criteria--criterion_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-criteria--criterion_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/criteria/{criterion_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>criterion_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="criterion_id"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the criterion. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>criterion</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="criterion"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="1"
               data-component="url">
    <br>
<p>The criterion ID. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="Komunikasi"
               data-component="body">
    <br>
<p>Criterion name. Example: <code>Komunikasi</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="core"
               data-component="body">
    <br>
<p>Factor type. One of: <code>core</code>, <code>secondary</code>. Example: <code>core</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>aspect</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="aspect"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="personal"
               data-component="body">
    <br>
<p>Example: <code>personal</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>personal</code></li> <li><code>organizational</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>target_score</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="target_score"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="4"
               data-component="body">
    <br>
<p>Target score (1–5). Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="Updated description"
               data-component="body">
    <br>
<p>optional Description. Example: <code>Updated description</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="v"
               data-component="body">
    <br>
<p>Must not be greater than 50 characters. Example: <code>v</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>catatan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="catatan"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="architecto"
               data-component="body">
    <br>
<p>Example: <code>architecto</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PUTapi-admin-criteria--criterion_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PUTapi-admin-criteria--criterion_id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-admin-criteria--criterion_id-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PUTapi-admin-criteria--criterion_id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="PUTapi-admin-criteria--criterion_id-"
               value="39"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>39</code></p>
        </div>
        </form>

                    <h2 id="admin-DELETEapi-admin-criteria--criterion_id-">Delete Evaluation Criterion</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Delete an evaluation criterion. Admin only.</p>

<span id="example-requests-DELETEapi-admin-criteria--criterion_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/admin/criteria/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/criteria/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/criteria/1';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-criteria--criterion_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Criterion deleted successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-admin-criteria--criterion_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-criteria--criterion_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-criteria--criterion_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-criteria--criterion_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-criteria--criterion_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-criteria--criterion_id-" data-method="DELETE"
      data-path="api/admin/criteria/{criterion_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-criteria--criterion_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-criteria--criterion_id-"
                    onclick="tryItOut('DELETEapi-admin-criteria--criterion_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-criteria--criterion_id-"
                    onclick="cancelTryOut('DELETEapi-admin-criteria--criterion_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-criteria--criterion_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/criteria/{criterion_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-criteria--criterion_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-criteria--criterion_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-criteria--criterion_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>criterion_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="criterion_id"                data-endpoint="DELETEapi-admin-criteria--criterion_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the criterion. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>criterion</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="criterion"                data-endpoint="DELETEapi-admin-criteria--criterion_id-"
               value="1"
               data-component="url">
    <br>
<p>The criterion ID. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-admin-interviewers">Get All Interviewers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Returns a list of all users with the <code>interviewer</code> role. Admin only.</p>

<span id="example-requests-GETapi-admin-interviewers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/admin/interviewers" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/interviewers"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/interviewers';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-admin-interviewers">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;data&quot;: [
        {
            &quot;id&quot;: 2,
            &quot;name&quot;: &quot;Budi Santoso&quot;,
            &quot;email&quot;: &quot;budi@himatik.ac.id&quot;,
            &quot;role&quot;: &quot;interviewer&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-admin-interviewers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-admin-interviewers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-admin-interviewers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-admin-interviewers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-admin-interviewers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-admin-interviewers" data-method="GET"
      data-path="api/admin/interviewers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-admin-interviewers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-admin-interviewers"
                    onclick="tryItOut('GETapi-admin-interviewers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-admin-interviewers"
                    onclick="cancelTryOut('GETapi-admin-interviewers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-admin-interviewers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/admin/interviewers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-admin-interviewers"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-admin-interviewers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-admin-interviewers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-POSTapi-admin-interviewers">Create Interviewer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Create a new interviewer account. Admin only.</p>

<span id="example-requests-POSTapi-admin-interviewers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/interviewers" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Budi Santoso\",
    \"email\": \"budi@himatik.ac.id\",
    \"password\": \"password123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/interviewers"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Budi Santoso",
    "email": "budi@himatik.ac.id",
    "password": "password123"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/interviewers';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Budi Santoso',
            'email' =&gt; 'budi@himatik.ac.id',
            'password' =&gt; 'password123',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-interviewers">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Interviewer created successfully!&quot;,
    &quot;data&quot;: {
        &quot;id&quot;: 2,
        &quot;name&quot;: &quot;Budi Santoso&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-admin-interviewers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-interviewers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-interviewers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-interviewers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-interviewers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-interviewers" data-method="POST"
      data-path="api/admin/interviewers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-interviewers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-interviewers"
                    onclick="tryItOut('POSTapi-admin-interviewers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-interviewers"
                    onclick="cancelTryOut('POSTapi-admin-interviewers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-interviewers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/interviewers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-admin-interviewers"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-interviewers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-interviewers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-admin-interviewers"
               value="Budi Santoso"
               data-component="body">
    <br>
<p>Interviewer's full name. Example: <code>Budi Santoso</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-admin-interviewers"
               value="budi@himatik.ac.id"
               data-component="body">
    <br>
<p>Email address. Must be unique. Example: <code>budi@himatik.ac.id</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-admin-interviewers"
               value="password123"
               data-component="body">
    <br>
<p>Min 8 characters. Example: <code>password123</code></p>
        </div>
        </form>

                    <h2 id="admin-PUTapi-admin-interviewers--user_id-">Update Interviewer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Update an interviewer's account details. Leave <code>password</code> blank to keep it unchanged. Admin only.</p>

<span id="example-requests-PUTapi-admin-interviewers--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8000/api/admin/interviewers/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Budi Santoso\",
    \"email\": \"budi@himatik.ac.id\",
    \"password\": \"newpassword123\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/interviewers/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Budi Santoso",
    "email": "budi@himatik.ac.id",
    "password": "newpassword123"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/interviewers/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Budi Santoso',
            'email' =&gt; 'budi@himatik.ac.id',
            'password' =&gt; 'newpassword123',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-PUTapi-admin-interviewers--user_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Interviewer updated successfully!&quot;,
    &quot;data&quot;: {}
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-admin-interviewers--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-admin-interviewers--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-admin-interviewers--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-admin-interviewers--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-admin-interviewers--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-admin-interviewers--user_id-" data-method="PUT"
      data-path="api/admin/interviewers/{user_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-admin-interviewers--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-admin-interviewers--user_id-"
                    onclick="tryItOut('PUTapi-admin-interviewers--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-admin-interviewers--user_id-"
                    onclick="cancelTryOut('PUTapi-admin-interviewers--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-admin-interviewers--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/admin/interviewers/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="2"
               data-component="url">
    <br>
<p>The user ID of the interviewer. Example: <code>2</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="Budi Santoso"
               data-component="body">
    <br>
<p>Interviewer's full name. Example: <code>Budi Santoso</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="budi@himatik.ac.id"
               data-component="body">
    <br>
<p>Email address. Example: <code>budi@himatik.ac.id</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="PUTapi-admin-interviewers--user_id-"
               value="newpassword123"
               data-component="body">
    <br>
<p>optional New password (min 8 chars). Leave blank to keep current. Example: <code>newpassword123</code></p>
        </div>
        </form>

                    <h2 id="admin-DELETEapi-admin-interviewers--user_id-">Delete Interviewer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Delete an interviewer account. Admin only.</p>

<span id="example-requests-DELETEapi-admin-interviewers--user_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/admin/interviewers/1" \
    --header "Authorization: Bearer {YOUR_SANCTUM_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/interviewers/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_SANCTUM_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/interviewers/1';
$response = $client-&gt;delete(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_SANCTUM_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-DELETEapi-admin-interviewers--user_id-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;message&quot;: &quot;Interviewer deleted successfully.&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (403):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: false,
    &quot;message&quot;: &quot;Unauthorized.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-admin-interviewers--user_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-admin-interviewers--user_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-admin-interviewers--user_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-admin-interviewers--user_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-admin-interviewers--user_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-admin-interviewers--user_id-" data-method="DELETE"
      data-path="api/admin/interviewers/{user_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-admin-interviewers--user_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-admin-interviewers--user_id-"
                    onclick="tryItOut('DELETEapi-admin-interviewers--user_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-admin-interviewers--user_id-"
                    onclick="cancelTryOut('DELETEapi-admin-interviewers--user_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-admin-interviewers--user_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/admin/interviewers/{user_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-admin-interviewers--user_id-"
               value="Bearer {YOUR_SANCTUM_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_SANCTUM_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-admin-interviewers--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-admin-interviewers--user_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user_id"                data-endpoint="DELETEapi-admin-interviewers--user_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the user. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="user"                data-endpoint="DELETEapi-admin-interviewers--user_id-"
               value="2"
               data-component="url">
    <br>
<p>The user ID of the interviewer. Example: <code>2</code></p>
            </div>
                    </form>

                <h1 id="public">Public</h1>

    

                                <h2 id="public-GETapi-landing">Get Landing Page Departments</h2>

<p>
</p>

<p>Returns a database-driven list of departments/biros displayed on the public landing page.
Includes safe public agenda and program kerja fields for the landing profile section.
No authentication required.</p>

<span id="example-requests-GETapi-landing">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/landing" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/landing"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/landing';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-landing">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">[
    {
        &quot;name&quot;: &quot;Biro Kreatif&quot;,
        &quot;description&quot;: &quot;Biro Kreatif HIMATIK adalah biro yang dibentuk untuk menangani desain publikasi.&quot;,
        &quot;work_programs&quot;: [],
        &quot;agendas&quot;: [
            {
                &quot;title&quot;: &quot;Produksi Company Profile&quot;,
                &quot;description&quot;: null,
                &quot;start_date&quot;: null,
                &quot;end_date&quot;: null,
                &quot;location&quot;: null
            }
        ]
    }
]</code>
 </pre>
    </span>
<span id="execution-results-GETapi-landing" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-landing"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-landing"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-landing" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-landing">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-landing" data-method="GET"
      data-path="api/landing"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-landing', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-landing"
                    onclick="tryItOut('GETapi-landing');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-landing"
                    onclick="cancelTryOut('GETapi-landing');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-landing"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/landing</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-landing"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-landing"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="public-GETapi-announcements">Get Accepted Candidates List</h2>

<p>
</p>

<p>Returns a public list of accepted candidates with their assigned department.
Only returns data when announcements have been published by the admin.</p>

<span id="example-requests-GETapi-announcements">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/announcements" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/announcements"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/announcements';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-announcements">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;is_published&quot;: true,
    &quot;data&quot;: [
        {
            &quot;nama&quot;: &quot;Ahmad Rizki&quot;,
            &quot;nim&quot;: &quot;2211501234&quot;,
            &quot;prodi&quot;: &quot;Teknik Informatika&quot;,
            &quot;assigned_department&quot;: &quot;Biro Humas&quot;
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (200, Not yet published):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;success&quot;: true,
    &quot;is_published&quot;: false,
    &quot;data&quot;: []
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-announcements" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-announcements"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-announcements"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-announcements" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-announcements">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-announcements" data-method="GET"
      data-path="api/announcements"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-announcements', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-announcements"
                    onclick="tryItOut('GETapi-announcements');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-announcements"
                    onclick="cancelTryOut('GETapi-announcements');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-announcements"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/announcements</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-announcements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-announcements"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="endpoints">Endpoints</h1>

    

                                <h2 id="endpoints-GETapi-documentation">Handles the API request and renders the Swagger documentation view.</h2>

<p>
</p>



<span id="example-requests-GETapi-documentation">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/documentation" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/documentation"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/documentation';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-documentation">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&lt;!DOCTYPE html&gt;
&lt;html lang=&quot;en&quot;&gt;
&lt;head&gt;
    &lt;meta charset=&quot;UTF-8&quot;&gt;
    &lt;title&gt;L5 Swagger UI&lt;/title&gt;
    &lt;link rel=&quot;stylesheet&quot; type=&quot;text/css&quot; href=&quot;http://localhost:8000/api/docs/asset/swagger-ui.css?v=6c89a7b56731679f8e6fbbe97762408d&quot;&gt;
    &lt;link rel=&quot;icon&quot; type=&quot;image/png&quot; href=&quot;http://localhost:8000/api/docs/asset/favicon-32x32.png?v=40d4f2c38d1cd854ad463f16373cbcb6&quot; sizes=&quot;32x32&quot;/&gt;
    &lt;link rel=&quot;icon&quot; type=&quot;image/png&quot; href=&quot;http://localhost:8000/api/docs/asset/favicon-16x16.png?v=f0ae831196d55d8f4115b6c5e8ec5384&quot; sizes=&quot;16x16&quot;/&gt;
    &lt;style&gt;
    html
    {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
    }
    *,
    *:before,
    *:after
    {
        box-sizing: inherit;
    }

    body {
      margin:0;
      background: #fafafa;
    }
    &lt;/style&gt;
    &lt;/head&gt;

&lt;body &gt;
&lt;div id=&quot;swagger-ui&quot;&gt;&lt;/div&gt;

&lt;script src=&quot;http://localhost:8000/api/docs/asset/swagger-ui-bundle.js?v=f1a737f742dd692d123be959f22f726f&quot;&gt;&lt;/script&gt;
&lt;script src=&quot;http://localhost:8000/api/docs/asset/swagger-ui-standalone-preset.js?v=7289ecfaa5507c519cb95606e6216dbb&quot;&gt;&lt;/script&gt;
&lt;script&gt;
    window.onload = function() {
        const urls = [];

                    urls.push({name: &quot;L5 Swagger UI&quot;, url: &quot;http://localhost:8000/api/docs?api-docs.json&quot;});
        
        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: &#039;#swagger-ui&#039;,
            urls: urls,
            &quot;urls.primaryName&quot;: &quot;L5 Swagger UI&quot;,
            operationsSorter: null,
            configUrl: null,
            validatorUrl: null,
            oauth2RedirectUrl: &quot;http://localhost:8000/api/oauth2-callback&quot;,

            requestInterceptor: function(request) {
                request.headers[&#039;X-CSRF-TOKEN&#039;] = &#039;&#039;;
                return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: &quot;StandaloneLayout&quot;,
            docExpansion : &quot;none&quot;,
            deepLinking: true,
            filter: true,
            persistAuthorization: &quot;false&quot;,

        })

        window.ui = ui

            }
&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;
</code>
 </pre>
    </span>
<span id="execution-results-GETapi-documentation" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-documentation"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-documentation"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-documentation" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-documentation">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-documentation" data-method="GET"
      data-path="api/documentation"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-documentation', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-documentation"
                    onclick="tryItOut('GETapi-documentation');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-documentation"
                    onclick="cancelTryOut('GETapi-documentation');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-documentation"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/documentation</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-documentation"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-documentation"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-GETapi-oauth2-callback">Handles the OAuth2 callback and retrieves the required file for the redirect.</h2>

<p>
</p>



<span id="example-requests-GETapi-oauth2-callback">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/oauth2-callback" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/oauth2-callback"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/oauth2-callback';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-GETapi-oauth2-callback">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <details class="annotation">
            <summary style="cursor: pointer;">
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">content-type: text/html; charset=utf-8
cache-control: no-cache, private
access-control-allow-origin: *
 </code></pre></details>         <pre>

<code class="language-json" style="max-height: 300px;">&lt;!doctype html&gt;
&lt;html lang=&quot;en-US&quot;&gt;
&lt;body&gt;
&lt;script src=&quot;oauth2-redirect.js&quot;&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;
</code>
 </pre>
    </span>
<span id="execution-results-GETapi-oauth2-callback" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-oauth2-callback"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-oauth2-callback"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-oauth2-callback" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-oauth2-callback">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-oauth2-callback" data-method="GET"
      data-path="api/oauth2-callback"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-oauth2-callback', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-oauth2-callback"
                    onclick="tryItOut('GETapi-oauth2-callback');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-oauth2-callback"
                    onclick="cancelTryOut('GETapi-oauth2-callback');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-oauth2-callback"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/oauth2-callback</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-oauth2-callback"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-oauth2-callback"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="endpoints-POSTapi-admin-criteria--department_id--reset">POST api/admin/criteria/{department_id}/reset</h2>

<p>
</p>



<span id="example-requests-POSTapi-admin-criteria--department_id--reset">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/admin/criteria/1/reset" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8000/api/admin/criteria/1/reset"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8000/api/admin/criteria/1/reset';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>

</span>

<span id="example-responses-POSTapi-admin-criteria--department_id--reset">
</span>
<span id="execution-results-POSTapi-admin-criteria--department_id--reset" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-admin-criteria--department_id--reset"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-criteria--department_id--reset"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-admin-criteria--department_id--reset" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-criteria--department_id--reset">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-admin-criteria--department_id--reset" data-method="POST"
      data-path="api/admin/criteria/{department_id}/reset"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-criteria--department_id--reset', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-admin-criteria--department_id--reset"
                    onclick="tryItOut('POSTapi-admin-criteria--department_id--reset');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-admin-criteria--department_id--reset"
                    onclick="cancelTryOut('POSTapi-admin-criteria--department_id--reset');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-admin-criteria--department_id--reset"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/admin/criteria/{department_id}/reset</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-admin-criteria--department_id--reset"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-admin-criteria--department_id--reset"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>department_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="department_id"                data-endpoint="POSTapi-admin-criteria--department_id--reset"
               value="1"
               data-component="url">
    <br>
<p>The ID of the department. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                            </div>
            </div>
</div>
</body>
</html>
