<template>
    <div class="wrap">
        <BoardList v-for="(v, k) in data" v-bind:key="k" v-bind:data="v"></BoardList>
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
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    justify-content: space-between;
}
</style>

<script setup lang="ts">
import { ref } from 'vue'

import BoardList from './item/BoardList.vue'
import http from '../http'
import { Pinia } from '../pinia'

Pinia().setTitle('掲示板')
const data = ref<{value: any}>()

http
.post('/api/boards')
.then(res => {
    data.value = res.data
})
.catch(e => {
    console.log(e)
})
</script>