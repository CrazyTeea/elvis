import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import {getRandom} from "../mixins/utils.js";
import {useExperimentStore} from "@/store/experiment1Store.js";

let experimentStore = useExperimentStore()

export class Figure {
    constructor(exp, figures = []) {
        this.figures = []
        this.figure_results = []
        this.experiment_id = exp.id;
        this.exp_number = exp.number
        this.addFigures(figures)
    }

    addFigures(figures) {
        for (let f in figures) {
            let fig = figures[f]
            console.log(fig)
            this.figures.push({
                name: fig.name,
                size_min: fig.options.size.min,
                size_max: fig.options.size.max,
                brightness_min: fig.options.brightness.min,
                brightness_max: fig.options.brightness.max,
                colors: fig.options.color,
                experiment_id: this.experiment_id,
                angles: fig.options.angle ?? [],
                xx: [],
                yy: [],
                ww: [],
                hh: [],
                x_h:fig.options.x_h,
                y_h:fig.options.y_h,
                x_v:fig.options.x_v,
                y_v:fig.options.y_v,
                show_time: fig.options.show_time
            })
        }
    }

    async store() {
        let form = new FormData()
        for (let f of this.figures) {
            form.append('figure[]', JSON.stringify(f))
        }
        for (let f of this.figure_results) {
            form.append('figure_result[]', JSON.stringify(f))
        }
        await axios.post('/experiment/store-figures', form)

    }

    getFigures() {

        return this.figures
    }

    getFigureResults() {

        return this.figure_results
    }

    async fetchFigures() {
        let graphql = new GraphqlAPI('figures')
        this.figures = await graphql.fetch({experiment_id: this.experiment_id}, [
            'id',
            'xx',
            'yy',
            'ww',
            'hh',
            'x',
            'y',
            'w',
            'h',
            'x_h',
            'y_h',
            'x_v',
            'y_v',
            'color',
            'brightness',
            'created_at',
            'updated_at',
            'experiment_id',
            'name',
            'reaction_time',
            'size_min',
            'size_max',
            'brightness_min',
            'brightness_max',
            'show_time',
            'angle',
            'angles',
            'colors'])

        this.figures = this.figures.map(item => {
            if (item.angles) {
                item.angles = item.angles.split(',').filter(item => !!item)
            }
            if (item.colors) {
                item.colors = item.colors.split(',').filter(item => !!item)
            }
            if (item.xx) {
                item.xx = item.xx.split(',').filter(item => !!item)
            }
            if (item.yy) {
                item.yy = item.yy.split(',').filter(item => !!item)
            }
            if (item.ww) {
                item.ww = item.ww.split(',').filter(item => !!item)
            }
            if (item.hh) {
                item.hh = item.hh.split(',').filter(item => !!item)
            }
            return item
        })
        return this.getFigures()
    }

    setFigureResults(values) {
        this.figure_results = values
    }

    generate(oblast, exp3 = false) {
        for (let f of this.figures) {

            let y2 = oblast.y2 - oblast.y1;
            f.color = f.colors.at(getRandom(0, f.colors.length - 1))
            f.angle = f.angles.at(getRandom(0, f.angles.length - 1)) ?? 0
            f.brightness = getRandom(f.brightness_min, f.brightness_max)

            if (this.exp_number === 3) {
                f.angle += f.angle_value
            }

            let i = 0;
            while (i < experimentStore.line.maxCount) {
                f.w = getRandom(f.size_min, f.size_max);
                if (f.name === 'rectangle' || f.name === 'ellipse') {
                    f.h = f.w
                } else if (f.name === 'rectangle2') {
                    f.h = Math.ceil(getRandom(f.size_min, f.size_max) / 3)
                    f.w = Math.ceil(getRandom(f.size_min, f.size_max) / 2)
                } else if (f.name === 'rectangle3') {
                    f.w = 15;
                    f.h = getRandom(f.size_min, f.size_max);
                } else {
                    f.h = getRandom(f.size_min, f.size_max);
                }

                let x, y = 0;

                if (!exp3) {
                    let x2 = oblast.x2 - oblast.x1;
                    x = getRandom(0, x2)
                    while (x <= 0 || x > x2 || x + f.w >= x2) {
                        x = getRandom(0, x2)
                    }
                    y = getRandom(0, y2)
                    while (y >= y2 || y <= 0 || y + f.h >= y2) {
                        y = getRandom(0, y2)
                    }
                    f.x = x;
                    f.y = y;
                } else {
                    x = f.angle == 0 ? f.x_h : f.x_v
                    y = f.angle == 0 ? f.y_h : f.y_v
                }


                f.x = x;
                f.y = y;
                f.xx.push(x)
                f.yy.push(y)
                f.hh.push(f.h)
                f.ww.push(f.w)
                i++;
            }

        }

    }
}
