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
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">
                            Adicionar
                        </button>
                    </template>
                </card-component>
                <!-- fim do card de listagem de marcas -->
            </div>
        </div>
        <modal-component id="modalMarca" titulo="Adicionar marca">
            <template v-slot:conteudo>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome">
                        <input type="text" class="form-control" id="novoNome" v-model="nomeMarca">
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component titulo="Imagem da marca" id="NovoImagemMarca">
                        <input type="file" class="form-control" id="NovoImagemMarca" @change="carregarImagem($event)">
                        <!-- nao da pra usar vmodel -->
                    </input-container-component>
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
    data() {
        return {
            urlBase: 'http://localhost:8002/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: [],
        }
    },
    computed: {
        token() {
            let token = String(document.cookie).split(';').find(indice => {
                return indice.includes('token=');
            });

            token = String(token).split('=')[1];
            token = 'Bearer ' + token;
            return token;
        }
    },
    methods: {
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token,
                }
            }
            axios.post(this.urlBase, formData, config)
                .then(response => {
                    console.log(response);
                })
                .catch(errors => {
                    console.log(errors);
                })
        },
    }
}
</script>
