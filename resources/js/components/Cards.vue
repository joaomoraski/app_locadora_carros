<template>
    <div :class="classes">
        <div class="card-header">{{tituloCard}}</div>

        <div class="card-body">
            <slot name="conteudo"></slot>
        </div>
        <div class="card-footer">
            <slot name="rodape"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: ['tituloCard', 'classes']
}
</script>
