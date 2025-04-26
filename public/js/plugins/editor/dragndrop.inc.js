const { useState } = await require("../components/hooks.inc.js");

const [ isMouseDown, setIsMouseDown ] = useState(false);
const [ focusedComponent, setFocusedComponent ] = useState(null);

let ghost_element = null;

const draw_ghost_element = () => {
    if(ghost_element !== null) return;

    const cloned_element = focusedComponent().cloneNode(true);
    cloned_element.id = 'ghost_node';

    ghost_element = document.createElement('div');

    ghost_element.append(cloned_element);
    ghost_element.classList.add('radius-1');
    ghost_element.style = `
        position: absolute; top: 0; left: 0;
        z-index: 10;
        border: 1px dashed teal; background-color: var(--info); opacity: .6;
    `;

    document.querySelector('body')?.append(ghost_element);
};

const loop_route_to_slice = (route) => {
    let ptr = PAGESTRUCTURE;

    for(let i = 0 ; i < route.length ; i++) {
        ptr = ptr[route[i]];
    }

    return ptr;
};

const release_current_element = (e, { get_full_component_selector, redraw_page_content_from_state }) => {
    setIsMouseDown(false);
    setFocusedComponent(null);

    const released_on = e.target.closest('[component]');

    if(
        released_on === null ||
        released_on === document.querySelector('article.builder-canvas [component].active') ||
        ( released_on.getAttribute('children-allowed') !== undefined && released_on.getAttribute('children-allowed') === 'false')
    ) {
        ghost_element?.remove();
        ghost_element = null;
        return;
    }
    
    const old_component_route = get_full_component_selector(null, []);
    const new_component_route = get_full_component_selector(released_on, []);

    const old_ptr = loop_route_to_slice(old_component_route);
    let new_ptr = loop_route_to_slice(new_component_route);
    
    // Copy the old ptr to the new ptr
    new_ptr.children.push(old_ptr);
    // Remove the old ptr
    let removal_ptr = PAGESTRUCTURE;
    for(let i = 0; i < old_component_route.length; i++) {
        if(removal_ptr[old_component_route[i]] === old_ptr) {
            removal_ptr.splice(old_component_route[i], 1);
            break;
        }
        removal_ptr = removal_ptr[old_component_route[i]];
        
    }
    ghost_element?.remove();
    ghost_element = null;
    redraw_page_content_from_state();
};

const hold_current_element = (e, {canvas_focused_component}) => {
    const component = e.target.closest('[component]');
    if(component === null) return;
    if(canvas_focused_component() === null || canvas_focused_component() !== component) return;
    setIsMouseDown(true);
    setFocusedComponent(component);
};

const handle_mouse_move = ({ pageX, pageY }) => {
    if(!isMouseDown() || focusedComponent() === null) return;
    // Move ghost element
    draw_ghost_element();
    
    ghost_element.style.setProperty('top', `${pageY + 10}px`);
    ghost_element.style.setProperty('left', `${pageX + 10}px`);

    document.querySelectorAll('.builder-canvas [component]:not([children-allowed="false"])')?.
        forEach((node, _) => {
            node.classList.add('edit-hover');
        });
};

const handle_canvas_drag_n_drop = methods => {
    const canvas = document.querySelector('.builder-canvas');

    canvas?.addEventListener('mousedown', e => hold_current_element(e, methods));
};

const main = methods => {
    // Handle canvas drag & drop
    handle_canvas_drag_n_drop(methods);
    // Handle Tree drag & drop
};

export const init_drag_drop = methods => {
    const body = document.querySelector('body');
    body?.addEventListener('mousemove', handle_mouse_move);
    body?.addEventListener('mouseup', e => release_current_element(e, methods));
    return { main };
};