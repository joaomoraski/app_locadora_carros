<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
<!--                        @if (session('status'))-->
<!--                        <div class="alert alert-success" role="alert">-->
<!--                            {{ session('status') }}-->
<!--                        </div>-->
<!--                        @endif-->
                        Bem-vindo {{ user.length }} <br>
                        Você está logado!!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'], // analogas ao atributo data
        // data() {
        //     return {
        //         email: '',
        //         password: '',
        //     }
        // },
        methods: {
            // login(e) {
            //     const url = "http://localhost:8000/api/login";
            //     const configuracao = {
            //         method: 'post',
            //         body: new URLSearchParams({
            //             'email': this.email,
            //             'password': this.password, // this é utilizado dentro dos metodos, tanto pro data quanto pro props
            //         })
            //     }
            //
            //     fetch(url, configuracao)
            //         .then(response => response.json())
            //         .then(data => {
            //             if(data.token) {
            //                 document.cookie = 'token='+data.token+';SameSite=Lax';
            //             }
            //             //retornar o processo normal
            //             e.target.submit();
            //         }) //como vem encadeando apartir do de cima entao o nome da variavel é a resposta da de cima
            // },
        }
    }
</script>
