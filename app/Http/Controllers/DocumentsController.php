<?php

namespace App\Http\Controllers;

use App\Forms\RegisterFinishForm;
use App\Models\Document;
use App\Models\State;
use App\Table\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    'label' => 'ID',
                    'name' => 'id',
                    'order' => true //true, asc ou desc
                ],
                [
                    'label' => 'Portaria',
                    'name' => 'ordinance',
                    'order' => true //true, asc ou desc
                ],
                [
                    'label' => 'Perfil',
                    'name' => 'role',
                    'order' => true //true, asc ou desc
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

        if($data['role']=='national'){
            $data['state_id'] = null;
            $data['county_id'] = null;
        }

        if($data['role']=='state'){
            $data['county_id'] = null;
            if($data['state_id'] == null){
                $request->session()->flash('error', 'Selecione um Estado!');
                return redirect()->back()->withInput();
            }
        }

        if($data['role']=='county'){
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

//        dd($data);

        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['situation'] = 'PEN';

        $document = Document::create($data);

        $request->session()->flash('message', 'Solicitação criada com sucesso!');

        return redirect()->route('documents.edit', [ 'document' => $document->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {

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
            'url' => route('admin.users.update', [ 'user' => $document->id ]),
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();
        session()->flash('message','Solicitação excluída com sucesso');
        return redirect()->route('documents.index');
    }
}
