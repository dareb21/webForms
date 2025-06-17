<?php
namespace App\Services;
Class googleUser
{
    public function convertToObjet()
    {
        return $user = (object) session('google_user');
    }
}
