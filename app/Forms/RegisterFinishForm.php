<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class RegisterFinishForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('ordinance', 'file', [
                'label' => 'Diário Oficial',
                'rules' => "required"
            ])
            ->add('declaration', 'file', [
                'label' => 'Declaração',
                'rules' => "required"
            ])
            ->add('role', 'select', [
                'label' => 'Perfil Pretendido',
                'rules' => "required",
                'choices' => ['national' => 'English', 'fr' => 'French'],
                'empty_value' => 'Selecione'
            ])
            ->add('state_id', 'select', [
                'label' => 'Estado'
            ])
            ->add('county_id', 'select', [
                'label' => 'Município'
            ]);
    }
}
