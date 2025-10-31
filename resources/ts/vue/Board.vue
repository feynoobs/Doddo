<template>
    <div class="wrap">
        <ThreadList v-bind:data="data"></ThreadList>
        <form @submit.prevent="post" novalidate class="mt-[50px]">
            <button type="submit" class="mt-[10px]">スレッドを作る</button>
            <div>
                <span>タイトル：</span>
                <input v-model="title" type="text" name="title" class="border inline-block w-[450px]">
                <div class="error">{{ titleError }}</div>
            </div>
            <div>
                <span>名前：</span>
                <input v-model="name" class="border" type="text" name="name">
                <span>{{ nameError }}</span>
                <span>E-mail：</span>
                <input v-model="email" class="border" type="email" name="email">
                <span>{{ emailError }}</span>
            </div>
            <textarea v-model="message" rows="4" cols="12"></textarea>
            <div class="error">{{ messageError }}</div>
        </form>
    </div>
</template>

<style scoped>
div.wrap {
    margin: 20px 10%;
    border-radius: 10px;
    background-color: #eee;
    padding: 20px 20px;
}

div.wrap textarea {
    word-wrap: break-word;
    padding: 0.5em;
    border: solid 1px #333;
    min-width: 40em;
}

div.wrap button {
    display: block;
    padding: 10px 20px;
    border-radius: 10px;
    background-color: green;
    margin-bottom: 10px;
}

div.wrap div {
    margin-bottom: 10px;
}

div.wrap input.border {
    border: 1px solid #ccc;
    padding: 5px;
}

div.wrap input.long {
    border: 1px solid #ccc;
    padding: 5px;
    width: 250px;
    display: inline-block;
}

</style>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import ThreadList from './item/ThreadList.vue'
import http from '../http'
import { Pinia } from '../pinia'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'

Pinia().setTitle('板')

const props = defineProps({
    id: String
});
const router = useRouter()
const data = ref<{value: any}>()

const params = new URLSearchParams()
params.append('id', (props.id ?? '').toString())
http
.post('/api/threads', params)
.then(res => {
    data.value = res.data
    Pinia().setTitle(res.data.board.name)
})
.catch(e => {
    console.error(e)
})

const schema = yup.object({
    title: yup.string().required('タイトルは必須です'),
    name: yup.string().nullable(),
    email: yup.string().nullable(),
    message: yup.string().required('メッセージは必須です'),
})
const { handleSubmit } = useForm({ validationSchema: schema })
const { value: title, errorMessage: titleError } = useField<string | null>('title')
const { value: name, errorMessage: nameError } = useField<string | null>('name')
const { value: email, errorMessage: emailError } = useField<string | null>('email')
const { value: message, errorMessage: messageError, resetField: resetMessage } = useField<string>('message')

const post = handleSubmit((values) => {
    const params = new URLSearchParams()
    params.append('name', (values.name ?? '') as string)
    params.append('email', (values.email ?? '') as string)
    params.append('message', (values.message ?? '') as string)
    params.append('board_id', (props.id ?? '') as string)
    params.append('title', (values.title ?? '') as string)

    http.post('/api/post/thread', params)
        .then(res => {
            alert('投稿しました。')
            resetMessage()
            router.push({ name: 'thread', params: { id: res.data.thread_id } })
        })
        .catch(e => {
            alert('エラーは発生しました。')
            console.error(e)
        })
})
</script>