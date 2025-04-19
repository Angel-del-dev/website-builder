const { _close_modal, _create_generic_footer, _create_generic_header, modal } = await require("./modal.inc.js");

const _create_modal = (title, body, footer) => {
    const modal_node = modal({
        width: '50vmin', minheight: '20vmin',
        title
    });
    modal_node.append(body, footer);
};

const _create_generic_body = text => {
    const body = document.createElement('div');
    body.style = 'width: 100%; min-height: 10vmin;';
    const text_node = document.createElement('span');
    text_node.innerHTML = text;
    body.append(text_node);
    return body;
};

export const Alert = (
    { 
        text = '', 
        title='Notification',
        onExit = null,
        confirm_icon = 'fa-solid fa-check',
        confirm_text = 'Confirm', 
    }
) => {
    _create_modal(
        title,
        _create_generic_body(text),
        _create_generic_footer(confirm_icon, confirm_text, onExit)
    );
};

export const Confirm = (
    { 
        text = '',
        title = 'Confirm',
        onConfirm = null,
        onCancel = null,
        confirm_icon = 'fa-solid fa-check',
        confirm_text = 'Confirm', 
        cancel_icon = 'fa-solid fa-xmark',
        cancel_text = 'Cancel', 
    }
) => {
    const footer_node = document.createElement('div');
    footer_node.style = `
        width: 100%; padding: 10px;
        display: flex; justify-content: flex-end; align-items: center; gap: 10px;
    `;

    // Confirm
    const btn_confirm = document.createElement('button');
    btn_confirm.classList.add('btn-success');
    const confirm_icon_node = document.createElement('i');
    confirm_icon_node.classList.add(...confirm_icon.split(' '));
    btn_confirm.append(confirm_icon_node, document.createTextNode(confirm_text));
    btn_confirm.addEventListener('click', e => _close_modal(e, onConfirm));
    // Cancel
    const btn_cancel = document.createElement('button');
    btn_cancel.classList.add('btn-danger');
    const cancel_icon_node = document.createElement('i');
    cancel_icon_node.classList.add(...cancel_icon.split(' '));
    btn_cancel.append(cancel_icon_node, document.createTextNode(cancel_text));
    btn_cancel.addEventListener('click', e => _close_modal(e, onCancel));

    [btn_confirm, btn_cancel].forEach((btn, _) => {
        btn.classList.add('flex', 'justify-center', 'align-center', 'gap-1');
    });
    // Invoke
    footer_node.append(btn_cancel, btn_confirm);

    _create_modal(
        title,
        _create_generic_body(text),
        footer_node
    );
};

export const Prompt = (
    { 
        title = 'Confirm',
        onConfirm = null,
        onCancel = null,
        confirm_icon = 'fa-solid fa-check',
        confirm_text = 'Confirm', 
        cancel_icon = 'fa-solid fa-xmark',
        cancel_text = 'Cancel', 
        items = []
    }
) => {
    const content = document.createElement('div');
    content.classList.add('flex', 'justify-start', 'align-start', 'flex-column', 'gap-2');
    const items_nodes = [];
    items.forEach(({ name = '', type = 'label', placeholder = '', value = '' }, _) => {
        let item = null;
        switch(type.toUpperCase()) {
            case 'TEXT':
            case 'PASSWORD':
                item = document.createElement('input');
                item.type = type;
                item.classList.add('pretty-input');
                item.value = value;
                item.placeholder = placeholder;
            break;
            case 'LABEL':
                item = document.createElement('label');
                item.append(document.createTextNode(value));
            break;
            default:
                throw new Exception(`Type unknown '${type}'`);
            break;
        }
        if(item === null) return;
        content.append(item);
        items_nodes.push(item);
    });


    const footer_node = document.createElement('div');
    footer_node.style = `
        width: 100%; padding: 10px;
        display: flex; justify-content: flex-end; align-items: center; gap: 10px;
    `;

    // Confirm
    const btn_confirm = document.createElement('button');
    btn_confirm.classList.add('btn-success');
    const confirm_icon_node = document.createElement('i');
    confirm_icon_node.classList.add(...confirm_icon.split(' '));
    btn_confirm.append(confirm_icon_node, document.createTextNode(confirm_text));
    btn_confirm.addEventListener('click', e => _close_modal(e, () => onConfirm(items_nodes)));
    // Cancel
    const btn_cancel = document.createElement('button');
    btn_cancel.classList.add('btn-danger');
    const cancel_icon_node = document.createElement('i');
    cancel_icon_node.classList.add(...cancel_icon.split(' '));
    btn_cancel.append(cancel_icon_node, document.createTextNode(cancel_text));
    btn_cancel.addEventListener('click', e => _close_modal(e, onCancel));

    [btn_confirm, btn_cancel].forEach((btn, _) => {
        btn.classList.add('flex', 'justify-center', 'align-center', 'gap-1');
    });
    // Invoke
    footer_node.append(btn_cancel, btn_confirm);

    _create_modal(
        title,
        content,
        footer_node
    );
};