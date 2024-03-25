<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Common\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Exceptions\RegisterException;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __construct(Response $response, User $user)
    {
        $this->user = $user;
        $this->response = $response;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $userData = $data['user'];
            $userData['password'] = Hash::make($userData['password']);
            $user = $this->user->register($userData);
            $user->createWallet($data['wallet']);

            return (new UserResource($user))
                ->additional(['token' => $user->getToken()])
                ->response()
                ->setStatusCode(200);
        } catch (RegisterException $exception) {
            return $exception->render();
        }
    }
}
