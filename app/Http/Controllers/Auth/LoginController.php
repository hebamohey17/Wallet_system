<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Common\Response;
use App\Exceptions\UserNotFoundException;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function __construct(Response $response, User $user)
    {
        $this->user = $user;
        $this->response = $response;
    }

    public function login(LoginRequest $request)
    {
        try {
            $this->user->authenticate($request->login_data, $request->password, $request->device_token);
            $user = auth()->user();

            return (new UserResource($user))
                ->additional(['token' => $user->getToken()])
                ->response()
                ->setStatusCode(200);
        } catch (UserNotFoundException $exception) {
            return $exception->render();
        }
    }

    public function logout()
    {
        auth()->user()->logout();

        return $this->response->success([], 'successLogout');
    }
}
