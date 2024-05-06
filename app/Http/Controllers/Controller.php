<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Startupium Api"
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="API Server"
 * )
 *@OA\Tag(
 *     name="Auth",
 *     description="API Endpoints of user login and register"
 * )
 * @OA\Tag(
 *     name="User projects",
 *     description="API Endpoints of user projects"
 * )
 *  @OA\Tag(
 *     name="User bookmarks",
 *     description="API Endpoints of user projects in bookmarks"
 * )
 *  @OA\Tag(
 *     name="User drafts",
 *     description="API Endpoints of user projects in drafts"
 * )
 * @OA\Tag(
 *     name="User favorites",
 *     description="API Endpoints of user projects in favorites"
 * )
 * @OA\Tag(
 *     name="Comments",
 *     description="API Endpoints of user comments to project"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="API Endpoints of user"
 * )
 * @OA\Tag(
 *     name="Projects",
 *     description="API Endpoints of Active or on Moderation projects"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
