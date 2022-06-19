<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\articles;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class articleController extends Controller
{


    /**
     * view articles
     * @OA\get (
     *     path="/api/articles",
     *     tags={"articles"},
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="subject", type="string", example="title"),
     *              @OA\Property(property="likes", type="number", example=1),
     *              @OA\Property(property="views", type="number", example=1),
     *              @OA\Property(property="comments", type="string", example="{}"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     * @OA\Property(property="message", type="string", example="article not found"),
     *          )
     *      )
     * )
     */
    public function index() {
        // show a list of articles, 5 per page
        $articles = articles::latest();

        if($articles->count()){
            $all = articles::paginate(
                $perPage = 5,
                $columns = ['id', 'subject', 'likes', 'comments', 'views', 'created_at'],
                $pageName = 'list'
            )->toArray();

            return $all;

        }else{
            return [
                'status'=> 'false',
                'message' => 'No articles at the moment'
            ];
        }

    }


    /**
     * View Article
     * @OA\get (
     *     path="/api/article/{id}",
     *     tags={"view article"},
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="subject", type="string", example="subject"),
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="body", type="string", example="content"),
     *              @OA\Property(property="views", type="number", example=1),
     *              @OA\Property(property="likes", type="number", example=1),
     *              @OA\Property(property="tags", type="string", example="one, two"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     * @OA\Property(property="message", type="string", example="article not found"),
     *          )
     *      )
     * )
     */
    public function viewArticle($id){
        //views article based on the id provided
        $article = articles::find($id)->first();

        if($article){

            return [
                'subject'=>$article->subject,
                'id' => $article->id,
                'body' => substr($article->body, 0, 100),
                'views'=> count(json_decode($article->views, true)),
                'likes' => count(json_decode($article->views, true)),
                'tags' => $article->tags,
                'comments' => $article->comments
            ];

        }else{
            return [
                'status'=> 'failed',
                'message'=> 'article not found'
            ];
        }
    }

    /**
     * Add Comments
     * @OA\Post (
     *     path="/api/articles/{id}/comment",
     *     tags={"add comment"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="comment",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "comment":"Hello There",
     *                     "name":"Joel"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     * @OA\Property(property="message", type="string", example="article not found"),
     *          )
     *      )
     * )
     */

    public function comment(Request $request, $id) {
        // method for collecting and saving in the database

        sleep(600);
        $article = articles::find($id)->first();

        if($article){
            $validate = Validator::make($request->all(), [
                'comment' => 'required|text|min:50',
                'name' => 'required'
            ]);

            if ($validate->fails()) {
                return $validate->messages();
            }

            $comments = json_decode($article->comments, true);

            $comments[] = ['name'=>$request->name, 'comment'=>$request->comment];

            $article->update([
                'comment' => json_encode($comments)
            ]);

            return [
                'status' => 'success'
            ];

        }else{
            return [
                'status' => 'failed',
                'message' => 'article not found'
            ];
        }
    }



    /**
     * Update views
     * @OA\get (
     *     path="/api/articles/{id}/view",
     *     tags={"views"},
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     * @OA\Property(property="message", type="string", example="article not found"),
     *          )
     *      )
     * )
     */
    public function views($id){
        // to update the number of views
        $article = articles::find($id)->first();

        if ($article) {

            $views = json_decode($article->views, true);
            $t = true;
            for($i = 0; $i < count($views); $i++){
                if ($views[$i] == request()->getClientIp()) {
                    $t = false;
                    break;
                }
            }

        if($t){
            $views[] = request()->getClientIp();

            $article->update([
                'views' => count($views),
                'viewsIp' => json_encode($views)
            ]);

            return [
                'status' => 'success'
            ];
        }
        } else {
            return [
                'status' => 'failed',
                'message' => 'article not found'
            ];
        }
    }


    /**
     * update likes
     * @OA\get (
     *     path="/api/articles/{id}/like",
     *     tags={"views"},
     *
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="failed"),
     * @OA\Property(property="message", type="string", example="article not found"),
     *          )
     *      )
     * )
     */
    public function likes($id)
    {
        // update the number of likes
        $article = articles::find($id)->first();

        if ($article) {
            $likes = json_decode($article->likes, true);
            $t = true;
            for ($i = 0; $i < count($likes); $i++) {
                if ($likes[$i] == request()->getClientIp()) {
                    $t = false;
                    break;
                }
            }

            if ($t) {
                $likes[] = request()->getClientIp();

                $article->update([
                    'likes' => count($likes),
                    'likesIp' => json_encode($likes)
                ]);

                return [
                    'status'=>'success'
                ];
            }
        } else {
            return [
                'status' => 'failed',
                'message' => 'article not found'
            ];
        }
    }
}


