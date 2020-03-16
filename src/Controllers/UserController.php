<?php

namespace ConfrariaWeb\User\Controllers;

use ConfrariaWeb\User\Requests\StoreUserRequest;
use ConfrariaWeb\User\Requests\UpdateUserRequest;
use ConfrariaWeb\User\Resources\Select2UserResource;
use ConfrariaWeb\User\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [];
        $this->data['statuses'] = resolve('UserStatusService')->pluck();
        $this->data['roles'] = resolve('RoleService')->pluck();
    }

    public function apiTokenGenerate($id)
    {
        $user = resolve('UserService')->apiTokenGenerate($id);
        return back()->withInput()->with('status', 'Token alterado com sucesso');
    }

    public function datatable(Request $request)
    {
        $data = $request->all();
        $data['where'] = [];
        if (isset($data['search']['value'])) {
            $data['where']['name'] = $data['search']['value'];
            $data['orWhere']['email'] = $data['search']['value'];
        }

        $datatable = resolve('UserService')->datatable($data);
        return (UserResource::collection($datatable['data']))
            ->additional([
                'draw' => $datatable['draw'],
                'recordsTotal' => $datatable['recordsTotal'],
                'recordsFiltered' => $datatable['recordsFiltered']
            ]);
    }

    public function select2(Request $request)
    {
        $data = $request->all();
        $data['name'] = isset($data['term']) ? $data['term'] : NULL;
        $users = resolve('UserService')->where($data)->get();
        return Select2UserResource::collection($users);
    }

    public function index(Request $request)
    {
        $this->data['get'] = array_filter($request->all(), function ($e) {
            if (is_array($e)) {
                return array_filter($e);
            }
            return $e;
        });
        $this->data['roles'] = resolve('RoleService')->all();
        return view(config('cw_user.views') . 'users.index', $this->data);
    }

    public function create()
    {
        return view(config('cw_user.views') . 'users.create', $this->data);
    }

    public function store(StoreUserRequest $request)
    {
        $user = resolve('UserService')->create($request->all());
        return redirect()
            ->route('admin.users.edit', $user->id)
            ->with('status', 'Cadastro criado com sucesso!');
    }

    public function show($id, $page = 'overview')
    {
        $this->data['user'] = resolve('UserService')->find($id);
        return view(config('cw_user.views') . 'users.show', $this->data);
    }

    public function edit($id)
    {
        $this->data['user'] = resolve('UserService')->find($id);
        return view(config('cw_user.views') . 'users.edit', $this->data);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = resolve('UserService')->update($request->all(), $id);
        return redirect()
            ->route('admin.users.edit', $user->id)
            ->with('status', 'Usuário editado com sucesso!');
    }

    public function destroy($id)
    {
        $user = resolve('UserService')->destroy($id);
        if (!$user) {
            return back()->withInput()->with('danger', 'Usuário não encontrado');
        }
        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuário deletado com sucesso');
    }

}
