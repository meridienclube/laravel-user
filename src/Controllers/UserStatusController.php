<?php
namespace ConfrariaWeb\User\Controllers;

use ConfrariaWeb\User\Requests\StoreUserRequest;
use ConfrariaWeb\User\Requests\UpdateUserRequest;
use ConfrariaWeb\User\Resources\Select2UserStatusResource;
use ConfrariaWeb\User\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserStatusController extends Controller
{
    protected $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function index(Request $request, $page = 'index')
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(StoreUserRequest $request)
    {
        //
    }

    public function show($id, $page = 'overview')
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(UpdateUserRequest $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
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
        $data['name'] = isset($data['term'])? $data['term'] : NULL;
        $status = resolve('UserStatusService')->where($data)->get();
        return Select2UserStatusResource::collection($status);
    }
}
