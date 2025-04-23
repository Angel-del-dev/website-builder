const { Request } = await require("../components/Request.inc.js");
const { modal } = await require("../components/modal.inc.js");
const { useTranslation, useState } = await require("../components/hooks.inc.js");

const [ getFocusedElement, setFocusedElement ] = useState(null);
const [ getTranslation ] = await useTranslation('backoffice');

const save_page = async e => {
    e.preventDefault();
    await Request({
        url: `/${BACKOFFICE_PREFIX}/pages/page/save-changes`,
        method: 'POST',
        data: { Page: CURRENTPAGE, Contents: JSON.stringify(PAGESTRUCTURE) }
    });
    document.getElementById('save-page')?.classList.add('d-none');
}

const get_full_component_selector = (element = null, indexes = []) => {
    if(element === null) element = getFocusedElement();
    indexes.push(parseInt(element.getAttribute('position')));
    const parent_component = element.parentNode.closest('[component]');
    if(parent_component === null) {
        // This is the end condition, there's no more parent components
        return indexes.reverse(); // Reverse the indexes array for easier traversing
    }

    indexes.push('children');
    return get_full_component_selector(parent_component, indexes);
};

const change_property_value = async e => {
    const PropertyName = e.target.getAttribute('control-name');
    const Value = e.target.value.trim();
    const indexes = get_full_component_selector(null, []);
    
    let slice_ptr = PAGESTRUCTURE;
    for(let slice of indexes) {
        slice_ptr = slice_ptr[slice];
    }
    slice_ptr[PropertyName] = Value;

    // Obtain new html
    const { Html, TreeStructure } = await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/render-page-from-json`,
        method: 'POST',
        data: { Page: JSON.stringify(PAGESTRUCTURE) }
    });

    const parser = new DOMParser();
    const canvas = parser.parseFromString(Html, 'text/html');

    document.querySelector('article.builder-canvas').innerHTML = canvas.querySelector('article.builder-canvas').innerHTML;

    const panel = document.querySelector('[data-panel="editor-panel-component-tree-structure"]');
    if(panel !== null) {
        const structure = parser.parseFromString(TreeStructure, 'text/html');
        
        panel.querySelector('ul').innerHTML = structure.querySelector('ul').innerHTML;
    }

    document.querySelector('[data-panel="editor-panel-component-options"] div').innerHTML = '';
    setFocusedElement(null);
    document.getElementById('save-page')?.classList.remove('d-none');
}

const generate_controls = (Panel, Controls) => {
    Panel.innerHTML = '';
    Object.keys(Controls).forEach((k, _) => {
        const {
            type, label, value, options
        } = Controls[k];
        const row = document.createElement('div');
        row.classList.add('flex', 'justify-between', 'align-center', 'w-100');
        const lbl = document.createElement('label');
        lbl.style = `
            color: var(--white);
        `;

        let element = null;
        switch(type.toUpperCase()) {
            case 'TEXT':
                element = document.createElement('input');
                element.type = 'text';
                if(value !== undefined && value !== null) element.value = value;
            break;
            case 'LIST':
                element = document.createElement('select');
                if(value !== undefined && value !== null) element.value = value;
                if(options !== undefined && options !== null) {
                    options.forEach((opt, _) => {
                        const option = document.createElement('option');
                        option.value = opt.value;
                        option.append(document.createTextNode(opt.name));
                        element.append(option);
                    });
                }
            break;
        }
        if(element === null) throw new Error(`component '${type}' is not available`);

        element.setAttribute('control-name', label);
        element.addEventListener('change', change_property_value);

        element.style.setProperty('width', '40%');

        lbl.append(document.createTextNode(`${getTranslation(`editor-property-${label}`)}:`));

        row.append(lbl, element);
        Panel.append(row);
    });
};

const get_component_properties = async () => {
    const panel = document.querySelector('[data-panel="editor-panel-component-options"]')?.querySelector('div');
    
    if(panel === null) return;

    const element = getFocusedElement();
    if(element === null) return;
    let prop = element.getAttribute('properties');
    if(prop === null) return;
    const Properties = JSON.parse(prop);
    const { Controls } = await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/get-properties`,
        method: 'POST',
        data: { ...Properties }
    });
    generate_controls(panel, Controls);
}

const removepanel = async e => {
    e.preventDefault();

    const Panel_node = e.target.closest('section[data-panel]');
    const Panel = Panel_node.getAttribute('data-panel');

    await Request({
        url: `/${BACKOFFICE_PREFIX}/editor/remove-panel`,
        method: 'POST',
        data: { Panel }
    });
    
    location.reload();
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

const toggle_child_element = e => {
    const element = e.target.closest('.component-tree-item');
    if(element === null) return;

    const panel = e.target.closest('[data-panel]');
    panel.querySelectorAll('.active')?.forEach((panel_node, _) => panel_node.classList.remove('active'));
    const selected_item = e.target.closest('li').querySelector('span');
    selected_item.classList.add('active');

    document.querySelectorAll('.builder-canvas .active')?.forEach((builder_node, _) => builder_node.classList.remove('active'));

    const element_from_canvas_id = selected_item.id.replaceAll('tree-', '');
    setFocusedElement(document.getElementById(element_from_canvas_id));
    document.getElementById(`${element_from_canvas_id}`)?.classList.add('active');

    get_component_properties();

    const ul = element.querySelector('ul');
    if(ul === null) return;
    const classList = ul.classList;

    if(classList.contains('d-none')) classList.remove('d-none');
    else classList.add('d-none');
};

const handle_context_menu = e => {
    e.preventDefault();
    alert('Handle context menu');
};

const select_current_component = e => {
    const component_node = e.target.closest('[component]');
    if(component_node === null) return;

    const component = component_node.getAttribute('component');
    const id = component_node.id;

    document.querySelectorAll('.builder-canvas [component].active')?.forEach((cmp, _) => cmp.classList.remove('active'));
    component_node.classList.add('active');
    setFocusedElement(component_node);
    get_component_properties();
    // Select the element on the tree structure panel if exists

    const tree = document.querySelector('section[data-panel="editor-panel-component-tree-structure"]')
    if(tree === null) return;
    tree.querySelectorAll('.active')?.forEach((tree_node, _) => tree_node.classList.remove('active'));
    tree.querySelector(`#tree-${id}`)?.classList.add('active');
};

document.querySelectorAll('.removepanel')?.forEach((item, _) => {
    item.addEventListener('click', removepanel);
});

document.getElementById('main-editor')?.addEventListener('contextmenu', handle_context_menu);
document.getElementById('add-panel')?.addEventListener('click', show_modal_panels);
document.querySelector('section[data-panel="editor-panel-component-tree-structure"]')?.addEventListener('click', toggle_child_element);
document.querySelector('.builder-canvas')?.addEventListener('click', select_current_component);
document.getElementById('save-page')?.addEventListener('click', save_page);