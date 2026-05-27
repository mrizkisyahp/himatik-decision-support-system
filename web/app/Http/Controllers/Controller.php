<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "HIMATIK DSS Recruitment API Documentation",
    version: "1.0.0",
    description: "API endpoints for the HIMATIK Decision Support System (DSS) Staff Recruitment campaign."
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local Development Server"
)]
abstract class Controller
{
    //
}
