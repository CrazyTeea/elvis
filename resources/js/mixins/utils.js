import AbortablePromise from "promise-abortable/src";

const getRandom = (a, b) => {
    a=Number(a)
    b=Number(b)
    return a + (b - a + 1)*crypto.getRandomValues(new Uint32Array(1))[0]/2**32 | 0
}

const toNumber = val => ~~val

class SuperTimer {
    constructor(kek) {
        this.promise = null
        this.abortName = 'abort'
    }

    sleep(ms) {
        this.promise = new AbortablePromise((resolve, reject, signal) => {
            setTimeout(resolve, ms, 'kek')
            signal.onabort = reject
        })
        return this.promise
    }

    async timeout(fn, ms, ...args) {
        await this.sleep(ms);
        return fn(...args);
    }

    stop() {
        this.promise.abort()
    }
}

export {
    getRandom,
    SuperTimer,
    toNumber
}
