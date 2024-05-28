import {SVG} from "@svgdotjs/svg.js";
import Triangle from 'svg-triangle';

export default function () {
    const rounded = (w, color = null) => {
        if (!color)
            color = '#000'
        const svg = SVG();
        return svg.ellipse(w, w).center(w / 2, w / 2).size(w, w).fill(color).parent().svg()
    }
    const triangle = (w, color) => {
        const svg = new Triangle({
            width: w,
            height: w,
            color,
            direction: 'up',
            radius: 2

        });
        return svg.triangle.outerHTML
    }
    return {
        rounded,
        triangle
    }
}
