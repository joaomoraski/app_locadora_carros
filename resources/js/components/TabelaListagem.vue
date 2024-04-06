<template>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="text-uppercase" scope="col" v-for="(titulo, key) in titulos" :key="key">{{ titulo }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="element in dados" :key="element.id">
            <!-- verificar se os titulos(nome das colunas) possuem o valor passado pelo "key", que seriam as colunas do banco-->
            <td v-if="titulos.includes(key)" v-for="(att,  key) in element" :key="key">
                <span v-if="key === 'imagem'">
                    <img :src="'/storage/'+att" width="40" height="40" alt="Imagem da marca">
                </span>
                <span v-else>
                    {{ att }}
                </span>
            </td>
<!--            <th scope="row">{{ element.id }}</th>-->
<!--            <td>{{ element.nome }}</td>-->
<!--            <td><img :src="'/storage/' + element.imagem" width="40" height="40"/></td>-->
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['dados', 'titulos'],
    }
</script>
