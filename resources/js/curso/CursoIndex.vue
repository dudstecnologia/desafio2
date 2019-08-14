<template>
    <div class="topo">
		<div class="row">
            <div class="col-sm">
                <h2>Curso</h2>
            </div>

            <div class="col-sm-alto">
                <b-button @click="openModal(null)" variant="info">Cadastrar Novo</b-button>
            </div>
        </div>

		<b-modal id="modal-1" ref="modal" title="Cadastro" hide-footer>
            
                <form v-on:submit="formCurso()">

                    <b-form-group label="Nome">
                        <b-form-input
                        id="nome"
                        v-model="curso.nome"
                        required
                        ></b-form-input>
                    </b-form-group>

					<b-form-group id="input-group-3" label="Professor" label-for="input-3">
						<b-form-select
						id="input-3"
						v-model="curso.id_professor"
						required >
						<option v-for="(p, index) in professores" :key="index" :value="p.id_encrypt" :selected="p.id_encrypt">{{ p.nome }}</option>
						</b-form-select>
					</b-form-group>

                    <div class="text-right">
                        <b-button type="submit" class="btn btn-success">Salvar</b-button>
                    </div>    
                </form>
            
        </b-modal>

		<div class="row">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Data de Criação</th>
                    <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(c, index) in cursos" :key="index" :value="c.id_encrypt">
                        <td>{{ c.nome_curso }}</td>
                        <td>{{ c.nome_professor }}</td>
                        <td>{{ c.data_criacao }}</td>
                        <td>
                            <button class="btn btn-sm btn-info"
                                @click="openModal(c.id_encrypt)">
                                    Editar
                                </button>
                            
                            <click-confirm id="clickconfirm"
                                :messages="{title: 'Deseja mesmo excluir?', yes: 'Sim', no: 'Não'}"
                                yes-class="btn btn-danger" >

                                <button class="btn btn-sm btn-danger"
                                @click="deleteCurso(c.id_encrypt, index)">
                                    Excluir
                                </button>

                            </click-confirm>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
</template>

<script>

import axios from 'axios';

export default {
    data() {
        return {
            id: null,
			professores: [],
			cursos: [],
            curso: {
                nome: '',
                id_professor: '',
            },

        };
    },
    created() {
        this.getDados();
    },
    methods: {
        getDados() {
            var app = this;
            axios
                .get('/lv_desafio2/public/api/curso')
                .then(resp => {
					
					//console.log(resp.data.professores);
					//console.log(resp.data.cursos);
					
					app.professores = resp.data.professores;
					app.cursos = resp.data.cursos;
                });
        },

        formCurso() {
            
            event.preventDefault();
            
            var app = this;
            var novoProfessor = app.professor;
            var url = '/lv_desafio2/public/api/curso';

            if(this.id) url = `/lv_desafio2/public/api/curso/${this.id}`;

            axios.post(url, {
                nome: app.curso.nome,
                id_professor: app.curso.id_professor
            })
                .then(function (resp) {
				
                //console.log(resp.data.retorno);
                
                app.getDados();

                app.curso = [];

                app.$refs.modal.hide();
                app.toastSuccess(`${resp.data.retorno}`);
                
            })
            .catch(function (resp) {
                //console.log(resp.data);
                
                app.$refs.modal.hide();
                app.toastError(`${resp.data.retorno}`);
                
            });
        },
        
        deleteCurso(id, index) {

            var app = this;
            axios.delete('/lv_desafio2/public/api/curso/' + id)
                .then(function (resp) {
                    
                    app.cursos.splice(index, 1);
                    
                    app.toastSuccess(`${resp.data.retorno}`);
                })
                .catch(function (resp) {
                    app.toastError(`${resp.data.retorno}`);
                });
        },
        openModal(id) {
            
            if(!id) {
                this.professor = [];
                this.id = null;

                this.$refs.modal.show();

            } else {
                this.id = id
                var app = this;
                axios.get(`/lv_desafio2/public/api/curso/${id}`)
                    .then(function (resp) {

						//console.log(resp.data);

						app.professores = resp.data.professores;
                        
                        app.curso.nome = resp.data.curso.nome;
                        app.curso.id_professor = resp.data.curso.id_professor_encrypt;
						
                        app.$refs.modal.show();

                        if(resp.data.retorno)
							app.toastError(`${resp.data.retorno}`);
                    })
                    .catch(function (resp) {
                        
                        //console.log("Ocorreu um erro");
                        app.toastError(`${resp.data.retorno}`);
					});
            }

            
		},
        toastSuccess($msg) {
            
           this.$notification.success($msg, {
               timer: 5, showLeftIcn: false });
        },
        toastError($msg) {

            this.$notification.error($msg, 
               { timer: 5, infiniteTimer: false, showLeftIcn: false });
        }
    }
}

</script>

<style scoped>
    .topo {
        margin-top: 10px;
    }

    #clickconfirm {
        display: inline;
    }
</style>