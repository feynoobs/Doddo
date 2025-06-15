import { defineStore } from 'pinia'

export const Pinia = defineStore('Pinia', {
    state: () => ({
        title: '',
    }),
    actions: {
        setTitle(title: string) {
            this.title = title
        }
    },
    persist: true,
})