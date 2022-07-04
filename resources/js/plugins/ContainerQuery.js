window.containerQuery = (function() {
    const containerQuery = {
        init: function(element) {},
    };

    if (!window.ResizeObserver) {
        return containerQuery;
    }

    const ro = new ResizeObserver(function(entries) {
        const tasks = [];

        entries.forEach(function(entry) {
            const breakpoints = JSON.parse(entry.target.dataset.saContainerQuery);
            const mode = entry.target.dataset.saContainerQueryMode || 'all'; // all, bigger

            if (!['all', 'bigger'].includes(mode)) {
                throw Error('Undefined mode:  ' + mode);
            }

            const sortFn = function(a, b) { return b - a; };

            const add = [];
            const remove = [];

            Object.keys(breakpoints).map(parseFloat).sort(sortFn).forEach(function(width) {
                let elementWidth = 0;

                if (entry.borderBoxSize) {
                    const borderBoxSize = Array.isArray(entry.borderBoxSize) ? entry.borderBoxSize[0] : entry.borderBoxSize;

                    elementWidth = borderBoxSize.inlineSize;
                } else {
                    elementWidth = entry.target.getBoundingClientRect().width;
                }

                if (elementWidth >= width
                    && (mode !== 'bigger' || add.length === 0)
                ) {
                    add.push(breakpoints[width]);
                } else {
                    remove.push(breakpoints[width]);
                }
            });

            tasks.push(function() {
                entry.target.classList.remove.apply(entry.target.classList, remove);
                entry.target.classList.add.apply(entry.target.classList, add);
            });
        });

        setTimeout(function() {
            tasks.forEach(function(task) {
                task();
            });
        }, 0);
    });

    containerQuery.init = function(element) {
        ro.observe(element);
    };
    document.querySelectorAll('[data-sa-container-query]').forEach((item) => {
        containerQuery.init(item);
    });
    // $('[data-sa-container-query]').each(function() {
    //     containerQuery.init(this);
    // });

    return containerQuery;
})();
