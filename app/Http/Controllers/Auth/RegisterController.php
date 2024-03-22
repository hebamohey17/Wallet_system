<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Common\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Exceptions\RegisterException;
use App\Http\Requests\RegisterRequest;

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
            $user = $this->user->register(
                $request->email,
                $request->password,
                $request->device_token,
                $request->phone_number,
                $request->name,
            );

            return (new UserResource($user))
                ->additional(['token' => $user->getToken()])
                ->response()
                ->setStatusCode(200);
        } catch (RegisterException $exception) {
            return $exception->render();
        }
    }
}
