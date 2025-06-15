import { createRouter, createWebHistory } from "vue-router"
import Group from './vue/Group.vue'
import Board from './vue/Board.vue'
import Thread from './vue/Thread.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        /*
        {
            name: 'login',
            path: '/login',
            component: Login,
        },
        */
        {
            name: 'group',
            path: '/',
            component: Group,
        },
        {
            name: 'board',
            path: '/board/:id',
            component: Board,
            props: true
        },
        {
            name: 'thread',
            path: '/thread/:id',
            component: Thread,
            props: true
        },
    ]
})

export default router;