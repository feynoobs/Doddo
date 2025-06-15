<template>
    <div class="wrap">
        <ResponseList v-if="data !== undefined" v-bind:data="data"></ResponseList>
        <button type="button" v-on:click="post">書き込む</button>
        <div>
            <span>名前:</span>
            <input v-model="name" class="border" type="text" name="name">
            <span>E-mail:</span>
            <input v-model="email" class="border" type="email" name="email">
        </div>
        <textarea v-model="message" rows="4" cols="12"></textarea>
    </div>
</template>

<style scoped>
div.wrap {
    margin: 20px 10%;
    border-radius: 10px;
    background-color: #eee;
    padding: 20px 20px;
}

div.wrap > textarea {
    word-wrap: break-word;
    padding: 0.5em;
    border: solid 1px #333;
    min-width: 40em;
}

div.wrap > button {
    display: block;
    padding: 10px 20px;
    border-radius: 10px;
    background-color: green;
    margin-bottom: 10px;
}

div.wrap > div {
    margin-bottom: 10px;
}

div.wrap > div > input.border {
    border: 1px solid #ccc;
    padding: 5px;
}

</style>

<script setup lang="ts">
import { ref } from 'vue'

import ResponseList from './item/ResponseList.vue'
import http from '../http'
import { Pinia } from '../pinia'

const props = defineProps({
    id: Number
});
console.log(props.id)
const data = ref<{value: any}>()

const params = new URLSearchParams()
params.append('id', (props.id ?? '').toString())
http
.post('/api/responses', params)
.then(res => {
    Pinia().setTitle(res.data.thread.name)
    data.value = res.data
})
.catch(e => {
    console.error(e)
})

const name = ref<string>()
const email = ref<string>()
const message = ref<string>()

const post = () => {
    const params = new URLSearchParams()

    params.append('name', (name.value ?? '') as string)
    params.append('email', (email.value ?? '') as string)
    params.append('message', (message.value ?? '') as string)
    params.append('ip', '255.255.255.255')
    params.append('thread_id', (props.id ?? '') as string)

    http
    .post('/api/post', params)
    .then(res => {
        console.log(res)
        // data.value = res.data
        const params = new URLSearchParams()
        params.append('id', (props.id ?? '').toString())
        return http.post('/api/responses', params)
    })
    .then(res => {
            data.value = res.data
    })
    .catch(e => {
        console.error(e)
    })
}
</script>