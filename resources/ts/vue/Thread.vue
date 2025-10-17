<template>
    <div class="wrap">
        <ResponseList v-if="data !== undefined" v-bind:data="data"></ResponseList>
        <form @submit.prevent="post" novalidate>
            <button type="submit">書き込む</button>
            <div>
                <span>名前:</span>
                <input v-model="name" class="border" type="text" name="name">
                <span>{{ nameError }}</span>
                <span>E-mail:</span>
                <input v-model="email" class="border" type="email" name="email">
                <span>{{ emailError }}</span>
            </div>
            <textarea v-model="message" rows="4" cols="12"></textarea>
            <p class="error">{{ messageError }}</p>
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

div.wrap p.error {
    color: #d00;
}

</style>

<script setup lang="ts">
import { ref } from 'vue'
import ResponseList from './item/ResponseList.vue'
import http from '../http'
import { Pinia } from '../pinia'
import { useForm, useField } from 'vee-validate'
import * as yup from 'yup'

const props = defineProps({
    id: Number
})
const data = ref<{value: any}>()

const params = new URLSearchParams()
params.append('id', (props.id ?? '').toString())
http.post('/api/responses', params)
    .then(res => {
        Pinia().setTitle(res.data.thread.name)
        data.value = res.data
    })
    .catch(e => {
        console.error(e)
    })

const schema = yup.object({
    name: yup.string().nullable(),
    email: yup.string().nullable(),
    message: yup.string().required('メッセージは必須です'),
})
const { handleSubmit } = useForm({ validationSchema: schema })
const { value: name, errorMessage: nameError } = useField<string | null>('name')
const { value: email, errorMessage: emailError } = useField<string | null>('email')
const { value: message, errorMessage: messageError, resetField: resetMessage } = useField<string>('message')

const post = handleSubmit((values) => {
    const params = new URLSearchParams()
    params.append('name', (values.name ?? '') as string)
    params.append('email', (values.email ?? '') as string)
    params.append('message', (values.message ?? '') as string)
    params.append('thread_id', (props.id ?? '') as string)

    http.post('/api/post', params)
        .then(res => {
            const params = new URLSearchParams()
            params.append('id', (props.id ?? '').toString())
            return http.post('/api/responses', params)
        })
        .then(res => {
            alert('投稿しました。')
            resetMessage()
            data.value = res.data
        })
        .catch(e => {
            alert('エラーは発生しました。')
            console.error(e)
        })
})
</script>