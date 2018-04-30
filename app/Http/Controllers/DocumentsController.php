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
            'data' => ['state' => $state]
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
