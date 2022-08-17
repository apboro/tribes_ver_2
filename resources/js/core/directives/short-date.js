import { timeFormatting } from "../functions";

export default {
    bind (el, binding, vnode, oldVnode) {
        timeFormatting({
            date: new Date(binding.value),
            month: 'long'
        })

        el.textContent = timeFormatting({
            date: new Date(binding.value),
            day: '2-digit',
            month: '2-digit',
            year: "2-digit",
        });
    }
}
