<template>
    <div class="form-group">
        <label :for="id" class="form-label">{{titulo}}</label>
        <slot></slot>
        <!-- slot deixa livre pra vc criar o input ai -->
    </div>
</template>

<script>
    export default {
        props: ['id', 'titulo']
    }
</script>
