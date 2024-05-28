import {createWebHistory, createRouter} from 'vue-router'
import monkeyDisplay from '@/components/pages/MonkeyDisplay.vue'
import monkeyDisplay2 from '@/components/pages/MonkeyDisplay2.vue'

const routes = [
    {path: '/', name: 'home', component: () => import('./components/pages/Home.vue')},
    {path: '/info', name: 'info', component: () => import('./components/pages/Info.vue')},
    {
        path: '/:monkey_id/experiment-1',
        name: 'experiment1',
        props: true,
        component: () => import('@/components/pages/Experiment1.vue')
    },
    {path: '/experiment-1-monitor', name: 'experiment1-monitor', props: true, component: monkeyDisplay},
    {
        path: '/:monkey_id/experiment-2',
        name: 'experiment2',
        props: true,
        component: () => import('@/components/pages/Experiment2.vue')
    },
    {path: '/experiment-2-monitor', name: 'experiment2-monitor', props: true, component: monkeyDisplay2}

]

export default createRouter({
    history: createWebHistory(),
    routes,
})
