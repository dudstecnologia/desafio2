<template>
    <div class="topo">
        <div class="row">
            <div class="col-sm">
                <h2>Professor</h2>
            </div>

            <div class="col-sm-alto">
                <b-button @click="openModal(null)" variant="info">Cadastrar Novo</b-button>
            </div>
        </div>

        <b-modal id="modal-1" ref="modal" title="Cadastro" hide-footer>
            
                <form v-on:submit="formProfessor()">

                    <b-form-group label="Nome">
                        <b-form-input
                        id="nome"
                        v-model="professor.nome"
                        required
                        ></b-form-input>
                    </b-form-group>

                    <b-form-group label="Data de Nascimento">
                        <b-form-input
                        id="data_nascimento"
                        v-model="professor.data_nascimento"
                        type="date"
                        required
                        ></b-form-input>
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
                    <tr v-for="(p, index) in professores" :key="index" :value="p.id_encrypt">
                        <td>{{ p.nome }}</td>
                        <td>{{ p.data_nascimento }}</td>
                        <td>{{ p.data_criacao }}</td>
                        <td>
                            <button class="btn btn-sm btn-info"
                                @click="openModal(p.id_encrypt)">
                                    Editar
                                </button>
                            
                            <click-confirm id="clickconfirm"
                                :messages="{title: 'Deseja mesmo excluir?', yes: 'Sim', no: 'Não'}"
                                yes-class="btn btn-danger" >

                                <button class="btn btn-sm btn-danger"
                                @click="deleteProfessor(p.id_encrypt, index)">
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

//import axios from 'axios';

export default {
    data() {
        return {
            id: null,
            professores: [],
            professor: {
                nome: '',
                data_nascimento: '',
            },

        };
    },
    created() {
        this.getProfessores();
    },
    methods: {
        getProfessores() {
            /*
            axios.get('/desafio2/api/professor')
                .then(response => {
                    
                    this.professores = response.data;
                })
                .catch(function (resp) {
                    console.log(resp.data);
            });
            */

            this.$http.get('/api/professor')
                .then(response => {
                    
                    this.professores = response.data;
                })
                .catch(function (resp) {
                    console.log(resp.data);
            });
        },

        formProfessor() {
            
            event.preventDefault();
            
            var app = this;
            var novoProfessor = app.professor;
            var url = '/api/professor';

            if(this.id) url = `/api/professor/${this.id}`;

            this.$http.post(url, {
                nome: app.professor.nome,
                data_nascimento: app.professor.data_nascimento
            })
                .then(function (resp) {
                //console.log(resp.data);
                
                app.getProfessores();

                app.professor = [];

                app.$refs.modal.hide();
                app.toastSuccess(`${resp.data.retorno}`);
                
            })
            .catch(function (resp) {
                //console.log(resp.data);
                
                app.$refs.modal.hide();
                app.toastError(`${resp.data.retorno}`);
                
            });
        },
        
        deleteProfessor(id, index) {

            var app = this;
            this.$http.delete('/api/professor/' + id)
                .then(function (resp) {
                    
                    app.professores.splice(index, 1);
                    
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
                axios.get(`/api/professor/${id}`)
                    .then(function (resp) {

                        //console.log(resp.data);
                        
                        app.professor.nome = resp.data.nome;
                        app.professor.data_nascimento = resp.data.data_nascimento;
                        
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