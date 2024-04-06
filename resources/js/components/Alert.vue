<template>
    <div :class="estilo">
        {{ titulo }}
        <hr/>
        <!--        V if imprimir apenas se existir -->
        <span v-if="detalhes.data.message">{{ detalhes.data.message }}</span>
        <br v-if="detalhes.data.id"><span v-if="detalhes.data.id">{{ "Id do registro: " + detalhes.data.id }}</span>
        <!--        V if para printar apenas se tirar errors -->
        <ul v-if="detalhes.data.errors">
            <!--            v-for para percorrer cada erro na tela-->
            <!--            como é um vfor, precisamo passar o v-bind do key para diferenciar dos outros -->
            <li v-for="(e, key) in detalhes.data.errors" :key="key">
                {{ e[0] }}
            </li>
        </ul>
    </div>
</template>

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
