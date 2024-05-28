import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";

export const useMonkeyStore = defineStore('monkey', {
    state: () => ({
        data: {}
    }),
    getters: {
        /**
         *
         * @param state
         * @returns {function(*): Promise<{}|[]>}
         */
        getMonkey(state) {
            let api = new GraphqlAPI('monkey')
            return async (id) => (await api.fetch({id}, ['id', 'name', 'elvis_id', 'age', 'weight', 'comment']))
        },
        getMonkeys(state) {
            let api = new GraphqlAPI('monkeys')
            return async () => (await api.fetch(null, ['id', 'name', 'elvis_id', 'age', 'weight', 'comment']))
        }
    }
})
