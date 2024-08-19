import {defineStore} from "pinia";
import {getRandom, SuperTimer, toNumber} from "../mixins/utils.js";
import axios from "axios";
import svgMixin from "@mixins/svgMixin.js";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import audioFile from "@assets/clicker.m4a";


const {triangle} = svgMixin()
const audioCtx = new (window.AudioContext || window.webkitAudioContext || window.audioContext);
export const useExperiment3Store = defineStore('experiment3', {
    state: () => {
        return {
            active: false,
            is_window: false,
            data: {
                figures: [],
                figure_results: [],
                figure: {},
                oblast: {},
                timer: null,
                helpers: [],
                helper: null
            },
            result: {},
            line: {
                maxCount: 2,
                current: 0,
                showDelay: 1000,
                helperRange: {min: 3000, max: 5000},
                touch: 1000,
                stopRange: {min: 3000, max: 5000},
            },
            id: null,
            monkey_id: null,
            showFigure: false,
            showHelper: false,
            text: '',
            timer: null,
            timer2: null,
            pause1: false,
            p: null,
            pause2: false,
            click: {x: 0, y: 0},
            react: false
        }
    },

    getters: {
        getActive: (state) => state.active,
        getData: (state) => state.data,
        getResult: (state) => state.result,
        getLine: (state) => state.line,
        getTrueValue(state) {
            return state.data.figure_results?.filter(item => !!item.reaction_time && item.reaction_time !== -1).length
        },
        getReactionTime(state) {
            return toNumber(state.data.figure_results?.reduce((a, item) => a + toNumber(item.reaction_time), 0) / state.data.figure_results?.length)
        },

    },
    actions: {
        setActive(value) {
            this.active = value
        },
        setData(value) {
            this.data = value
        },
        setResult(value) {
            this.result = value
        },
        updateFigure(figure, values, addResults = true) {
            this.data.figure = {
                ...this.data.figure,
                ...values
            }
            let i = this.data.figures.findIndex((element) => element.id === figure.id)
            if (i !== -1) {
                this.data.figures[i] = {
                    ...this.data.figures[i],
                    ...values
                }
                if (addResults) {
                    this.data.figure_results.push({
                        figure_id: figure.id,
                        experiment_id: this.data.figures.at(i).experiment_id,
                        ...values
                    })
                }
            }
        },
        async startStopPause() {
            this.pause1 = !this.pause1
        },
        setClick(x, y) {
            this.click = {x, y}
        },
        updateClickPosition(figure) {
            let i = this.data.figure_results.findIndex(element =>
                element.figure_id === figure.id
                && element.experiment_id === figure.experiment_id
                && element.x === figure.x
                && element.y === figure.y);
            if (i !== -1) {
                this.data.figure_results[i] = {
                    ...this.data.figure_results[i],
                    x_click: localStorage.getItem('x'),
                    y_click: localStorage.getItem('y')
                }
            }
        },

        async sleep() {
            if (this.pause1) {
                const date = Date.now();
                let currentDate = null;
                do {
                    currentDate = Date.now();
                    await (new SuperTimer()).sleep(1000)
                    this.text += '<p>пауза от пользователя </p>'
                    if (!this.pause1) {
                        break;
                    }
                } while (currentDate - date < 999999);
            }

        },

        reset(oblast) {
            let {monkey_id, line, is_window} = this
            this.line.current = 0
            this.$reset();
            this.monkey_id = monkey_id
            this.is_window = is_window
            this.setData({
                oblast: oblast.value.options
            })
            this.line = line
        },

        //duration of the tone in milliseconds. Default is 500
//frequency of the tone in hertz. default is 440
//volume of the tone. Default is 1, off is 0.
//type of tone. Possible values are sine, square, sawtooth, triangle, and custom. Default is sine.
//callback to use on end of tone
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
        async getHelpers() {
            let columns =
                '    monkey_id\n' +
                '    id\n' +
                '    helpers {\n' +
                '      thickness\n' +
                '      name\n' +
                '      id\n' +
                '      experiment_id\n' +
                '      br\n' +
                '      offset\n' +
                '      offsetX\n' +
                '      offsetY\n' +
                '      brTrue\n' +
                '      brFalse\n' +
                '    }\n' +
                '    '
            let response = await GraphqlAPI.get_api('experiment', {id: this.id}, columns)
            this.data.helpers = response.helpers
        },

        saveValues() {
            localStorage.setItem('line3', JSON.stringify(this.line))
            localStorage.setItem('data3', JSON.stringify(this.data))
        },

        restoreValues() {
            let line = localStorage.getItem('line3');
            if (line) {
                this.line = JSON.parse(line)
            }
            let data = localStorage.getItem('data3');
            if (data) {
                this.data = JSON.parse(data)
            }
        },

        async runExperiment(figure) {
            this.data.figures = await figure.fetchFigures()
            await this.getHelpers()

            this.data.figure_results = []

            this.setActive(true)

            while (this.line.current < this.line.maxCount) {
                await this.sleep()
                this.data.figure = this.data.figures.at(getRandom(0, this.data.figures.length - 1))
                let angle = this.data.figure.angles.at(getRandom(0, 1))
                let x = this.data.figure.xx.at(getRandom(0, this.data.figure.xx.length - 1))
                let y = this.data.figure.yy.at(getRandom(0, this.data.figure.yy.length - 1))
                let w = this.data.figure.ww[0]
                let h = this.data.figure.hh[0]
                let color = this.data.figure.colors.at(getRandom(0, this.data.figure.colors.length - 1))
                let brightness = getRandom(this.data.figure.brightness_min, this.data.figure.brightness_max)
                this.updateFigure(this.data.figure, {x, y, w, h, color, brightness}, false)
                this.data.helper = this.data.helpers.at(getRandom(0, this.data.helpers.length - 1))
                this.line.current++
                this.text += '<p>Ждем сигнал </p>'




                let params = {
                    angle,
                    x: this.data.figure.x,
                    y: this.data.figure.y,
                    w: this.data.figure.h,
                    h: this.data.figure.w,
                    color,
                    x_oblast: this.data.oblast.position.x1,
                    y_oblast: this.data.oblast.position.y1
                }



                let figureTask = async () => {
                    this.beep();
                    this.text += '<p>Показываем фигуру </p>'
                    let ap = new SuperTimer();
                    await ap.timeout(() => {
                        this.showFigure = true
                        this.text += '<p>Ждем</p>'
                    }, this.line.showDelay);

                    await this.sleep()

                    ap = new SuperTimer();
                    await ap.timeout(() => {
                        this.showFigure = false
                        this.text += '<p>показали фигуру </p>'
                        this.showHelper = true
                    }, this.data.figure.show_time);
                }


                let helperTask = async () => {

                    let a = new SuperTimer();
                    await a.timeout(() => {
                        this.showHelper = false
                        this.text += '<p>Отключили подсказку </p>'
                    }, getRandom(this.line.helperRange.min, this.line.helperRange.max))

                }


                let clickTask = async () => {
                    this.timer = new SuperTimer();
                    let time = (new Date()).getTime()
                    try {
                        await this.sleep()
                        await this.timer.timeout(() => {
                            this.showFigure = false
                            this.showHelper = false
                            this.updateFigure(this.data.figure, {
                                reaction_time: -1,
                                ...params
                            })
                            console.log('не сработало')
                            this.text += '<p>Отключили фигуру </p>'
                        }, this.line.touch)
                    } catch (e) {
                        await this.sleep()
                        this.showFigure = false
                        this.showHelper = false
                        console.log('сработало', localStorage.getItem('react'))
                        let t = (new Date()).getTime() - time
                        if (localStorage.getItem('react') === 'true') {
                            const audio = new Audio(audioFile);
                            await audio.play();
                            axios
                                .post('/experiment/send-com', {name: 'feed'})
                                .catch(e => console.info(e))
                        }
                        this.updateFigure(this.data.figure, {
                            reaction_time: localStorage.getItem('react') === 'true' ? t : -1,
                            ...params
                        })
                    }
                }

                await Promise.all([figureTask(),helperTask(), clickTask()])

                this.updateClickPosition(this.data.figure)
                await this.sleep()
                this.text += '<p>пауза </p>'
                let ap = new SuperTimer()

                await ap.timeout(() => {

                }, getRandom(this.line.stopRange.min, this.line.stopRange.max))

            }
            figure.setFigureResults(this.data.figure_results)
            figure.store();
            await axios.post(`/files/add/3/${this.monkey_id}`)
            this.setActive(false)
        },
        stopTimer() {
            this.timer.stop()
        },
        generateTriangle() {
            const {w, color} = this.data.figure;
            return triangle(w, color)
        },
        getFigurePositionCenter(always) {
            let style = '';
            if (!always) {
                if (!this.active || !this.showFigure) {
                    return style
                }
            }


            try {
                let figure = this.data.figure
                let {color, w: width, h, angle, brightness, x, y} = figure


                style = `background-color: ${color};filter: brightness(${brightness}%);width: ${width}px; height: ${h}px; left: ${x}px; top: ${y}px; transform:rotate(${angle}deg)`;

            } catch (e) {
            }
            return style

        },
    },
    share: {
        enable: true,
        initialize: true,
    }
})
