<?php

namespace App\Http\Controllers;

use App\Helpers\SenderHelper;
use App\Http\Traits\ErrorMessages;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NewsletterApiController extends Controller
{
    use ErrorMessages;

    private Client $client;

    private array $headers;

    private string $email;

    public function __invoke()
    {
        if (! isset(request()->body['email']) || ! isset(request()->body['marketing_accepted'])) {
            return $this->missingRequiredParameterMessage();
        }

        if (! isset(request()->body['marketing_accepted']) || request()->body['marketing_accepted'] == 0 || request()->body['marketing_accepted'] == false) {
            return $this->validatorErrorMessage(['marketing_accepted' => ['A feltételek elfogadása kötelező']]);
        }

        $this->client = new \GuzzleHttp\Client();
        $this->headers = [
            'Authorization' => 'Bearer '.config('pam.sender.api_token'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $this->email = request()->body['email'];

        $subscriber = $this->getSubscriberByEmail();
        if ($subscriber && $this->notSubscribedInTheGroup(collect($subscriber['data']['subscriber_tags']))) {
            return $this->subscribeToThisGroupToo($subscriber);
        }

        return $this->tryToSubscribeByEmail();
    }

    private function tryToSubscribeByEmail()
    {
        try {
            $response = $this->client->post(
                config('pam.sender.base_url').'subscribers',
                [
                    'headers' => $this->headers,
                    'json' => [
                        'email' => $this->email,
                        'groups' => [SenderHelper::getSenderGroupID()],
                    ],
                ]
            );

            return  $response->getBody();
        } catch (\Exception $e) {
            $response = '{'.Str::between($e->getMessage(), '{', '}').'}';

            return response(['data' => [
                'errors' => ['message' => [json_decode($response, true)['message']]],
            ]], $e->getCode());
        }
    }

    private function getSubscriberByEmail()
    {
        try {
            $response = $this->client->get(
                config('pam.sender.base_url').'subscribers/by_email/'.$this->email,
                [
                    'headers' => $this->headers,
                ]
            );

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function notSubscribedInTheGroup(Collection $subscriberGroups)
    {
        return ! $subscriberGroups->pluck('id')->contains(SenderHelper::getSenderGroupID());
    }

    private function subscribeToThisGroupToo($subscriber)
    {
        $response = $this->client->post(
            config('pam.sender.base_url').'subscribers/groups/'.SenderHelper::getSenderGroupID(),
            [
                'headers' => $this->headers,
                'json' => ['subscribers' => [$subscriber['data']['id']]],
            ]
        );

        return $response->getBody();
    }
}
