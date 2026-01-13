<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\BadRequestException;
use App\Exceptions\RoleNotFoundException;
use App\Exceptions\UserNotFoundException;


use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\UserCreateRequest;
use App\Services\V1\Admin\UserService;


use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $role)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->setInput($request)
                ->getAllUsers();
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request, $role)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->setInput($request)
                ->createUser();
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (BadRequestException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($role, $id)
    {
        try {
            return $this->userService
                ->userExists()
                ->roleExists($role)
                ->showUser($id);
        } catch (RoleNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (UserNotFoundException $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->sendErrorResponse($e->getMessage(), $e->getMessage(), 403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
