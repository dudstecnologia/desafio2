<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use DB;

use App\Curso;
use App\Professor;

class CursoController extends Controller
{
    
    public function index() {

        $professores = $this->getProfessores();

        foreach ($professores as $p) {
            $p->id_professor = 0;
        }

        $cursos = DB::table('curso')
            ->join('professor', 'professor.id_professor', '=', 'curso.id_professor')
            ->select('curso.id_curso', 'curso.nome as nome_curso', 'curso.data_criacao', 'professor.nome as nome_professor')
            ->get();

        foreach($cursos as $c) {
            $c->id_encrypt      = encrypt($c->id_curso);
            $c->data_criacao    = date('d/m/Y', strtotime($c->data_criacao));
            $c->id_curso        = 0;
        }

        return ['professores' => $professores, 'cursos' => $cursos];
    }

    public function select($id) {

        try {
            $id = decrypt($id);

            $professores = $this->getProfessores();

            $curso = Curso::find($id);

            foreach ($professores as $p) {
                if($p->id_professor == $curso['id_professor']) {
                    $curso->id_professor_encrypt = $p->id_encrypt;
                }

                $p->id_professor = 0;
            }

            $curso->id_encrypt = encrypt($curso->id_curso);
            $curso->id_curso = 0;
            $curso->id_professor = 0;

            return ['professores' => $professores, 'curso' => $curso];
            
        } catch(\Exception $erro) {
                
            return ['retorno'=>'Ops! Ocorreu um erro ao selecionar'];
        }
    }

    public function store(Request $request) {

        if(!$this->validaForm($request))
            return ['retorno'=>'Ops! O campo Nome é obrigatório'];
        
        if(DB::table('curso')
            ->where('nome', $request->nome)
            ->where('id_professor', decrypt($request->id_professor))
            ->count() != 0) {
            
            return ['retorno'=>'Ops! Este Curso já está cadastrado'];
        }

        try {

            $curso = new Curso();

            $curso->nome            = $request->nome;
            $curso->id_professor    = decrypt($request->id_professor);
            $curso->data_criacao    = date('Y-m-d');

            $curso->save();

            return ['retorno'=>'Curso salvo com sucesso'];

        } catch(\Exception $erro) {

            return ['retorno'=>'Ops! Ocorreu um erro ao salvar'];
        }
    }

    public function update(Request $request, $id) {

        if(!$this->validaForm($request))
            return back()->with('alert', 'Ops! O campo Nome é obrigatório');

        try {
            
            $id = decrypt($id);

            $curso = Curso::find($id);

            $curso->nome         = $request->nome;
            $curso->id_professor = decrypt($request->id_professor);

            $curso->save();

            return ['retorno'=>'Curso atualizado com sucesso'];
            
        } catch(\Exception $erro) {

            return ['retorno'=>'Ops! Ocorreu um erro ao atualizar'];
        }

    }

    public function delete($id) {

        try {
            $id = decrypt($id);

            $curso = Curso::find($id);

            $curso->delete();

            return ['retorno'=>'Curso excluído com sucesso'];

        } catch(\Exception $erro) {

            return ['retorno'=>'Ops! Ocorreu um erro ao excluir'];
        }
    }

    private function validaForm(Request $request) {

        $validator = Validator::make($request->all(), [
            'nome' => 'required',
        ]);

        if ($validator->fails())
            return false;
        
        return true;
    }

    private function getProfessores() {

        $professores = Professor::all('id_professor', 'nome');

        foreach($professores as $p) {
            $p->id_encrypt = encrypt($p->id_professor);
        }

        return $professores;
    }
    
}
