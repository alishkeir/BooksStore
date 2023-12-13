<?php
/*
Author: Hódi
Date: 2021. 06. 21. 14:39
Project: alomgyar-webshop-be
*/

namespace App\Http\Traits;

trait ErrorMessages
{
    private function missingRequiredParameterMessage()
    {
        return response([
            'data' => [
                'errors' => ['Hiányzó kötelező paraméter'],
            ],
        ], 400);
    }

    private function badRefMessage()
    {
        return response([
            'data' => [
                'errors' => ['Nem megfelelő Ref'],
            ],
        ], 400);
    }

    private function notFoundMessage()
    {
        return response([
            'data' => [
                'errors' => ['Nem található'],
            ],
        ], 404);
    }

    private function authFailedMessage()
    {
        return response([
            'data' => [
                'errors' => [__('auth.failed')],
            ],
        ], 403);
    }

    private function validatorErrorMessage($errors)
    {
        return response([
            'data' => [
                'errors' => [$errors],
            ],
        ], 400);
    }

    private function badMethodMessage()
    {
        return response([
            'data' => [
                'errors' => ['Hibás kérés'],
            ],
        ], 400);
    }
}
