<?php

namespace App\Http\Controllers;

use App\Http\Traits\ErrorMessages;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ErrorMessages;

    private $prefix;

    public function __invoke()
    {
        if (! request()->expectsJson()) {
            return $this->badMethodMessage();
        }

        $this->prefix = config('pam.api_prefix');
        if (is_null(json_decode(request()->getContent(), true)) || ! isset(json_decode(request()->getContent(), true)['request'])) {
            return $this->badMethodMessage();
        }
        $requests = json_decode(request()->getContent(), true)['request'];

        return $this->response($requests);
    }

    public function response($requests)
    {
        $storeId = request('store');
        $server = ['HTTP_ACCEPT' => 'application/json'];
        if (request()->bearerToken()) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer '.request()->bearerToken();
        }

        foreach ($requests as $request) {
            $req = Request::create($this->prefix.$storeId.$request['path'], $request['method'], $request, [], [], $server);
            $res = app()->handle($req);
            $success = $res->getStatusCode() >= 200 && $res->getStatusCode() < 400;

            $response[] = [
                'path' => $request['path'],
                'ref' => $request['ref'],
                'request_id' => $request['request_id'],
                'success' => $success,
                'status' => $res->getStatusCode(),
                'body' => json_decode($res->getContent()) ? (isset(json_decode($res->getContent())->data) ? json_decode($res->getContent())->data : json_decode($res->getContent())) : ['message' => 'Bad request'],
            ];
        }

        return [
            'success' => ! collect($response)->contains('success', false),
            'response' => $response,
        ];
    }
}
