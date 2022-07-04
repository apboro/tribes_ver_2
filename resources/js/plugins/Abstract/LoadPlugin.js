export class LoadPlugin {
    constructor() {
        this.init();
    }

    init() {
        window.loadJs = async (plugin, rootElem) => {
            let classname = plugin.ucFirst();
            let {default: MyClass} = await import('../../plugins/' + classname + '.js');
            if(!window[plugin]){
                window[plugin] = new MyClass(rootElem);
            } else {
                if (typeof window[plugin].update === "function") {
                    window[plugin].update();
                }
            }
            window.modal ? window.modal.loading(false) : null;
        };

        window.loadPlugins = () =>{
            let plugins = document.querySelectorAll('[data-plugin]');
        
            let pluginsArray = [];
        
            plugins.forEach((elem) => {
                let plugin = elem.dataset.plugin;
                pluginsArray.push({plugin, elem});
            });
            
            pluginsArray.getUnique().forEach((i) => {
                loadJs(i.plugin, i.elem);
            });
        };

        window.loadPlugins();
    }
}
