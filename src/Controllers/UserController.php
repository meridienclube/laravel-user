<?php
namespace ConfrariaWeb\User\Controllers;

use ConfrariaWeb\User\Requests\StoreUserRequest;
use ConfrariaWeb\User\Requests\UpdateUserRequest;
use ConfrariaWeb\User\Resources\UserResource;
use ConfrariaWeb\User\Resources\UserSelectCollection;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $data;
    public function __construct()
    {
        $this->data = [];
    }
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string $page
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request, $page = 'index')
    {
        $all = array_filter($request->all(), function ($e) {
            if (is_array($e)) {
                return array_filter($e);
            }
            return $e;
        });
        $this->data['get'] = $all;
        $this->data['roles'] = resolve('RoleService')->all();
        return view(config('cw_user.views') . $page, $this->data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(Auth::user()->allowedRoles);
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['contact_types'] = resolve('UserContactTypeService')->pluck('name', 'id');
        $this->data['employees'] = resolve('UserService')->employees()->pluck('name', 'id');
        return view(config('cw_user.views') . '.create', $this->data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = resolve('UserService')->create($request->all());
        return redirect()
            ->route('users.edit', $user->id)
            ->with('status', 'Cadastro criada com sucesso!');
    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $page = 'overview')
    {
        $this->data['buttons']['javascript:void(0);'] = [
            'label' => __('plan.new'),
            'class' => 'btn-success',
            'attributes' => 'data-toggle=modal data-target=#modalNewPlan' . $id
        ];
        $this->data['buttons'][route('tasks.create', ['destinated_id' => $id])] = [
            'class' => 'btn-default',
            'label' => __('tasks.new')
        ];
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['contact_types'] = resolve('UserContactTypeService')->pluck('name', 'id');
        $this->data['page'] = 'users.show.' . $page;
        $this->data['user'] = resolve('UserService')->find($id);
        return view(config('cw_user.views') . '.show', $this->data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->data['statuses'] = Auth::user()->roleStatuses->pluck('name', 'id');
        $this->data['roles'] = resolve('RoleService')->pluck();
        $this->data['user'] = resolve('UserService')->find($id);
        $this->data['employees'] = resolve('UserService')->employees()->pluck('name', 'id');
        $this->data['users'] = resolve('UserService')->pluck();
        $this->data['contact_types'] = resolve('UserContactTypeService')->pluck('name', 'id');
        return view(config('cw_user.views') . '.edit', $this->data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = resolve('UserService')->update($request->all(), $id);
        return redirect()
            ->route('users.edit', $user->id)
            ->with('status', 'Pessoa editado com sucesso!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = resolve('UserService')->destroy($id);
        if (!$user) {
            return back()->withInput()->with('danger', __('users.deleted.error'));
        }
        return redirect()
            ->route('users.index')
            ->with('status', __('users.deleted.name', ['name' => $user->name]));
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStep(Request $request)
    {
        $data = $request->all();
        $user = resolve('UserService')->updateStep($data['step_id'], $data['user_id']);
        return response()->json($user);
    }
    public function contactStore(Request $request, $id)
    {
        $user = resolve('UserService')->createContact($request->all(), $id);
        return back()->with('status', 'Contato cadastrada com sucesso!');
    }
    public function contactDestroy($user_id, $contact_id)
    {
        $user = resolve('UserService')->destroyContact($user_id, $contact_id);
        return back()->with('status', 'Contato deletado com sucesso!');
    }
    public function indicateStore(StoreUserRequest $request, $id)
    {
        $user = resolve('UserService')->createIndicate($request->all(), $id);
        return back()->with('status', 'Indicação cadastrada com sucesso!');
    }
    public function base(Request $request)
    {
        $all = $request->all();
        $this->data['roles'] = resolve('RoleService')->all();
        $this->data['get'] = $all;
        $this->data['steps'] = Auth::user()->roleSteps;
        $this->data['employees'] = resolve('UserService')->employees();
        $this->data['users'] = resolve('UserService')->base(Auth::id());
        //dd($this->data['users']);
        return view('users.index', $this->data);
    }
    public function sendSale(Request $request, $id)
    {
        $cpfcnpj = $request->input('cpf_cnpj');
        $user = resolve('UserService')->find($id);
        $userCpf = resolve('UserService')->findBy('cpf_cnpj', $cpfcnpj);
        if ($userCpf) {
            return back()->withInput();
        }
        resolve('UserService')->update(['cpf_cnpj' => $cpfcnpj], $user->id);
        if ($user) {
            $firstStep = $user->roleSteps->first();
            if ($firstStep) {
                resolve('UserService')->updateStep($firstStep->id, $id);
            }
            return redirect('http://lego.meridienclube.com.br/cadastro/associados/adicionar-plano?cpf=' . $cpfcnpj . '&' . http_build_query($user->toArray()));
        }
        return back()->withInput();
    }
    public function buttons_list($item)
    {
        $b = '<div class="btn-group btn-group-sm float-right" role="group" aria-label="...">
        <a href="http://intranet.meridienclube.com.br/intranet.php?load=Modulos-intranet-SAC-PesquisaAssociado"
        data-placement="bottom"
        class="btn btn-clean btn-icon btn-label-primary btn-icon-md "
        title="Extrato" data-toggle="kt-tooltip">
        <i class="la la-external-link"></i>
        </a>';
        if (auth()->user()->hasPermission('users.show')) {
            $b .= '<a href="' . route('users.show', $item->id) . '" data-placement="bottom"
            class="btn btn-clean btn-icon btn-label-primary btn-icon-md "
            title="Visualizar usuário" data-toggle="kt-tooltip">
            <i class="la la-eye"></i>
            </a>';
        }
        if (auth()->user()->hasPermission('users.edit')) {
            $b .= '<a href="' . route('users.edit', $item->id) . '" data-placement="bottom"
            class="btn btn-clean btn-icon btn-label-success btn-icon-md "
            title="Editar usuário" data-toggle="kt-tooltip">
            <i class="la la-edit"></i>
            </a>';
        }
        if (auth()->user()->hasPermission('users.destroy')) {
            $b .= '<a href="javascript:void(0);" data-placement="bottom"
            onclick="event.preventDefault();
            if(!confirm(\'Tem certeza que deseja deletar este item?\')){ return false; }
            document.getElementById(\'delete-user-' . $item->id . '\').submit();"
            class="btn btn-clean btn-icon btn-label-danger btn-icon-md "
            title="Deletar usuário" data-toggle="kt-tooltip">
            <i class="la la-remove"></i>
            </a>
            <form
            action="' . route('users.destroy', $item->id) . '"
            method="POST" id="delete-user-{{ $user->id }}">
            <input type="hidden" name="_method" value="DELETE">
            ' . csrf_field() . '
            </form>';
        }
        $b .= '</div>';
        return $b;
    }
    public function jsonKanban(Request $request)
    {
        //$all = array_filter($request->all());
        $user_id = $request->input('user_id');
        //$data = Cache::remember('jsonKanban', 600, function () use ($all) {
        $data = [];
        $UserService = resolve('UserService')->find($user_id);
        //foreach (Auth::user()->roleSteps as $roleStep) {
        foreach ($UserService->roleSteps as $roleStep) {
            $items = [];
            foreach ($roleStep->users as $user) {
                $lastTask = ($user->tasks()->whereDate('datetime', '<=', \Carbon\Carbon::now())->exists()) ?
                    '[' . $user->tasks()->whereDate('datetime', '<=', \Carbon\Carbon::now())->first()->datetime->format('d-m-Y') . ']' :
                    NULL;
                $items[] = [
                    'id' => Str::slug($user->name),
                    'title' => $user->name . '<br>' . $lastTask,
                    'data-userid' => $user->id,
                    'data-stepid' => $roleStep->id
                ];
            }
            $data[] = [
                'id' => Str::slug($roleStep->id),
                'title' => $roleStep->name,
                'class' => 'info',
                'data-stepid' => $roleStep->id,
                'item' => $items
            ];
        }
        //return $data;
        //});
        return response()->json($data);
    }
    public function datatable(Request $request)
    {
        $data = $request->all();
        $data['where'] = [];
        if (isset($data['search']['value'])) {
            $data['where']['name'] = $data['search']['value'];
            $data['orWhere']['contacts']['phone'] = $data['search']['value'];
            $data['orWhere']['contacts']['cellphone'] = $data['search']['value'];
            $data['orWhere']['contacts']['email'] = $data['search']['value'];
            $data['orWhere']['roles'][] = $data['search']['value'];
        }
        if (isset($data['columns'][1]['search']['value'])) {
            $data['where']['name'] = $data['columns'][1]['search']['value'];
        }
        if (isset($data['columns'][2]['search']['value'])) {
            $data['where']['cpf_cnpj'] = $data['columns'][2]['search']['value'];
        }
        if (isset($data['columns'][3]['search']['value'])) {
            $data['where']['roles'] = [$data['columns'][3]['search']['value']];
        }
        if (isset($data['columns'][4]['search']['value']) && $data['columns'][4]['search']['value'] > 0) {
            $data['where']['employees'] = [$data['columns'][4]['search']['value']];
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
        $data['name'] = isset($data['term']['term'])? $data['term']['term'] : NULL;
        $users = resolve('UserService')->where($data)->get();
        return new UserSelectCollection($users);
    }
}
