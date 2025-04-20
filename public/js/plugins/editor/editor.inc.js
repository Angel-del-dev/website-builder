const { Request } = await require("../components/Request.inc.js");
const { modal } = await require("../components/modal.inc.js");
const { useTranslation } = await require("../components/hooks.inc.js");

const [ getTranslation ] = await useTranslation('backoffice');

const removepanel = async e => {
    e.preventDefault();

    const Panel_node = e.target.closest('section[data-panel]');
    const Panel = Panel_node.getAttribute('data-panel');

    await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/remove-panel`,
        method: 'POST',
        data: { Panel }
    });
    
    Panel_node.remove();
};

const _add_component = async (Panel, Side) => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/add-panel`,
        method: 'POST',
        data: { Panel, Side }
    });
    location.reload();
};

const show_modal_panels = async () => {
    const { Panels } = await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/get-user`,
        method: 'POST'
    });
    
    const content = modal({
        minwidth: '100vmin', width: '100vmin', height: '40vmin', minheight: '40vmin',
        title: getTranslation('editor-add-panel')
    });
    
    const wrapper = document.createElement('div');
    wrapper.classList.add('w-100', 'h-100', 'flex', 'justify-between', 'align-start', 'gap-2', 'overflow-y');
    wrapper.style = `
        flex-wrap: wrap;
    `;
    for(let panel of Panels) {
        const div = document.createElement('div');
        div.classList.add('p-1', 'flex-grow-1', 'radius-1', 'shadow-small', 'flex', 'justify-center', 'align-center', 'flex-column', 'gap-2');
        div.style = `
            width: 40%;
            height: 30vmin;
            background-color: var(--light);
        `;

        const _name = document.createElement('b');
        _name.append(document.createTextNode(getTranslation(panel.PANEL)));

        const _description = document.createElement('span');
        _description.style = ` width: 40%; text-align: left; `;
        _description.append(document.createTextNode(getTranslation(panel.DESCRIPTION)));

        const btn_container = document.createElement('div');
        btn_container.classList.add('flex', 'justify-center', 'align-center', 'gap-2');
        const btn_left = document.createElement('button');
        btn_left.classList.add('pointer', 'btn-primary');
        btn_left.addEventListener('click', e => {
            e.preventDefault();
            _add_component(panel['PANEL'], 0);
        });
        btn_left.append(document.createTextNode(getTranslation('editor-add-panel-left')));

        const btn_right = document.createElement('button');
        btn_right.classList.add('pointer', 'btn-primary');
        btn_right.addEventListener('click', e => {
            e.preventDefault();
            _add_component(panel['PANEL'], 1);
        });
        btn_right.append(document.createTextNode(getTranslation('editor-add-panel-right')));

        btn_container.append(btn_left, btn_right);
        div.append(_name, _description, btn_container);
        wrapper.append(div);
    }

    content.append(wrapper);
};

document.querySelectorAll('.removepanel')?.forEach((item, _) => {
    item.addEventListener('click', removepanel);
});

document.getElementById('add-panel')?.addEventListener('click', show_modal_panels);