<?php

namespace App\Http\Controllers;

use App\Forms\RegisterFinishForm;
use App\Models\Document;
use App\Models\State;
use App\Table\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{

    public function __construct(Table $table)
    {
        $this->middleware('auth');
        $this->table = $table;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $this->table
            ->model(Document::class)
            ->where([
                'field' => 'user_id',
                'operator' => '=',
                'search' => $user->id
            ])
            ->columns([
                [
                    'label' => 'Perfil Pretendido',
                    'name' => 'role',
                    'order' => true //true, asc ou desc
                ],
                [
                    'label' => 'Portaria',
                    'name' => 'ordinance',
                    'order' => true //true, asc ou desc
                ],
                [
                    'label' => 'Declaração',
                    'name' => 'declaration',
                    'order' => true //true, asc ou desc
                ],
                [
                    'label' => 'Situação',
                    'name' => 'situation',
                    'order' => true
                ]

            ])
            ->filters([
                [
                    'name' => 'role',
                    'operator' => 'LIKE'
                ]
            ])
            ->addShowAction('documents.show')
            ->addEditAction('documents.edit')
            ->addDeleteAction('documents.destroy')
            //->addMoreAction([
            //    [
            //        'label' => 'Grupos',
            //        'route' => 'admin.users.create'
            //    ],
            //    [
            //        'label' => 'Unidades',
            //        'route' => 'admin.users.update'
            //    ]
            //])
            ->search();

        return view('adminlte::modules.register.finish.index', ['table' => $this->table]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $state = State::select(DB::raw("CONCAT(initials,' - ',name) AS name"),'id')
            ->orderBy('name','asc')->pluck('name','id')->toArray();

        $form = \FormBuilder::create(RegisterFinishForm::class,[
            'url' => route('documents.store'),
            'method' => 'POST',
            'data' => ['state' => $state, 'county' => []]
        ]);

        return view('adminlte::modules.register.finish.create',compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form = \FormBuilder::create(RegisterFinishForm::class);

        if(!$form->isValid()){
            return redirect()
                ->back()
                ->withErrors($form->getErrors())
                ->withInput();
        }

        $data = $form->getFieldValues();

        if($data['role']=='Nacional'){
            $data['state_id'] = null;
            $data['county_id'] = null;
        }

        if($data['role']=='Estadual'){
            $data['county_id'] = null;
            if($data['state_id'] == null){
                $request->session()->flash('error', 'Selecione um Estado!');
                return redirect()->back()->withInput();
            }
        }

        if($data['role']=='Municipal'){
            if($data['state_id'] == null){
                $request->session()->flash('error', 'Selecione um Estado!');
                return redirect()->back()->withInput();
            }else{
                if($data['county_id'] == null){
                    $request->session()->flash('error', 'Selecione um Município!');
                    return redirect()->back();
                }
            }
        }


        // Define o valor default para a variável que contém o nome da imagem
        $nameordinance = null;
        $namedeclaration = null;

        // Verifica se informou o arquivo e se é válido
        if ($request->hasFile('ordinance') && $request->file('ordinance')->isValid()) {

            // Define um aleatório para o arquivo baseado no timestamps atual
            $name = uniqid(date('HisYmd'));;

            // Recupera a extensão do arquivo
            $extension = $request->ordinance->extension();

            // Define finalmente o nome
            $nameordinance = "{$name}.{$extension}";

            // Faz o upload:
            $upload = $request->ordinance->storeAs('ordinance', $nameordinance);
            // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao

            // Verifica se NÃO deu certo o upload (Redireciona de volta)
            if ( !$upload ){
                return redirect()
                    ->back()
                    ->with('error', 'Falha ao fazer upload')
                    ->withInput();
            }

            $data['ordinance'] = $nameordinance;
        }

        if ($request->hasFile('declaration') && $request->file('declaration')->isValid()) {

            // Define um aleatório para o arquivo baseado no timestamps atual
            $name1 = uniqid(date('HisYmd'));;

            // Recupera a extensão do arquivo
            $extension1 = $request->declaration->extension();

            // Define finalmente o nome
            $namedeclaration = "{$name1}.{$extension1}";

            // Faz o upload:
            $upload1 = $request->declaration->storeAs('declaration', $namedeclaration);

            // Verifica se NÃO deu certo o upload (Redireciona de volta)
            if ( !$upload1 ){
                return redirect()
                    ->back()
                    ->with('error', 'Falha ao fazer upload')
                    ->withInput();
            }

            $data['declaration'] = $namedeclaration;
        }


        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['situation'] = 'Pendente de Envio';

        $document = Document::create($data);

        $request->session()->flash('message', 'Solicitação criada com sucesso!');

        return redirect()->route('documents.show', [ 'document' => $document->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        return view('adminlte::modules.register.finish.show',compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document)
    {
        $state = State::select(DB::raw("CONCAT(initials,' - ',name) AS name"),'id')
            ->orderBy('name','asc')->pluck('name','id')->toArray();

        $state_id = State::with('mesoregion.county')->find($document->state_id);
        $count = [];
        $state_id->mesoregion->each(function ($mesoregion) use (&$count){
            $count = array_merge($count,$mesoregion->county->sortBy("name")->toArray());
        });

        foreach ($count as $key => $value){
            $county[$value['id']] = $value['name'];
        }

        asort($county);


        $form = \FormBuilder::create(RegisterFinishForm::class,[
            'url' => route('documents.update', [ 'document' => $document->id ]),
            'method' => 'PUT',
            'model' => $document,
            'data' => ['state' => $state, 'county' => $county]
        ]);

        return view('adminlte::modules.register.finish.edit',compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Document $document, Request $request)
    {
        $user = Auth::user();

        if(($document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida') and $document->user_id == $user->id){
            $form = \FormBuilder::create(RegisterFinishForm::class, [
                'data' => ['id' => $document->id]
            ]);


            if(!$form->isValid()){
                return redirect()
                    ->back()
                    ->withErrors($form->getErrors())
                    ->withInput();
            }

            $data = $form->getFieldValues();



            if($data['role']=='Nacional'){
                $data['state_id'] = null;
                $data['county_id'] = null;
            }

            if($data['role']=='Estadual'){
                $data['county_id'] = null;
                if($data['state_id'] == null){
                    $request->session()->flash('error', 'Selecione um Estado!');
                    return redirect()->back()->withInput();
                }
            }

            if($data['role']=='Municipal'){
                if($data['state_id'] == null){
                    $request->session()->flash('error', 'Selecione um Estado!');
                    return redirect()->back()->withInput();
                }else{
                    if($data['county_id'] == null){
                        $request->session()->flash('error', 'Selecione um Município!');
                        return redirect()->back();
                    }
                }
            }


            // Define o valor default para a variável que contém o nome da imagem
            $nameordinance = null;
            $namedeclaration = null;

            // Verifica se informou o arquivo e se é válido
            if ($request->hasFile('ordinance') && $request->file('ordinance')->isValid()) {

                // Define um aleatório para o arquivo baseado no timestamps atual
                $name = uniqid(date('HisYmd'));;

                // Recupera a extensão do arquivo
                $extension = $request->ordinance->extension();

                // Define finalmente o nome
                $nameordinance = "{$name}.{$extension}";

                // Faz o upload:
                $upload = $request->ordinance->storeAs('ordinance', $nameordinance);
                // Se tiver funcionado o arquivo foi armazenado em storage/app/public/categories/nomedinamicoarquivo.extensao

                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if ( !$upload ){
                    return redirect()
                        ->back()
                        ->with('error', 'Falha ao fazer upload')
                        ->withInput();
                }

                $data['ordinance'] = $nameordinance;
            }

            if ($request->hasFile('declaration') && $request->file('declaration')->isValid()) {

                // Define um aleatório para o arquivo baseado no timestamps atual
                $name1 = uniqid(date('HisYmd'));;

                // Recupera a extensão do arquivo
                $extension1 = $request->declaration->extension();

                // Define finalmente o nome
                $namedeclaration = "{$name1}.{$extension1}";

                // Faz o upload:
                $upload1 = $request->declaration->storeAs('declaration', $namedeclaration);

                // Verifica se NÃO deu certo o upload (Redireciona de volta)
                if ( !$upload1 ){
                    return redirect()
                        ->back()
                        ->with('error', 'Falha ao fazer upload')
                        ->withInput();
                }

                $data['declaration'] = $namedeclaration;
            }


            $user = Auth::user();
            $data['user_id'] = $user->id;
            $data['situation'] = 'Pendente de Envio';

            \Storage::delete('ordinance/'.$document->ordinance);
            \Storage::delete('declaration/'.$document->declaration);

            $document->update($data);
            session()->flash('message','Solicitação editada com sucesso');

            return redirect()->route('documents.show', [ 'document' => $document->id ]);
        }

        return redirect()
            ->back()
            ->with('error', 'Essa solicitação não pode ser alterada!')
            ->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {

        $user = Auth::user();

        if(($document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida') and $document->user_id == $user->id){

            Storage::delete('ordinance/'.$document->ordinance);
            Storage::delete('declaration/'.$document->declaration);

            $document->delete();
            session()->flash('message','Solicitação excluída com sucesso');
            return redirect()->route('documents.index');
        }

        return redirect()
            ->back()
            ->with('error', 'Essa solicitação não pode ser excluida!')
            ->withInput();
    }

    public function send($id)
    {
        $document = Document::findOrFail($id);

        if( $document->situation == 'Pendente de Envio' or $document->situation == 'Devolvida'){

            $data['situation'] = 'Enviada';

            $document->update($data);

            session()->flash('message','Solicitação enviada com sucesso');
            return redirect()->route('documents.index');
        }

        return redirect()
            ->back()
            ->with('error', 'Essa solicitação não pode ser enviada!')
            ->withInput();
    }
}
