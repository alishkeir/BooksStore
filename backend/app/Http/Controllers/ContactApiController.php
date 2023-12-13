<?php

namespace App\Http\Controllers;

use App\Http\Traits\ErrorMessages;
use App\Mail\ContactMail;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactApiController extends Controller
{
    use ErrorMessages;

    protected array $validRefs = ['send'];

    private string $adminEmail;

    private $token;

    public function __invoke(Request $request)
    {
        $this->refCheck();
        $this->setAdminEmail();

        $this->token = $request->body['captcha'];

        return $this->send($request->body);
    }

    private function refCheck()
    {
        if (! in_array(request('ref'), $this->validRefs) && request()->expectsJson()) {
            return $this->badRefMessage();
        }
    }

    private function send($prospect)
    {
        $validator = $this->validator();

        if ($validator->fails()) {
            return $this->validatorErrorMessage($validator->errors());
        }

        Mail::to($this->adminEmail)->send(new ContactMail($prospect, (int) request('store')));

        return response([
            'data' => [
                'success' => true,
                'message' => trans('messages.contact-success'),
            ],
        ], Response::HTTP_OK);
    }

    private function validator()
    {
        $validator = Validator::make(request()->body, [
            'subject' => ['required'],
            'name' => ['required'],
            'email' => ['required', 'email:dns,rfc'],
            'message' => ['required'],
            'privacy' => ['required', 'accepted'],
            'captcha' => ['required'],
        ], [], [
            'privacy' => 'Adatvédelmi Nyilatkozat',
        ]);

        if (isset(request()->body['captcha'])) {
            $validator->after(function ($validator) {
                $recaptchaResponse = $this->checkReCaptcha();
                if (! $recaptchaResponse?->tokenProperties?->valid) {
                    $validator->errors()->add(
    //                    'captcha', $recaptchaResponse->tokenProperties->invalidReason
                        'captcha', 'Hiba történt az űrlap elküldésekor. Kérjük próbálja meg újra.'
                    );
                }
            });
        }

        return $validator;
    }

    private function setAdminEmail()
    {
        $this->adminEmail = option('contact_email', 'admin@alomgyar.hu');
    }

    private function checkReCaptcha()
    {
        $client = new Client();
        $url = 'https://recaptchaenterprise.googleapis.com/v1beta1/projects/'.config('pam.google_project_id').'/assessments?key='.config('pam.google_api_key');

        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode([
                'event' => [
                    'token' => $this->token,
                    'siteKey' => config('pam.recaptcha_key'),
                    'expectedAction' => 'login',
                ],
            ]),
        ]);

        return json_decode($response->getBody());
    }
}
