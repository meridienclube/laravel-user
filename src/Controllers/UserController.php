<?php

namespace ConfrariaWeb\User\Controllers;

use ConfrariaWeb\User\Requests\StoreUserRequest;
use ConfrariaWeb\User\Requests\UpdateUserRequest;
use ConfrariaWeb\User\Resources\Select2UserResource;
use ConfrariaWeb\User\Resources\UserResource;
use ConfrariaWeb\User\Resources\UserSelectCollection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [];
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
        $all = array_filter($request->all(), function ($e) {
            if (is_array($e)) {
                return array_filter($e);
            }
            return $e;
        });
        $this->data['get'] = $all;
        $this->data['roles'] = resolve('RoleService')->all();
        return view(config('cw_user.views') . 'users.index', $this->data);
    }

    public function create()
    {
        //dd(Auth::user()->allowedRoles);
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['contact_types'] = resolve('ContactTypeService')->pluck('name', 'id');
        $this->data['employees'] = resolve('UserService')->employees()->pluck('name', 'id');
        return view(config('cw_user.views') . '.create', $this->data);
    }

    public function store(StoreUserRequest $request)
    {
        $user = resolve('UserService')->create($request->all());
        return redirect()
            ->route('admin.users.edit', $user->id)
            ->with('status', 'Cadastro criada com sucesso!');
    }

    public function show($id, $page = 'overview')
    {
        $this->data['buttons']['javascript:void(0);'] = [
            'label' => __('plan.new'),
            'class' => 'btn-success',
            'attributes' => 'data-toggle=modal data-target=#modalNewPlan' . $id
        ];
        $this->data['buttons'][route('admin.tasks.create', ['destinated_id' => $id])] = [
            'class' => 'btn-default',
            'label' => __('tasks.new')
        ];
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['contact_types'] = resolve('ContactTypeService')->pluck('name', 'id');
        $this->data['page'] = 'users.show.' . $page;
        $this->data['user'] = resolve('UserService')->find($id);
        return view(config('cw_user.views') . '.show', $this->data);
    }

    public function edit($id)
    {
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['user'] = resolve('UserService')->find($id);
        $this->data['employees'] = resolve('UserService')->employees()->pluck('name', 'id');
        $this->data['users'] = resolve('UserService')->pluck();
        $this->data['contact_types'] = resolve('ContactTypeService')->pluck('name', 'id');
        return view(config('cw_user.views') . '.edit', $this->data);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = resolve('UserService')->update($request->all(), $id);
        return redirect()
            ->route('admin.users.edit', $user->id)
            ->with('status', 'Pessoa editado com sucesso!');
    }

    public function destroy($id)
    {
        $user = resolve('UserService')->destroy($id);
        if (!$user) {
            return back()->withInput()->with('danger', __('users.deleted.error'));
        }
        return redirect()
            ->route('admin.users.index')
            ->with('status', __('users.deleted.name', ['name' => $user->name]));
    }

}
