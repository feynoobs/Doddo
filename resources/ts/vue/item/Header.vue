<template>
    <div class="center">
        <span class="inner">{{ title }}</span>
    </div>
</template>

<style scoped>
div.center {
    position: relative;
    height: 100px;
    text-align: center;
}
span.inner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateY(-50%) translateX(-50%);
    font-weight:bold;
    font-size: xx-large;
}
</style>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Pinia } from '../../pinia'
const title = ref<String>()
title.value = Pinia().title
watch(() => Pinia().title, (newVal: String, oldVal: String) : void => {
    if (newVal !== '') {
        title.value = newVal
    }
    else {
        if (oldVal !== '') {
            title.value = newVal
        }
    }
})
</script>