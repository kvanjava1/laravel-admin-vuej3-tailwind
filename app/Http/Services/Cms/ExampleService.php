<?php

namespace App\Http\Services\Cms;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

use App\Http\Libraries\Message;

use App\Models\Mysql\User;

class ExampleService
{
    function __construct(Message $message, MessageBag $messageBag)
    {
        $this->message = $message;
        $this->messageBag = $messageBag;
    }
    
    public function setInput(Request $req): self
    {
        $this->req = $req;
        return $this;
    }

    public function checkProcess(): Message 
    {
        
    }
}
