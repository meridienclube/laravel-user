{{ Form::select2(
    $name,
    $value?? [],
    $value?? [],
    $attributes?? [],
    ['server_side' => ['route' => $route?? 'api.users.select2']]
) }}
