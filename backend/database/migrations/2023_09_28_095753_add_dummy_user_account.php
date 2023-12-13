<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDummyUserAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $validUsername = 'alomgyar-kiajanlo';
        $validEmail = 'alomgyar-kiajanlo@alomgyar.hu';
        $validPassword = 'feltoltokEnIsAjanlast';
        // CHECK IF USER EXISTS
        // CREATE IF NOT EXISTS
        if (! DB::table('users')->where('email', $validEmail)->exists()) {
            $user = User::create([
                'name' => $validUsername,
                'email' => $validEmail,
                'password' => bcrypt($validPassword),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
