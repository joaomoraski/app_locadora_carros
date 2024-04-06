<template>
    <div :class="estilo">
        {{ titulo }} <!-- Possivel manter dessa forma no refactoring-->
        <hr/>
        <!-- Implementar o objeto literal para usar o transacao detalhes para definir os campos-->
        <!-- Trocar o detalhes para ser um objeto q vai controlar o que esta sendo passado para o front -->
        <p> {{ detalhes.mensagem }} </p>
        <!--        V if para printar apenas se tirar errors -->
        <ul v-if="detalhes.dados">
            <!--            v-for para percorrer cada erro na tela-->
            <!--            como é um vfor, precisamo passar o v-bind do key para diferenciar dos outros -->
            <li v-for="(e, key) in detalhes.dados" :key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>
</template>

<!--        V if imprimir apenas se existir -->
<!--        <span v-if="detalhes.dados">{{ detalhes.dados }}</span>-->
<!--        <br v-if="detalhes.data.id"><span v-if="detalhes.data.id">{{ "Id do registro: " + detalhes.data.id }}</span>-->


<script>
export default {
    props: ['tipo', 'titulo', 'detalhes'],
    computed: {
        estilo() {
            return 'alert alert-' + this.tipo
        }
    }
}
</script>
