import {createWebHistory, createRouter} from 'vue-router'
import monkeyDisplay from '@/components/pages/MonkeyDisplay.vue'
import monkeyDisplay2 from '@/components/pages/MonkeyDisplay2.vue'
import monkeyDisplay3 from '@/components/pages/MonkeyDisplay3.vue'
import monkeyDisplay4 from '@/components/pages/MonkeyDisplay4.vue'

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
    {path: '/experiment-2-monitor', name: 'experiment2-monitor', props: true, component: monkeyDisplay2},
    {
        path: '/:monkey_id/experiment-3',
        name: 'experiment3',
        props: true,
        component: () => import('@/components/pages/Experiment3.vue')
    },
    {path: '/experiment-3-monitor', name: 'experiment3-monitor', props: true, component: monkeyDisplay3},
    {
        path: '/:monkey_id/experiment-4',
        name: 'experiment4',
        props: true,
        component: () => import('@/components/pages/Experiment4.vue')
    },
    {path: '/experiment-4-monitor', name: 'experiment4-monitor', props: true, component: monkeyDisplay4}

]

export default createRouter({
    history: createWebHistory(),
    routes,
})
