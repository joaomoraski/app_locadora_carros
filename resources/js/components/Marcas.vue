<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo-card="Busca de marcas" classes="card">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col">
                                <input-container-component titulo="Id" id="marcaId">
                                    <input type="number" class="form-control" id="marcaId">
                                </input-container-component>
                            </div>
                            <div class="col">
                                <input-container-component titulo="Nome" id="name">
                                    <input type="text" class="form-control" id="name">
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>

                <!-- tabelinha -->
                <card-component titulo-card="Listagem de marcas" classes="card mt-5">
                    <template v-slot:conteudo>
                        <tabela-listagem-component></tabela-listagem-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal"
                                data-bs-target="#modalMarca">
                            Adicionar
                        </button>
                    </template>
                </card-component>
                <!-- fim do card de listagem de marcas -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:alertas>
                <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro realizado com súcesso!"
                                 v-if="transacaoStatus === 'adicionado'"></alert-component>
                <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao tentar cadastrar marca"
                                 v-if="transacaoStatus === 'erro'"></alert-component>
            </template>
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome">
                        <!-- // v-model-->
                        <input type="text" class="form-control" id="novoNome" v-model="nomeMarca">
                    </input-container-component>
                    {{ nomeMarca }}
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem da marca" id="NovoImagemMarca">
                        <input type="file" class="form-control" id="NovoImagemMarca" @change="carregarImagem($event)">
                        <!-- nao da pra usar vmodel -->
                    </input-container-component>
                    {{ arquivoImagem }}
                </div>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
export default {
    // Implementar o metodo data para retornar um objeto literal
    data() {
        // data() return o nome marca como '', e o arquivo imagem
        // arquivo imagem é um array pq um input do file é arrays pq vc pode mandar mais de um arquivo
        return {
            urlBase: 'http://localhost:8002/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: [],
        }
    },

    // usada para recuperar o token da forma que precisamos
    computed: {
        token() {
            // retorna token pegando dos cookies e pegando apenas aonde é o token=
            // pegar so o token que começa com token=, usando o includes
            // n foi usado starts with pq teve incompatibilidade com alguns browsers
            let token = String(document.cookie).split(';').find(indice => {
                return indice.includes('token=');
            });

            // split pelo =
            // pega o token
            token = String(token).split('=')[1];
            token = 'Bearer ' + token;
            return token;
        }
    },

    // Implementar com oo metodo de carregar imagem vai se comportar
    methods: {
        // recupera o evento como parameto e passamos o e.target.files para o arquivo imagem
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            // cria uma variavel de formData para montar o payload
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            // pega o primeiro para garantir que seja apenas a primeira imagem
            formData.append('imagem', this.arquivoImagem[0]);

            let config = {
                // define os headers da request
                // content-type indica o tipo do payload da req
                // accept json
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token,
                }
            }

            // performa o request
            // then -> assincrono para recuperar a resposta quando estiver pronta
            // catch ve se aconteceu algum erro e handle disso
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    this.transacaoStatus = 'adicionado'
                    this.transacaoDetalhes = response
                    console.log(response);
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro'
                    // Acessamos diretamente pelo componente, por isso precisa ser um array
                    this.transacaoDetalhes = errors.response
                    // console.log(errors.response.data.message);
                })
        },
    }
}
</script>
