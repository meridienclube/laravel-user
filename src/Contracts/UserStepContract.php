<?php

namespace ConfrariaWeb\User\Contracts;

interface UserStepContract
{

    public function all();

    public function create(array $data);

    public function destroy($id);

    public function find($id);

    public function findBy($field, $value);

    public function update(array $data, $id);

    public function updateOrCreate(array $attributes, array $values = array());

    public function where(array $data);
}
