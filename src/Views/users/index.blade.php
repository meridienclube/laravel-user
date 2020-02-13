@extends(config('cw_user.layout'))
@section('title', __('user::titles.users'))
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                @include(config('cw_user.views') . 'users.partials.list')
            </div>
        </div>
    </div>
@endsection
