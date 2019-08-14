<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

use DB;
use App\Professor;

class ProfessorController extends Controller
{
    
    public function index() {
        
        $professores = Professor::all();

        foreach($professores as $p) {
            $p->id_encrypt = encrypt($p->id_professor);
            $p->data_nascimento = date('d/m/Y', strtotime($p->data_nascimento));
            $p->data_criacao    = date('d/m/Y', strtotime($p->data_criacao));
            $p->id_professor = 0;
        }

        return $professores;
    }

    public function select($id) {

        try {
            $id = decrypt($id);

            $professor = Professor::find($id);

            $professor->id_encrypt = encrypt($professor->id_professor);
            $professor->id_professor = 0;
            
            return $professor;

        } catch(\Exception $erro) {
            
            return ['retorno'=>'Ops! Ocorreu um erro ao selecionar'];
        }
    }

    public function store(Request $request) {

        try {

            if(!$this->validaForm($request))
                return ['retorno'=>'Ops! Os campos Nome e Data de Nascimento são obrigatórios'];

            if(DB::table('professor')
                ->where('nome', $request->nome)
                ->where('data_nascimento', $request->data_nascimento)
                ->count() != 0) {
                
                return ['retorno'=>'Ops! Este Professor já está cadastrado'];
            }

            $professor = new Professor();

            $professor->nome            = $request->nome;
            $professor->data_nascimento = $request->data_nascimento;
            $professor->data_criacao    = date('Y-m-d');

            $professor->save();

            return ['retorno'=>'Professor salvo com sucesso'];

        } catch(\Exception $erro) {

            return ['retorno'=>$erro->getMessage()];
        }
    }
    
    public function update(Request $request, $id) {

        if(!$this->validaForm($request))
            return back()->with('alert', 'Ops! Os campos Nome e Data de Nascimento são obrigatórios');

        try {
            $id = decrypt($id);

            $professor = Professor::find($id);

            $professor->nome            = $request->nome;
            $professor->data_nascimento = $request->data_nascimento;

            $professor->save();

            return ['retorno'=>'Professor atualizado com sucesso'];
            
        } catch(\Exception $erro) {

            return ['retorno'=>'Ops! Ocorreu um erro ao atualizar'];
        }
    }

    public function delete($id) {

        try {

            $id = decrypt($id);

            $professor = Professor::find($id);

            $professor->delete();

            return ['retorno'=>'Professor excluído com sucesso'];

        } catch(\Exception $erro) {

            return ['retorno'=>'Ops! Ocorreu um erro ao excluir'];
        }
    }
    
    private function validaForm(Request $request) {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
            'data_nascimento' => 'required',
        ]);

        if ($validator->fails())
            return false;
        
        return true;
    }

}
