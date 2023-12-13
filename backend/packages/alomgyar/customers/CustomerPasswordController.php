<?php

namespace Alomgyar\Customers;

use Alomgyar\Customers\Actions\UpdateCustomerPassword;
use Alomgyar\Customers\Events\CustomerSuccessfulPasswordResetEvent;
use Alomgyar\Customers\Requests\CustomerChangePasswordRequest;
use Alomgyar\Customers\Rules\PasswordValidationRules;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;

class CustomerPasswordController extends Controller
{
    use PasswordValidationRules;

    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UpdateCustomerPassword $updater)
    {
        $customer = $this->validateToken($request->body);

        if (is_a($customer, MessageBag::class)) {
            return response(['data' => ['errors' => $customer]], 400);
        }
        $updater->update($customer, $request->body);
        event(new CustomerSuccessfulPasswordResetEvent($customer));

        //Delete the tokens
        DB::table('password_resets')->where('email', $customer->email)->delete();
        DB::table('personal_access_tokens')->where([
            'tokenable_id' => $customer->id,
            'tokenable_type' => 'Alomgyar\Customers\Customer',
            'name' => 'auth',
        ])->delete();

        return $request->wantsJson()
            ? new JsonResponse(['data' => ['message' => 'Jelszó módosítás sikeres. Kérlek jelentkezz be új jelszavaddal']], 200)
            : back()->with('status', 'password-updated');
    }

    public function changePassword(CustomerChangePasswordRequest $request)
    {
        $user = Customer::where('email', $request->body['email'])->where('store', request('store'))->first();
        if (! $user) {
            return response()->json(['error' => 'Felhasználó nem található'], 401);
        }
        $user->password = bcrypt($request->body['password']);
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'A jelszó sikeresen megváltozott']);
    }

    private function validateToken($input)
    {
        $input['store'] = request('store');
        $validator = Validator::make($input, [
            'token' => ['required', 'string'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
            ],
            'store' => ['required', Rule::in([0, 1, 2])],
            'password' => $this->passwordRules(),
        ], [
            'token.required' => 'A token megadása kötelező',
            'email.required' => 'Az email megadása kötelező',
            'email.email' => 'Érvénytelen email formátum',
            'password.required' => 'Jelszó mező kitöltése kötelező',
            'password.confirmed' => 'Jelszó mezők nem egyeznek',
        ])->after(function ($validator) use ($input) {
            if (! isset($input['email']) || ! isset($input['token'])) {
//                $validator->errors()->add('auth', __('auth.failed'));
                return;
            }
            $token = DB::table('password_resets')
                ->whereEmail($input['email'])
                ->where('created_at', '>=', Carbon::now()->subHours(1))
                ->first();

            if (empty($token) || ! Hash::check($input['token'], $token->token)) {
                $validator->errors()->add('auth', 'A jelszóváltoztatási kérelem lejárt. Kérjük kezdje újra a folyamatot.');
            }
        });
//        ->validateWithBag('updatePassword')

        if ($validator->fails()) {
            return $validator->errors();
        }

        return Customer::whereEmail($input['email'])->where('store', request('store'))->first();
    }
}
