<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class UserForm extends Form
{
    public function buildForm()
    {

        $id = $this->getData('id') ?? "NULL";

        $this
//            ->add('type', 'choice', [
//                'label' => 'Tipo',
//                'choices' => ['cpf' => 'Pessoa Física', 'cnpj' => 'Pessoa Jurídica'],
//                'choice_options' => [
//                    'wrapper' => ['class' => 'choice-wrapper'],
//                    'label_attr' => ['class' => 'label-class'],
//                    'attr' => ['onchange' => 'aplicaMascara(this.value)']
//                ],
//                'empty_value' => 'Selecione',
//                'expanded' => false,
//                'multiple' => false,
//                'rules' => "required"
//            ])
            ->add('cpf', 'text', [
                'label' => 'CPF',
                'rules' => "required|max:18|unique:users,cpf,{$id}"
            ])
            ->add('name', 'text', [
                'label' => 'Nome',
                'rules' => 'required|max:255'
            ])
            ->add('email', 'email', [
                'label' => 'E-mail',
                'rules' => "required|max:255|email|unique:users,email,{$id}"
            ]);
    }

}
