<?php

namespace App\Http\Controllers;

use Alomgyar\Comments\Comment;
use Alomgyar\Posts\Post;
use Alomgyar\Products\Product;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CustomerProductCommentResource;
use App\Http\Traits\ErrorMessages;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

class CommentApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['get', 'add', 'update', 'destroy'];

    private int $perPage;

    private int $page;

    private mixed $customer;

    private array $where;

    public function __construct()
    {
        $this->refCheck();

        $this->perPage = 20;
        $this->page = request()->body['page'] ?? 1;
        $this->customer = (isset(request()->body['customer']) && request()->body['customer']) ? $this->getCustomer() : null;
        $this->where = [
            $this->whereEntity(),
            ['store', request('store')],
        ];
    }

    private function refCheck()
    {
        if (! in_array(request('ref'), $this->validRefs) && request()->expectsJson()) {
            return $this->badRefMessage();
        }
    }

    public function get()
    {
        if (! isset(request()->body['entity_id']) && ! isset(request()->body['slug'])) {
            return $this->missingRequiredParameterMessage();
        }

        if ($this->customer) {
            return $this->customerComments();
        } else {
            return $this->guestComments();
        }
    }

    private function guestComments()
    {
        $comments = $this->getComments();

        return response([
            'data' => [
                'comments' => CommentResource::collection($comments),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                    'last_page' => ! $comments->hasMorePages(),
                ],
            ],
        ]);
    }

    private function customerComments()
    {
        array_push($this->where, ['customer_id', $this->customer->id]);
        $comments = Comment::select('id')->active()->where($this->where)->latest()->get();

        return response([
            'data' => [
                'comments' => CustomerProductCommentResource::collection($comments),
            ],
        ]);
    }

    public function store()
    {
        if (! isset(request()->body['entity_type']) || ! isset(request()->body['entity_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = $this->validator();

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        Comment::create([
            'comment' => request()->body['comment'],
            'original_comment' => request()->body['comment'],
            'product_id' => request()->body['entity_type'] === 'product' ? request()->body['entity_id'] : null,
            'post_id' => request()->body['entity_type'] === 'magazine' ? request()->body['entity_id'] : null,
            'entity_type' => $this->setEntityType(request()->body['entity_type']),
            'customer_id' => request()->user()->id,
            'status' => 1,
            'store' => request('store'),
        ]);

        $comments = $this->getComments();

        return response([
            'data' => [
                'comments' => CommentResource::collection($comments),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                    'last_page' => ! $comments->hasMorePages(),
                ],
            ],
        ]);
    }

    private function setEntityType(string $entityType)
    {
        if ($entityType === 'product') {
            return Comment::ENTITY_PRODUCT;
        }

        if ($entityType === 'magazine') {
            return Comment::ENTITY_MAGAZINE;
        }
    }

    public function update()
    {
        if (! isset(request()->body['comment_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $validator = $this->validator();

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        $comment = Comment::find(request()->body['comment_id']);

        if (empty($comment)) {
            return $this->badMethodMessage();
        }

        if ($comment->customer_id != request()->user()->id) {
            return $this->authFailedMessage();
        }

        $comment->update([
            'comment' => request()->body['comment'],
            'status' => 1,
        ]);

        return response([
            'data' => new CommentResource($comment),
        ]);
    }

    public function destroy()
    {
        if (empty(request()->body['comment_id'])) {
            return $this->missingRequiredParameterMessage();
        }

        $comment = Comment::find(request()->body['comment_id']);

        if (empty($comment)) {
            return $this->badMethodMessage();
        }

        if ($comment->customer_id != request()->user()->id) {
            return $this->authFailedMessage();
        }

        $comment->delete();

        $comments = $this->getComments($comment->product_id ?? $comment->post_id);

        return response([
            'data' => [
                'comments' => CommentResource::collection($comments),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                    'last_page' => ! $comments->hasMorePages(),
                ],
            ],
        ]);
    }

    private function validator()
    {
        $validator = Validator::make(request()->body, [
            'comment' => ['required'],
        ]);

        return $validator;
    }

    private function getCustomer()
    {
        if ($token = request()->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            if (! empty($accessToken)) {
                return $accessToken->tokenable;
            }
        }
    }

    private function whereEntity()
    {
        if (isset(request()->body['entity_id']) || isset(request()->body['slug'])) {
            $entityId = request()->body['entity_id'];

            if (! isset(request()->body['entity_id']) && isset(request()->body['slug'])) {
                $entityId = request()->body['entity_type'] === 'product'
                    ? Product::whereSlug(request()->body['slug'])->thisStore()->first()->id
                    : Post::whereSlug(request()->body['slug'])->first()->id;
            }

            return request()->body['entity_type'] === 'product'
                ? ['product_id', $entityId]
                : ['post_id', $entityId];
        }
    }

    private function getComments($entityId = null)
    {
        if ($entityId) {
            $entityType = request()->body['entity_type'] === 'product' ? 'product_id' : 'post_id';
            $this->where[0] = [$entityType, $entityId];
        }

        return Comment::with('customer')
                            ->active()
                            ->where($this->where)
                            ->latest()
                            ->paginate($this->perPage, ['*'], 'page', $this->page);
    }
}
