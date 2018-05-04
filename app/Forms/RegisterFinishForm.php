<?php

namespace App\Forms;

use App\Models\County;
use Kris\LaravelFormBuilder\Form;

class RegisterFinishForm extends Form
{
    public function buildForm()
    {

        $model = $this->model;

        $state = $this->getData('state') ?? "NULL";

//        $county = $this->getData('county') ?? "NULL";

        $this
            ->add('ordinance', 'file', [
                'label' => 'Diário Oficial',
                'rules' => "required|mimes:pdf"
            ])
            ->add('declaration', 'file', [
                'label' => 'Declaração',
                'rules' => "required|mimes:pdf"
            ])
            ->add('role', 'select', [
                'label' => 'Perfil Pretendido',
                'rules' => "required",
                'choices' => ['state' => 'Estadual', 'county' => 'Municipal', 'national' => 'Nacional'],
                'empty_value' => 'Selecione',
                'rules' => "required"
            ])
            ->add('state_id', 'select', [
                'label' => 'Estado',
                'choices' => $state,
                'empty_value' => 'Selecione',
            ])
            ->add('county_id', 'select', [
                'label' => 'Município',
                'class' => County::class,
                'property' => 'name',
                'property_key' => 'id',
                'selected' => $this->model ? $this->model->state->county->id : '',
                'empty_value' => 'Selecione',
                'query_builder' => function (County $model) use($model){
//                    consultar os counties de acordo com o state
                    return $model->where('state_id', $model->state_id);
                }
            ]);
    }
}
