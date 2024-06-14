import {defineStore} from "pinia";
import {getRandom, SuperTimer} from "@mixins/utils.js";
import axios from "axios";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import {reactive, ref} from "vue";


export const useExperiment2Store = defineStore('experiment2', {
    state: () => {
        return {
            active: false,
            zopa: false,
            comment: '',
            position: '',
            stimul: null,
            monkey_id: null,
            experiment_id: null,
            data: {
                stimuls: [],
                helpers: [],
                positions: [],
            },
            positions: [],
            timer: null,
            is_window: false,
            results: [],
            line: {
                countProbs: 3,
                currentProb: 0,
                startDelay: 1000,
                showHelpers: false,
                crntHelper: {},
                startHelp: {
                    min: 1000,
                    max: 1000
                },
                waitQuestion: {
                    min: 1000,
                    max: 1000
                },
                stopDelay: {
                    min: 1000,
                    max: 1000
                },
            }
        }
    },
    getters: {
        getActive: (state) => {
            return state.zopa
        }
    },
    actions: {
        setActive(val) {
            this.zopa = val
        },
        setExperimentId(val) {
            this.experiment_id = val
        },
        reset() {
            let line = this.line
            let win = this.is_window
            line.currentProb = 0;
            this.$reset()
            this.is_window = win
            this.line = {...line}
        },
        sendStimul() {
            axios.post(`/experiment/command/${this.stimul.name}`, this.stimul).catch(e => console.info(e))
        },

        async getExperimentData() {

            let columns =
                '    position_strings\n' +
                '    monkey_id\n' +
                '    id\n' +
                'positions {\n' +
                'name\n' +
                'id\n' +
                'experiment_id }\n' +
                '    helpers {\n' +
                '      thickness\n' +
                '      name\n' +
                '      id\n' +
                '      experiment_id\n' +
                '      br\n' +
                '    }\n' +
                '    stimuls {\n' +
                '      name\n' +
                '      length\n' +
                '      id\n' +
                '      frequency\n' +
                '      experiment_id\n' +
                '    }'
            let response = await GraphqlAPI.get_api('experiment', {id: this.experiment_id}, columns)
            this.data.helpers = response.helpers
            this.data.positions = response.position_strings
            this.positions = response.positions
            this.data.stimuls = response.stimuls
        },
        async storeResults() {
            await axios.post('/experiment/store-exp2-results',)
        },
        async storeExperiment() {
            let data = await axios.post('/experiment/store', {
                'experiment': {
                    monkey_id: this.monkey_id,
                    number: 2,
                    name: 'Рефлекс на стимул',
                },
                'helpers': this.helpers,
                'stimul': this.stimul,
                'positions': this.positions
            })
            this.experiment_id = data.data['experiment']['id'];
            return data.data
        },
        async runExperiment() {
            await this.getExperimentData()

            this.setActive(true)
            while (this.line.currentProb < this.line.countProbs) {
                this.line.crntHelper = this.data.helpers.at(getRandom(0, this.data.helpers.length - 1))
                this.position = this.data.positions.at(getRandom(0, this.data.positions.length - 1))
                this.stimul = this.data.stimuls.at(getRandom(0, this.data.stimuls.length - 1))
                this.line.currentProb++
                this.comment += "<p>Сигнал</p>"
                let t = new SuperTimer()
                await t.timeout(() => {
                    this.comment += "<p>Сигнал ждем</p>"
                    this.sendStimul()
                }, this.line.startDelay)
                this.comment += "<p>стимул</p>"
                t = new SuperTimer()
                await t.timeout(() => {
                    this.line.showHelpers = true
                    this.comment += "<p>Пауза перед подсказкой</p>"
                }, getRandom(this.line.startHelp.min, this.line.startHelp.max))
                this.comment += "<p>стимул</p>"
                this.timer = new SuperTimer()

                await (async () => {
                    try {
                        await this.timer.timeout(() => {
                            this.line.showHelpers = false
                            this.comment += "<p>Пауза перед подсказкой(нажала)</p>"
                        }, getRandom(this.line.waitQuestion.min, this.line.waitQuestion.max))
                    } catch (e) {
                        this.line.showHelpers = false
                    }
                })()
                t = new SuperTimer()
                this.comment += "<p>Пауза</p>"
                await t.timeout(() => {
                    this.comment += "<p>следующая</p>"
                }, getRandom(this.line.stopDelay.min, this.line.stopDelay.max))
                this.results.push({
                    experiment_id: this.experiment_id,
                    stimul_id: this.stimul.id,
                    position_id: this.position.id,
                })

            }
            await axios.post(`/files/add/2/${this.monkey_id}`)
            this.setActive(false)

        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
