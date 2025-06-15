<template>
    <div class="wrap">
        <ThreadList v-bind:data="data"></ThreadList>
    </div>
</template>

<style scoped>
input.btn {
    display: block;
    margin: 20px auto;
    width: 160px;
    text-align: center;
    border: 1px solid #333;
}
div.wrap {
    margin: 20px 10%;
    border-radius: 10px;
    background-color: #eee;
    padding: 20px 20px;
}
</style>

<script setup lang="ts">
import { ref } from 'vue'

import ThreadList from './item/ThreadList.vue'
import http from '../http'
import { Pinia } from '../pinia'

Pinia().setTitle('板')

const props = defineProps({
    id: Number
});

const data = ref<{value: any}>()

const params = new URLSearchParams()
params.append('id', (props.id ?? '').toString())
http
.post('/api/threads', params)
.then(res => {
    data.value = res.data
})
.catch(e => {
    console.error(e)
})
</script>