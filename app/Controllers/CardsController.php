<?php
namespace App\Controllers;

class CardsController extends BaseController{
     public function cards(): string
    {
        return view('app-cards');
    }
}