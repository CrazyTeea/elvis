import {defineStore} from "pinia";
import {getRandom, SuperTimer} from "@mixins/utils.js";
import axios from "axios";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";


export const useExperiment2Store = defineStore('experiment2', {
    state: () => {
        return {
            active: false,
            comment: '',
            positions: [],
            position: '',
            monkey_id: null,
            experiment_id: null,
            stimul: {
                name: '',
                frequency: 0,
                length: 0,
            },
            helpers: [],
            timer: null,
            is_window: false,
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
        getActive: (state)=>state.active
    },
    actions: {
        setActive(val) {
            this.active = val
        },
        reset() {
            let {experiment_id, line, helpers,stimul, positions} = this
            line.currentProb = 0
            this.$reset()
            this.experiment_id = experiment_id
            this.line = line
            this.helpers = helpers
            this.positions = positions
            this.stimul = stimul
            console.log(this.line)
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
        async getExperimentData() {

            let columns =
                '    position_strings\n' +
                '    monkey_id\n' +
                '    id\n' +
                '    helpers {\n' +
                '      thickness\n' +
                '      name\n' +
                '      id\n' +
                '      experiment_id\n' +
                '      br\n' +
                '    }\n' +
                '    stimul {\n' +
                '      name\n' +
                '      length\n' +
                '      id\n' +
                '      frequency\n' +
                '      experiment_id\n' +
                '    }'
            let response = await GraphqlAPI.get_api('experiment', {id: this.experiment_id}, columns)
            console.log(typeof response, response)
            this.helpers = response.helpers
            this.positions = response.position_strings
            this.stimul = response.stimul
        },
        async runExperiment() {
            await this.getExperimentData()
            console.log(this.line)
            this.setActive(true)
            console.log(this.active)
            while (this.line.currentProb < this.line.countProbs) {
                this.line.crntHelper = this.helpers.at(getRandom(0, this.helpers.length - 1))
                this.position = this.positions.at(getRandom(0, this.positions.length - 1))

                this.comment += "<p>Сигнал</p>"
                let t = new SuperTimer()
                await t.timeout(() => {
                    this.comment += "<p>Сигнал ждем</p>"
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
                this.line.currentProb++
            }
            this.setActive(false)
            console.log(this.active)
        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
