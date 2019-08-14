<template>
    <div>
        <b-navbar
            toggleable="md"
            type="dark"
            variant="primary ">
            <div class="container">
                <b-navbar-brand href="#">CRUD</b-navbar-brand>
                <b-navbar-nav class="ml-auto">
                       
                    <b-navbar-brand :to="{ name: 'aluno-index' }">Aluno</b-navbar-brand>
                    <b-navbar-brand :to="{ name: 'curso-index' }">Curso</b-navbar-brand>
                    <b-navbar-brand :to="{ name: 'professor-index' }">Professor</b-navbar-brand>
                </b-navbar-nav>
            </div>
        </b-navbar>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {}
</script>