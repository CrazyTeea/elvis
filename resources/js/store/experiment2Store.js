import {defineStore} from "pinia";
import {getRandom, SuperTimer} from "@mixins/utils.js";
import axios from "axios";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import {reactive, ref} from "vue";

const audioCtx = new (window.AudioContext || window.webkitAudioContext || window.audioContext);

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

        stopTimer(){
            this.timer.stop()
        },

        sendStimul() {
            axios.post(`/experiment/send-com`, {...this.stimul, position:this.position}).catch(()=>{})
        },

        beep(duration, frequency, volume, type, callback) {
            let oscillator = audioCtx.createOscillator();
            let gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            if (volume) {
                gainNode.gain.value = volume;
            }
            if (frequency) {
                oscillator.frequency.value = frequency;
            }
            if (type) {
                oscillator.type = type;
            }
            if (callback) {
                oscillator.onended = callback;
            }

            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + ((duration || 500) / 1000));
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
            await axios.post('/experiment/store-exp2-results', {results: this.results})
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
                this.beep();
                let t = new SuperTimer()
                await t.timeout(() => {
                    this.comment += "<p>Сигнал ждем</p>"
                    this.sendStimul()
                }, this.line.startDelay)

                let time = (new Date()).getTime()
                this.comment += "<p>стимул</p>"
                t = new SuperTimer()
                await t.timeout(() => {
                    this.line.showHelpers = true
                    this.comment += "<p>Пауза перед подсказкой</p>"
                }, (+this.stimul.length) + getRandom(this.line.startHelp.min, this.line.startHelp.max))
                this.comment += "<p>стимул</p>"
                this.timer = new SuperTimer()
                let reaction = -1

                await (async () => {
                    try {
                        await this.timer.timeout(() => {
                            this.line.showHelpers = false

                        }, getRandom(this.line.waitQuestion.min, this.line.waitQuestion.max))
                    } catch (e) {
                        let t = (new Date()).getTime() - time
                        reaction = localStorage.getItem('react') === 'true' ? t : -1
                        if (localStorage.getItem('react') === 'true') {
                            axios.post('/experiment/send-com', {name: 'feed', }).catch(e => console.info(e))
                        }

                        this.comment += "<p>Пауза перед подсказкой(нажала)</p>"
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
                    position_id: (this.positions.find(item=>item.name === this.position)).id,
                    helper_id: this.line.crntHelper.id,
                    x: localStorage.getItem('x_clk'),
                    y: localStorage.getItem('y_clk'),
                    reaction
                })

            }
            await axios.post(`/files/add/2/${this.monkey_id}`)
            await this.storeResults()
            this.setActive(false)

        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
